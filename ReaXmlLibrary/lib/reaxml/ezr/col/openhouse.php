<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @package Library REAXML Library for Joomla! 3.3
 * @version 0.0.79: openhouse.php 2015-03-20T17:13:33.572
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/ 
class ReaxmlEzrColOpenhouse extends \ReaxmlEzrColumn {
	const XPATH = '//inspectionTimes/inspection';
	public function getValue() {
		$found = $this->xml->inspectionTimes->inspection;
		if (isset ( $found )) {
			if (count ( $found ) > 1) {
				return (strlen ( $found [0] . '' ) > 0);
			} else if (count ( $found ) > 0) {
				return (strlen ( $found . '' ) > 0);
			}
		}
		return $this->isNew() ? false : null;
	}
}