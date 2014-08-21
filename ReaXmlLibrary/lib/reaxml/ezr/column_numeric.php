<?php
class ReaxmlEzrColumn_numeric extends ReaxmlEzrColumn {
	public function getValue() {
		return $this->isNew () ? 0 : null;
	}
}