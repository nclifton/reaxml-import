<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @copyright Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license GNU General Public License version 2 or later; see LICENSE.txt
 *         
 */

// load classes
JLoader::registerPrefix ( 'ReaXmlImport', JPATH_COMPONENT_ADMINISTRATOR );

// Access check: is this user allowed to access the backend of this component?
$canDo = ReaXmlImportHelpersAdmin::getActions ();
if (! $canDo->get ( 'core.manage' )) {
	return JError::raiseWarning ( 404, JText::_ ( 'JERROR_ALERTNOAUTHOR' ) );
}

// load class libraries
jimport ( 'joomla.filesystem.file' );
jimport ( 'joomla.filesystem.folder' );

// Load plugins
JPluginHelper::importPlugin ( 'plg_reaxml' );

// application
$app = JFactory::getApplication ();

// Require specific controller if requested from admin or site
if ($controller = $app->input->get ( 'controller', 'display' )) {
	require_once (JPATH_COMPONENT_ADMINISTRATOR . '/controllers/' . $controller . '.php');
}
// Create the controller
$classname = 'ReaXmlImportControllers' . ucwords ( $controller );
$controller = new $classname ();

// Load styles and javascripts if formst id not raw.
if ($app->input->get ( 'format', 'html' ) !== 'raw') {
	ReaXmlImportHelpersAdmin::load ();
}

// Perform the Request task
$controller->execute ();
