<?php
class ReaxmlEzrColOhstarttime2 extends \ReaxmlEzrColumn {
	const XPATH = '//inspectionTimes/inspection[2]';

	public function getValue() {
		$found = $this->xml->inspectionTimes->inspection;
		if (isset ( $found )) {
			if (count ( $found ) > 1) {
				return $this->parseInspectionStartTime ( $found [1] . '' );
			}
		}
		return null;
	}
}