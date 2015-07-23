<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @package Component REAXML Import for Joomla! 3.4
 * @version 1.5.11: import.php 2015-07-24T00:42:53.638
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014, 2015 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 *
 **/

class ReaXmlImportHelpersImport {
	static function load() {
		$document = JFactory::getDocument ();
		
		// stylesheets
		$document->addStylesheet ( JUri::base () . 'components/com_reaxmlimport/assets/css/import.css' );
		
		// javascripts
		JHtml::_ ( 'jquery.framework' );
		JHtml::_ ( 'jquery.ui' );
		$document->addScript ( JUri::base () . 'components/com_reaxmlimport/assets/js/import.js' );
	}
}