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
 * Checks a job definition for validity, i.e. all files exist, FTP and database connections
 * are set up correctly.
 */
class UStepValidator extends UAbstractPart
{
	var $jobKey = null;

	var $siteDef = null;

	protected function _prepare()
	{
		$this->jobKey = $this->_parametersArray['jobkey'];
		$globalState = $this->getGlobalState();
		$this->siteDef = $globalState['JobDefinitions'][$this->jobKey]['definition'];

		$this->setState('prepared');
	}

	protected function _run()
	{
		$this->setState('running');

		// Get the inbox location
		$inboxDir = UConfig::inboxDir;
		if (!is_dir(realpath($inboxDir)))
		{
			// Try appending it to UNITE's root
			$unite_root = realpath(dirname(__FILE__) . '/../..');
			$inboxDir = realpath($unite_root . '/' . $inboxDir);
		}
		else
		{
			$inboxDir = realpath(UConfig::inboxDir);
		}

		// Test existence of package
		if (!isset($this->siteDef['siteInfo']))
		{
			$this->setState('error', 'There is no siteInfo section in the job description; aborting');

			return false;
		}
		$package = $this->siteDef['siteInfo']['package'];
		$packageFrom = $this->siteDef['siteInfo']['packageFrom'];

		// If the source of the package is "inbox", make sure the package file exists
		if (($packageFrom == 'inbox') && !file_exists($package))
		{
			// Is the path local to the inbox?
			$package = rtrim($inboxDir, '/\\') . '/' . $package;

			if (!file_exists($package))
			{
				$this->setState('error', 'The referenced package, ' . basename($package) . ' does not exist!');

				return false;
			}
			else
			{
				$this->siteDef['siteInfo']['package'] = $package;
			}
		}
		// If the package is to be fetched from S3
		elseif ($packageFrom == 's3')
		{
			// Make sure we have an access key
			if (empty($this->siteDef['s3']['accesskey']))
			{
				$this->setState('error', 'The S3 Access Key is empty.');

				return false;
			}

			// Make sure we have a secret key
			if (empty($this->siteDef['s3']['secretkey']))
			{
				$this->setState('error', 'The S3 Secret Key is empty.');

				return false;
			}

			// Make sure we have a bucket key
			if (empty($this->siteDef['s3']['bucket']))
			{
				$this->setState('error', 'The S3 bucket is empty.');

				return false;
			}

			// Make sure we have a filename
			if (empty($this->siteDef['s3']['filename']))
			{
				$this->setState('error', 'The S3 filename is empty.');

				return false;
			}
		}
		// If the package is to be fetched after a remote backup
		elseif ($packageFrom == 'remote')
		{
			// Make sure we have a host
			if (empty($this->siteDef['remote']['host']))
			{
				$this->setState('error', 'The remote host is empty.');

				return false;
			}
			if (empty($this->siteDef['remote']['secret']))
			{
				$this->setState('error', 'The remote secret key is empty.');

				return false;
			}

			// Figure out which connection method to use
			$api = UUtilJsonapi::getInstance();
			$api->host = $this->siteDef['remote']['host'];
			$api->secret = $this->siteDef['remote']['secret'];

			$works = false;
			$exception = null;
			foreach (array('GET', 'POST') as $verb)
			{
				if ($works)
				{
					break;
				}
				$api->verb = $verb;

				foreach (array('raw', 'html') as $format)
				{
					if ($works)
					{
						break;
					}
					$api->format = $format;

					try
					{
						$ret = $api->doQuery('getVersion', array());
						$works = true;
						$this->_versionInfo = $ret->body->data;
					}
					catch (RemoteException $e)
					{
						$exception = $e;
					}
				}
			}

			if (!$works)
			{
				// Darn, can't make it work, matey...
				$this->setState('error', 'Sorry, I cannot find a way to connect to your remote site.');

				return false;
			}
			else
			{
				// Store the verb and format for later use
				$this->siteDef['remote']['verb'] = $verb;
				$this->siteDef['remote']['format'] = $format;
			}
		}

		// Test absolute path
		$absolutepath = $this->siteDef['siteInfo']['absolutepath'];
		if (!file_exists($absolutepath) && !($this->siteDef['siteInfo']['ftphost'] && $this->siteDef['siteInfo']['ftpuser']))
		{
			// Try creating the directory
			$created = @mkdir($absolutepath, 0755, true);
			if (!$created)
			{
				$this->setState('error', 'The configured absolute path,' . $absolutepath . ' does not exist.');

				return false;
			}
		}

		// Test FTP connection, if defined
		$siteInfo = $this->siteDef['siteInfo'];
		if ($siteInfo['ftphost'] && $siteInfo['ftpuser'])
		{
			$ftp = UUtilFtp::getInstance($siteInfo['ftphost'], $siteInfo['ftpport'], $siteInfo['ftpuser'], $siteInfo['ftppass'], $siteInfo['ftpdir']);
			if (!$ftp->connect())
			{
				$this->setState('error', 'Could not connect to FTP server. The error message was: ' . $ftp->getError());

				return false;
			}
		}

		// Test main database connection
		if (isset($this->siteDef['databaseInfo']['joomla']))
		{
			$maindbInfo = $this->siteDef['databaseInfo']['joomla'];
			$dbKey = 'joomla';
		}
		else
		{
			$maindbInfo = $this->siteDef['databaseInfo']['site'];
			$dbKey = 'site';
		}

		if (!array_key_exists('driver', $this->siteDef['databaseInfo'][$dbKey]))
		{
			$this->siteDef['databaseInfo'][$dbKey]['driver'] = 'mysqli';
		}

		$dbconfig = array(
			'driver'   => $maindbInfo['dbdriver'],
			'host'     => $maindbInfo['dbhost'],
			'user'     => $maindbInfo['dbuser'],
			'password' => $maindbInfo['dbpass'],
			'database' => $maindbInfo['dbname'],
			'prefix'   => $maindbInfo['dbprefix'],
			'select'   => true
		);
		if (array_key_exists('dbport', $maindbInfo))
		{
			$dbconfig['port'] = $maindbInfo['dbport'];
		}
		$db = UFactory::getDatabase($dbconfig);
		if ($db->getError())
		{
			$this->setState('error', 'Could not connect to database server.');

			return false;
		}

		// Test existence of extrafiles packages
		if (isset($this->siteDef['extrafiles']))
		{
			$extrafiles = $this->siteDef['extrafiles'];
			if (count($extrafiles) > 0)
			{
				foreach ($extrafiles as $filedef)
				{
					if (!file_exists($inboxDir . '/' . $filedef['file']))
					{
						$this->setState('error', 'The extrafiles package ' . $filedef['file'] . ' does not exist. Aborting.');

						return false;
					}
				}
			}
		}

		// Test existence of extrasql scripts
		if (isset($this->siteDef['extrasql']))
		{
			$extrasql = $this->siteDef['extrasql'];
			if (count($extrasql) > 0)
			{
				foreach ($extrasql as $sqldef)
				{
					if (!file_exists($inboxDir . '/' . $sqldef['file']))
					{
						$this->setState('error', 'The extrasql SQL file ' . $sqldef['file'] . ' does not exist. Aborting.');

						return false;
					}
				}
			}
		}

		// Set the local logging, if such a value is supplied
		if (!empty($this->siteDef['siteInfo']['localLog']))
		{
			UUtilLogger::setLocalLog($this->siteDef['siteInfo']['localLog']);
		}

		// All checks passed
		$this->setState('postrun');
	}

	protected function _finalize()
	{
		// Merge back the changes to the global state
		$globalState = $this->getGlobalState();
		$globalState['JobDefinitions'][$this->jobKey]['definition'] = $this->siteDef;
		$this->setGlobalState($globalState);

		$this->setState('finished');
	}
}