<?php
error_reporting ( E_ALL & ~ E_STRICT & ~ E_NOTICE );
class ReaxmlEzrColImage_fname extends \ReaxmlEzrImagecolumn {
	const XPATH_IMG = '/*/objects/img[@id="%s"]';
	const XPATH_URL = '/*/objects/img[@id="%s"]/@url';
	const XPATH_FILE = '/*/objects/img[@id="%s"]/@file';
	const XPATH_FORMAT = '/*/objects/img[@id="%s"]/@format';
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