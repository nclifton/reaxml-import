<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @package Library REAXML Library for Joomla!
 * @version 1.5.10: lastupdate.php 2016-08-15T00:04:57.830
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014, 2015 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @since 3.6
 *
 **/ 
class ReaxmlEzrColLastupdate extends \ReaxmlEzrColumn {
	const XPATH = '//@modTime';
	public function getValue() {
		$found = $this->xml->xpath ( self::XPATH );
		if (count ( $found ) > 0) {
			if ($this->isNew ()) {
				$datestring = $found [0] . '';
				$time = strtotime ( $datestring );
				if ($time === false) {
					$date = DateTime::createFromFormat ( 'Y-m-d-H:i:s', $datestring );
					return $date->getTimestamp();
				} else {
					return time();
				}
			}
		}
		return ($this->isNew ()) ? time () : null;

	}
}