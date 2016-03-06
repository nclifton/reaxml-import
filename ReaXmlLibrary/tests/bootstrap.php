<?php

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/
error_reporting ( E_ALL );

// /Users/nclifton/Documents/MAMP/htdocs/ezrea/libraries' );// /Users/nclifton/Documents/MAMP/htdocs/ezrea/libraries

define ( '_JEXEC', 1 );

define ( 'JPATH_BASE', realpath(__DIR__ .'/../../REAXMLImport/tests/htdocs') );
define ( 'JPATH_ROOT', realpath(__DIR__ .'/../../REAXMLImport/tests/htdocs') );
require_once JPATH_BASE . '/includes/defines.php';

define ( 'REAXML_LIBRARIES', __DIR__ . '/../lib' );
//define ( 'JPATH_LIBRARIES', JPATH_BASE . '/Libraries' );

require_once JPATH_BASE . '/includes/framework.php';

JLoader::setup ();

// autoloader for the classes under test

include_once ('AutoLoader.php');
// Register the directory to your include files
ReaxmlTestingAutoLoader::registerDirectory ( REAXML_LIBRARIES . '/reaxml' );
ReaxmlTestingAutoLoader::registerDirectory ( REAXML_LIBRARIES . '/reaxml/db' );
ReaxmlTestingAutoLoader::registerDirectory ( REAXML_LIBRARIES . '/reaxml/ezr' );
ReaxmlTestingAutoLoader::registerDirectory ( REAXML_LIBRARIES . '/reaxml/ezr/col' );

JLoader::registerPrefix ( 'Reaxml', REAXML_LIBRARIES . '/reaxml' );
JLoader::registerPrefix ( 'ReaxmlDb', REAXML_LIBRARIES . '/reaxml/db' );
JLoader::registerPrefix ( 'ReaxmlEzr', REAXML_LIBRARIES . '/reaxml/ezr' );
JLoader::registerPrefix ( 'ReaxmlEzrCol', REAXML_LIBRARIES . '/reaxml/ezr/col' );

$lang = JFactory::getLanguage ();
$lang->load ( 'lib_reaxml', __DIR__ . '/..', 'en-GB', true );

define ( 'REAXML_LOG_CATEGORY', 'REAXML-Import' );

// JLoader::registerPrefix('Reaxml', REAXML_LIBRARIES . '/reaxml');

include_once 'dbtestcase.php';
include_once (JPATH_BASE . '/components/com_ezrealty/helpers/upload.helper.php');

?>

