<?php
class ReaxmlEzrColFamilyroompresent extends \ReaxmlEzrColumn {
	public function getValue() {
		return $this->isNew () ? false : null;
	}
}