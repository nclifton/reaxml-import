<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @copyright Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license GNU General Public License version 2 or later; see LICENSE.txt
 *         
 */

class ReaXmlImportHelpersImport {
	static function load() {
		$document = JFactory::getDocument ();
		
		// stylesheets
		$document->addStylesheet ( JUri::base () . 'components/com_reaxmlimport/assets/css/import.css' );
		
		// javascripts
		$document->addScript ( JUri::base () . 'components/com_reaxmlimport/assets/js/import.js' );
	}
}