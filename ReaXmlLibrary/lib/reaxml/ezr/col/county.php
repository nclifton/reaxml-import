<?php
class ReaxmlEzrColCounty extends \ReaxmlEzrColumn {
	const XPATH_MUNICIPALITY = '//municipality';
	public function getValue() {
		$found = $this->xml->xpath ( self::XPATH_MUNICIPALITY );
		if (count($found) >0) {
			return $found [0].'';
		} else {
			return $this->isNew () ? '' : null;
		}
	}
}