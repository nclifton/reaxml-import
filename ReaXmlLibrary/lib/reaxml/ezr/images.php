<?php
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
		return new ReaxmlEzrImage ( $this->xml, $this->dbo, $this->configuration, $this->row, $this->idx );
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
		$image = new ReaxmlEzrImage ( $this->xml, $this->dbo, $this->configuration, $this->row, $this->idx );
		return $image->ordering;
	}
}