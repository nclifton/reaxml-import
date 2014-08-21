<?php
class ReaxmlEzrColBedrooms extends \ReaxmlEzrColumn {
	const XPATH_FEATURES_BEDROOMS = '//features/bedrooms';
	public function getValue() {
		$found = $this->xml->xpath ( self::XPATH_FEATURES_BEDROOMS );
		if (count($found) >0) {
			return intval ( $found [0] );
		} else {
			return $this->isNew () ? 0 : null;
		}
	}
}