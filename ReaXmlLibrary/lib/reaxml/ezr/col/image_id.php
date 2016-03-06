<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @package Library REAXML Library for Joomla! 3.4
 * @version 1.5.3: image_id.php 2015-07-23T22:28:40.085
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014, 2015 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 *
 **/ 
class ReaxmlEzrColImage_id extends \ReaxmlEzrImagecolumn {
	public function getValueAt($idx) {
		if (! $this->isNew ()) {
			$this->dbo->lookupEzrImageIdUsingMls_idAndOrdering($this->row->mls_id, $this->ordering);
		} else {
			null;
		}
	}
}