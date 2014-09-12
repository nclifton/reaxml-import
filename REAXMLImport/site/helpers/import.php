<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @package Component REAXML Import for EZ Realty for Joomla! 3.3
 * @version 0.43.119: import.php 2014-09-12T13:54:30.355
 * @author Neil Clifton
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/

class ReaXmlImportHelpersImport {
	static function load() {
		$document = JFactory::getDocument ();
		
		// stylesheets
		$document->addStylesheet ( JUri::base () . 'components/com_reaxmlimport/assets/css/import.css' );
		
		// javascripts
		$document->addScript ( JUri::base () . 'components/com_reaxmlimport/assets/js/import.js' );
	}
}