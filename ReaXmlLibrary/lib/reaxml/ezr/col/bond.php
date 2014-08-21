<?php
class ReaxmlEzrColBond extends \ReaxmlEzrColumn {
	public function getValue() {
		return $this->isNew () ? 0 : null;
	}
}