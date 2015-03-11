<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @package Library REAXML Library for Joomla! 3.3
 * @version 0.0.62: bedrooms.php 2015-03-12T03:45:43.139
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/ 
class ReaxmlEzrColBedrooms extends \ReaxmlEzrColumn {
	const XPATH_FEATURES_BEDROOMS = '//features/bedrooms';
	public function getValue() {
		$found = $this->xml->xpath ( self::XPATH_FEATURES_BEDROOMS );
		if (count($found) >0) {
			return intval ( $found [0] );
		} else {
			return $this->isNew () ? 0 : null;
		}
	}
}