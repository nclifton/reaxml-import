<?php
class ReaxmlEzrColFrontage extends \ReaxmlEzrColumn {
	const XPATH = '//landDetails/frontage';
	public function getValue() {
		$found = $this->xml->xpath ( self::XPATH );
		if (count($found) == 0) {
			return $this->isNew () ? '' : null;
		} else {
			return  $found [0].'';
		}
	}
}