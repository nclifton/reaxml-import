<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @package Library REAXML Library for Joomla! 3.3
 * @version 0.0.56: importer.php 2015-03-11T19:57:05.574
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 *         
 */
defined ( '_JEXEC' ) or die ();

jimport ( 'joomla.filesystem.file' );
jimport ( 'joomla.filesystem.folder' );
jimport ( 'joomla.log.log' );
jimport ( 'reaxml.ezr.dbo' );
jimport ( 'reaxml.ezr.row' );
jimport ( 'joomla.ezr.images' );

defined ( 'REAXML_LOG_CATEGORY' ) or define ( 'REAXML_LOG_CATEGORY', 'REAXML-Import' );
class ReaxmlImporter {
	static $loggerAdded = false;
	private $configuration;
	private $runtag;
	const IMPORT_FILE_NAME_REGEX = '\.(xml|jpg|jpeg|gif|pdf|doc|xls|zip)$';
	const IMPORT_ZIP_FILE_NAME_REGEX = '#\.(zip)$#i';
	const LOG_NAME = 'REAXMLImport';
	const LOG_EXT = '.log';
	
	/**
	 * @param Object $configuration        	
	 */
	public function setConfiguration(ReaxmlConfiguration $configuration) {
		$this->configuration = $configuration;
		$this->checkDirectories ();
	}

	public function __construct() {
		$lang = JFactory::getLanguage ();
		$lang->load ( 'lib_reaxml', JPATH_SITE, 'en-GB', true );
	}

