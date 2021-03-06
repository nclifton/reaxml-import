<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @package Library REAXML Library for Joomla!
 * @version 1.5.10: rent_type.php 2016-08-15T00:04:57.830
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014, 2015 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @since 3.6
 *
 **/ 
class ReaxmlEzrColRent_type extends \ReaxmlEzrColumn {

	const XPATH_COMMERCIAL_LISTING_TYPE = '//commercialListingType/@value';
	const XPATH_RENTAL = '//rental';
	const XPATH_HOLIDAY_RENTAL = '//holidayRental';
	const XPATH_RESIDENTIAL = '//residential';
	const XPATH_RURAL = '//rural';
	const XPATH_LAND = '//land';
	
	const RENT_TYPE_NOT_APPLICABLE = 0;
	const RENT_TYPE_LONG_TERM = 1;
	const RENT_TYPE_SHORT_TERM = 2;
	const RENT_TYPE_STUDENT = 3;
	const RENT_TYPE_COMMERCIAL = 4;
	
	const COMMERCIAL_LISTING_TYPE_LEASE = 'lease';
	const COMMERCIAL_LISTING_TYPE_BOTH = 'both';
	
		
	public function getValue() {
		if (! ($this->xml->xpath ( self::XPATH_RENTAL ) == false)) {
			return self::RENT_TYPE_LONG_TERM;
		}
		if (! ($this->xml->xpath ( self::XPATH_HOLIDAY_RENTAL ) == false)) {
			return self::RENT_TYPE_SHORT_TERM;
		}
		if (! ($this->xml->xpath ( self::XPATH_RESIDENTIAL ) == false)) {
			return self::RENT_TYPE_NOT_APPLICABLE;
		}
		if (! ($this->xml->xpath ( self::XPATH_RURAL ) == false)) {
			return self::RENT_TYPE_NOT_APPLICABLE;
		}
		if (! ($this->xml->xpath ( self::XPATH_LAND ) == false)) {
			return self::RENT_TYPE_NOT_APPLICABLE;
		}

		$foundCommercialListingType = $this->xml->xpath ( self::XPATH_COMMERCIAL_LISTING_TYPE );
		if (! ($foundCommercialListingType == false)) {
			if	($foundCommercialListingType [0] == self::COMMERCIAL_LISTING_TYPE_LEASE) {
				return self::RENT_TYPE_COMMERCIAL;
			} else if ($foundCommercialListingType [0] == self::COMMERCIAL_LISTING_TYPE_BOTH) {
				return self::RENT_TYPE_COMMERCIAL;
			} else {
				return self::RENT_TYPE_NOT_APPLICABLE;
			}
		} 

		return ($this->isNew()) ? self::RENT_TYPE_NOT_APPLICABLE : null;
	}
}