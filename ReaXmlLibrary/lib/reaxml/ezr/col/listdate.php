<?php
class ReaxmlEzrColListdate extends \ReaxmlEzrColumn {
	const XPATH = '//@modTime';
	public function getValue() {
		$found = $this->xml->xpath ( self::XPATH );
		if (count ( $found ) > 0) {
			if ($this->isNew ()) {
				$datestring = $found [0] . '';
				$time = strtotime ( $datestring );
				if ($time === false) {
					$date = DateTime::createFromFormat ( 'Y-m-d-H:i:s', $datestring );
					return $date->format ( 'Y-m-d' );
				} else {
					return date ( 'Y-m-d', $time );
				}
			}
		}
		return ($this->isNew ()) ? date ( 'Y-m-d', time () ) : null;
	}
}