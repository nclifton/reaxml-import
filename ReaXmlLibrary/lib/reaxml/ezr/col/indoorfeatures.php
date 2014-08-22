<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/ 
class ReaxmlEzrColIndoorfeatures extends \ReaxmlEzrColumn {
	public static $mappings = array (
			array (
					'xpath' => '//features/airConditioning',
					'text' => 'LIB_REAXML_INDOOR_FEATURE_AIR_CONDITIONING' 
			),
			array (
					'xpath' => '//features/vacuumSystem',
					'text' => 'LIB_REAXML_INDOOR_FEATURE_VACUUM_SYSTEM' 
			),
			array (
					'xpath' => '//features/openFirePlace',
					'text' => 'LIB_REAXML_INDOOR_FEATURE_OPEN_FIRE_PLACE' 
			),
			array (
					'xpath' => '//features/heating[@type="gas"] | /*/features/gasHeating',
					'text' => 'LIB_REAXML_INDOOR_FEATURE_GAS_HEATING' 
			),
			array (
					'xpath' => '//features/heating[@type="electric"]',
					'text' => 'LIB_REAXML_INDOOR_FEATURE_ELECTRIC_HEATING' 
			),
			array (
					'xpath' => '//features/heating[@type="GDH"]',
					'text' => 'LIB_REAXML_INDOOR_FEATURE_GDH_HEATING' 
			),
			array (
					'xpath' => '//features/heating[@type="solid"]',
					'text' => 'LIB_REAXML_INDOOR_FEATURE_SOLID_FUEL_HEATING' 
			),
			array (
					'xpath' => '//features/heating[@type="other"]',
					'text' => 'LIB_REAXML_INDOOR_FEATURE_HEATING' 
			),
			array (
					'xpath' => '//features/hotWaterService[@type="gas"]',
					'text' => 'LIB_REAXML_INDOOR_FEATURE_GAS_HOT_WATER' 
			),
			array (
					'xpath' => '//features/hotWaterService[@type="electric"]',
					'text' => 'LIB_REAXML_INDOOR_FEATURE_ELECTRIC_HOT_WATER' 
			),
			array (
					'xpath' => '//features/hotWaterService[@type="solar"] | /*/ecoFriendly/solarHotWater',
					'text' => 'LIB_REAXML_INDOOR_FEATURE_SOLAR_HOT_WATER' 
			),
			array (
					'xpath' => '//features/insideSpa | /*/features/spa[@type="indoor"]',
					'text' => 'LIB_REAXML_FEATURE_SPA' 
			),
			array (
					'xpath' => '//features/builtInRobes',
					'text' => 'LIB_REAXML_INDOOR_FEATURE_BUILTIN_ROBES' 
			),
			array (
					'xpath' => '//features/ductedCooling',
					'text' => 'LIB_REAXML_INDOOR_FEATURE_DUCTED_COOLING' 
			),
			array (
					'xpath' => '//features/ductedHeating',
					'text' => 'LIB_REAXML_INDOOR_FEATURE_DUCTED_HEATING' 
			),
			array (
					'xpath' => '//features/evaporativeCooling',
					'text' => 'LIB_REAXML_INDOOR_FEATURE_EVAPORATIVE_COOLING' 
			),
			array (
					'xpath' => '//features/hydronicHeating',
					'text' => 'LIB_REAXML_INDOOR_FEATURE_HYDRONIC_HEATING' 
			),
			array (
					'xpath' => '//features/reverseCycleAirCon',
					'text' => 'LIB_REAXML_INDOOR_FEATURE_REVERSE_CYCLE_AIR_CONDITIONING' 
			),
			array (
					'xpath' => '//features/splitSystemAirCon',
					'text' => 'LIB_REAXML_INDOOR_FEATURE_SPLIT_SYSTEM_AIR_CONDITIONING' 
			),
			array (
					'xpath' => '//features/splitSystemHeating',
					'text' => 'LIB_REAXML_INDOOR_FEATURE_SPLIT_SYSTEM_HEATING' 
			) 
	);
	public function getValue() {
		return $this->getFeaturesValue(self::$mappings, 'lookupEzrIndoorFeatures');
	}
}