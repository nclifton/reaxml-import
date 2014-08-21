<?php
class ReaxmlEzrColOffpeak extends \ReaxmlEzrColumn {
	public function getValue() {
		return $this->isNew () ? 0 : null;
	}
}