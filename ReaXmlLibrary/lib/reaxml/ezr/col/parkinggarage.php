<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/ 
class ReaxmlEzrColParkinggarage extends \ReaxmlEzrColumn {
	const XPATH_FEATURES_GARAGES = '//features/garages';
	public function getValue() {
		$found = $this->xml->xpath ( self::XPATH_FEATURES_GARAGES );
		if (count($found) >0) {
			return intval ( $found [0] );
		} else {
			return $this->isNew () ? 0 : null;
		}
	}
}