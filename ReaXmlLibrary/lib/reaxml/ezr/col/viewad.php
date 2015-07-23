<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @package Library REAXML Library for Joomla! 3.4
 * @version 1.5.3: viewad.php 2015-07-23T22:28:40.085
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014, 2015 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 *
 **/ 
class ReaxmlEzrColViewad extends \ReaxmlEzrColumn {
	const XPATH_ADDRESS_DISPLAY = '//address/@display';
	public function getValue() {
		$found = $this->xml->xpath ( self::XPATH_ADDRESS_DISPLAY );
		if (count($found) >0) {
			return (strtolower ( $found [0] ) == 'yes');
		} else {
			return $this->isNew () ? true : null;
		}
	}
}