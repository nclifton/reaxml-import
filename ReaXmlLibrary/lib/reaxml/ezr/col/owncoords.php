<?php

class ReaxmlEzrColOwncoords extends ReaxmlEzrColumn{
	
	/* (non-PHPdoc)
	 * @see ReaxmlDbColumn::getValue()
	 */
	public function getValue() {
		return $this->isNew() ? false : null;
	}

	
}