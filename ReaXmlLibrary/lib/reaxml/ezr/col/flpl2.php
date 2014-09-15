<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @package Library REAXML Library for Joomla! 3.3
 * @version 0.0.54: flpl2.php 2014-09-15T18:47:20.143
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/ 
class ReaxmlEzrColFlpl2 extends \ReaxmlEzrColumn {
	const XPATH_FLOORPLAN = '//objects/floorplan[@id="2"]';
	const XPATH_URL = '//objects/floorplan[@id="2"]/@url';
	const XPATH_FILE = '//objects/floorplan[@id="2"]/@file';
	const XPATH_FORMAT = '//objects/floorplan[@id="2"]/@format';
	const IMAGES_SUBDIRECTORY = 'floorplans';
	public function getValue() {
		$found = $this->xml->xpath ( self::XPATH_FLOORPLAN );
		if (count ( $found ) == 0) {
			return $this->isNew () ? '' : null;
		} else {
			$found = $this->xml->xpath ( self::XPATH_FILE );
			if (count ( $found ) > 0) {
				return $this->copyWorkImageFile ( $found [0], 'floorplans' );
			}
			$found = $this->xml->xpath ( self::XPATH_URL );
			if (count ( $found ) > 0) {
				return $this->downloadUrlImageFile ( $found [0], $this->xml->xpath ( self::XPATH_FORMAT )[0], 'floorplans' );
			}
			if (! $this->isNew ()) {
				$filename = $this->dbo->lookupEzrFlpl2 ( $this->getId () );
				$this->deleteImagesFile ( $filename, self::IMAGES_SUBDIRECTORY );
			}
			return '';
		}
		return null;
	}
}