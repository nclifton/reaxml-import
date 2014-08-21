<?php
class ReaxmlEzrColAucdate extends \ReaxmlEzrColumn {
	const XPATH = '//auction/@date';

	public function getValue() {
		$found = $this->xml->xpath ( self::XPATH );
		if (count($found) > 0){
			$datestring = $found[0].'';
			return date('Y-m-d', strtotime($datestring));
		} else {
			return null;
		}
	}
}