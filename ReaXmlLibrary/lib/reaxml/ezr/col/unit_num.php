<?php
class ReaxmlEzrColUnit_num extends \ReaxmlEzrColumn {
	const XPATH_ADDRESS_SUBNUMBER = '//address/subNumber';
	public function getValue() {
		$found = $this->xml->xpath ( self::XPATH_ADDRESS_SUBNUMBER );
		if (count($found) >0) {
			return $found [0].'';
		} else {
			return $this->isNew () ? '' : null;
		}
	}
}