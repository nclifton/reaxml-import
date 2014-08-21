<?php
class ReaxmlEzrColAdline extends \ReaxmlEzrColumn {
	const XPATH_HEADLINE = '//headline';
	public function getValue() {
		$found = $this->xml->xpath ( self::XPATH_HEADLINE );
		if (count($found) == 0) {
			if ($this->isNew ()) {
				return '';
			} else {
				return null;
			}
		} else {
			return $found [0].'';
		}
	}
}