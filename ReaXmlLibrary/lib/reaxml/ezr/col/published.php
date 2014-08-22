<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/ 
class ReaxmlEzrColPublished extends ReaxmlEzrColumn {
	const XPATH = '//@status';
	
	/*
	 * (non-PHPdoc) @see ReaxmlDbColumn::getValue()
	 */
	public function getValue() {
		$found = $this->xml->xpath ( self::XPATH );
		if (count($found) == 0) {
			if ($this->isNew ()) {
				throw new RuntimeException ( JText::_ ( 'LIB_REAXML_ERROR_MESSAGE_XML_NO_MARKET_STATUS' ) );
			} else {
				return null;
			}
		}
		$status = $found [0].'';
		switch ($status) {
			case 'withdrawn' :
				return false;
				break;
			
			default :
				return true;
				break;
		}
	}
}