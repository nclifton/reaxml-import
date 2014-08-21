<?php
class ReaxmlEzrColLot_num extends \ReaxmlEzrColumn {
	const XPATH_ADDRESS_LOTNUMBER = '//address/lotNumber';
	public function getValue() {
		$found = $this->xml->xpath ( self::XPATH_ADDRESS_LOTNUMBER );
		if (count($found) == 0) {
			return $this->isNew () ? '' : null;
		} else {
			return $found [0].'';
		}
	}
}