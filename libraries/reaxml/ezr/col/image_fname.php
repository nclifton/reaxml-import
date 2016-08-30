<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @package Library REAXML Library for Joomla!
 * @version 1.5.10: image_fname.php 2016-08-15T00:04:57.830
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014, 2015 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @since 3.6
 *
 **/ 
error_reporting ( E_ALL & ~ E_STRICT & ~ E_NOTICE );
define ( 'DS', DIRECTORY_SEPARATOR );
class ReaxmlEzrColImage_fname extends \ReaxmlEzrImagecolumn {
	private static $checkedDirectories = false;
	const XPATH_IMG = '//img[(parent::objects|parent::images) and @id="%s"]';
	const XPATH_URL = '//img[(parent::objects|parent::images) and @id="%s"]/@url';
	const XPATH_FILE = '//img[(parent::objects|parent::images) and @id="%s"]/@file';
	const XPATH_FORMAT = '//img[(parent::objects|parent::images) and @id="%s"]/@format';
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
				if ($fname !== '') {
					$fname = $this->generateThumnailImage ( self::IMAGES_SUBDIRECTORY, $fname, self::THUMBS_SUB_SUBDIRECTORY );
				}
				return $fname;
			}
			$nodes = $this->xml->xpath ( sprintf ( self::XPATH_URL, $ids [$idx] ) );
			if (count ( $nodes ) > 0) {
                $format = $this->xml->xpath(sprintf(self::XPATH_FORMAT, $ids [$idx]))[0];
                $fname = $this->downloadUrlImageFile ( $nodes [0] . '', $format . '', self::IMAGES_SUBDIRECTORY );
				if ($fname !== '') {
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
	private function generateThumnailImage($mainPath, $newImage, $thumbPath) {
		ReaxmlImporter::LogAdd ( JText::sprintf ( 'LIB_REAXML_INFO_MESSAGE_GENERATING_THUMBNAIL', $newImage ), JLog::INFO );
		
		$mainPath = JPATH_ROOT . DS . 'images' . DS . 'ezrealty' . DS . $mainPath;
		
		$thumbPath = $mainPath . DS . $thumbPath . DS;
		$src = $mainPath . DS . $newImage;
        $newThumbWidth = $this->configuration->get('newthumbwidth',200);
        $this->imageHelper->createThumbs ( $src, $thumbPath, $newThumbWidth );
		return $newImage;
	}


}