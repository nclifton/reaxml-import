<?php
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