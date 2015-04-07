<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @package Component REAXML Import for Joomla! 3.4
 * @version 1.2.26: admin.php 2015-04-07T14:42:50.797
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 *
 **/

class ReaXmlImportHelpersAdmin {
	public static $extension = 'com_reaxmlimport';
	
	/**
	 *
	 * @return JObject
	 */
	public static function getActions() {
		$user = JFactory::getUser ();
		$result = new JObject ();
		
		$assetName = 'com_reaxmlimport';
		$level = 'component';
		
		$actions = JAccess::getActions ( 'com_reaxmlimport', $level );
		
		foreach ( $actions as $action ) {
			$result->set ( $action->name, $user->authorise ( $action->name, $assetName ) );
		}
		
		return $result;
	}
	static function load() {
		
		// javascripts - making sutre JQuery dependancy is injected before our script
		JHtml::_ ( 'jquery.framework' );
		JHtml::_ ( 'jquery.ui' );
		JHtml::script ( JUri::base () . 'components/com_reaxmlimport/assets/js/jquery-ui-1.9.2.custom.js' );
		JHtml::script ( JUri::base () . 'components/com_reaxmlimport/assets/js/import.js' );
		
		// stylesheets
		JHtml::stylesheet ( JUri::base () . 'components/com_reaxmlimport/assets/css/jquery-ui-1.9.2.custom.css' );
		JHtml::stylesheet ( JUri::base () . 'components/com_reaxmlimport/assets/css/jquery.ui.theme.css' );
		JHtml::stylesheet ( JUri::base () . 'components/com_reaxmlimport/assets/css/import.css' );
	}
	static function getLogFilesHtml($model) {
		return self::getHtmlFileList ( $model->getLogFiles (), $model->getLogRelUrl () );
	}
	private static function getHtmlFileList($files, $relUrl) {
		$content = '';
		foreach ( $files as $file ) {
			$content .= '<a href="' . $relUrl . '/' . $file . '">' . $file . '</a><br/>';
		}
		return $content;
	}
	static function getInputFilesHtml($model) {
		return self::getHtmlFileList ( $model->getInputFiles (), $model->getInputRelUrl () );
	}
	static function getErrorFilesHtml($model) {
		return self::getHtmlFileList ( $model->getErrorFiles (), $model->getErrorRelUrl () );
	}
}
