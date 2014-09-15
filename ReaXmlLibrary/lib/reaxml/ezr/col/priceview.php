<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @package Library REAXML Library for Joomla! 3.3
 * @version 0.0.53: priceview.php 2014-09-15T16:21:18.708
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/ 
class ReaxmlEzrColPriceview extends \ReaxmlEzrColumn {
	const XPATH_PRICE_VIEW = '//priceView';
	
	public function getValue() {
		$found = $this->xml->xpath ( self::XPATH_PRICE_VIEW );
		if (count($found) == 0) {
			if ($this->isNew ()) {
				return '';
			} else {
				return null;
			}
		} else {

			return $found[0].'';

		}
	}
}