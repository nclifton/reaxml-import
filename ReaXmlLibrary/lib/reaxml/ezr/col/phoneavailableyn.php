<?php
class ReaxmlEzrColPhoneavailableyn extends \ReaxmlEzrColumn {
	public function getValue() {
		return $this->isNew () ? false : null;
	}
}