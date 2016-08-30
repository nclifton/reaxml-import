<?php

/**
 * @copyright    Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license        GNU General Public License version 2 or later; see LICENSE.txt
 **/
error_reporting(E_ALL);



define('_JEXEC', 1);

include_once 'JoomlaSiteHelper.php';

if (!file_exists(JOOMLA_INSTALL)) {
    $siteHelper = new JoomlaSiteHelper();
    echo $siteHelper->deleteSite('reaxml');
    echo $siteHelper->createSite('reaxml');
    echo $siteHelper->installExtension('reaxml',EZREALTY_INSTALL_FILE);
}

define('JPATH_BASE', realpath(JOOMLA_INSTALL));
define('JPATH_ROOT', realpath(JOOMLA_INSTALL));

// Global definitions
$parts = explode(DIRECTORY_SEPARATOR, JPATH_BASE);

// Defines.
define('JPATH_SITE', JPATH_ROOT);
if (!defined('JPATH_CONFIGURATION'))
    define('JPATH_CONFIGURATION', realPath(__DIR__));
define('JPATH_ADMINISTRATOR', JPATH_ROOT . DIRECTORY_SEPARATOR . 'administrator');
define('JPATH_LIBRARIES', JPATH_ROOT . DIRECTORY_SEPARATOR . 'libraries');
define('JPATH_PLUGINS', JPATH_ROOT . DIRECTORY_SEPARATOR . 'plugins');
define('JPATH_INSTALLATION', JPATH_ROOT . DIRECTORY_SEPARATOR . 'installation');
define('JPATH_THEMES', JPATH_BASE . DIRECTORY_SEPARATOR . 'templates');
define('JPATH_CACHE', JPATH_BASE . DIRECTORY_SEPARATOR . 'cache');
define('JPATH_MANIFESTS', JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'manifests');

define('REAXML_LIBRARIES', realpath(__DIR__ . '/../libraries'));
define('REAXML_ADMIN_COMPONENTS', realpath(__DIR__ . '/../administrator/components'));
define('REAXML_SITE_COMPONENTS', realpath(__DIR__ . '/../components'));

require_once JPATH_BASE . '/includes/framework.php';

JLoader::setup();

// autoloader for the classes under test

include_once('AutoLoader.php');
include_once('vendor/autoload.php');

// Register the directory to your include files
ReaxmlTestingAutoLoader::registerDirectory(REAXML_LIBRARIES . '/reaxml');
ReaxmlTestingAutoLoader::registerDirectory(REAXML_LIBRARIES . '/reaxml/db');
ReaxmlTestingAutoLoader::registerDirectory(REAXML_LIBRARIES . '/reaxml/ezr');
ReaxmlTestingAutoLoader::registerDirectory(REAXML_LIBRARIES . '/reaxml/ezr/col');

JLoader::registerPrefix('Reaxml', REAXML_LIBRARIES . '/reaxml');
JLoader::registerPrefix('ReaxmlDb', REAXML_LIBRARIES . '/reaxml/db');
JLoader::registerPrefix('ReaxmlEzr', REAXML_LIBRARIES . '/reaxml/ezr');
JLoader::registerPrefix('ReaxmlEzrCol', REAXML_LIBRARIES . '/reaxml/ezr/col');

JLoader::registerPrefix('ReaXmlImport', REAXML_ADMIN_COMPONENTS . '/com_reaxmlimport');
JLoader::registerPrefix('ReaXmlImport', REAXML_SITE_COMPONENTS . '/com_reaxmlimport');

define('REAXML_LOG_CATEGORY', 'REAXML-Import');

include_once 'dbtestcase.php';
include_once 'ReaxmlLanguageTrait.php';
include_once 'component/selenium/reaxml_selenium_testcase.php';
include_once 'component/selenium/seleniumdbhelper.php';

include_once(JPATH_BASE . '/components/com_ezrealty/helpers/upload.helper.php');

?>

