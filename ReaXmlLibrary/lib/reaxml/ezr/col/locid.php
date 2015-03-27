<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @package Library REAXML Library for Joomla! 3.4
 * @version 1.2.1: locid.php 2015-03-28T04:18:12.779
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/ 
class ReaxmlEzrColLocid extends ReaxmlEzrColumn {
	const SUBURB_XPATH = '//address/suburb';
	const POSTCODE_XPATH = '//address/postcode';
	const STATE_XPATH = '//address/state';
	const COUNTRY_XPATH = '//address/country';
	
		
	/*
	 * (non-PHPdoc) @see ReaxmlDbColumn::getValue()
	 */
	public function getValue() {
		$found = $this->xml->xpath ( self::SUBURB_XPATH );
		if (count($found) == 0) {
			if ($this->isNew ()) {
				throw new RuntimeException ( JText::_ ( 'LIB_REAXML_ERROR_MESSAGE_ADDRESS_SUBURB_NOT_IN_XML' ) );
			} else {
				return null;
			}
		}
		$suburb = $found [0].'';

		$found = $this->xml->xpath ( self::STATE_XPATH );
		if (count($found) == 0) {
			throw new RuntimeException ( JText::_ ( 'LIB_REAXML_ERROR_MESSAGE_ADDRESS_STATE_NOT_IN_XML' ) );
		}
		$state = $found[0].'';
		
		$found = $this->xml->xpath ( self::POSTCODE_XPATH );
		if (count($found) == 0) {
			throw new RuntimeException ( JText::_ ( 'LIB_REAXML_ERROR_MESSAGE_ADDRESS_POSTCODE_NOT_IN_XML' ) );
		}
		$postcode = $found[0].'';
		

		$found = $this->xml->xpath ( self::COUNTRY_XPATH );
		if (count($found) == 0) {
			throw new RuntimeException ( JText::_ ( 'LIB_REAXML_ERROR_MESSAGE_ADDRESS_COUNTRY_NOT_IN_XML' ) );
		}
		$country = $found[0].'';
		
		$locid = $this->dbo->lookupEzrLocidUsingLocalityDetails ( $suburb, $postcode, $state, $country );
		if ($locid == false) {
			$stateid = $this->dbo->lookupEzrStidUsingState($state);
			if($stateid == false){
				$countryid = $this->dbo->lookupEzrCnidUsingCountry($country);
				if ($countryid==false){
					$countryid = $this->dbo->insertEzrCountry($country);
				}
				$stateid = $this->dbo->insertEzrState($state,$countryid);
			}			
			return $this->dbo->insertEzrLocality($suburb,$stateid,$postcode);					
		}
		return $locid;
	}
}