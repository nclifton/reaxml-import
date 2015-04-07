<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @package Component REAXML Import for Joomla! 3.4
 * @version 1.2.26: reaxmlimport.php 2015-04-07T14:42:50.797
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 *
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

