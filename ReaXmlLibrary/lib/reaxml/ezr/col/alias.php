<?php

class ReaxmlEzrColAlias extends ReaxmlEzrColumn{
	/* (non-PHPdoc)
	 * @see ReaxmlDbColumn::getValue()
	 */
	public function getValue() {
		return $this->isNew() ? $this->getId() : null;
		
	}

	
}