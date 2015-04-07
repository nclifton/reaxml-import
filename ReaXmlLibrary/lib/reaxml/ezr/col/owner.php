<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @package Library REAXML Library for Joomla! 3.4
 * @version 1.2.5: owner.php 2015-04-07T14:41:26.244
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 *
 **/ 

class ReaxmlEzrColOwner extends ReaxmlEzrColumn {
	const XPATH_PRIMARY_AGENT = '//listingAgent[@id=1] | //listingAgent[1]';
	const XPATH_NAME = 'name';
	const XPATH_TELEPHONE = 'telephone';
	const XPATH_EMAIL = 'email';
	
	/*
	 * (non-PHPdoc) @see ReaxmlDbColumn::getValue()
	 */
	public function getValue() {
		$foundagent = $this->xml->xpath ( self::XPATH_PRIMARY_AGENT );
		if ($foundagent == false) {
			if ($this->isNew ()) {
				throw new RuntimeException ( JText::_ ( 'LIB_REAXML_ERROR_MESSAGE_LISTING_AGENT_NAME_NOT_IN_XML' ) );
			} else {
				return null;
			}
		}
		// lookup ezrealty agent using name in the ezportal table
		$name = $foundagent[0]->xpath(self::XPATH_NAME);
		if ($name == false){
			throw new RuntimeException ( JText::_ ( 'LIB_REAXML_ERROR_MESSAGE_LISTING_AGENT_NAME_NOT_IN_XML' ) );
		}
		$uid = $this->dbo->lookupEzrAgentUidUsingAgentName ( $name [0] );
		if ($uid == false) {
			$email = $foundagent[0]->xpath(self::XPATH_EMAIL);
			$email = ($email==false?null:$email[0]);
			$telephone = $foundagent[0]->xpath(self::XPATH_TELEPHONE);
			$telephone = ($telephone==false?null:$telephone[0]);
			return $this->dbo->insertEzrAgent($name[0],$email,$telephone);
		}
		return $uid;
	}
}