<?php
class ReaxmlEzrColAppliances extends \ReaxmlEzrColumn {
	const XPATH_FEATURES_DISHWASHER = '//features/dishwasher';
	public function getValue() {
		$found = $this->xml->xpath ( self::XPATH_FEATURES_DISHWASHER );
		if (count($found) == 0) {
			return $this->isNew () ? '' : null;
		} else {
			if ($this->featureBoolean($found[0])) {
				return JText::_('LIB_REAXML_APPLIANCE_FEATURE_DISHWASHER');
			} else {
				return '';
			}
		}
	}
}