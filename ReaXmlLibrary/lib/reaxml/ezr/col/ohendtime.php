<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/ 
class ReaxmlEzrColOhendtime extends \ReaxmlEzrColumn {
	public function getValue() {
		$found = $this->xml->inspectionTimes->inspection;
		if (isset ( $found )) {
			if (count ( $found ) > 1) {
				$found = $found [0] . '';
			} else {
				$found = $found . '';
			}
			return $this->parseInspectionEndTime ( $found );
		}
		return null;
	}
}