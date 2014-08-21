<?php
class ReaxmlEzrColOhdate2 extends \ReaxmlEzrColumn {
	public function getValue() {
		$found = $this->xml->inspectionTimes->inspection;
		if (isset ( $found )) {
			if (count ( $found ) > 1) {
				return $this->parseInspectionDate ( $found [1] . '' );
			}
		}
		return null;		
	}
}