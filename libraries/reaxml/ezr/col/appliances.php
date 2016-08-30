<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @package Library REAXML Library for Joomla!
 * @version 1.5.10: appliances.php 2016-08-15T00:04:57.830
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014, 2015 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @since 3.6
 *
 **/ 
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