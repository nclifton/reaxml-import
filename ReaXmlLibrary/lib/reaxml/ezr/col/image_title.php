<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/ 
class ReaxmlEzrColImage_title extends \ReaxmlEzrImagecolumn {
	public function getValueAt($idx) {
		return $this->isNewImage ($idx) ? '' : null;
	}
}