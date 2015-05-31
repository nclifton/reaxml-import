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

class UStepDbrestore extends UAbstractPart
{
	var $jobKey = null;

	var $siteDef = null;

	var $dbDefs = null;

	protected function _prepare()
	{
		$this->jobKey = $this->_parametersArray['jobkey'];
		$globalState = $this->getGlobalState();
		$this->siteDef = $globalState['JobDefinitions'][$this->jobKey]['definition'];
		$this->dbDefs = $this->siteDef['databaseInfo'];

		$this->setState('prepared');
	}

	protected function _run()
	{
		foreach ($this->dbDefs as $file => $config)
		{
			UUtilLogger::WriteLog("\t\tProcessing database $file");

			$databaseInfo = $this->siteDef['databaseInfo'][$file];
			// Init MySQL database connection
			$dbconfig = array(
				'driver'   => $databaseInfo['dbdriver'],
				'host'     => $databaseInfo['dbhost'],
				'user'     => $databaseInfo['dbuser'],
				'password' => $databaseInfo['dbpass'],
				'database' => $databaseInfo['dbname'],
				'prefix'   => $databaseInfo['dbprefix'],
				'select'   => true
			);
			if (array_key_exists('dbport', $databaseInfo))
			{
				$dbconfig['port'] = $databaseInfo['dbport'];
			}
			$db = UFactory::getDatabase($dbconfig);

			if ($db->getError())
			{
				$this->setState('error', 'Could not connect to database server.');

				return false;
			}

			// Download MySQL dump file to temp directory
			$localFile = '';
			if (!$this->retrieveFile('installation/sql/' . $file . '.sql', $localFile))
			{
				$this->setState('error', 'Could not download the ' . $file . '.sql file!');

				return false;
			}

			UUtilLogger::WriteLog("\t\t\tImporting $file.sql to database...");
			$error = UUtilDbrestore::restoreDump($localFile, $db, array('changecollation' => $databaseInfo['changecollation']));
			@unlink($localFile);

			if ($error !== true)
			{
				$this->setState('error', $error);

				return false;
			}

			// Now try the same thing with multiple parts
			$i = 0;
			$done = false;
			while (!$done)
			{
				$i++;
				// Download MySQL dump file to temp directory
				$localFile = '';
				if (!$this->retrieveFile('installation/sql/' . $file . '.s' . sprintf('%02u', $i), $localFile))
				{
					$done = true;
					break;
				}
				UUtilLogger::WriteLog("Importing $file.s" . sprintf('%02u', $i) . " to database...");
				$error = UUtilDbrestore::restoreDump($localFile, $db);
				@unlink($localFile);

				if ($error !== true)
				{
					$this->setState('error', $error);

					return false;
				}
			}
		}

		$this->setState('postrun');
	}

	protected function _finalize()
	{
		// Merge back any changes to the global state
		$globalState = $this->getGlobalState();
		$globalState['JobDefinitions'][$this->jobKey]['definition'] = $this->siteDef;
		$this->setGlobalState($globalState);

		$this->setState('finished');
	}

	private function retrieveFile($remoteFile, &$localFile)
	{
		// Is FTP enabled?
		if ($this->siteDef['siteInfo']['ftphost'] && $this->siteDef['siteInfo']['ftpuser'])
		{
			UUtilLogger::WriteLog("\t\t\tTrying to retrieve $remoteFile using FTP");
			$ftp = UUtilFtp::getInstance($this->siteDef['siteInfo']['ftphost'], $this->siteDef['siteInfo']['ftpport'], $this->siteDef['siteInfo']['ftpuser'], $this->siteDef['siteInfo']['ftppass'], $this->siteDef['siteInfo']['ftpdir']);
			$ftp->disconnect();
			$ftp->connect();
			if (!$ftp->downloadToTemp('installation/sql/' . $remoteFile, $localFile))
			{
				UUtilLogger::WriteLog("\t\t\tCould not download the $remoteFile file!");

				return false;
			}
			else
			{
				return true;
			}
		}
		else
		{
			// Use direct access
			UUtilLogger::WriteLog("\t\t\tTrying to retrieve $remoteFile using direct access");
			$remoteFileAbsolute = rtrim($this->siteDef['siteInfo']['absolutepath'], '/\\') . '/' . $remoteFile;
			$localFile = tempnam(UConfig::tempDir, 'usd');

			if (!file_exists($remoteFileAbsolute))
			{
				@unlink($localFile);

				return false;
			}

			return @copy($remoteFileAbsolute, $localFile);
		}
	}
}