<?php
class ReaxmlEzrColOvenyn extends \ReaxmlEzrColumn {
	public function getValue() {
		return $this->isNew () ? false : null;
	}
}