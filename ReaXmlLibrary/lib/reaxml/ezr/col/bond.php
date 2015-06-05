<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @package Library REAXML Library for Joomla! 3.4
 * @version 1.4.8: bond.php 2015-07-01T05:31:35.565
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014, 2015 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 *
 **/ 
class ReaxmlEzrColBond extends \ReaxmlEzrColumn {
	const XPATH = '//rental/bond|//holidayRental/bond';
	public function getValue() {
		$found = $this->xml->xpath ( self::XPATH );
		if (count($found) == 0){
			return $this->isNew () ? 0 : null;
		} else {
			return floatval($found[0]);
		}
	}
}