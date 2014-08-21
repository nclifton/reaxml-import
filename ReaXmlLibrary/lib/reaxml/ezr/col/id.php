<?php
class ReaxmlEzrColId extends \ReaxmlEzrColumn {
	
	public function getValue(){
		return ($this->isNew()) ? null : $this->dbo->getId($this->getId());
	}
	
}