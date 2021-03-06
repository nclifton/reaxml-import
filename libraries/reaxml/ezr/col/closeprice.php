<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @package Library REAXML Library for Joomla!
 * @version 1.5.10: closeprice.php 2016-08-15T00:04:57.830
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014, 2015 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @since 3.6
 *
 **/ 
class ReaxmlEzrColCloseprice extends \ReaxmlEzrColumn {
	const XPATH_CLOSE_PRICE_DISPLAY = '//soldDetails/soldPrice[@display="yes"]';
	const XPATH_CLOSE_PRICE_HIDE = '//soldDetails/soldPrice[@display="no" or @display="range" ]';
	public function getValue() {
		$founddisplay = $this->xml->xpath ( self::XPATH_CLOSE_PRICE_DISPLAY );
		$foundhide = $this->xml->xpath ( self::XPATH_CLOSE_PRICE_HIDE );
		if ($founddisplay == false && $foundhide == false) {
			if ($this->isNew ()) {
				return 0.0;
			} else {
				return null;
			}
		} else if ($founddisplay == false) {
			return 0.0;
		} else {
			return floatval ( $founddisplay [0] );
		}
	}
}