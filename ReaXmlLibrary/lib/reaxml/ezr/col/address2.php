<?php
class ReaxmlEzrColAddress2 extends \ReaxmlEzrColumn {
	const XPATH_ADDRESS_STREET = '//address/street';
	
	public function getValue() {
		$found = $this->xml->xpath(self::XPATH_ADDRESS_STREET);
		if (count($found) > 0){
			return $found[0].'';
		} else {
			return $this->isNew() ? '': null;
		}
	}
}