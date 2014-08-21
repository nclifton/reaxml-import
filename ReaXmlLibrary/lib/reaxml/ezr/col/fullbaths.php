<?php
class ReaxmlEzrColFullbaths extends \ReaxmlEzrColumn {
	public function getValue() {
		return $this->isNew () ? 0 : null;
	}
}