<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/ 
class ReaxmlEzrColBuildingfeatures extends \ReaxmlEzrColumn {
	public static $mappings = array (
			array (
					'xpath' => '//features/balcony',
					'text' => 'LIB_REAXML_BUILDING_FEATURE_BALCONY' 
			),
			array (
					'xpath' => '//features/deck',
					'text' => 'LIB_REAXML_BUILDING_FEATURE_DECK' 
			),
			array (
					'xpath' => '//features/courtyard',
					'text' => 'LIB_REAXML_BUILDING_FEATURE_COURTYARD' 
			),
			array (
					'xpath' => '//features/floorboards',
					'text' => 'LIB_REAXML_BUILDING_FEATURE_FLOORBOARDS' 
			)
	);
	public function getValue() {
		return $this->getFeaturesValue(self::$mappings, 'lookupEzrBuildingFeatures');
	}
}