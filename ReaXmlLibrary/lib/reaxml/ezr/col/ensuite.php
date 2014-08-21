<?php
class ReaxmlEzrColEnsuite extends \ReaxmlEzrColumn {
	const XPATH_FEATURES_ENSUITE = '//features/ensuite';
	public function getValue() {
		$found = $this->xml->xpath ( self::XPATH_FEATURES_ENSUITE );
		if (count($found) >0) {
			return intval ( $found [0] );
		} else {
			return $this->isNew () ? 0 : null;
		}
	}
}