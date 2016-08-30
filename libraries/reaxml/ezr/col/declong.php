<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @package Library REAXML Library for Joomla!
 * @version 1.5.10: declong.php 2016-08-15T00:04:57.830
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014, 2015 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @since 3.6
 *
 **/ 
class ReaxmlEzrColDeclong extends \ReaxmlEzrMapcolumn {
	const XPATH_ADDRESS_LONGITUDE = '//address/longitude';
	public function getValue() {
		$found = $this->xml->xpath ( self::XPATH_ADDRESS_LONGITUDE );
		if (count($found) == 0) {
			//ReaxmlImporter::logAdd ( sprintf('Longitude not found in address XML. Use map %s',array('disabled','when new','always')[$this->configuration->usemap]), JLog::DEBUG );
			return $this->isNew () ? ($this->configuration->usemap > 0 ? $this->getLongitude () : '')  : ($this->configuration->usemap == 2 ? $this->getLongitude () : null);
		} else {
			return $found [0].'';
		}
	}
}