	private function newLogger() {
		if (JFile::exists ( $this->configuration->log_dir . DIRECTORY_SEPARATOR . self::LOG_NAME . self::LOG_EXT )) {
			$this->runtag = date ( 'YmdHis', filemtime ( $this->configuration->log_dir . DIRECTORY_SEPARATOR . self::LOG_NAME . self::LOG_EXT ) );
			JFile::move ( self::LOG_NAME . self::LOG_EXT, self::LOG_NAME . '-' . $this->runtag . self::LOG_EXT, $this->configuration->log_dir );
		}
		
		if (!self::$loggerAdded) {		
			JLog::addLogger ( array (
					'text_file' => self::LOG_NAME . self::LOG_EXT,
					'text_file_path' => $this->configuration->log_dir,
					'text_file_no_php' => true,
					'text_entry_format' => '{DATE} {TIME} {PRIORITY} {MESSAGE}' 
			), JLog::ALL, array (
					REAXML_LOG_CATEGORY 
			) );
			self::$loggerAdded = true;
		}
	}
	private function start() {
		
		$this->logStart ();
		
		try {
			$files = $this->moveInputToWork ();
			
			$dbo = new ReaxmlEzrDbo ();
			
			foreach ( $files as $file ) {
				try {
					if (strtolower ( JFile::getExt ( $file ) ) == 'xml') {
						
						JLog::add ( JText::sprintf ( 'LIB_REAXML_LOG_IMPORTING', basename ( $file ) ), JLog::INFO, REAXML_LOG_CATEGORY );
						
						$xml = simplexml_load_file ( $file );
						
						// loop over the properties
						
						$propertyList = $this->listProperties ( $xml );
						
						// log message if there are no properties in the file
						if (count ( $propertyList ) == 0) {
							throw new Exception ( JText::_ ( 'LIB_REAXML_ERROR_MESSAGE_NO_PROPERTIES' ) );
						}
						
						foreach ( $propertyList as $property ) {
							
							$propertyXml = new SimpleXMLElement ( $property->asXML () );
							// create a ezrealty row from the xml
							$row = new ReaxmlEzrRow ( $propertyXml, $dbo, $this->configuration );
							$images = new ReaxmlEzrImages ( $propertyXml, $dbo, $this->configuration, $row );
							
							// insert or update the database with the row;
							if ($dbo->exists ( $row )) {
								JLog::add ( JText::sprintf ( 'LIB_REAXML_LOG_UPDATING_LISTING', $row->getValue ( 'mls_id' ) ), JLog::INFO, REAXML_LOG_CATEGORY );
								$dbo->update ( $row, $images );
							} else {
								JLog::add ( JText::sprintf ( 'LIB_REAXML_LOG_ADDING_NEW_LISTING', $row->getValue ( 'mls_id' ) ), JLog::INFO, REAXML_LOG_CATEGORY );
								$dbo->insert ( $row, $images );
							}
						}
					}
				} catch ( Exception $e ) {
					JLog::add ( JText::sprintf ( 'LIB_REAXML_CANNOT_IMPORT_FROM_FILE', basename ( $file ), $e->getMessage () ), JLog::ERROR, REAXML_LOG_CATEGORY );
					JFile::move ( $file, $this->configuration->error_dir . DIRECTORY_SEPARATOR . basename ( $file ) );
				}
			}
			foreach ( $files as $file ) {
				JFile::move ( $file, $this->configuration->done_dir . DIRECTORY_SEPARATOR . basename ( $file ) );
			}
			
			JLog::add ( JText::_ ( 'LIB_REAXML_LOG_ENDING' ), JLog::INFO, REAXML_LOG_CATEGORY );
		} finally {
		}
	}
	private function prepareDirectories() {
		$this->moveAnyFilesToTimeStampedDirectory ( 'done_dir' );
		$this->moveAnyFilesToTimeStampedDirectory ( 'error_dir' );
	}
	private function moveAnyFilesToTimeStampedDirectory($dir) {
		$path = $this->configuration->$dir . DIRECTORY_SEPARATOR . $this->runtag;
		$files = JFolder::files ( $this->configuration->$dir, null, false, true );
		if (count ( $files ) > 0) {
			foreach ( $files as $file ) {
				if (! JFolder::exists ( $path )) {
					// JLog::add ( 'Created ' . $path, JLog::INFO, REAXML_LOG_CATEGORY );
					JFolder::create ( $path );
				}
				JFile::move ( $file, $path . DIRECTORY_SEPARATOR . basename ( $file ) );
				// JLog::add ( 'Moved ' . $file . ' to ' . $path . DIRECTORY_SEPARATOR . basename($file), JLog::INFO, REAXML_LOG_CATEGORY );
			}
		}
	}
	private function addPropertyTypeToList($xml, $propertyList, $propertyType) {
		if (isset ( $xml->$propertyType )) {
			if (is_array ( $xml->$propertyType )) {
				$propertyList = array_merge ( $propertyList, $xml->$propertyType );
			} else {
				array_push ( $propertyList, $xml->$propertyType );
			}
		}
		return $propertyList;
	}
	private function listProperties(SimpleXMLElement $xml) {
		$propertyTypes = array (
				'residential',
				'business',
				'commercial',
				'commercialLand',
				'land',
				'rental',
				'rural' 
		);
		$propertyList = array ();
		foreach ( $propertyTypes as $propertyType ) {
			$propertyList = $this->addPropertyTypeToList ( $xml, $propertyList, $propertyType );
		}
		return $propertyList;
	}
	
