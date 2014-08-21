<?php
class ReaxmlEzrColShowprice extends \ReaxmlEzrColumn {
	const XPATH_PRICE_RENT_DISPLAY = '//price/@display | /*/rent/@display';
	public function getValue() {
		$found = $this->xml->xpath ( self::XPATH_PRICE_RENT_DISPLAY );
		if (count($found) == 0) {
			if ($this->isNew ()) {
				return true;
			} else {
				return null;
			}
		} else {
			return (strtolower ( ($found [0]) ) == 'yes');
		}
	}
}