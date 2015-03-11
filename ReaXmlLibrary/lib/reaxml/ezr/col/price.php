<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @package Library REAXML Library for Joomla! 3.3
 * @version 0.0.61: price.php 2015-03-12T02:22:10.059
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/ 
class ReaxmlEzrColPrice extends \ReaxmlEzrColumn {
	//const XPATH_PRICE = '/[residential or rural or land or commercial or commercialLand or business]/Price | /[rental or holidayRental]/rent[@period="weekly" or @period="week"] ';
	const XPATH_PRICE = '//residential/price | //rural/price | //land/price | //commercial/price | //commercialLand/price | //business/price | //rental/rent[@period="weekly" or @period="week"]  | //holidayRental/rent[@period="weekly" or @period="week"]';
	
	public function getValue() {
		$found = $this->xml->xpath ( self::XPATH_PRICE );
		if (count($found) == 0) {
			if ($this->isNew ()) {
				throw new RuntimeException(JText::_('LIB_REAXML_ERROR_MESSAGE_PRICE_NOT_FOUND_IN_NEW_PROPERTY_XML'));
			} else {
				return null;
			}
		} else {

			return floatval($found[0]);

		}
	}
}