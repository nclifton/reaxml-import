<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @package Library REAXML Library for Joomla! 3.3
 * @version 0.0.62: freq.php 2015-03-12T03:45:43.139
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
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