<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/ 
class ReaxmlEzrColParking extends \ReaxmlEzrColumn {
	const XPATH_FEATURES_SECURE_PARKING = '//features/secureParking';
	const XPATH_FEATURES_PARKING_COMMENTS = '//parkingComments';
	public function getValue() {
		$parking = array ();
		$idx = 0;
		$found = $this->xml->xpath ( self::XPATH_FEATURES_SECURE_PARKING );
		if (count($found) >0) {
			switch (strtolower ( $found [0] )) {
				case 'yes' :
				case '1' :
				case 'true' :
					$parking [$idx ++] = JText::_ ( 'LIB_REAXML_EZR_PARKING_SECURE_PARKING' );
					break;
				
				default :
					;
					break;
			}
		}
		$found = $this->xml->xpath ( self::XPATH_FEATURES_PARKING_COMMENTS );
		if (count($found) >0) {
			$parking [$idx ++] = $found [0].'';
		}
		
		return (count ( $parking ) > 0) ? join ( ". ", $parking ) : (($this->isNew ()) ? '' : null);
	}
}