<?php

class ReaxmlEzrColFeatured extends ReaxmlEzrColumn{
	
	public function getValue() {
		return $this->isNew() ? '1' : null;
	}

	
}