<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @package Library REAXML Library for Joomla! 3.4
 * @version 1.4.8: image_fname.php 2015-07-01T05:31:35.565
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014, 2015 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 *
 **/ 
error_reporting ( E_ALL & ~ E_STRICT & ~ E_NOTICE );
define ( 'DS', DIRECTORY_SEPARATOR );
class ReaxmlEzrColImage_fname extends \ReaxmlEzrImagecolumn {
	private static $checkedDirectories = false;
	const XPATH_IMG = '//objects/img[@id="%s"]';
	const XPATH_URL = '//objects/img[@id="%s"]/@url';
	const XPATH_FILE = '//objects/img[@id="%s"]/@file';
	const XPATH_FORMAT = '//objects/img[@id="%s"]/@format';
	const IMAGES_SUBDIRECTORY = 'properties';
	const THUMBS_SUB_SUBDIRECTORY = 'th';
	public function getValueAt($idx) {
		$ids = parent::getIdSequence ();
		$nodes = $this->xml->xpath ( sprintf ( self::XPATH_IMG, $ids [$idx] ) );
		if (count ( $nodes ) == 0) {
			return $this->isNew () ? '' : null;
		} else {
			
			if (! self::$checkedDirectories) {
				self::checkNFixDirectories ();
				self::$checkedDirectories = true;
			}
			
			$nodes = $this->xml->xpath ( sprintf ( self::XPATH_FILE, $ids [$idx] ) );
			if (count ( $nodes ) > 0) {
				$fname = $this->copyWorkImageFile ( $nodes [0] . '', self::IMAGES_SUBDIRECTORY );
				if (fname !== '') {
					$fname = $this->generateThumnailImage ( self::IMAGES_SUBDIRECTORY, $fname, self::THUMBS_SUB_SUBDIRECTORY );
				}
				return $fname;
			}
			$nodes = $this->xml->xpath ( sprintf ( self::XPATH_URL, $ids [$idx] ) );
			if (count ( $nodes ) > 0) {
				$fname = $this->downloadUrlImageFile ( $nodes [0] . '', $this->xml->xpath ( sprintf ( self::XPATH_FORMAT, $ids [$idx] ) )[0] . '', self::IMAGES_SUBDIRECTORY );
				if (fname !== '') {
					$fname = $this->generateThumnailImage ( self::IMAGES_SUBDIRECTORY, $fname, self::THUMBS_SUB_SUBDIRECTORY );
				}
				return $fname;
			}
			if (! $this->isNew ()) {
				$filename = $this->dbo->lookupEzrImageFnameUsingMls_idAndOrdering ( $this->getId (), $idx + 1 );
				$this->deleteImagesFile ( $filename, self::IMAGES_SUBDIRECTORY );
				$this->deleteImagesFile ( $filename, self::IMAGES_SUBDIRECTORY . DS . self::THUMBS_SUB_SUBDIRECTORY );
			}
			return '';
		}
		return null;
	}
	private static function checkNFixDirectories() {
		if (! file_exists ( JPATH_ROOT . DS . 'images' . DS . 'ezrealty' )) {
			mkdir ( JPATH_ROOT . DS . 'images' . DS . 'ezrealty' );
		}
		if (! file_exists ( JPATH_ROOT . DS . 'images' . DS . 'ezrealty' . DS . self::IMAGES_SUBDIRECTORY )) {
			mkdir ( JPATH_ROOT . DS . 'images' . DS . 'ezrealty' . DS . self::IMAGES_SUBDIRECTORY );
		}
		if (! file_exists ( JPATH_ROOT . DS . 'images' . DS . 'ezrealty' . DS . self::IMAGES_SUBDIRECTORY . DS . self::THUMBS_SUB_SUBDIRECTORY )) {
			mkdir ( JPATH_ROOT . DS . 'images' . DS . 'ezrealty' . DS . self::IMAGES_SUBDIRECTORY . DS . self::THUMBS_SUB_SUBDIRECTORY );
		}
	}
	private function generateThumnailImage($mainPath, $newimage, $thumbPath) {
		ReaxmlImporter::LogAdd ( JText::sprintf ( 'LIB_REAXML_INFO_MESSAGE_GENERATING_THUMBNAIL', $newimage ), JLog::INFO );
		
		$mainPath = JPATH_ROOT . DS . 'images' . DS . 'ezrealty' . DS . $mainPath;
		
		$thumbPath = $mainPath . DS . $thumbPath . DS;
		$src = $mainPath . DS . $newimage;
		$ezrparams = JComponentHelper::getParams ( 'com_ezrealty' );
		$newThumbwidth = $ezrparams->get ( 'newthumbwidth', 200 );
		ReaxmlEzrImagehelper::createThumbs ( $src, $thumbPath, $newThumbwidth );
		return $newimage;
	}
}