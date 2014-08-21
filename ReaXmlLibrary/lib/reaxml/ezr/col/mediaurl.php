<?php
class ReaxmlEzrColMediaurl extends \ReaxmlEzrColumn {
	const XPATH = '//videoLink/@href';
	
	public function getValue() {
		$found = $this->xml->xpath(self::XPATH);
		if (count($found) > 0){
			return $found[0];
		} else {
			return $this->isNew() ? '': null;
		}
	}
}