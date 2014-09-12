<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @package Library REAXML Library for Joomla! 3.3
 * @version 0.0.52: assoc_agent.php 2014-09-12T14:10:36.970
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/
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