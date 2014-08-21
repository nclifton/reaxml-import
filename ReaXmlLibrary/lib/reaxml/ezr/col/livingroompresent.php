<?php
class ReaxmlEzrColLivingroompresent extends \ReaxmlEzrColumn {
	public function getValue() {
		return $this->isNew () ? false : null;
	}
}