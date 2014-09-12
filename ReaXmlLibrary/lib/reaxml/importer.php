<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @package Library REAXML Library for Joomla! 3.3
 * @version 0.0.52: importer.php 2014-09-12T14:10:36.970
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/
	public function start() {
		$this->checkDirectories ();
		$dt = new DateTime ();
		$this->runtag = $dt->format ( 'YmdHis' );
		$this->newLogger ();
		$this->prepareDirectories ();
		$this->run ();
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
	public function moveInputToWork() {
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