<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @package Library REAXML Library for Joomla!
 * @version 1.5.10: image_ordering.php 2016-08-15T00:04:57.830
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014, 2015 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @since 3.6
 *
 **/ 
class ReaxmlEzrColImage_ordering extends \ReaxmlEzrImagecolumn {
	const XPATH_IMAGES_IMG_ID = '//img[(parent::objects|parent::images) and @id="%s"]';
	public function getValueAt($idx) {
		$ids = parent::getIdSequence ();
		$nodes = $this->xml->xpath ( sprintf ( self::XPATH_IMAGES_IMG_ID, $ids [$idx] ) );
		if (count ( $nodes ) == 0) {
			return $this->isNew () ? $idx + 1 : null;
		} else {
			return $idx + 1;
		}
	}
}