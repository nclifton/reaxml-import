<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @package Component REAXML Import for Joomla! 3.4
 * @version 1.5.26: import.php 2016-08-15T02:12:57.600
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014, 2015, 2016 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 *
 **/
jimport ( 'reaxml.configuration' );
jimport ( 'reaxml.importer' );
class ReaXmlImportModelsImport extends JModelBase {
	const REAXML_IMPORT_LOG_FILE_NAME = 'REAXMLImport.log';
	public function getConfiguration() {
		$params = JComponentHelper::getParams ( 'com_reaxmlimport' );
		$configuration = new ReaxmlConfiguration ();
		$configuration->input_dir = $params->get ( 'input_dir' );
		$configuration->work_dir = $params->get ( 'work_dir' );
		$configuration->done_dir = $params->get ( 'done_dir' );
		$configuration->error_dir = $params->get ( 'error_dir' );
		$configuration->log_dir = $params->get ( 'log_dir' );
		$configuration->usemap = $params->get( 'usemap' );
        $configuration->default_country = $params->get( 'default_country' );

        return $configuration;
	}
	public function getLatestLog() {
		$params = JComponentHelper::getParams ( 'com_reaxmlimport' );
		if (null !== ($logdir = $params->get ( 'log_dir' ))) {
			$file = JFolder::makeSafe ( $logdir ) . DIRECTORY_SEPARATOR . self::REAXML_IMPORT_LOG_FILE_NAME;
			if (JFile::exists ( $file )) {
				$log = file_get_contents ( $file );
				return $log;
			}
		}
		return null;
	}
	public function import() {
		$configuration = $this->getConfiguration ();
		$importer = new ReaxmlImporter();
		$importer->setConfiguration($configuration);
		return $importer->import ();
	}
}