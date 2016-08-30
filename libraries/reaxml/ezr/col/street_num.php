<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @package Library REAXML Library for Joomla!
 * @version 1.5.10: street_num.php 2016-08-15T00:04:57.830
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014, 2015 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @since 3.6
 *
 **/ 
class ReaxmlEzrColStreet_num extends \ReaxmlEzrColumn {
	const XPATH_ADDRESS_STREETNUMBER = '//address/streetNumber';
	public function getValue() {
		$found = $this->xml->xpath ( self::XPATH_ADDRESS_STREETNUMBER );
		if (count($found) > 0) {
			return $found [0].'';
		} else {
			return $this->isNew () ? '' : null;
		}
	}
}