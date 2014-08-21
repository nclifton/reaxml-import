<?php
class ReaxmlEzrColImage_title extends \ReaxmlEzrImagecolumn {
	public function getValueAt($idx) {
		return $this->isNewImage ($idx) ? '' : null;
	}
}