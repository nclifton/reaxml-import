<?php
class ReaxmlEzrColCid extends ReaxmlEzrColumn {
	const XPATH = '//category/@name';
	
	/*
	 * (non-PHPdoc) @see ReaxmlDbColumn::getValue()
	 */
	public function getValue() {
		$found = $this->xml->xpath ( self::XPATH );
		if (count($found) == 0) {
			if ($this->isNew ()) {
				throw new RuntimeException ( JText::_ ( 'LIB_REAXML_ERROR_MESSAGE_CATEGORY_NAME_NOT_IN_XML' ) );
			} else {
				return null;
			}
		}
		// lookup ezrealty agent using name in the ezportal table
		$id = $this->dbo->lookupEzrCategoryIdUsingCategoryName ( $found [0] );
		if ($id == false) {
			throw new RuntimeException ( JText::sprintf ( 'LIB_REAXML_ERROR_MESSAGE_DB_NO_CATEGORY_MATCH',  $found [0] ) );
		}
		return $id;
	}
}