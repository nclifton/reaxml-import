<?php

defined ( '_JEXEC' ) or die ( 'Restricted access' );


/**
 *
 * @package Library REAXML Library for Joomla!
 * @version 1.5.10: images.php 2016-08-15T00:04:57.830
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014, 2015 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @since 3.6
 *
 **/ 
class ReaxmlEzrImages extends ReaxmlEzrRow implements Countable, Iterator {
	private $idx = 0;
	private $row;
	/*
	 * (non-PHPdoc)
	 * @see ReaxmlEzrRow::__construct()
	 */
	public function __construct(SimpleXMLElement $xml, ReaxmlEzrDbo $dbo = null, ReaxmlConfiguration $configuration = null, ReaxmlEzrRow $row = null) {
		$this->row = $row;
		parent::__construct ( $xml, $dbo, $configuration );
	}
	
	/**
	 * (non-PHPdoc)
	 *
	 * @see Countable::count()
	 */
	public function count() {
		return $this->image_count;
	}
	
	/*
	 * (non-PHPdoc)
	 * @see Iterator::valid()
	 */
	public function valid() {
		return ($this->idx >= 0 && $this->idx < $this->image_count);
	}
	
	/*
	 * (non-PHPdoc)
	 * @see Iterator::rewind()
	 */
	public function rewind() {
		$this->idx = 0;
	}
	
	/*
	 * (non-PHPdoc)
	 * @see Iterator::current()
	 */
	public function current() {
		return new ReaxmlEzrImage ( $this->row, $this->idx );
	}
	
	/*
	 * (non-PHPdoc)
	 * @see Iterator::next()
	 */
	public function next() {
		++ $this->idx;
	}
	
	/*
	 * (non-PHPdoc)
	 * @see Iterator::key()
	 */
	public function key() {
		$image = new ReaxmlEzrImage ( $this->row, $this->idx );
		return $image->ordering;
	}
}