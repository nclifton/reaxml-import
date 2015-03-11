<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @package Library REAXML Library for Joomla! 3.3
 * @version 0.0.62: landareasqft.php 2015-03-12T03:45:43.139
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/ 
class ReaxmlEzrColLandareasqft extends \ReaxmlEzrColumn {
	const XPATH_LAND_DETAILS_AREA = '//landDetails/area';
	const XPATH_LAND_DETAILS_AREA_UNIT = '//landDetails/area/@unit';
	public function getValue() {
		$found = $this->xml->xpath ( self::XPATH_LAND_DETAILS_AREA );
		if (count($found) == 0) {
			return $this->isNew () ? '' : null;
		} else {
			$unitfound = $this->xml->xpath ( self::XPATH_LAND_DETAILS_AREA_UNIT );
			if ($unitfound == false) {
				return $found [0].'';
			}
			switch ($unitfound [0]) {
				case 'square' :
					return number_format ( floatval ( $found [0] ) * 9.290304, 0, '.', ',' );
					break;
				case 'acre' :
					return number_format ( floatval ( $found [0] ) / 0.00024711, 0, '.', ',' );
					break;
				case 'hectare' :
					return number_format ( floatval ( $found [0] ) * 10000, 0, '.', ',' );
				
				default :
					return $found [0].'';
					break;
			}
		}
	}
}