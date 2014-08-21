<?php
class ReaxmlEzrColOhstarttime extends \ReaxmlEzrColumn {

	public function getValue() {
		$found = $this->xml->inspectionTimes->inspection;
		if (isset ( $found )) {
			if (count ( $found ) > 1) {
				$found = $found [0] . '';
			} else {
				$found = $found . '';
			}
			return $this->parseInspectionStartTime ( $found );
		}
		return null;
	}
}