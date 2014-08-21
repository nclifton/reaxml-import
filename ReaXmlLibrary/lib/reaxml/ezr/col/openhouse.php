<?php
class ReaxmlEzrColOpenhouse extends \ReaxmlEzrColumn {
	const XPATH = '//inspectionTimes/inspection';
	public function getValue() {
		$found = $this->xml->inspectionTimes->inspection;
		if (isset ( $found )) {
			if (count ( $found ) > 1) {
				return (strlen ( $found [0] . '' ) > 0);
			} else if (count ( $found ) > 0) {
				return (strlen ( $found . '' ) > 0);
			}
		}
		return $this->isNew() ? false : null;
	}
}