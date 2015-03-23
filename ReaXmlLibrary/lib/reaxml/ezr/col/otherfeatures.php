<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @package Library REAXML Library for Joomla! 3.3
 * @version 0.0.79: otherfeatures.php 2015-03-20T17:13:33.572
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/ 
class ReaxmlEzrColOtherfeatures extends \ReaxmlEzrColumn {
	public static $mappings = array (
			array (
					'xpath' => '//features/remoteGarage',
					'text' => 'LIB_REAXML_OTHER_FEATURE_REMOTE_CONTROL_GARAGE_DOOR' 
			),
			array (
					'xpath' => '//features/secureParking',
					'text' => 'LIB_REAXML_OTHER_FEATURE_SECURE_PARKING' 
			),
			array (
					'xpath' => '//features/alarmSystem',
					'text' => 'LIB_REAXML_OTHER_FEATURE_ALARM_SYSTEM' 
			),
			array (
					'xpath' => '//features/intercom',
					'text' => 'LIB_REAXML_OTHER_FEATURE_INTERCOM' 
			),
			array (
					'xpath' => '//features/fullyFenced',
					'text' => 'LIB_REAXML_OTHER_FEATURE_FULLY_FENCED' 
			),
			array (
					'xpath' => '//features/broadband',
					'text' => 'LIB_REAXML_OTHER_FEATURE_BROADBAND' 
			),
			array (
					'xpath' => '//features/payTV',
					'text' => 'LIB_REAXML_OTHER_FEATURE_PAY_TV' 
			),
			array (
					'xpath' => '//ecoFriendly/solarPanels',
					'text' => 'LIB_REAXML_OTHER_FEATURE_SOLAR_PANELS' 
			),
			array (
					'xpath' => '//ecoFriendly/waterTank',
					'text' => 'LIB_REAXML_OTHER_FEATURE_WATER_TANK' 
			),
			array (
					'xpath' => '//ecoFriendly/greyWaterSystem',
					'text' => 'LIB_REAXML_OTHER_FEATURE_GREY_WATER_SYSTEM' 
			) 
	);
	const XPATH_OTHER_FEATURES = '//features/otherFeatures';
	public function getValue() {
		$valuestring = $this->getFeaturesValue ( self::$mappings, 'lookupEzrOtherFeatures' );
		
		/*
		 * is there an other features element in the xml? if so we need to add these to our mapped other features list.
		 */
		$found = $this->xml->xpath ( self::XPATH_OTHER_FEATURES );
		if (count ( $found ) == 0) {
			return $valuestring;
		} else {
			/*
			 * if null then is not new and there is no change to the mapped other features, so we need to get the current features from the db to be able to add the unmapped other featurers
			 */
			if ($valuestring == null) {
				$valuestring = $this->dbo->lookupEzrOtherFeatures ( $this->getId () );
			}
			/*
			 * drop any unmapped features from the list
			 */
			if (strlen ( $valuestring ) > 0) {
				$values = explode ( ";", $valuestring );
			} else {
				$values = array ();
			}
			$originalvalues = $values;
			foreach ( $values as $key => $value ) {
				if ($this->featureNotMapped ( $value )) {
					unset ( $values [$key] );
				}
			}
			/*
			 * add the xml other features (supposedly are unmapped other features)
			 */
			$otherFeatures = explode ( ',', $found [0] );
			// trim
			foreach ( $otherFeatures as $key => $value ) {
				$otherFeatures [$key] = ucfirst ( trim ( $value ) );
			}
			$values = array_values ( array_unique ( array_merge ( $values, $otherFeatures ) ) );
			/*
			 * if no changes to what was in the values array, ignoring order, and is not new, then return false
			 */
			if ((count ( $originalvalues ) == count ( $values )) && (count ( array_diff ( $originalvalues, $values ) ) > 0) && (! $this->isNew)) {
				return null;
			}
			$valuestring = join ( ";", $values );
			return ($this->isNew () && count ( $values ) == 0) ? '' : $valuestring;
		}
	}
	private function featureNotMapped($feature) {
		foreach ( self::$mappings as $mapping ) {
			if (JText::_ ( $mapping ['text'] ) == $feature) {
				return false; // is mapped
			}
		}
		return true; // is not mapped
	}
}