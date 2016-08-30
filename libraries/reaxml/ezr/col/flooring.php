<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @package Library REAXML Library for Joomla!
 * @version 1.5.10: flooring.php 2016-08-15T00:04:57.830
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014, 2015 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @since 3.6
 *
 **/ 
class ReaxmlEzrColFlooring extends \ReaxmlEzrColumn {
	const XPATH_FEATURES_FLOORBOARDS = '//features/floorboards';
	public function getValue() {
		$found = $this->xml->xpath ( self::XPATH_FEATURES_FLOORBOARDS );
		if (count($found) >0) {
			switch (strtolower ( $found [0] )) {
				case 'yes' :
				case '1' :
				case 'true' :
					return JText::_('LIB_REAXML_EZR_FLOORING_FLOORBOARDS');
					break;
				
				default :
					return '';
					break;
			}
		} else {
			return $this->isNew () ? '' : null;
		}
	}
}