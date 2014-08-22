<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/ 
class ReaxmlEzrColImage_id extends \ReaxmlEzrImagecolumn {
	public function getValueAt($idx) {
		if (! $this->isNew ()) {
			$this->dbo->lookupEzrImageIdUsingMls_idAndOrdering($this->row->mls_id, $this->ordering);
		} else {
			null;
		}
	}
}