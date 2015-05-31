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

class UStepCustomsql extends UAbstractPart
{
	var $jobKey = null;

	var $siteDef = null;

	var $dbDefs = null;

	protected function _prepare()
	{
		$this->jobKey = $this->_parametersArray['jobkey'];
		$globalState = $this->getGlobalState();
		$this->siteDef = $globalState['JobDefinitions'][$this->jobKey]['definition'];
		$this->dbDefs = array_key_exists('extrasql', $this->siteDef) ? $this->siteDef['extrasql'] : array();

		$this->setState('prepared');
	}

	protected function _run()
	{
		if (!count($this->dbDefs))
		{
			$this->setState('postrun');

			return;
		}

		$inboxDir = UConfig::inboxDir;
		if (!is_dir(realpath($inboxDir)))
		{
			// Try appending it to UNITE's root
			$inboxDir = realpath(dirname(__FILE__) . '/../../' . $inboxDir);
		}

		foreach ($this->dbDefs as $item)
		{
			$defaultKey = isset($this->siteDef['databaseInfo']['joomla']) ? 'joomla' : 'site';
			$dbname = empty($item['db']) ? $defaultKey : $item['db'];

			if (!file_exists($item['file']))
			{
				$localFile = $inboxDir . DIRECTORY_SEPARATOR . $item['file'];
			}

			$databaseInfo = $this->siteDef['databaseInfo'][$dbname];

			UUtilLogger::WriteLog("\t\tImporting extra SQL file " . basename($localFile) . ' to database ' . $dbname);

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

			if (!file_exists($localFile))
			{
				$this->setState('error', "Could not open $localFile for reading");

				return false;
			}

			$error = UUtilDbrestore::restoreDump($localFile, $db);

			if ($error !== true)
			{
				$this->setState('error', $error);

				return false;
			}

			// Do we have to delete the package?
			if ($this->siteDef['siteInfo']['deletePackage'])
			{
				UUtilLogger::WriteLog("\t\tRemoving $localFile");

				$ret = @unlink($localFile);

				if ($ret === false)
				{
					UUtilLogger::WriteLog('Could not delete extra SQL file ' . $localFile . '. You\'ll have to do it manually.');
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
}