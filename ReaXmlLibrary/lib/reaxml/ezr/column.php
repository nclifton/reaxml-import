<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @package Library REAXML Library for Joomla! 3.3
 * @version 0.0.61: column.php 2015-03-12T02:22:10.059
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/ 
jimport ( 'joomla.filesystem.file' );
jimport ( 'joomla.log.log' );
abstract class ReaxmlEzrColumn extends ReaxmlDbColumn {
	const XPATH_UNIQUEID = '//uniqueID';
	private $isNew;
	public function getValue() {
		return $this->isNew () ? '' : null;
	}
	public function getId() {
		$found = $this->xml->xpath ( self::XPATH_UNIQUEID );
		if (count ( $found ) == 0) {
			throw new RuntimeException ( JText::_ ( 'LIB_REAXML_ERROR_MESSAGE_UNIQUE_ID_NOT_IN_XML' ) );
		} else {
			return $found [0] . '';
		}
	}
	public function isNew() {
		if (! isset ( $this->isNew )) {
			$this->isNew = ! ($this->dbo->exists ( $this->getId () ));
		}
		return $this->isNew;
	}
	public function featureBoolean($value) {
		switch (strtolower ( $value )) {
			case '' :
			case '0' :
			case 'no' :
				return false;
				break;
			case 'yes' :
				return true;
				break;
			default :
				$value = $value . '';
				if (is_numeric ( $value ) && intval ( $value ) > 0) {
					return true;
				}
				break;
		}
		return false;
	}
	public function getFeaturesValue($mappings, $getCurrentValueDboFunction) {
		$currentFeatures = array ();
		if (! $this->isNew ()) {
			$features = $this->dbo->$getCurrentValueDboFunction ( $this->getId () );
			if (strlen ( $features ) > 0) {
				$currentFeatures = explode ( ";", $features );
			}
		}
		$newFeatures = $currentFeatures;
		foreach ( $mappings as $map ) {
			$found = $this->xml->xpath ( $map ['xpath'] );
			if (count ( $found ) > 0) {
				$text = JText::_ ( $map ['text'] );
				$key = array_search ( $text, $newFeatures );
				if ($this->featureBoolean ( $found [0] . '' )) {
					if ($key === false) {
						array_push ( $newFeatures, $text );
					}
				} else {
					unset ( $newFeatures [$key] );
				}
			}
		}
		if (count ( $newFeatures ) > 0) {
			$newFeatures = array_values ( $newFeatures );
		}
		if (! $this->isNew ()) {
			$intersect = array_intersect ( $newFeatures, $currentFeatures );
			if ((count ( $newFeatures ) == count ( $intersect )) && (count ( $currentFeatures ) == count ( $intersect ))) {
				return null;
			}
		}
		return count ( $newFeatures ) == 0 ? '' : join ( ';', $newFeatures );
	}
	protected function copyWorkImageFile($file, $subdir) {
		$src = $this->configuration->work_dir . DIRECTORY_SEPARATOR . $file;
		$ext = JFile::getExt ( $src );
		$dest = JPATH_ROOT . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'ezrealty' . DIRECTORY_SEPARATOR . $subdir . DIRECTORY_SEPARATOR . md5 ( uniqid () ) . '.' . $ext;
		if (JFile::exists ( $src )) {
			try {
				JFile::copy ( $src, $dest );
				JLog::add ( JText::sprintf ( 'LIB_REAXML_INFO_MESSAGE_COPYING_IMAGE_FILE', basename($src), basename($dest) ), JLog::INFO, REAXML_LOG_CATEGORY );
				return basename ( $dest );
			} catch ( Exception $e ) {
				JLog::add ( $e->getMessage (), JLog::ERROR, REAXML_LOG_CATEGORY );
				return '';
			}
		} else {
			JLog::add ( JText::sprintf ( 'LIB_REAXML_FILE_NOT_FOUND', basename ( $src ) ), JLog::ERROR, REAXML_LOG_CATEGORY );
			return '';
		}
	}
	protected function downloadUrlImageFile($url, $ext, $subdir) {
		$dest = JPATH_ROOT . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'ezrealty' . DIRECTORY_SEPARATOR . $subdir . DIRECTORY_SEPARATOR . md5 ( uniqid () ) . '.' . $ext;
		JLog::add ( JText::sprintf ( 'LIB_REAXML_INFO_MESSAGE_DOWLOADING', $url ), JLog::INFO, REAXML_LOG_CATEGORY );
		try {
			copy ( $url, $dest );
			return basename ( $dest );
		} catch ( Exception $e ) {
			JLog::add ( $e->getMessage (), JLog::ERROR, REAXML_LOG_CATEGORY );
			return '';
		}
	}
	protected function deleteImagesFile($filename, $subdir) {
		if ($filename != null && $filename !== '') {
			$file = JPATH_ROOT . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'ezrealty' . DIRECTORY_SEPARATOR . $subdir . DIRECTORY_SEPARATOR . $filename;
			if (JFile::exists ( $file )) {
				JLog::add ( JText::sprintf ( 'LIB_REAXML_INFO_MESSAGE_DELETING', basename ( $file ) ), JLog::INFO, REAXML_LOG_CATEGORY );
				JFile::delete ( $file );
			}
		}
	}
	protected function parseInspectionDate($found) {
		if (strlen ( $found ) > 0) {
			$datestring = explode ( ' ', $found )[0];
			return date ( 'Y-m-d', strtotime ( $datestring ) );
		}
		return null;
	}
	protected function parseInspectionStartTime($found) {
		if (strlen ( $found ) > 0) {
			$timestring = explode ( ' ', $found )[1];
			return date ( 'H:i:s', strtotime ( $timestring ) );
		}
		return null;
	}
	protected function parseInspectionEndTime($found) {
		if (strlen ( $found ) > 0) {
			$timestring = explode ( ' ', $found )[3];
			return date ( 'H:i:s', strtotime ( $timestring ) );
		}
		return null;
	}
}