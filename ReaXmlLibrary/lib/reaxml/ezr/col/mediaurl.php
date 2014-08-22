<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/ 
class ReaxmlEzrColMediaurl extends \ReaxmlEzrColumn {
	const XPATH = '//videoLink/@href';
	
	public function getValue() {
		$found = $this->xml->xpath(self::XPATH);
		if (count($found) > 0){
			return $found[0];
		} else {
			return $this->isNew() ? '': null;
		}
	}
}