<?php
class ReaxmlEzrColStreet_num extends \ReaxmlEzrColumn {
	const XPATH_ADDRESS_STREETNUMBER = '//address/streetNumber';
	public function getValue() {
		$found = $this->xml->xpath ( self::XPATH_ADDRESS_STREETNUMBER );
		if (count($found) > 0) {
			return $found [0].'';
		} else {
			return $this->isNew () ? '' : null;
		}
	}
}