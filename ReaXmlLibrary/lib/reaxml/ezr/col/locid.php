<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @package Library REAXML Library for Joomla! 3.3
 * @version 0.0.61: locid.php 2015-03-12T02:22:10.059
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/ 
class ReaxmlEzrColLocid extends ReaxmlEzrColumn {
	const XPATH = '//address/suburb';
	
	/*
	 * (non-PHPdoc) @see ReaxmlDbColumn::getValue()
	 */
	public function getValue() {
		$found = $this->xml->xpath ( self::XPATH );
		if (count($found) == 0) {
			if ($this->isNew ()) {
				throw new RuntimeException ( JText::_ ( 'LIB_REAXML_ERROR_MESSAGE_ADDRESS_SUBURB_NOT_IN_XML' ) );
			} else {
				return null;
			}
		}
		// lookup locality id using name in the ezportal table
		$suburb = $found [0].'';
		$id = $this->dbo->lookupEzrLocidUsingSuburb ( $suburb );
		if ($id == false) {
			throw new RuntimeException ( JText::sprintf ( 'LIB_REAXML_ERROR_MESSAGE_DB_NO_ADDRESS_SUBURB_MATCH', $suburb ) );
		}
		return $id;
	}
}