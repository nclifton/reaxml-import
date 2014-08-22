<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/ 
class ReaxmlEzrColImage_ordering extends \ReaxmlEzrImagecolumn {
	const XPATH_IMAGES_IMG_ID = '/*/objects/img[@id="%s"]';
	public function getValueAt($idx) {
		$ids = parent::getIdSequence ();
		$nodes = $this->xml->xpath ( sprintf ( self::XPATH_IMAGES_IMG_ID, $ids [$idx] ) );
		if (count ( $nodes ) == 0) {
			return $this->isNew () ? $idx + 1 : null;
		} else {
			return $idx + 1;
		}
	}
}