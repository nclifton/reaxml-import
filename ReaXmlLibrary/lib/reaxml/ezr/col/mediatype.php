<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @package Library REAXML Library for Joomla! 3.4
 * @version 1.3.3: mediatype.php 2015-04-21T14:32:48.880
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 *
 **/ 
class ReaxmlEzrColMediatype extends \ReaxmlEzrColumn {

	
	/* (non-PHPdoc)
	 * @see ReaxmlEzrColumn::getValue()
	 */
	public function getValue() {
		$mediaurl = new ReaxmlEzrColMediaurl($this->xml,$this->dbo,$this->configuration);
		if (strlen($mediaurl->getValue().'') == 0){
			return ($this->isNew()) ? 0 : null;
		} else {
			return ($this->isNew()) ? 1 : null;
		}
	}

	
	
}