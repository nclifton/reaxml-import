<?php
class ReaxmlEzrColCnid extends ReaxmlEzrColumn {
	const XPATH = '//address/country';
	
	/*
	 * (non-PHPdoc) @see ReaxmlDbColumn::getValue()
	 */
	public function getValue() {
		$found = $this->xml->xpath ( self::XPATH );
		if (count($found) == 0) {
			if ($this->isNew ()) {
				throw new RuntimeException ( JText::_ ( 'LIB_REAXML_ERROR_MESSAGE_ADDRESS_COUNTRY_NOT_IN_XML' ) );
			} else {
				return null;
			}
		}
		$value = $found [0].'';
		$id = $this->dbo->lookupEzrCnidUsingCountry ( $value );
		if ($id == false) {
			throw new RuntimeException ( JText::sprintf ( 'LIB_REAXML_ERROR_MESSAGE_DB_NO_ADDRESS_COUNTRY_MATCH', $value ) );
		}
		return $id;
	}
}