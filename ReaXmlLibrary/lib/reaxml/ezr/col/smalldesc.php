<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @package Library REAXML Library for Joomla! 3.4
 * @version 1.4.8: smalldesc.php 2015-07-01T05:31:35.565
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014, 2015 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 *
 **/ 
class ReaxmlEzrColSmalldesc extends \ReaxmlEzrColumn {
	const XPATH_DESCRIPTION = '//description';
	public function getValue() {
		$found = $this->xml->xpath ( self::XPATH_DESCRIPTION );
		if (count($found) == 0) {
			if ($this->isNew ()) {
				return '';
			} else {
				return null;
			}
		} else {
			$description = $found[0].'';
			$fixed = preg_replace('/<[^>]*>/', '',$description);
			return preg_replace("/\s+/", " ",substr($fixed,0,255));
		}
	}
}