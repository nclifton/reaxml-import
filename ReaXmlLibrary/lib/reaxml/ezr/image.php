<?php

defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @package Library REAXML Library for Joomla! 3.4
 * @version 1.5.3: image.php 2015-07-23T22:28:40.085
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014, 2015 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 *
 **/ 
class ReaxmlEzrImage {
	
	private $xml;
	private $dbo;
	private $configuration;
	private $idx;
	private $cols = array();
	
	
	/* (non-PHPdoc)
	 * @see ReaxmlDbColumn::__construct()
	 */
	public function __construct(SimpleXMLElement $xml, ReaxmlEzrDbo $dbo = null, ReaxmlConfiguration $configuration = null, ReaxmlEzrRow $row, $idx = 0) {
		// TODO: Auto-generated method stub
		$this->xml = $xml;
		$this->dbo = $dbo;
		$this->configuration = $configuration;
		$this->row = $row;
		$this->idx = $idx;
	}

	public function getValue($colName){
		$class_name = 'ReaxmlEzrColImage_' . ucfirst ( strtolower ( $colName ) );
		if (class_exists ( $class_name )) {
			if (! isset ( $this->cols [$colName] )) {
				$this->cols [$colName] = new $class_name ( $this->xml, $this->dbo, $this->configuration, $this->row );
			}
			return $this->cols [$colName]->getValueAt ($this->idx);
		} else {
			throw new InvalidArgumentException ( 'Image column with name "' . $colName . '" is not supported' );
		}
	}

	public function __get($name){
		return $this->getValue($name);
	} 
	
}