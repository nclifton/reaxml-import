<?php
class ReaxmlEzrColGarbagedisposalyn extends \ReaxmlEzrColumn {
	public function getValue() {
		return $this->isNew () ? false : null;
	}
}