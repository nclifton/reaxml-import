<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @package Library REAXML Library for Joomla! 3.3
 * @version 0.0.52: outdoorfeatures.php 2014-09-12T14:10:36.970
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/features/inGroundSpa',
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