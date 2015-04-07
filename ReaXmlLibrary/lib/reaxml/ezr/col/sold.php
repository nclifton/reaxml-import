<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @package Library REAXML Library for Joomla! 3.4
 * @version 1.2.5: sold.php 2015-04-07T14:41:26.244
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 *
 **/ 
class ReaxmlEzrColSold extends \ReaxmlEzrColumn {
	const XPATH_STATUS = '//@status';
	const XPATH_UNDER_OFFER = '//underOffer/@value';
	const SOLD_CURRENT = 1;
	const SOLD_UNDEROFFER = 2;
	const SOLD_SOLD = 5;
	const SOLD_LEASED = 9;
	const SOLD_WITHDRAWN = 8;
	const SOLD_OFFMARKET = 10;
	const STATUS_CURRENT = 'current';
	const STATUS_WITHDRAWN = 'withdrawn';
	const STATUS_SOLD = 'sold';
	const STATUS_OFFMAEKET = 'offmarket';
	const STATUS_LEASED = 'leased';
	public function getValue() {
		$foundunderoffer = $this->xml->xpath ( self::XPATH_UNDER_OFFER );
		$foundstatus = $this->xml->xpath ( self::XPATH_STATUS );
		if ((count($foundunderoffer) > 0) && $this->featureBoolean($foundunderoffer[0].'')) {
			return self::SOLD_UNDEROFFER;
		} else if (count($foundstatus) == 0) {
			if ($this->isNew ()) {
				throw new RuntimeException ( JText::_ ( 'LIB_REAXML_ERROR_MESSAGE_VALID_STATUS_NOT_IN_NEW_PROPERTY_LISTING_XML' ) );
			} else {
				return null;
			}
		} else {
			switch ($foundstatus [0].'') {
				case self::STATUS_CURRENT :
					return self::SOLD_CURRENT;
					break;
				case self::STATUS_WITHDRAWN :
					return self::SOLD_WITHDRAWN;
					break;
				case self::STATUS_SOLD :
					return self::SOLD_SOLD;
					break;
				case self::STATUS_OFFMAEKET :
					return self::SOLD_OFFMARKET;
					break;
				case self::STATUS_LEASED :
					return self::SOLD_LEASED;
					break;
				
				default :
					if ($this->isNew ()) {
						throw new RuntimeException ( JText::_ ( 'LIB_REAXML_ERROR_MESSAGE_VALID_STATUS_NOT_IN_NEW_PROPERTY_LISTING_XML' ) );
					} else {
						return null;
					}
					break;
			}
		}
	}
}