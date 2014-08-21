<?php
class ReaxmlEzrColCovenantsyn extends \ReaxmlEzrColumn {
	public function getValue() {
		return $this->isNew () ? false : null;
	}
}