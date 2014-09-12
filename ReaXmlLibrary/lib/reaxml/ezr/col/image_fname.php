<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @package Library REAXML Library for Joomla! 3.3
 * @version 0.0.52: image_fname.php 2014-09-12T14:10:36.970
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/objects/img[@id="%s"]/@format';
	const IMAGES_SUBDIRECTORY = 'properties';
	const THUMBS_SUB_SUBDIRECTORY = 'th';
	public function getValueAt($idx) {
		$ids = parent::getIdSequence ();
		$nodes = $this->xml->xpath ( sprintf ( self::XPATH_IMG, $ids [$idx] ) );
		if (count ( $nodes ) == 0) {
			return $this->isNew () ? '' : null;
		} else {
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
				$this->deleteImagesFile ( $filename, self::IMAGES_SUBDIRECTORY .DIRECTORY_SEPARATOR.self::THUMBS_SUB_SUBDIRECTORY);
			}
			return '';
		}
		return null;
	}
	private function generateThumnailImage($mainPath, $newimage, $thumbPath) {
		JLog::add ( JText::sprintf ( 'LIB_REAXML_INFO_MESSAGE_GENERATING_THUMBNAIL',$newimage), JLog::INFO, REAXML_LOG_CATEGORY );
		
		$mainPath = JPATH_ROOT . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'ezrealty' . DIRECTORY_SEPARATOR . $mainPath;
		$thumbPath = $mainPath . DIRECTORY_SEPARATOR . $thumbPath . DIRECTORY_SEPARATOR;
		$src = $mainPath . DIRECTORY_SEPARATOR . $newimage;
		$ezrparams = JComponentHelper::getParams ( 'com_ezrealty' );
		$newThumbwidth = $ezrparams->get ( 'newthumbwidth' );
		ReaxmlEzrImagehelper::createThumbs ( $src, $thumbPath, $newThumbwidth );
		return $newimage;
	}
}