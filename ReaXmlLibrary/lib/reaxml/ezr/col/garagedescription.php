<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/ 
class ReaxmlEzrColGaragedescription extends \ReaxmlEzrColumn {
	const XPATH_FEATURES_REMOTEGARAGE = '//features/remoteGarage';
	public function getValue() {
		$found = $this->xml->xpath ( self::XPATH_FEATURES_REMOTEGARAGE );
		if (count($found) >0) {
			switch (strtolower ( $found [0] )) {
				case 'yes' :
				case '1' :
				case 'true' :
					return JText::_ ( 'LIB_REAXML_EZR_GARAGE_REMOTE_CONTROL_DOOR' );
					break;
				
				default :
					return '';
					break;
			}
		} else {
			return $this->isNew () ? '' : null;
		}
	}
}