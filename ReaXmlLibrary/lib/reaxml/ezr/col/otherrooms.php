<?php
class ReaxmlEzrColOtherrooms extends \ReaxmlEzrColumn {
	const XPATH_FEATURES_TOILETS = '//features/toilets';
	const XPATH_FEATURES_GYM = '//features/gym';
	const XPATH_FEATURES_RUMPUSROOM = '//features/rumpusRoom';
	const XPATH_FEATURES_STUDY = '//features/study';
	const XPATH_FEATURES_WORKSHOP = '//features/workshop';
	private function isBooleanTrue($boolean) {
		switch ($boolean) {
			case 'yes' :
			case '1' :
			case 'true' :
				return true;
				break;
			
			default :
				return false;
				break;
		}
	}
	private function beforeThePercent($string) {
		return explode ( '%', $string )[0];
	}
	public function getValue() {
		$rooms = array ();
		$found = array ();
		$texts = array ();
		
		$texts ['toilets'] = explode ( '%', JText::_ ( 'LIB_REAXML_EZR_OTHERROOMS_TOILETS' ) )[0];
		$found ['toilets'] = $this->xml->xpath ( self::XPATH_FEATURES_TOILETS );
		if (! ($found ['toilets'] == false)) {
			if (strval ( $found ['toilets'] [0] ) > 0) {
				$rooms ['toilets'] = JText::sprintf ( 'LIB_REAXML_EZR_OTHERROOMS_TOILETS', $found ['toilets'] [0] );
			}
		}
		
		$texts ['gym'] = JText::_ ( 'LIB_REAXML_EZR_OTHERROOMS_GYM' );
		$found ['gym'] = $this->xml->xpath ( self::XPATH_FEATURES_GYM );
		if (! ($found ['gym'] == false)) {
			if ($this->isBooleanTrue ( $found ['gym'] [0] )) {
				$rooms ['gym'] = JText::_ ( 'LIB_REAXML_EZR_OTHERROOMS_GYM' );
			}
		}
		
		$texts ['rumpusroom'] = JText::_ ( 'LIB_REAXML_EZR_OTHERROOMS_RUMPUSROOM' );
		$found ['rumpusroom'] = $this->xml->xpath ( self::XPATH_FEATURES_RUMPUSROOM );
		if (! ($found ['rumpusroom'] == false)) {
			if ($this->isBooleanTrue ( $found ['rumpusroom'] [0] )) {
				$rooms ['rumpusroom'] = JText::_ ( 'LIB_REAXML_EZR_OTHERROOMS_RUMPUSROOM' );
			}
		}
		
		$texts ['study'] = JText::_ ( 'LIB_REAXML_EZR_OTHERROOMS_STUDY' );
		$found ['study'] = $this->xml->xpath ( self::XPATH_FEATURES_STUDY );
		if (! ($found ['study'] == false)) {
			if ($this->isBooleanTrue ( $found ['study'] [0] )) {
				$rooms ['study'] = JText::_ ( 'LIB_REAXML_EZR_OTHERROOMS_STUDY' );
			}
		}
		
		$texts ['workshop'] = JText::_ ( 'LIB_REAXML_EZR_OTHERROOMS_WORKSHOP' );
		$found ['workshop'] = $this->xml->xpath ( self::XPATH_FEATURES_WORKSHOP );
		if (! ($found ['workshop'] == false)) {
			if ($this->isBooleanTrue ( $found ['workshop'] [0] )) {
				$rooms ['workshop'] = JText::_ ( 'LIB_REAXML_EZR_OTHERROOMS_WORKSHOP' );
			}
		}
		
		if ($this->anyNotFound ( $found )) {
			if ($this->isNew ()) {
				foreach ( $found as $key => $value ) {
					if ($value == false) {
						unset ( $rooms [$key] );
					}
				}
			} else {
				$result = $this->dbo->lookupEzrOtherrooms ( $this->getId () );
				if (strlen ( $result ) > 0) {
					$otherrooms = explode ( ", ", $result );
					foreach ( $found as $key => $value ) {
						if ($value == false) {
							foreach ( $otherrooms as $roomstring ) {
								if (substr ( $roomstring, 0, strlen ( $texts [$key] ) ) == $texts [$key]) {
									$rooms [$key] = $roomstring;
								}
							}
						}
					}
				}
			}
		}
		
		$strings = array_values ( $rooms );
		if (count ( $strings ) > 0) {
			return join ( ', ', $strings );
		} else if ($this->isNew ()) {
			return '';
		} else {
			return null;
		}
	}
	private function anyNotFound($found) {
		foreach ( $found as $value ) {
			if ($value == false) {
				return true;
			}
		}
		return false;
	}
}