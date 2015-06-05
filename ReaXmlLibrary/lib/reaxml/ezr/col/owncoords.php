<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @package Library REAXML Library for Joomla! 3.4
 * @version 1.4.8: owncoords.php 2015-07-01T05:31:35.565
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014, 2015 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 *
 **/ 

class ReaxmlEzrColOwncoords extends ReaxmlEzrColumn{
	const XPATH_ADDRESS_LATITUDE = '//address/latitude';
	const XPATH_ADDRESS_LONGITUDE = '//address/longitude';

	/* (non-PHPdoc)
	 * @see ReaxmlDbColumn::getValue()
	 */
	public function getValue() {
		$found = $this->xml->xpath ( self::XPATH_ADDRESS_LATITUDE . '|' . self::XPATH_ADDRESS_LONGITUDE );
		if (count($found) == 0) {
			return $this->isNew() ? false : null;
		} else {
			return true;
		}
	}

	
}