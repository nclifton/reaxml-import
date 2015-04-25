<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @package Library REAXML Library for Joomla! 3.4
 * @version 1.3.3: smalldesc.php 2015-04-21T14:32:48.880
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014 Clifton IT Foundries Pty Ltd. All rights Reserved
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
			return preg_replace("/\s+/", " ",substr($found [0].'',0,255));
		}
	}
}