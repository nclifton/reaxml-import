<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @package Library REAXML Library for Joomla!
 * @version 1.5.10: cid.php 2016-08-15T00:04:57.830
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014, 2015 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @since 3.6
 *
 **/ 
class ReaxmlEzrColCid extends ReaxmlEzrColumn {
	const XPATH_RESIDENTIAL = '//residential/category/@name';
	const XPATH_RENTAL = '//rental/category/@name';
	const XPATH_COMMERCIAL = '//commercial/commercialCategory/@name';
	
	/*
	 * (non-PHPdoc) @see ReaxmlDbColumn::getValue()
	 */
	public function getValue() {
		$found = $this->xml->xpath ( self::XPATH_RESIDENTIAL . '|' . self::XPATH_RENTAL . '|' . self::XPATH_COMMERCIAL );
		if (count ( $found ) == 0) {
			if ($this->isNew ()) {
				throw new RuntimeException ( JText::_ ( 'LIB_REAXML_ERROR_MESSAGE_CATEGORY_NAME_NOT_IN_XML' ) );
			} else {
				return null;
			}
		}
		// lookup ezrealty category id using name in the ezrealty_catg table
		$id = $this->dbo->lookupEzrCategoryIdUsingCategoryName ( $found [0] );
		if ($id == false) {
			return $this->dbo->insertEzrCategory($found [0].'');
		}
		return $id;
	}
}