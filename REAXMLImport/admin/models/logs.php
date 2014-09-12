<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @package Component REAXML Import for EZ Realty for Joomla! 3.3
 * @version 0.43.119: logs.php 2014-09-12T13:54:30.355
 * @author Neil Clifton
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/
class ReaXmlImportModelsLogs extends JModelBase {
	public function getLogFiles() {
		$params = JComponentHelper::getParams ( 'com_reaxmlimport' );
		$logDir = $params->get ( 'log_dir' );
		
		$logFiles = array ();
		if (isset ( $logDir ) && JFolder::exists ( $logDir )) {
			$logFiles = JFolder::files ( $params->get ( 'log_dir' ) );
		}
		
		return $logFiles;
	}
	public function getLogRelUrl() {
		$params = JComponentHelper::getParams ( 'com_reaxmlimport' );
		return $params->get ( 'log_rel_url' ) . '';
	}
}