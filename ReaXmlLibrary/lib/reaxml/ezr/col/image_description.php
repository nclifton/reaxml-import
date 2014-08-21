<?php
class ReaxmlEzrColImage_description extends \ReaxmlEzrImagecolumn {
	public function getValueAt($idx) {
		return $this->isNewImage ($idx) ? '' : null;
	}
}