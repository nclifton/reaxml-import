<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
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