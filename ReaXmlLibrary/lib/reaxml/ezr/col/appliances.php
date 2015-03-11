<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @package Library REAXML Library for Joomla! 3.3
 * @version 0.0.59: appliances.php 2015-03-11T22:48:48.530
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
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