<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @package Library REAXML Library for Joomla! 3.4
 * @version 1.2.5: price.php 2015-04-07T14:41:26.244
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 *
 **/ 
class ReaxmlEzrColPrice extends \ReaxmlEzrColumn {
	const XPATH_RESIDENTIAL_PRICE = '//residential/price';
	const XPATH_RURAL_PRICE = '//rural/price';
	const XPATH_LAND_PRICE = '//land/price';
	const XPATH_BUSINESS_PRICE = '//business/price';
	const XPATH_COMMMERCIAL_RENTAL_RENT = '//commercial/commercialRent';
	const XPATH_COMMMERCIAL_PRICE = '//commercial/price';
	const XPATH_RENTAL_RENT = '//rental/rent';
	const XPATH_HOLIDAY_RENTAL_RENT ='//holidayRental/rent';
	//const XPATH_PRICE = self::XPATH_RESIDENTIAL_PRICE.'|'.self::XPATH_RURAL_PRICE.'|'.self::XPATH_LAND_PRICE.'|'.self::XPATH_BUSINESS_PRICE.'|'.self::XPATH_COMMMERCIAL_RENTAL_RENT.'|'.self::XPATH_COMMMERCIAL_PRICE.'|'.self::XPATH_RENTAL_RENT.'|'.self::XPATH_HOLIDAY_RENTAL_RENT ;
	
	public function getValue() {
		$found = $this->xml->xpath ( self::XPATH_RESIDENTIAL_PRICE.'|'.self::XPATH_RURAL_PRICE.'|'.self::XPATH_LAND_PRICE.'|'.self::XPATH_BUSINESS_PRICE.'|'.self::XPATH_COMMMERCIAL_RENTAL_RENT.'|'.self::XPATH_COMMMERCIAL_PRICE.'|'.self::XPATH_RENTAL_RENT.'|'.self::XPATH_HOLIDAY_RENTAL_RENT );
		if (count($found) == 0) {
			if ($this->isNew ()) {
				throw new RuntimeException(JText::_('LIB_REAXML_ERROR_MESSAGE_PRICE_NOT_FOUND_IN_NEW_PROPERTY_XML'));
			} else {
				return null;
			}
		} else {

			return floatval($found[0]);

		}
	}
}