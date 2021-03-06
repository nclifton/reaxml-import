<?php
defined('_JEXEC') or die ('Restricted access');

/**
 *
 * @package Component REAXML Import for Joomla! 3.4
 * @version 1.5.26: admin.php 2016-08-15T02:12:57.600
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014, 2015, 2016 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 *
 **/
class ReaXmlImportHelpersAdmin
{
    public static $extension = 'com_reaxmlimport';

    /**
     *
     * @return JObject
     */
    public static function getActions()
    {
        $user = JFactory::getUser();
        $result = new JObject ();

        $assetName = 'com_reaxmlimport';
        $level = 'component';

        $actions = JAccess::getActions('com_reaxmlimport', $level);

        foreach ($actions as $action) {
            $result->set($action->name, $user->authorise($action->name, $assetName));
        }

        return $result;
    }

    static function load()
    {

        // javascripts - making sure JQuery dependency is injected before our script
        JHtml::_('jquery.framework');
        JHtml::_('jquery.ui');
        JHtml::script('com_reaxmlimport/jquery-ui-1.9.2.custom.js',false,true);
        JHtml::script('com_reaxmlimport/beimport.js',false,true);

        // stylesheets
        JHtml::stylesheet('com_reaxmlimport/jquery-ui-1.9.2.custom.css',false,true);
        JHtml::stylesheet('com_reaxmlimport/jquery.ui.theme.css',false,true);
        JHtml::stylesheet('com_reaxmlimport/import.css',false,true);
    }

    static function getLogFilesHtml($model)
    {
        return self::getHtmlFileList($model->getLogFiles(), $model->getLogUrl());
    }

    private static function getHtmlFileList($files, $relUrl)
    {
        $content = '';
        foreach ($files as $file) {
            $content .= '<a href="' . $relUrl . '/' . $file . '">' . $file . '</a><br/>';
        }
        return $content;
    }

    static function getInputFilesHtml($model)
    {
        return self::getHtmlFileList($model->getInputFiles(), $model->getInputUrl());
    }

    static function getErrorFilesHtml($model)
    {
        return self::getHtmlFileList($model->getErrorFiles(), $model->getErrorUrl());
    }

    private static $dbo;

    private static function getDbo()
    {
        if (!isset(self::$dbo)) {
            self::$dbo = JFactory::getDbo();
        }
        return self::$dbo;
    }

    /**
     * Updates the extra_query column in the update_sites table with the download id
     */
    public static function updateUpdateSiteWithDownloadId()
    {
        $db = self::getDbo();
        $query = $db->getQuery(true)
            ->select('*')
            ->from($db->qn('#__extensions'))
            ->where($db->qn('element') . ' in (' . $db->q('reaxml') .','.$db->q('com_reaxmlimport').')' );
         $db->setQuery($query);
        $extensions = $db->loadObjectList();
        foreach ($extensions as $extension){
            $extension_id = $extension->extension_id;
            $query = $db->getQuery(true)
                ->select($db->qn('update_site_id'))
                ->from($db->qn('#__update_sites_extensions'))
                ->where($db->qn('extension_id') . ' = ' . $db->q($extension_id));
            $db->setQuery($query);
            $updateSiteIds = $db->loadColumn(0);

            // Loop through all update sites
            foreach ($updateSiteIds as $id) {
                $query = $db->getQuery(true)
                    ->select('*')
                    ->from($db->qn('#__update_sites'))
                    ->where($db->qn('update_site_id') . ' = ' . $db->q($id));
                $db->setQuery($query);
                $aSite = $db->loadObject();
                if ($aSite->enabled) {
                    if (!isset($aSite->extra_query) || $aSite->extra_query != self::getDownloadId()) {
                        $aSite->extra_query = 'dlid=' . self::getDownloadId();
                        $db->updateObject('#__update_sites', $aSite, 'update_site_id', true);
                    }
                }
            }
        }
    }

    private static function getDownloadId()
    {
        $params = JComponentHelper::getParams('com_reaxmlimport');
        return $params->get('update_dlid');
    }
}
