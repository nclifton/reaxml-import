<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/ 
class ReaxmlEzrColLot_num extends \ReaxmlEzrColumn {
	const XPATH_ADDRESS_LOTNUMBER = '//address/lotNumber';
	public function getValue() {
		$found = $this->xml->xpath ( self::XPATH_ADDRESS_LOTNUMBER );
		if (count($found) == 0) {
			return $this->isNew () ? '' : null;
		} else {
			return $found [0].'';
		}
	}
}