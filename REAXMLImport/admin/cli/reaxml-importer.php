<?php
/**
 *
 * @package Component REAXML Import for Joomla! 3.4
 * @version 1.5.11: reaxml-importer.php 2015-07-24T00:42:53.638
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014, 2015 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 *
 **/
 
 
if (! defined ( '_JEXEC' ))
	define ( '_JEXEC', 1 );
if (! defined ( 'JPATH_BASE' ))
	define ( 'JPATH_BASE', dirname ( __DIR__ ) );
	
$minphp = '5.5.1';
if (version_compare(PHP_VERSION, $minphp, 'lt'))
{
	$curversion = PHP_VERSION;
	$bindir = PHP_BINDIR;
	echo <<< ENDWARNING
================================================================================
WARNING! Incompatible PHP version $curversion
================================================================================

This CRON script must be run using PHP version $minphp or later. Your server is
currently using a much older version which would cause this script to crash. As
a result we have aborted execution of the script. Please contact your host and
ask them for the correct path to the PHP CLI binary for PHP $minphp or later, then
edit your CRON job and replace your current path to PHP with the one your host
gave you.

For your information, the current PHP version information is as follows.

PATH:    $bindir
VERSION: $curversion

Further clarifications:

1. There is absolutely no possible way that you are receiving this warning in
   error. We are using the PHP_VERSION constant to detect the PHP version you
   are currently using. This is what PHP itself reports as its own version. It
   simply cannot lie.

2. Even though your *site* may be running in a higher PHP version that the one
   reported above, your CRON scripts will most likely not be running under it.
   This has to do with the fact that your site DOES NOT run under the command
   line and there are different executable files (binaries) for the web and
   command line versions of PHP.

3. Please note that you MUST NOT ask us for support about this error. We cannot
   possibly know the correct path to the PHP CLI binary as we have not set up
   your server. Your host must know and give that information.

4. The latest published versions of PHP can be found at http://www.php.net/
   Any older version is considered insecure and must NOT be used on a live
   server. If your server uses a much older version of PHP than that please
   notify them that their servers are insecure and in need of an update.

This script will now terminate. Goodbye.

ENDWARNING;
	die();
}	
	
// Required by the CMS
if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

// Timezone fix; avoids errors printed out by PHP 5.3.3+ (thanks Yannick!)
if (function_exists('date_default_timezone_get') && function_exists('date_default_timezone_set'))
{
	if (function_exists('error_reporting'))
	{
		$oldLevel = error_reporting(0);
	}
	$serverTimezone	 = @date_default_timezone_get();
	if (empty($serverTimezone) || !is_string($serverTimezone))
		$serverTimezone	 = 'UTC';
	if (function_exists('error_reporting'))
	{
		error_reporting($oldLevel);
	}
	@date_default_timezone_set($serverTimezone);
}	
	
	// Load system defines
if (file_exists ( JPATH_BASE . '/defines.php' )) {
	require_once JPATH_BASE . '/defines.php';
}

if (! defined ( '_JDEFINES' )) {
	require_once JPATH_BASE . '/includes/defines.php';
}

// Get the framework.
require_once JPATH_LIBRARIES . '/import.legacy.php';

// Bootstrap the CMS libraries.
require_once JPATH_LIBRARIES . '/cms.php';

// Load the JApplicationCli class
JLoader::import('joomla.application.cli');

// load classes
if (file_exists(JPATH_LIBRARIES . '/reaxml')) {
	JLoader::registerPrefix('Reaxml', JPATH_LIBRARIES . '/reaxml');
}
JLoader::import ( 'reaxml.configuration' );
JLoader::import ( 'reaxml.importer' );

// CLI joomla application class
if (!class_exists('ReaxmlImporterCLI')) {
	class ReaxmlImporterCLI extends JApplicationCli
	{
		public function execute()
		{

			$configuration = $this->getConfiguration();
			$importer = new ReaxmlImporter ();
			$importer->setShowLog(true);
			$importer->setConfiguration($configuration);
			return $importer->import();
		}

		// setup the configuration object
		private function getConfiguration()
		{
			$params = JComponentHelper::getParams('com_reaxmlimport');
			$configuration = new ReaxmlConfiguration ();
			$configuration->input_dir = $params->get('input_dir');
			$configuration->work_dir = $params->get('work_dir');
			$configuration->done_dir = $params->get('done_dir');
			$configuration->error_dir = $params->get('error_dir');
			$configuration->log_dir = $params->get('log_dir');
			$configuration->input_url = $params->get('input_url');
			$configuration->work_url = $params->get('work_url');
			$configuration->done_url = $params->get('done_url');
			$configuration->error_url = $params->get('error_url');
			$configuration->log_url = $params->get('log_url');
			$configuration->usemap = $params->get('usemap', 0);
			return $configuration;
		}
	}
}

JApplicationCli::getInstance ( 'ReaxmlImporterCLI' )->execute ();
