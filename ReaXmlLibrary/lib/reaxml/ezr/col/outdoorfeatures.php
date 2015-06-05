<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @package Library REAXML Library for Joomla! 3.4
 * @version 1.4.8: outdoorfeatures.php 2015-07-01T05:31:35.565
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014, 2015 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 *
 **/ 
class ReaxmlEzrColOutdoorfeatures extends \ReaxmlEzrColumn {
	public static $mappings = array (
			array (
					'xpath' => '//features/pool[@type="inground"] | /*/features/poolInGround',
					'text' => 'LIB_REAXML_OUTDOOR_FEATURE_IN_GROUND_POOL' 
			),
			array (
					'xpath' => '//features/pool[@type="aboveground"] | /*/features/poolAboveGround',
					'text' => 'LIB_REAXML_OUTDOOR_FEATURE_ABOVE_GROUND_POOL' 
			),
			array (
					'xpath' => '//features/spa[@type="aboveground"] | /*/features/aboveGroundSpa',
					'text' => 'LIB_REAXML_OUTDOOR_FEATURE_ABOVE_GROUND_SPA' 
			),
			array (
					'xpath' => '//features/spa[@type="inground"] | /*/features/inGroundSpa',
					'text' => 'LIB_REAXML_OUTDOOR_FEATURE_IN_GROUND_SPA' 
			),
			array (
					'xpath' => '//features/outsideSpa',
					'text' => 'LIB_REAXML_FEATURE_SPA'
			),
			array (
					'xpath' => '//features/tennisCourt',
					'text' => 'LIB_REAXML_OUTDOOR_FEATURE_TENNIS_COURT'
			),
			array (
					'xpath' => '//features/shed',
					'text' => 'LIB_REAXML_OUTDOOR_FEATURE_SHED'
			),
			array (
					'xpath' => '//features/outdoorEnt',
					'text' => 'LIB_REAXML_OUTDOOR_FEATURE_OUTDOOR_ENTERTAINMENT'
			)
	);
	public function getValue() {
		return $this->getFeaturesValue(self::$mappings, 'lookupEzrOutdoorFeatures');
	}
}