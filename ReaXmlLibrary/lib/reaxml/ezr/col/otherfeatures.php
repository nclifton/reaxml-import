<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @package Library REAXML Library for Joomla! 3.3
 * @version 0.0.52: otherfeatures.php 2014-09-12T14:10:36.970
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/
			if ((count ( $originalvalues ) == count ( $values )) && (count ( array_diff ( $originalvalues, $values ) ) > 0) && (! $this->isNew)) {
				return null;
			}
			$valuestring = join ( ";", $values );
			return ($this->isNew () && count ( $values ) == 0) ? '' : $valuestring;
		}
	}
	private function featureNotMapped($feature) {
		foreach ( self::$mappings as $mapping ) {
			if (JText::_ ( $mapping ['text'] ) == $feature) {
				return false; // is mapped
			}
		}
		return true; // is not mapped
	}
}