<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/ 
class ReaxmlEzrColViewad extends \ReaxmlEzrColumn {
	const XPATH_ADDRESS_DISPLAY = '//address/@display';
	public function getValue() {
		$found = $this->xml->xpath ( self::XPATH_ADDRESS_DISPLAY );
		if (count($found) >0) {
			return (strtolower ( $found [0] ) == 'yes');
		} else {
			return $this->isNew () ? true : null;
		}
	}
}