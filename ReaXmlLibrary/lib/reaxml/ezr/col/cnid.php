<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @package Library REAXML Library for Joomla! 3.4
 * @version 1.4.8: cnid.php 2015-07-01T05:31:35.565
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014, 2015 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 *
 **/ 
class ReaxmlEzrColCnid extends ReaxmlEzrColumn {
	const XPATH = '//address/country';
	
	/*
	 * (non-PHPdoc) @see ReaxmlDbColumn::getValue()
	 */
	public function getValue() {
		$found = $this->xml->xpath ( self::XPATH );
		if (count($found) == 0) {
			if ($this->isNew ()) {
				throw new RuntimeException ( JText::_ ( 'LIB_REAXML_ERROR_MESSAGE_ADDRESS_COUNTRY_NOT_IN_XML' ) );
			} else {
				return null;
			}
		}
		$value = $found [0].'';
		$id = $this->dbo->lookupEzrCnidUsingCountry ( $value );
		if ($id == false) {
			throw new RuntimeException ( JText::sprintf ( 'LIB_REAXML_ERROR_MESSAGE_DB_NO_ADDRESS_COUNTRY_MATCH', $value ) );
		}
		return $id;
	}
}