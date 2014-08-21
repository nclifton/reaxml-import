<?php
class ReaxmlEzrColCloseprice extends \ReaxmlEzrColumn {
	const XPATH_CLOSE_PRICE_DISPLAY = '//soldDetails/soldPrice[@display="yes"]';
	const XPATH_CLOSE_PRICE_HIDE = '//soldDetails/soldPrice[@display="no" or @display="range" ]';
	public function getValue() {
		$founddisplay = $this->xml->xpath ( self::XPATH_CLOSE_PRICE_DISPLAY );
		$foundhide = $this->xml->xpath ( self::XPATH_CLOSE_PRICE_HIDE );
		if ($founddisplay == false && $foundhide == false) {
			if ($this->isNew ()) {
				return 0.0;
			} else {
				return null;
			}
		} else if ($founddisplay == false) {
			return 0.0;
		} else {
			return floatval ( $founddisplay [0] );
		}
	}
}