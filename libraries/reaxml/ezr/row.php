<?php


defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @package Library REAXML Library for Joomla!
 * @version 1.5.10: row.php 2016-08-15T00:04:57.830
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014, 2015 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @since 3.6
 *
 **/
class ReaxmlEzrRow {
	public $xml;
	public $dbo;
	public $configuration;
	public $cols = array ();
	public function __construct(SimpleXMLElement $xml,
                                ReaxmlEzrDbo $dbo=null,
                                ReaxmlConfiguration $configuration = null) {
		$this->xml = $xml;
		$this->dbo = $dbo;
		$this->configuration = $configuration;
	}
	public function getValue($colName) {
		$class_name = 'ReaxmlEzrCol' . ucfirst ( strtolower ( $colName ) );
		if (class_exists ( $class_name )) {
			if (! isset ( $this->cols [$colName] )) {
				$this->cols [$colName] = new $class_name ( $this );
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