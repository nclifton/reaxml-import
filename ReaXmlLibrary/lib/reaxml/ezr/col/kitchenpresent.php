<?php
class ReaxmlEzrColKitchenpresent extends \ReaxmlEzrColumn {
	public function getValue() {
		return $this->isNew () ? false : null;
	}
}