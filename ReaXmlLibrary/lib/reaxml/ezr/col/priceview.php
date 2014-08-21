<?php
class ReaxmlEzrColPriceview extends \ReaxmlEzrColumn {
	const XPATH_PRICE_VIEW = '//priceView';
	
	public function getValue() {
		$found = $this->xml->xpath ( self::XPATH_PRICE_VIEW );
		if (count($found) == 0) {
			if ($this->isNew ()) {
				return '';
			} else {
				return null;
			}
		} else {

			return $found[0].'';

		}
	}
}