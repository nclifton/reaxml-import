<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @package Library REAXML Library for Joomla! 3.4
 * @version 1.2.5: adline.php 2015-04-07T14:41:26.244
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 *
 **/ 
class ReaxmlEzrColAdline extends \ReaxmlEzrColumn {
	const XPATH_HEADLINE = '//headline';
	public function getValue() {
		$found = $this->xml->xpath ( self::XPATH_HEADLINE );
		if (count($found) == 0) {
			if ($this->isNew ()) {
				return '';
			} else {
				return null;
			}
		} else {
			return $found [0].'';
		}
	}
}