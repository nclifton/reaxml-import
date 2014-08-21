<?php
class ReaxmlEzrColPropdesc extends \ReaxmlEzrColumn {
	const XPATH_DESCRIPTION = '//description';
	public function getValue() {
		$found = $this->xml->xpath ( self::XPATH_DESCRIPTION );
		if (count ( $found ) == 0) {
			if ($this->isNew ()) {
				return '';
			} else {
				return null;
			}
		} else {
			return preg_replace ( "/\s+/", " ", $found [0] . '' );
		}
	}
}