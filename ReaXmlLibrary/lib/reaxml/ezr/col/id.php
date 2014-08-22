<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/ 
class ReaxmlEzrColId extends \ReaxmlEzrColumn {
	
	public function getValue(){
		return ($this->isNew()) ? null : $this->dbo->getId($this->getId());
	}
	
}