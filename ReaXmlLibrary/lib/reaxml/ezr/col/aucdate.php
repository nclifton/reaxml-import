<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @package Library REAXML Library for Joomla! 3.4
 * @version 1.3.3: aucdate.php 2015-04-21T14:32:48.880
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 *
 **/ 
class ReaxmlEzrColAucdate extends \ReaxmlEzrColumn {
	const XPATH = '//auction/@date';

	public function getValue() {
		$found = $this->xml->xpath ( self::XPATH );
		if (count($found) > 0){
			$datestring = $found[0].'';
			
			//check date format
			if (preg_match('/^\d{4}-\d{2}-\d{2}-\d{2}:\d{2}:\d{2}$/', $datestring) == 1){
				return date('Y-m-d', DateTime::createFromFormat('Y-m-d-H:i:s', $datestring)->getTimestamp());
			} elseif (preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}$/', $datestring) == 1) {
				return date('Y-m-d', strtotime($datestring));
			}
		} else {
			return null;
		}
	}
}