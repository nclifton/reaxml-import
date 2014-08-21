<?php
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