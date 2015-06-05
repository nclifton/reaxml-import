<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @package Library REAXML Library for Joomla! 3.4
 * @version 1.4.8: parking.php 2015-07-01T05:31:35.565
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014, 2015 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 *
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