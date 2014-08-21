<?php
class ReaxmlEzrColLivingarea extends \ReaxmlEzrColumn {
	const XPATH_FEATURES_LIVINGAREAS = '//features/livingAreas';
	public function getValue() {
		$found = $this->xml->xpath ( self::XPATH_FEATURES_LIVINGAREAS );
		if (count($found) >0) {
			return $found [0].'';
		} else {
			return $this->isNew () ? '' : null;
		}
	}
}