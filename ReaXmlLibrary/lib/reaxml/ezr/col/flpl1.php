<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @package Library REAXML Library for Joomla! 3.4
 * @version 1.5.3: flpl1.php 2015-07-23T22:28:40.085
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014, 2015 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 *
 **/ 
class ReaxmlEzrColFlpl1 extends \ReaxmlEzrColumn {
	const XPATH_FLOORPLAN = '//objects/floorplan[@id="1"]';
	const XPATH_URL = '//objects/floorplan[@id="1"]/@url';
	const XPATH_FILE = '//objects/floorplan[@id="1"]/@file';
	const XPATH_FORMAT = '//objects/floorplan[@id="1"]/@format';
	const IMAGES_SUBDIRECTORY = 'floorplans';
	
	public function getValue() {
		$found = array();
		$found = $this->xml->xpath ( self::XPATH_FLOORPLAN );
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
				$filename = $this->dbo->lookupEzrFlpl1 ( $this->getId () );
				$this->deleteImagesFile ( $filename, self::IMAGES_SUBDIRECTORY );
			}
			return '';
		}
		return null;
	}
}