<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
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