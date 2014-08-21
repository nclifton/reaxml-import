<?php
class ReaxmlEzrColImage_id extends \ReaxmlEzrImagecolumn {
	public function getValueAt($idx) {
		if (! $this->isNew ()) {
			$this->dbo->lookupEzrImageIdUsingMls_idAndOrdering($this->row->mls_id, $this->ordering);
		} else {
			null;
		}
	}
}