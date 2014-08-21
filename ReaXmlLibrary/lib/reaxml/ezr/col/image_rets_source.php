<?php
class ReaxmlEzrColImage_rets_source extends \ReaxmlEzrImagecolumn {
	public function getValueAt($idx) {
		return $this->isNewImage ($idx) ? '' : null;
	}
}