	/**
	 * starts the import process
	 */
	public function import() {
		$this->newLogger ();
		$dt = new DateTime ();
		$this->runtag = $dt->format ( 'YmdHis' );
		$this->prepareDirectories ();
		$this->start ();
		return $this->runtag;
	}
	protected function checkDirectories() {
		$this->configuration->log_dir = JFolder::makeSafe ( $this->configuration->log_dir );
		if (! JFolder::exists ( $this->configuration->log_dir )) {
			throw new Exception ( JText::sprintf ( 'LIB_REAXML_LOG_LOG_DIRECTORY_DOES_NOT_EXIST', $this->configuration->log_dir ), 500 );
		}
		$this->configuration->input_dir = JFolder::makeSafe ( $this->configuration->input_dir );
		if (! JFolder::exists ( $this->configuration->input_dir )) {
			throw new Exception ( JText::sprintf ( 'LIB_REAXML_LOG_INPUT_DIRECTORY_DOES_NOT_EXIST', $this->configuration->input_dir ), 500 );
		}
		$this->configuration->work_dir = JFolder::makeSafe ( $this->configuration->work_dir );
		if (! JFolder::exists ( $this->configuration->work_dir )) {
			throw new Exception ( JText::sprintf ( 'LIB_REAXML_LOG_WORK_DIRECTORY_DOES_NOT_EXIST', $this->configuration->work_dir ), 500 );
		}
		$this->configuration->done_dir = JFolder::makeSafe ( $this->configuration->done_dir );
		if (! JFolder::exists ( $this->configuration->done_dir )) {
			throw new Exception ( JText::sprintf ( 'LIB_REAXML_LOG_DONE_DIRECTORY_DOES_NOT_EXIST', $this->configuration->done_dir ), 500 );
		}
		$this->configuration->error_dir = JFolder::makeSafe ( $this->configuration->error_dir );
		if (! JFolder::exists ( $this->configuration->error_dir )) {
			throw new Exception ( JText::sprintf ( 'LIB_REAXML_LOG_ERROR_DIRECTORY_DOES_NOT_EXIST', $this->configuration->error_dir ), 500 );
		}
	}
	private function moveInputToWork() {
		$files = $this->getInputFiles ();
		if (count ( $files ) == 0) {
			return array ();
		}
		foreach ( $files as $file ) {
			if (preg_match ( self::IMPORT_ZIP_FILE_NAME_REGEX, $file )) {
				$this->extractInputZipToWork ( $file );
			}
			JFile::move ( $file, $this->configuration->work_dir . DIRECTORY_SEPARATOR . basename ( $file ) );
		}
		
		$files = JFolder::files ( $this->configuration->work_dir, self::IMPORT_FILE_NAME_REGEX, true, true );
		JLog::add ( JText::sprintf ( 'LIB_REAXML_LOG_FILES_IN_WORK', count ( $files ) ), JLog::INFO, REAXML_LOG_CATEGORY );
		return $files;
	}
	private function extractInputZipToWork($ZipFileName) {
		JLog::add ( JText::sprintf ( 'LIB_REAXML_LOG_EXPANDING', basename ( $ZipFileName ) ), JLog::INFO, REAXML_LOG_CATEGORY );
		$zip = new ZipArchive ();
		
		if ($zip->open ( $ZipFileName ) === TRUE) {
			// make all the folders
			for($i = 0; $i < $zip->numFiles; $i ++) {
				$OnlyFileName = $zip->getNameIndex ( $i );
				$FullFileName = $zip->statIndex ( $i );
				if ($FullFileName ['name'] [strlen ( $FullFileName ['name'] ) - 1] == "/") {
					@mkdir ( $this->configuration->work_dir . DIRECTORY_SEPARATOR . $FullFileName ['name'], 0700, true );
				}
			}
			
			// unzip into the folders
			for($i = 0; $i < $zip->numFiles; $i ++) {
				$OnlyFileName = $zip->getNameIndex ( $i );
				$FullFileName = $zip->statIndex ( $i );
				
				if (! ($FullFileName ['name'] [strlen ( $FullFileName ['name'] ) - 1] == "/")) {
					if (preg_match ( '#' . self::IMPORT_FILE_NAME_REGEX . '#i', $OnlyFileName )) {
						copy ( 'zip://' . $ZipFileName . '#' . $OnlyFileName, $this->configuration->work_dir . DIRECTORY_SEPARATOR . $FullFileName ['name'] );
					}
				}
			}
			$zip->close ();
		} else {
			JFile::move ( $ZipFileName, $this->configuration->error_dir . DIRECTORY_SEPARATOR . basename ( $ZipFileName ) );
			throw new RuntimeException ( JText::sprintf ( 'LIB_REAXML_ERROR_MESSAGE_CANNOT_OPEN_ZIP_FILE', $ZipFileName ) );
		}
	}
	private function getInputFiles() {
		$files = JFolder::files ( $this->configuration->input_dir, self::IMPORT_FILE_NAME_REGEX, false, true );
		if (count ( $files ) == 0) {
			JLog::add ( JText::_ ( 'LIB_REAXML_LOG_NO_FILES_TO_PROCESS' ), JLog::INFO, REAXML_LOG_CATEGORY );
		} else {
			JLog::add ( JText::sprintf ( 'LIB_REAXML_LOG_FILES_TO_MOVE_INTO_WORK', count ( $files ) ), JLog::INFO, REAXML_LOG_CATEGORY );
		}
		return $files;
	}
	private function logStart() {
		JLog::add ( JText::_ ( 'LIB_REAXML_LOG_STARTING' ), JLog::INFO, REAXML_LOG_CATEGORY );
	}
}