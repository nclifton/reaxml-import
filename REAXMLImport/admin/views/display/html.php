<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @package Component REAXML Import for EZ Realty for Joomla! 3.3
 * @version 0.43.119: html.php 2014-09-12T13:54:30.355
 * @author Neil Clifton
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/
	protected function addToolbar() {
		
		// Get the toolbar object instance
		JToolbar::getInstance ( 'toolbar' );
		
		JToolbarHelper::title ( JText::_ ( 'COM_REAXMLIMPORT_VIEW_DEFAULT_TITLE' ), 'download' );
		
		$canDo = ReaXmlImportHelpersAdmin::getActions ();
		if ($canDo->get ( 'core.admin' )) {
			JToolbarHelper::preferences ( 'com_reaxmlimport' );
		}
		JToolbarHelper::custom ( 'import', 'download', 'download', 'Import', false );
		JToolbarHelper::custom ( 'refresh', 'refresh', 'refresh', 'Refresh', false );
	}
}