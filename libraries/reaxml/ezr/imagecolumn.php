<?php

defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @package Library REAXML Library for Joomla!
 * @version 1.5.10: imagecolumn.php 2016-08-15T00:04:57.830
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014, 2015 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @since 3.6
 *
 **/ 
abstract class ReaxmlEzrImagecolumn extends ReaxmlEzrColumn {

	private static $ID_SEQUENCE;
    protected $imageHelper;

    public function __construct(ReaxmlEzrRow $row, ReaxmlEzrImagehelper $imageHelper=null)
    {
        parent::__construct($row);
        if (is_null($imageHelper)){
            $this->imageHelper = new ReaxmlEzrImagehelper();
        }
    }

    protected static function getIdSequence() {
		if (! isset ( self::$ID_SEQUENCE )) {
			self::$ID_SEQUENCE = [
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
            ];
		}
		return self::$ID_SEQUENCE;
	}
	abstract function getValueAt($idx);
	
	protected function isNewImage($idx) {
		return ($this->dbo->lookupImageId($this->row->mls_id,$idx+1) > 0);
	}
}