<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
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