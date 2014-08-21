<?php
class ReaxmlEzrColAuctime extends \ReaxmlEzrColumn {
	const XPATH = '//auction/@date';

	public function getValue() {
		$found = $this->xml->xpath ( self::XPATH );
		if (count($found) > 0){
			$datestring = $found[0];
			return date('H:i:s', strtotime($datestring));
		} else {
			return null;
		}
	}
}