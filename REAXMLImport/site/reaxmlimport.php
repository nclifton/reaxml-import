<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @copyright Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license GNU General Public License version 2 or later; see LICENSE.txt
 *         
 */

// sessions
jimport ( 'joomla.session.session' );

// load classes ore prepare to load classes
JLoader::registerPrefix ( 'ReaXmlImport', JPATH_COMPONENT_SITE );
JLoader::registerPrefix ( 'Reaxml', JPATH_LIBRARIES . '/reaxml' );
jimport ( 'joomla.filesystem.file' );
jimport ( 'joomla.filesystem.folder' );
jimport ( 'reaxml.configuration' );
jimport ( 'reaxml.importer' );

// Load plugins
JPluginHelper::importPlugin ( 'system', 'plg_reaxml' );

// application
$app = JFactory::getApplication ();

// Require specific controller if requested
if ($controller = $app->input->get ( 'controller', 'default' )) {
	require_once (JPATH_COMPONENT_SITE . '/controllers/' . $controller . '.php');
}

// Create the controller
$classname = 'ReaXmlImportControllers' . $controller;
$controller = new $classname ();

// Load styles and javascripts
ReaXmlImportHelpersImport::load ();

// Perform the Request task
$controller->execute ();

