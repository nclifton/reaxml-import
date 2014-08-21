<?php

class ReaxmlEzrColLanguage extends ReaxmlEzrColumn{
	
	/* (non-PHPdoc)
	 * @see ReaxmlDbColumn::getValue()
	 */
	public function getValue() {	
		return $this->isNew() ? '*' : null;
	}

	
}