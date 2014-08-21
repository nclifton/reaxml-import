<?php
class ReaxmlEzrColImage_path extends \ReaxmlEzrImagecolumn {
	public function getValueAt($idx) {
		return $this->isNewImage ($idx) ? '' : null;
	}
}