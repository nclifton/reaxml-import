<?php
class ReaxmlEzrColLaundryroompresent extends \ReaxmlEzrColumn {
	public function getValue() {
		return $this->isNew () ? false : null;
	}
}