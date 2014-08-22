<?php

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/ 
error_reporting ( E_ALL );

// /Users/nclifton/Documents/MAMP/htdocs/ezrea/libraries' );// /Users/nclifton/Documents/MAMP/htdocs/ezrea/libraries

define ( '_JEXEC', 1 );

define ( 'JPATH_BASE', '/Users/nclifton/Documents/MAMP/htdocs/ezrea' );
define ( 'REAXML_LIBRARIES', __DIR__.'/../lib');

require_once JPATH_BASE . '/includes/defines.php';
require_once JPATH_BASE . '/includes/framework.php';

JLoader::setup ();

// autoloader for the classes under test

include_once ('AutoLoader.php');
// Register the directory to your include files
ReaxmlTestingAutoLoader::registerDirectory ( REAXML_LIBRARIES.'/reaxml' );
//JLoader::registerPrefix ( 'Reaxml', JPATH_LIBRARIES . '/reaxml' );

JLoader::registerPrefix('Reaxml', REAXML_LIBRARIES . '/reaxml');


$lang = JFactory::getLanguage ();
$lang->load ( 'lib_reaxml', '..', 'en-GB', true );

define ( 'REAXML_LOG_CATEGORY', 'REAXML-Import' );

//JLoader::registerPrefix('Reaxml', REAXML_LIBRARIES . '/reaxml');

require_once 'phpunit.phar';
include_once 'dbtestcase.php';
include_once (JPATH_BASE . '/components/com_ezrealty/helpers/upload.helper.php');

?>

