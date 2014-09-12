<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @package Component REAXML Import for EZ Realty for Joomla! 3.3
 * @version 0.43.119: reaxmlimport.php 2014-09-12T13:54:30.355
 * @author Neil Clifton
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/

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

