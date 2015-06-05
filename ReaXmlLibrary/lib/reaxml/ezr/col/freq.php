<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @package Library REAXML Library for Joomla! 3.4
 * @version 1.4.8: freq.php 2015-07-01T05:31:35.565
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014, 2015 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 *
 **/ 
class ReaxmlEzrColFreq extends \ReaxmlEzrColumn {
	const XPATH_RENTAL_WEEKLY = '/rental/rent[@period="weekly" or @period="week"]/@period';
	const XPATH_HOLIDAY_RENTAL_WEEKLY = '/holidayRental/rent[@period="weekly" or @period="week"]/@period';
	const XPATH_RENTAL_MONTHLY = '/rental/rent[@period="monthly" or @period="month"]/@period';
	const XPATH_HOLIDAY_RENTAL_MONTHLY = '/holidayRental/rent[@period="monthly" or @period="month"]/@period';
	// const XPATH_WEEKLY = self::XPATH_RENTAL_WEEKLY . '|' . self::XPATH_HOLIDAY_RENTAL_WEEKLY;
	// const XPATH_MONTHLY = self::XPATH_RENTAL_MONTHLY . '|' . self::XPATH_HOLIDAY_RENTAL_MONTHLY;
	const XPATH_COMMERCIAL_RENTAL_ANNUAL_SPECIFIED = '/commercial/commercialRent[@period="annual"]/@period';
	const XPATH_COMMERCIAL_RENT = '/commercial/commercialRent';
	// const XPATH_COMMERCIAL_RENTAL_ANNUAL = self::XPATH_COMMERCIAL_RENT . '|' . self::XPATH_COMMERCIAL_RENTAL_ANNUAL_SPECIFIED;
	const XPATH_COMMERCIAL_RENT_PER_SQUARE_METRE_RANGE_MIN = '/commercial/commercialRent/rentPerSquareMetre/range/min';
	const XPATH_COMMERCIAL_RENT_PER_SQUARE_METRE_RANGE_MAX = '/commercial/commercialRent/rentPerSquareMetre/range/max';
	const XPATH_NOT_APPLICABLE = '/residential | /rural | /land | /commercialLand | /business ';
	const FREQ_NOT_APPLICABLE = 0;
	const FREQ_WEEKLY = 2;
	const FREQ_MONTHLY = 4;
	const FREQ_YEARLY = 7;
	const FREQ_PERSQUAREMETRE = 6;
	public function getValue() {
		if ($this->xml->xpath ( self::XPATH_NOT_APPLICABLE ) != false) {
			return self::FREQ_NOT_APPLICABLE;
		} else {
			if ($this->xml->xpath ( self::XPATH_RENTAL_WEEKLY . '|' . self::XPATH_HOLIDAY_RENTAL_WEEKLY ) != false) {
				return self::FREQ_WEEKLY;
			} else {
				if ($this->xml->xpath ( self::XPATH_RENTAL_MONTHLY . '|' . self::XPATH_HOLIDAY_RENTAL_MONTHLY ) != false) {
					return self::FREQ_MONTHLY;
				} else {
					if ($this->xml->xpath ( self::XPATH_COMMERCIAL_RENT_PER_SQUARE_METRE_RANGE_MIN ) != false && $this->xml->xpath ( self::XPATH_COMMERCIAL_RENT_PER_SQUARE_METRE_RANGE_MAX ) != false) {
						return self::FREQ_PERSQUAREMETRE;
					} else {
						if ($this->xml->xpath ( self::XPATH_COMMERCIAL_RENT . '|' . self::XPATH_COMMERCIAL_RENTAL_ANNUAL_SPECIFIED ) != false) {
							return self::FREQ_YEARLY;
						} else {
							if ($this->isNew ()) {
								return self::FREQ_NOT_APPLICABLE;
							} else {
								return null;
							}
						}
					}
				}
			}
		}
	}
}