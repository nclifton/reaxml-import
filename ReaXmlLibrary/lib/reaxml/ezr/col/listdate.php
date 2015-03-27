<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @package Library REAXML Library for Joomla! 3.4
 * @version 1.2.1: listdate.php 2015-03-28T04:18:12.779
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/ 
class ReaxmlEzrColListdate extends \ReaxmlEzrColumn {
	const XPATH = '//@modTime';
	public function getValue() {
		$found = $this->xml->xpath ( self::XPATH );
		if (count ( $found ) > 0) {
			if ($this->isNew ()) {
				$datestring = $found [0] . '';
				$time = strtotime ( $datestring );
				if ($time === false) {
					$date = DateTime::createFromFormat ( 'Y-m-d-H:i:s', $datestring );
					return $date->format ( 'Y-m-d' );
				} else {
					return date ( 'Y-m-d', $time );
				}
			}
		}
		return ($this->isNew ()) ? date ( 'Y-m-d', time () ) : null;
	}
}