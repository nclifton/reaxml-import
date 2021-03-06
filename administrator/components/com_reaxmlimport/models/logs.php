<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @package Component REAXML Import for Joomla! 3.4
 * @version 1.5.26: logs.php 2016-08-15T02:12:57.600
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014, 2015, 2016 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 *
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
	public function getLogUrl() {
		$params = JComponentHelper::getParams ( 'com_reaxmlimport' );
		return $params->get ( 'log_url' ) . '';
	}
}