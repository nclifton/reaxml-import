<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @package Library REAXML Library for Joomla! 3.4
 * @version 1.2.1: image_ordering.php 2015-03-28T04:18:12.779
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/ 
class ReaxmlEzrColImage_ordering extends \ReaxmlEzrImagecolumn {
	const XPATH_IMAGES_IMG_ID = '/*/objects/img[@id="%s"]';
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