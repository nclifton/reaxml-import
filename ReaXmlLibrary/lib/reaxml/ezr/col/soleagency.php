<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @package Library REAXML Library for Joomla! 3.3
 * @version 0.0.52: soleagency.php 2014-09-12T14:10:36.970
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/
	public function getValue() {
		
		$found = $this->xml->xpath ( self::XPATH_EXCLUSIVITY );
		if (count($found) > 0) {
			return (( string ) $found [0].'' == 'exclusive');
		}
		
		$found = $this->xml->xpath ( self::XPATH_AUTHORITY );
		if (count($found) > 0) {
			switch ($found [0].'') {
				case 'multilist' :
				case 'conjunctional' :
				case 'open' :
					return false;
					break;
				case 'exclusive' :
					return true;
					break;
			}
		}
		
		$found = $this->xml->xpath ( self::XPATH_LISTINGAGENT );
		if (count ( $found ) > 0){
			return (count ( $found ) == 1);
		}
		return ($this->isNew()) ? false : null;

	}
}