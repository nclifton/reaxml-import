<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/ 
class ReaxmlEzrColStid extends ReaxmlEzrColumn {
	const XPATH = '//address/state';
	
	/*
	 * (non-PHPdoc) @see ReaxmlDbColumn::getValue()
	 */
	public function getValue() {
		$found = $this->xml->xpath ( self::XPATH );
		if (count($found) == 0) {
			if ($this->isNew ()) {
				throw new RuntimeException ( JText::_ ( 'LIB_REAXML_ERROR_MESSAGE_ADDRESS_STATE_NOT_IN_XML' ) );
			} else {
				return null;
			}
		}
		$state = $found [0].'';
		$id = $this->dbo->lookupEzrStidUsingState ( $state );
		if ($id == false) {
			throw new RuntimeException ( JText::sprintf ( 'LIB_REAXML_ERROR_MESSAGE_DB_NO_ADDRESS_STATE_MATCH', $state ) );
		}
		return $id;
	}
}