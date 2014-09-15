<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @package Component REAXML Import for Joomla! 3.3
 * @version 0.43.121: reaxmlimport.php 2014-09-15T18:47:23.372
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/

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
