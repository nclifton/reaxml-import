<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/ 
class ReaxmlEzrColParkingspaceyn extends \ReaxmlEzrColumn {
	const XPATH_FEATURES_OPEN_SPACES = '//features/openSpaces';
	public function getValue() {
		$found = $this->xml->xpath ( self::XPATH_FEATURES_OPEN_SPACES );
		if (count($found) == 0) {
			return $this->isNew () ? false : null;
		} else {
			return $this->featureBoolean($found[0]);
		}
	}
}