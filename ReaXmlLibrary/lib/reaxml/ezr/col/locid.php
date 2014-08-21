<?php
class ReaxmlEzrColLocid extends ReaxmlEzrColumn {
	const XPATH = '//address/suburb';
	
	/*
	 * (non-PHPdoc) @see ReaxmlDbColumn::getValue()
	 */
	public function getValue() {
		$found = $this->xml->xpath ( self::XPATH );
		if (count($found) == 0) {
			if ($this->isNew ()) {
				throw new RuntimeException ( JText::_ ( 'LIB_REAXML_ERROR_MESSAGE_ADDRESS_SUBURB_NOT_IN_XML' ) );
			} else {
				return null;
			}
		}
		// lookup locality id using name in the ezportal table
		$suburb = $found [0].'';
		$id = $this->dbo->lookupEzrLocidUsingSuburb ( $suburb );
		if ($id == false) {
			throw new RuntimeException ( JText::sprintf ( 'LIB_REAXML_ERROR_MESSAGE_DB_NO_ADDRESS_SUBURB_MATCH', $suburb ) );
		}
		return $id;
	}
}