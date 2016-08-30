<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @package Library REAXML Library for Joomla! 3.4
 * @version 1.3.3: declat.php 2015-04-21T14:32:48.880
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 *         
 *         
 */
class ReaxmlEzrColDeclat extends \ReaxmlEzrMapcolumn {
	const XPATH_ADDRESS_LATITUDE = '//address/latitude';
	public function getValue() {
		$found = $this->xml->xpath ( self::XPATH_ADDRESS_LATITUDE );
		if (count ( $found ) == 0) {
			//ReaxmlImporter::logAdd ( sprintf('Latitude not found in address XML. Use map %s',array('disabled','when new','always')[$this->configuration->usemap]), JLog::DEBUG );
			return $this->isNew () ? ($this->configuration->usemap > 0 ? $this->getLatitude () : '') : ($this->configuration->usemap == 2 ? $this->getLatitude () : null);
		} else {
			return $found [0] . '';
		}
	}
}