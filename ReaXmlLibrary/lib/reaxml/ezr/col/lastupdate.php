<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @package Library REAXML Library for Joomla! 3.4
 * @version 1.3.3: lastupdate.php 2015-04-21T14:32:48.880
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
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