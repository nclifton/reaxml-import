<?php

/**
 *
 * @copyright Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license GNU General Public License version 2 or later; see LICENSE.txt
 *         
 */

error_reporting ( E_ALL );

// /Users/nclifton/Documents/MAMP/htdocs/ezrea/libraries' );// /Users/nclifton/Documents/MAMP/htdocs/ezrea/libraries

define ( '_JEXEC', 1 );

define ( 'JPATH_BASE', '/Users/nclifton/Documents/MAMP/htdocs/reaxml' );
define ( 'REAXML_LIBRARIES', __DIR__ . '/../../ReaXmlLibrary/lib' );
define ( 'JPATH_LIBRARIES', JPATH_BASE . '/Libraries' );

require_once JPATH_BASE . '/includes/defines.php';
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
$lang->load ( 'lib_reaxml', '..', 'en-GB', true );

define ( 'REAXML_LOG_CATEGORY', 'REAXML-Import' );

include_once __DIR__.'/dbtestcase.php';
include_once __DIR__.'/../installscript.php';

?>

