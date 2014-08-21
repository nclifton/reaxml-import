<?php

class ReaxmlEzrColOwner extends ReaxmlEzrColumn {
	const XPATH = '//listingAgent/name';
	
	/*
	 * (non-PHPdoc) @see ReaxmlDbColumn::getValue()
	 */
	public function getValue() {
		$agentName = $this->xml->xpath ( self::XPATH );
		if ($agentName == false) {
			if ($this->isNew ()) {
				throw new RuntimeException ( JText::_ ( 'LIB_REAXML_ERROR_MESSAGE_LISTING_AGENT_NAME_NOT_IN_XML' ) );
			} else {
				return null;
			}
		}
		// lookup ezrealty agent using name in the ezportal table
		$id = $this->dbo->lookupEzrAgentUidUsingAgentName ( $agentName [0] );
		if ($id == false) {
			throw new RuntimeException ( JText::sprintf ( 'LIB_REAXML_ERROR_MESSAGE_DB_NO_AGENT_MATCH', $agentName [0] ) );
		}
		return $id;
	}
}