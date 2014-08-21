<?php
class ReaxmlEzrColumn_boolean extends ReaxmlEzrColumn {
	public function getValue() {
		return $this->isNew () ? false : null;
	}
}