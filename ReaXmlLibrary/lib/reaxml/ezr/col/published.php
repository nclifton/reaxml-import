<?php
class ReaxmlEzrColPublished extends ReaxmlEzrColumn {
	const XPATH = '//@status';
	
	/*
	 * (non-PHPdoc) @see ReaxmlDbColumn::getValue()
	 */
	public function getValue() {
		$found = $this->xml->xpath ( self::XPATH );
		if (count($found) == 0) {
			if ($this->isNew ()) {
				throw new RuntimeException ( JText::_ ( 'LIB_REAXML_ERROR_MESSAGE_XML_NO_MARKET_STATUS' ) );
			} else {
				return null;
			}
		}
		$status = $found [0].'';
		switch ($status) {
			case 'withdrawn' :
				return false;
				break;
			
			default :
				return true;
				break;
		}
	}
}