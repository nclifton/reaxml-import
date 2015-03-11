<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @package Library REAXML Library for Joomla! 3.3
 * @version 0.0.61: buildingfeatures.php 2015-03-12T02:22:10.059
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
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