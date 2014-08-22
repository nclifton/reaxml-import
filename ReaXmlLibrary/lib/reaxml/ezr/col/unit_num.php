<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/ 
class ReaxmlEzrColUnit_num extends \ReaxmlEzrColumn {
	const XPATH_ADDRESS_SUBNUMBER = '//address/subNumber';
	public function getValue() {
		$found = $this->xml->xpath ( self::XPATH_ADDRESS_SUBNUMBER );
		if (count($found) >0) {
			return $found [0].'';
		} else {
			return $this->isNew () ? '' : null;
		}
	}
}