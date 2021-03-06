<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @package Library REAXML Library for Joomla!
 * @version 1.5.10: assoc_agent.php 2016-08-15T00:04:57.830
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014, 2015 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @since 3.6
 *
 **/ 
class ReaxmlEzrColAssoc_agent extends ReaxmlEzrColumn {
	const XPATH_ASSOC_AGENT = '//listingAgent[@id=2] | //listingAgent[2]'; // note 2 means the second occurance - xpath occurances start at 1.
	const XPATH_NAME = 'name';
	const XPATH_TELEPHONE = 'telephone';
	const XPATH_EMAIL = 'email';
	
	/*
	 * (non-PHPdoc) @see ReaxmlDbColumn::getValue()
	 */
	public function getValue() {
		$foundagent = $this->xml->xpath ( self::XPATH_ASSOC_AGENT );
		if ($foundagent == false) {
			return null;
		}
		$name = $foundagent [0]->xpath ( self::XPATH_NAME );
		if ($name == false) {
			return null;
		}
		// lookup ezrealty agent using name in the ezportal table
		$uid = $this->dbo->lookupEzrAgentUidUsingAgentName ( $name [0] );
		if ($uid == false) {
			$email = $foundagent [0]->xpath ( self::XPATH_EMAIL );
			$email = ($email == false ? null : $email [0]);
			$telephone = $foundagent [0]->xpath ( self::XPATH_TELEPHONE );
			$telephone = ($telephone == false ? null : $telephone [0]);
			return $this->dbo->insertEzrAgent ( $name [0], $email, $telephone );
		}
		return $uid;
	}
}