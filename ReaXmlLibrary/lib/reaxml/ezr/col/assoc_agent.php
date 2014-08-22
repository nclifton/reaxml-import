<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/ 
class ReaxmlEzrColAssoc_agent extends ReaxmlEzrColumn {
	const XPATH1 = '//listingAgent[@id="2"]/name';
	const XPATH2 = '//listingAgent[2]/name';
	
	/*
	 * (non-PHPdoc) @see ReaxmlDbColumn::getValue()
	 */
	public function getValue() {
		$agentName = $this->xml->xpath ( self::XPATH1 );
		if ($agentName == false) {
			$agentName = $this->xml->xpath ( self::XPATH2 );
			if ($agentName == false) {
				return false;
			}
		}
		// lookup ezrealty agent using name in the ezportal table
		$id = $this->dbo->lookupEzrAgentUidUsingAgentName ( $agentName [0] );
		if ($id == false) {
			throw new RuntimeException ( JText::sprintf( 'LIB_REAXML_ERROR_MESSAGE_DB_NO_ASSOC_AGENT_MATCH' ,$agentName[0]) );
		}
		return $id;
	}
}