<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @package Library REAXML Library for Joomla! 3.3
 * @version 0.0.52: type.php 2014-09-12T14:10:36.970
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/ 
class ReaxmlEzrColType extends \ReaxmlEzrColumn {
	const XPATH_AUTHORITY = '//authority/@value';
	const XPATH_COMMERCIAL_AUTHORITY = '//commercialAuthority/@value';
	const XPATH_COMMERCIAL_LISTING_TYPE = '//commercialListingType/@value';
	const XPATH_RENTAL = '//rental';
	const XPATH_HOLIDAY_RENTAL = '//holidayRental';
	const TRANSACTION_TYPE_FOR_SALE = 1;
	const TRANSACTION_TYPE_FOR_RENT = 2;
	const TRANSACTION_TYPE_FOR_LEASE = 3;
	const TRANSACTION_TYPE_FOR_AUCTION = 4;
	const TRANSACTION_TYPE_PROPERTY_EXCHANGE = 5;
	const TRANSACTION_TYPE_SALE_BY_TENDER = 6;
	const TRANSACTION_TYPE_SHARE_ACCOM = 7;
	const AUTHORITY_AUCTION = 'auction';
	const COMMERCIAL_LISTING_TYPE_LEASE = 'lease';
	const COMMERCIAL_AUTHORITY_AUCTION = 'auction';
	const COMMERCIAL_AUTHORITY_TENDER = 'tender';
	public function getValue() {
		if (! ($this->xml->xpath ( self::XPATH_RENTAL ) == false)) {
			return self::TRANSACTION_TYPE_FOR_RENT;
		}
		if (! ($this->xml->xpath ( self::XPATH_HOLIDAY_RENTAL ) == false)) {
			return self::TRANSACTION_TYPE_FOR_RENT;
		}
		$found = $this->xml->xpath ( self::XPATH_AUTHORITY );
		if (count($found) > 0) {
			switch ($found [0].'') {
				case self::AUTHORITY_AUCTION :
					return self::TRANSACTION_TYPE_FOR_AUCTION;
					break;
				
				default :
					return self::TRANSACTION_TYPE_FOR_SALE;
					break;
			}
		}
		$foundCommercialAuthority = $this->xml->xpath ( self::XPATH_COMMERCIAL_AUTHORITY );
		$foundCommercialListingType = $this->xml->xpath ( self::XPATH_COMMERCIAL_LISTING_TYPE );
		if (count($foundCommercialListingType) > 0) {
			if	($foundCommercialListingType [0].'' == self::COMMERCIAL_LISTING_TYPE_LEASE) {
				return self::TRANSACTION_TYPE_FOR_LEASE;
			}
			if (count($foundCommercialAuthority) == 0) {
				return self::TRANSACTION_TYPE_FOR_SALE;
			}
		} 
		if (count ($foundCommercialAuthority) > 0) {
			switch ($foundCommercialAuthority[0].'') {
				case self::COMMERCIAL_AUTHORITY_AUCTION :
					return self::TRANSACTION_TYPE_FOR_AUCTION;
					break;
				case self::COMMERCIAL_AUTHORITY_TENDER :
					return self::TRANSACTION_TYPE_SALE_BY_TENDER;
					break;
				
				default :
					return self::TRANSACTION_TYPE_FOR_SALE;
					break;
			}
		}
		return ($this->isNew()) ? self::TRANSACTION_TYPE_FOR_SALE : null;
	}
}