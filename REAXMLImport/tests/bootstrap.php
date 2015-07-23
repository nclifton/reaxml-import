<?php

/**
 *
 * @copyright Copyright (C) 2014,2015 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license GNU General Public License version 2 or later; see LICENSE.txt
 *         
 */


define ( '_JEXEC', 1 );

require_once(realpath(__DIR__.'/../vendor/autoload.php'));

define ( 'JPATH_BASE', realpath(__DIR__.'/../tests/htdocs') );
require_once JPATH_BASE . '/includes/defines.php';

define ( 'REAXML_LIBRARIES', __DIR__ . '/../../ReaXmlLibrary/lib' );

require_once JPATH_BASE . '/includes/framework.php';

JLoader::setup ();

// Register the directory to your include files

JLoader::registerPrefix ( 'Reaxml', REAXML_LIBRARIES . '/reaxml' );
JLoader::registerPrefix ( 'ReaxmlDb', REAXML_LIBRARIES . '/reaxml/db' );
JLoader::registerPrefix ( 'ReaxmlEzr', REAXML_LIBRARIES . '/reaxml/ezr' );
JLoader::registerPrefix ( 'ReaxmlEzrCol', REAXML_LIBRARIES . '/reaxml/ezr/col' );

$lang = JFactory::getLanguage ();
$lang->load ( 'lib_reaxml', '..', 'en-GB', true );

define ( 'REAXML_LOG_CATEGORY', 'REAXML-Import' );

include_once __DIR__.'/dbtestcase.php';
include_once __DIR__.'/../installscript.php';
include_once __DIR__.'/selenium/reaxml_selenium_testcase.php';
include_once __DIR__.'/selenium/seleniumdbhelper.php';

?>

