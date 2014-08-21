<?php

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