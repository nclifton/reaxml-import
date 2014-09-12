<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @package Library REAXML Library for Joomla! 3.3
 * @version 0.0.52: indoorfeatures.php 2014-09-12T14:10:36.970
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/features/spa[@type="indoor"]',
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