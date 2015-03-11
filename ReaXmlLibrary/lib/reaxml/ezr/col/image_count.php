<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @package Library REAXML Library for Joomla! 3.3
 * @version 0.0.59: image_count.php 2015-03-11T22:48:48.530
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/ 
class ReaxmlEzrColImage_count extends \ReaxmlEzrColumn {
	public function getValue() {
		if (! $this->isNew ()) {
			$count = $this->dbo->countEzrImagesUsingMls_id ( $this->getId () );
		} else {
			$count = 0;
		}
		if (isset ( $this->xml->objects->img )) {
			return max ( array (
					$count,
					count ( $this->xml->objects->img ) 
			) );
		} else {
			return $count;
		}
	}
}