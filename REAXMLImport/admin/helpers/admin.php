<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_reaxmlimport
 *
 * @copyright   Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined ( '_JEXEC' ) or die ();

/**
 * ReaXmlImport component helper.
 *
 * @package Joomla.Administrator
 * @subpackage com_reaxmlimport
 * @since 1.6
 */
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
