<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/ 
class ReaxmlEzrRow {
	public $xml;
	public $dbo;
	public $configuration;
	public $cols = array ();
	public function __construct(SimpleXMLElement $xml, ReaxmlEzrDbo $dbo=null, ReaxmlConfiguration $configuration = null) {
		$this->xml = $xml;
		$this->dbo = $dbo;
		$this->configuration = $configuration;
	}
	public function getValue($colName) {
		$class_name = 'ReaxmlEzrCol' . ucfirst ( strtolower ( $colName ) );
		if (class_exists ( $class_name )) {
			if (! isset ( $this->cols [$colName] )) {
				$this->cols [$colName] = new $class_name ( $this->xml, $this->dbo, $this->configuration );
			}
			return $this->cols [$colName]->getValue ();
		} else {
			throw new InvalidArgumentException ( 'Column with name "' . $colName . '" is not supported' );
		}
	}
	
	public function __get($name){
		return $this->getValue($name);
	}
}