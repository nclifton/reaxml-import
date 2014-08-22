<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @copyright Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license GNU General Public License version 2 or later; see LICENSE.txt
 *         
 */
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