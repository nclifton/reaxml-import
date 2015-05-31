<?php
/**
 * UNITE
 * The automated site restoration system
 *
 * @copyright Copyright (c)2009-2014 Nicholas K. Dionysopoulos
 * @license   GNU GPL version 3 or, at your option, any later version
 * @package   unite
 */

// Protection against direct access
defined('UNITE') or die('Restricted access');

/**
 * Prints out an application banner, sets the execution time limit and
 * traps PHP errors so that they can be logged.
 */
class UStepBanner extends UAbstractPart
{

	protected function _prepare()
	{
		// Initialize logging
		$version = UNITE_VERSION;
		$date = UNITE_DATE;
		$year = gmdate('Y');

		UUtilLogger::ResetLog();

		UUtilLogger::WriteLog("Starting Akeeba UNITE v.$version ($date)", true);
		echo <<<BANNER
Akeeba UNITE v.$version ($date)
Copyright (c)2009-$year Akeeba Ltd

This program comes with ABSOLUTELY NO WARRANTY. This is free software, and you
are welcome to redistribute it under certain conditions. The full text of the
license can be found in the LICENSE.TXT file in the installation ZIP package.

BANNER;
		echo str_repeat('=', 79) . "\n";

		$this->setState('prepared');
	}

	protected function _run()
	{
		// Try to set an infinite timeout
		if (function_exists('set_time_limit'))
		{
			set_time_limit(0);
		}

		// Clean-up the temporary directory
		$scanner = new UUtilDirscanner;

		try
		{
			$tempFiles = $scanner->getFiles(UConfig::tempDir, '', true);

			foreach ($tempFiles as $file)
			{
				if (substr($file, 0, 3) == 'usd')
				{
					@unlink($file);
				}
			}

			$this->setState('postrun');
		}
		catch (Exception $e)
		{
			$this->setError($e->getMessage());
		}
	}

	protected function _finalize()
	{
		// Try to register a customized error handler
		set_error_handler('uniteErrorHandler');

		$this->setState('finished');
	}
}

/**
 * Nifty trick to track and log PHP errors to UNITE's Global Log
 *
 * @param int    $errno
 * @param string $errstr
 * @param string $errfile
 * @param int    $errline
 *
 * @return bool
 */
function uniteErrorHandler($errno, $errstr, $errfile, $errline)
{
	// Log only errors set to be trapped in your php.ini
	if (!(error_reporting() & $errno))
	{
		// This error code is not included in error_reporting
		return false;
	}

	switch ($errno)
	{

		case E_ERROR:
		case E_USER_ERROR:
			// Can I really catch fatal errors? It doesn't seem likely...
			UUtilLogger::WriteLog("PHP FATAL ERROR on line $errline in file $errfile:");
			UUtilLogger::WriteLog($errstr);
			UUtilLogger::WriteLog("Execution aborted due to fatal error");
			exit(1);
			break;

		case E_WARNING:
		case E_USER_WARNING:
			// Log as debug messages so that we don't spook the user with warnings
			UUtilLogger::WriteLog("PHP WARNING on line $errline in file $errfile:", true);
			UUtilLogger::WriteLog($errstr, true);
			break;

		case E_NOTICE:
		case E_USER_NOTICE:
			// Log as debug messages so that we don't spook the user with notices
			UUtilLogger::WriteLog("PHP NOTICE on line $errline in file $errfile:", true);
			UUtilLogger::WriteLog($errstr, true);
			break;

		default:
			// These are E_DEPRECATED, E_STRICT etc. Ignore that crap.
			/**
			 * UUtilLogger::WriteLog("PHP UNKNOWN NOTICE [$errno] on line $errline in file $errfile:", true);
			 * UUtilLogger::WriteLog($errstr, true);
			 * /* */
			break;
	}

	/* Don't execute PHP's internal error handler */

	return true;
}
