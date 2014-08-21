<?php
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