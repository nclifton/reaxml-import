<?php
error_reporting ( E_ALL );

// /Users/nclifton/Documents/MAMP/htdocs/ezrea/libraries' );// /Users/nclifton/Documents/MAMP/htdocs/ezrea/libraries

define ( '_JEXEC', 1 );

define ( 'JPATH_BASE', '/Users/nclifton/Documents/MAMP/htdocs/ezrea' );
require_once JPATH_BASE . '/includes/defines.php';
require_once JPATH_BASE . '/includes/framework.php';

JLoader::setup ();

// autoloader for the classes under test

include_once ('AutoLoader.php');
// Register the directory to your include files
//ReaxmlTestingAutoLoader::registerDirectory ( '../admin' );
//ReaxmlTestingAutoLoader::registerDirectory ( '../site' );

$lang = JFactory::getLanguage ();
$lang->load ( 'lib_reaxml', '..', 'en-GB', true );

define ( 'REAXML_LOG_CATEGORY', 'REAXML-Import' );

include_once 'dbtestcase.php';
include_once '../installscript.php';

?>

