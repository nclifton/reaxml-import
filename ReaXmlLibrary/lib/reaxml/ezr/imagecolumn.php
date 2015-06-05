<?php

defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @package Library REAXML Library for Joomla! 3.4
 * @version 1.4.8: imagecolumn.php 2015-07-01T05:31:35.565
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014, 2015 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 *
 **/ 
abstract class ReaxmlEzrImagecolumn extends ReaxmlEzrColumn {
	protected $row;
	/*
	 * (non-PHPdoc)
	 * @see ReaxmlDbColumn::__construct()
	 */
	public function __construct(SimpleXMLElement $xml, ReaxmlEzrDbo $dbo = null, ReaxmlConfiguration $configuration = null, ReaxmlEzrRow $row) {
		$this->row = $row;
		parent::__construct ( $xml, $dbo, $configuration );
	}
	private static $ID_SEQUENCE;
	protected static function getIdSequence() {
		if (! isset ( self::$ID_SEQUENCE )) {
			self::$ID_SEQUENCE = array (
					'm',
					'a',
					'b',
					'c',
					'd',
					'e',
					'f',
					'g',
					'h',
					'i',
					'j',
					'k',
					'l',
					'n',
					'o',
					'p',
					'q',
					'r',
					's',
					't',
					'u',
					'v',
					'w',
					'x',
					'y',
					'z' 
			);
		}
		return self::$ID_SEQUENCE;
	}
	abstract function getValueAt($idx);
	
	protected function isNewImage($idx) {
		return ($this->dbo->lookupImageId($this->row->mls_id,$idx+1) > 0);
	}
}