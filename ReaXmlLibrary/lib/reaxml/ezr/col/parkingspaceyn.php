<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @package Library REAXML Library for Joomla! 3.4
 * @version 1.5.3: parkingspaceyn.php 2015-07-23T22:28:40.085
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014, 2015 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 *
 **/ 
class ReaxmlEzrColParkingspaceyn extends \ReaxmlEzrColumn {
	const XPATH_FEATURES_OPEN_SPACES = '//features/openSpaces';
	public function getValue() {
		$found = $this->xml->xpath ( self::XPATH_FEATURES_OPEN_SPACES );
		if (count($found) == 0) {
			return $this->isNew () ? false : null;
		} else {
			return $this->featureBoolean($found[0]);
		}
	}
}