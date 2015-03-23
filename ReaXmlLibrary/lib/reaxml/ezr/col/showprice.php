<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @package Library REAXML Library for Joomla! 3.3
 * @version 0.0.79: showprice.php 2015-03-20T17:13:33.572
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/ 
class ReaxmlEzrColShowprice extends \ReaxmlEzrColumn {
	const XPATH_PRICE_RENT_DISPLAY = '//price/@display | /*/rent/@display';
	public function getValue() {
		$found = $this->xml->xpath ( self::XPATH_PRICE_RENT_DISPLAY );
		if (count($found) == 0) {
			if ($this->isNew ()) {
				return true;
			} else {
				return null;
			}
		} else {
			return (strtolower ( ($found [0]) ) == 'yes');
		}
	}
}