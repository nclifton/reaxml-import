<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @package Library REAXML Library for Joomla! 3.4
 * @version 1.2.1: unit_num.php 2015-03-28T04:18:12.779
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/ 
class ReaxmlEzrColUnit_num extends \ReaxmlEzrColumn {
	const XPATH_ADDRESS_SUBNUMBER = '//address/subNumber';
	public function getValue() {
		$found = $this->xml->xpath ( self::XPATH_ADDRESS_SUBNUMBER );
		if (count($found) >0) {
			return $found [0].'';
		} else {
			return $this->isNew () ? '' : null;
		}
	}
}