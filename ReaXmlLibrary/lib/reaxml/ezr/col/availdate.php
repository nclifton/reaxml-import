<?php
class ReaxmlEzrColAvaildate extends \ReaxmlEzrColumn {
	const XPATH = '//dateAvailable';

	public function getValue() {
		$found = $this->xml->xpath ( self::XPATH );
		if (count($found) > 0){
			$datestring = $found[0];
			return date('Y-m-d', strtotime($datestring));
		} else {
			return null;
		}
	}
}