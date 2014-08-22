<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/ 
class ReaxmlEzrColFreq extends \ReaxmlEzrColumn {
	const XPATH_WEEKLY = ' /rental/rent[@period="weekly" or @period="week"]/@period  | /holidayRental/rent[@period="weekly" or @period="week"]/@period';
	const XPATH_NOT_APPLICABLE = '/residential | /rural | /land | /commercial | /commercialLand | /business ';
	
	const FREQ_NOT_APPLICABLE = 0;
	const FREQ_WEEKLY = 2;
	
	public function getValue() {
		$foundweekly = $this->xml->xpath ( self::XPATH_WEEKLY );
		$foundnotapplicable = $this->xml->xpath ( self::XPATH_NOT_APPLICABLE );
		if ($foundweekly == false && $foundnotapplicable == false) {
			if ($this->isNew ()) {
				return self::FREQ_NOT_APPLICABLE;
			} else {
				return null;
			}
		} else if ($foundnotapplicable == false) {
			return self::FREQ_WEEKLY;
		} else {
			return self::FREQ_NOT_APPLICABLE;
		}
	}
}