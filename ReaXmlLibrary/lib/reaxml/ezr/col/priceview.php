<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/ 
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