<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @package Library REAXML Library for Joomla!
 * @version 1.5.10: pdfinfo1.php 2016-08-15T00:04:57.830
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014, 2015 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @since 3.6
 *
 **/ 
class ReaxmlEzrColPdfinfo1 extends \ReaxmlEzrColumn {
	const XPATH_DOCUMENT = '//objects/document[@id="1"]';
	const XPATH_URL = '//objects/document[@id="1"]/@url';
	const XPATH_FILE = '//objects/document[@id="1"]/@file';
	const XPATH_FORMAT = '//objects/document[@id="1"]/@format';
	const IMAGES_SUBDIRECTORY = 'pdfs';

	public function getValue() {
		$found = $this->xml->xpath ( self::XPATH_DOCUMENT );
		if (count ( $found ) == 0) {
			return $this->isNew () ? '' : null;
		} else {
			$found = $this->xml->xpath ( self::XPATH_FILE );
			if (count ( $found ) > 0) {
				return $this->copyWorkImageFile ( $found [0], self::IMAGES_SUBDIRECTORY );
			}
			$found = $this->xml->xpath ( self::XPATH_URL );
			if (count ( $found ) > 0) {
				return $this->downloadUrlImageFile ( $found [0], $this->xml->xpath ( self::XPATH_FORMAT )[0], self::IMAGES_SUBDIRECTORY );
			}
			if (! $this->isNew ()) {
				$filename = $this->dbo->lookupEzrPdfinfo1 ( $this->getId () );
				$this->deleteImagesFile ( $filename, self::IMAGES_SUBDIRECTORY );
			}
			return '';
		}
		return null;
	}
}