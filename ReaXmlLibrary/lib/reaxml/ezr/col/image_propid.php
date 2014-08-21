<?php
class ReaxmlEzrColImage_propid extends \ReaxmlEzrImagecolumn {
	public function getValueAt($idx) {
		return $this->row->id;
	}
}