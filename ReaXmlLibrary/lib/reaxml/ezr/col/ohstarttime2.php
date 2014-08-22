<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/ 
class ReaxmlEzrColOhstarttime2 extends \ReaxmlEzrColumn {
	const XPATH = '//inspectionTimes/inspection[2]';

	public function getValue() {
		$found = $this->xml->inspectionTimes->inspection;
		if (isset ( $found )) {
			if (count ( $found ) > 1) {
				return $this->parseInspectionStartTime ( $found [1] . '' );
			}
		}
		return null;
	}
}