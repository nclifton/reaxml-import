<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @package Library REAXML Library for Joomla!
 * @version 1.5.10: image_count.php 2016-08-15T00:04:57.830
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014, 2015 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @since 3.6
 *
 **/ 
class ReaxmlEzrColImage_count extends \ReaxmlEzrColumn {
	public function getValue() {
		if (! $this->isNew ()) {
			$count = $this->dbo->countEzrImagesUsingMls_id ( $this->getId () );
		} else {
			$count = 0;
		}
		if (isset ( $this->xml->objects->img)){
		    $container = 'objects';
        } else {
            $container = 'images';
        }
		if (isset ( $this->xml->$container->img )) {
			return max ( [
					$count,
					count ( $this->xml->$container->img )
            ]);
		} else {
			return $count;
		}
	}
}