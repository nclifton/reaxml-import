<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/ 

abstract class ReaxmlDbColumn {
	
	public $xml;
	public $dbo;
	public $configuration;
	
	public function __construct(SimpleXMLElement $xml, ReaxmlEzrDbo $dbo=null, ReaxmlConfiguration $configuration=null) {
		$this->xml = $xml;
		$this->dbo = $dbo;
		$this->configuration = $configuration;
	}
	
	
	
	abstract public function getValue();
	
}