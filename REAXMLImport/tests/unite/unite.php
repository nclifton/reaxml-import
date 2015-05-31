<?php
/**
 * UNITE
 * The automated site restoration system
 *
 * @copyright Copyright (c)2009-2014 Nicholas K. Dionysopoulos
 * @license   GNU GPL version 3 or, at your option, any later version
 * @package   unite
 * @version   $Id$
 *
 * Usage:
 *     unite.php [--script <scriptname>]
 */

// Make sure we're being called from the command line, not a web interface
if (array_key_exists('REQUEST_METHOD', $_SERVER))
{
	die('You are not supposed to access this script from the web. You have to run it from the command line. If you don\'t understand what this means, you must not try to use this file before reading the documentation. Thank you.');
}

// Basic defines
define('UNITE', 1);

require_once __DIR__ . '/version.php';

// Make sure we don't get strict errors from date functions
if (function_exists('date_default_timezone_get') && function_exists('date_default_timezone_set'))
{
	if (function_exists('error_reporting'))
	{
		$oldLevel = error_reporting(0);
	}

	$serverTimezone = @date_default_timezone_get();

	if (empty($serverTimezone) || !is_string($serverTimezone))
	{
		$serverTimezone = 'UTC';
	}

	if (function_exists('error_reporting'))
	{
		error_reporting($oldLevel);
	}

	@date_default_timezone_set($serverTimezone);
}

// Load the factory (and autoloader)
require_once dirname(__FILE__) . '/engine/factory.php';

// Prime the global state array
$globalState = array(
	'TotalJobs'      => 0,
	'ValidJobs'      => 0,
	'ExecutedJobs'   => 0,
	'FailedJobs'     => 0,
	'JobDefinitions' => array()
);

// Used to pass empty configuration options
$blankArray = array();

// Get the scripting which defines our run order
$scripting = UFactory::getScripting();

// Run the pre-run steps
$presteps = $scripting->getPreSteps();

if (!empty($presteps))
{
	foreach ($presteps as $stepName)
	{
		$step = UFactory::getStep($stepName);
		$step->setup($blankArray, $globalState);
		$done = false;

		while (!$done)
		{
			$ret = $step->tick();

			if ($ret['Error'])
			{
				UUtilLogger::WriteLog($ret['Error']);
				die("*** Process halted due to error");
			}
			elseif (!$ret['HasRun'])
			{
				$done = true;
			}
		}

		$globalState = $step->getGlobalState();
	}
}

// Fetch a list of jobs
$jobProviders = $scripting->getJobProviders();

if (!empty($jobProviders))
{
	foreach ($jobProviders as $stepName)
	{
		$step = UFactory::getStep($stepName);
		$step->setup($blankArray, $globalState);
		$done = false;

		while (!$done)
		{
			$ret = $step->tick();

			if ($ret['Error'])
			{
				UUtilLogger::WriteLog($ret['Error']);
				die("*** Process halted due to error");
			}
			elseif (!$ret['HasRun'])
			{
				$done = true;
				$globalState = $step->getGlobalState();
			}
		}

		$globalState = $step->getGlobalState();
	}
}

UUtilLogger::WriteLog("Found {$globalState['TotalJobs']} job(s) to run\n");

// Do we have any jobs to do?
if (!empty($globalState['JobDefinitions']))
{
	$currentJob = 0;

	// Step through each job
	foreach ($globalState['JobDefinitions'] as $jobkey => $job)
	{
		$currentJob++;
		UUtilLogger::WriteLog("### Starting job #$currentJob ###");
		// Get the job steps
		$jobsteps = $scripting->getJobSteps();

		if (!empty($jobsteps))
		{
			$hasErroredOut = false;

			// Step through each job step for each defined job
			foreach ($jobsteps as $stepName)
			{
				UUtilLogger::WriteLog("\tRunning $stepName");
				$step = UFactory::getStep($stepName);
				$config = array(
					'jobkey' => $jobkey
				);
				$step->setup($config, $globalState);
				$done = false;

				while (!$done)
				{
					$ret = $step->tick();

					if ($ret['Error'])
					{
						// The step has errored out. Abort! Abort!
						UUtilLogger::WriteLog($ret['Error']);
						$hasErroredOut = true;
						$done = true;
					}
					elseif (!$ret['HasRun'])
					{
						$done = true;
					}
				}

				$globalState = $step->getGlobalState();

				UFactory::unsetStep($stepName);

				// Break processing on error
				if ($hasErroredOut)
				{
					break;
				}
			}

			if ($hasErroredOut)
			{
				$globalState['FailedJobs']++;
			}
			else
			{
				$globalState['ExecutedJobs']++;

				// Remove XML file if not keepxml global configuration is defined
				if (!UConfig::keepxml)
				{
					if ($job['engine'] == 'xml')
					{
						$xmlFile = $job['source'];
						UUtilLogger::WriteLog("Removing $xmlFile");
						@unlink($xmlFile);
					}
				}
			}

			UUtilLogger::unsetLocalLog();

			UUtilLogger::WriteLog("### Finished job #$currentJob ###\n");
		}
	}
}

$hasErroredOut = false;
$poststeps = $scripting->getPostSteps();

if (!empty($poststeps))
{
	foreach ($poststeps as $stepName)
	{
		UUtilLogger::WriteLog("\tRunning $stepName");
		$step = UFactory::getStep($stepName);
		$config = array();
		$step->setup($config, $globalState);
		$done = false;

		while (!$done)
		{
			$ret = $step->tick();

			if ($ret['Error'])
			{
				// The step has errored out. Abort! Abort!
				UUtilLogger::WriteLog($ret['Error']);
				$hasErroredOut = true;
				$done = true;
			}
			elseif (!$ret['HasRun'])
			{
				$done = true;
			}
		}

		$globalState = $step->getGlobalState();

		// Break processing on error
		if ($hasErroredOut)
		{
			break;
		}
	}
}