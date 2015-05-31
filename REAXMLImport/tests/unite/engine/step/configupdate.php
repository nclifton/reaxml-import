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

class UStepConfigupdate extends UAbstractPart
{
	var $jobKey = null;

	var $siteDef = null;

	private $config = array();

	protected function _prepare()
	{
		$this->jobKey = $this->_parametersArray['jobkey'];
		$globalState = $this->getGlobalState();
		$this->siteDef = $globalState['JobDefinitions'][$this->jobKey]['definition'];

		$this->setState('prepared');
	}

	protected function _run()
	{
		$this->loadConfig();

		UUtilLogger::WriteLog("\t\tUpdating site's Global Configuration");

		$this->config['offline'] = 0;
		$this->config['log_path'] = $this->siteDef['siteInfo']['absolutepath'] . '/log';
		$this->config['tmp_path'] = $this->siteDef['siteInfo']['absolutepath'] . '/tmp';

		if (!is_null($this->siteDef['siteInfo']['livesite']))
		{
			$livesite = $this->siteDef['siteInfo']['livesite'];
			$livesite = trim($livesite);
			$livesite = rtrim($livesite, '/');
			$this->config['live_site'] = $livesite;
		}
		else
		{
			$this->config['live_site'] = '';
		}

		if ($this->siteDef['siteInfo']['ftphost'] && $this->siteDef['siteInfo']['ftpuser'])
		{
			$this->config['ftp_enable'] = 1;
			$this->config['ftp_host'] = $this->siteDef['siteInfo']['ftphost'];
			$this->config['ftp_port'] = $this->siteDef['siteInfo']['ftpport'];
			$this->config['ftp_user'] = $this->siteDef['siteInfo']['ftpuser'];
			$this->config['ftp_pass'] = $this->siteDef['siteInfo']['ftppass'];
			$this->config['ftp_root'] = $this->siteDef['siteInfo']['ftpdir'];
		}
		else
		{
			$this->config['ftp_enable'] = 0;
			$this->config['ftp_host'] = '';
			$this->config['ftp_port'] = '';
			$this->config['ftp_user'] = '';
			$this->config['ftp_pass'] = '';
			$this->config['ftp_root'] = '';
		}

		if (isset($this->siteDef['databaseInfo']['joomla']))
		{
			$dbKey = 'joomla';
		}
		else
		{
			$dbKey = 'site';
		}

		if (array_key_exists('dbport', $this->siteDef['databaseInfo'][$dbKey]))
		{
			$this->config['host'] = $this->siteDef['databaseInfo'][$dbKey]['dbhost'] . ':' . $this->siteDef['databaseInfo'][$dbKey]['dbport'];
		}
		else
		{
			$this->config['host'] = $this->siteDef['databaseInfo'][$dbKey]['dbhost'];
		}

		$this->config['user'] = $this->siteDef['databaseInfo'][$dbKey]['dbuser'];
		$this->config['password'] = $this->siteDef['databaseInfo'][$dbKey]['dbpass'];
		$this->config['db'] = $this->siteDef['databaseInfo'][$dbKey]['dbname'];
		$this->config['dbprefix'] = $this->siteDef['databaseInfo'][$dbKey]['dbprefix'];

		$this->config['mailfrom'] = $this->siteDef['siteInfo']['email'];
		$this->config['fromname'] = $this->siteDef['siteInfo']['name'];
		$this->config['sitename'] = $this->siteDef['siteInfo']['name'];

		$result = $this->saveConfig();

		// PART II. Change admin user, if this is specified
		if (!empty($this->siteDef['siteInfo']['adminPassword']))
		{
			UUtilLogger::WriteLog("\t\tModifying Super Administrator user");
			$uid = $this->siteDef['siteInfo']['adminID'];
			$username = $this->siteDef['siteInfo']['adminUser'];
			$password = $this->siteDef['siteInfo']['adminPassword'];
			$this->changeAdminUser($uid, $username, $password);
		}

		// Finalization
		if (!$result)
		{
			$this->setState('error');
		}
		else
		{
			$this->setState('postrun');
		}
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

	private function loadConfig()
	{
		UUtilLogger::WriteLog("\t\tRetrieving configuration.php...");
		if (!$this->retrieveFile('configuration.php', $localFile))
		{
			UUtilLogger::WriteLog('Could not download the configuration.php file!');

			return false;
		}

		// This bit loads the previous configuration. It's a too hack-ish way to do this, but
		// importing the configuration.php polutes the global namespace by defining the JConfig
		// class. Since unloading classes is impossible in PHP, I have to work around this by
		// implementing a simple custom parser of class variables which doesn't need to load the
		// class itself. Hey, I didn't even know it was possible before coding this :D
		// Load file as a string array
		$filedata = file($localFile);
		// Loop all lines
		$hold = '';

		foreach ($filedata as $line)
		{
			$line = trim($line); // Trim whitespace of the line
			if ((substr($line, 0, 3) == 'var') || (substr($line, 0, 6) == 'public') || (!empty($hold))) // only deal w/ lines defining class variables
			{
				if (substr($line, 0, 6) == 'public')
				{
					$line = trim(substr($line, 7, strlen($line) - 3)); // remove the public keyword
				}

				if (substr($line, 0, 3) == 'var')
				{
					$line = trim(substr($line, 4, strlen($line) - 3)); // remove the var keyword
				}

				if (substr($line, -1) == "\n")
				{
					$line = substr($line, 0, strlen($line) - 1);
				}

				if (substr($line, -1) != ';')
				{
					$hold .= $line;

					continue;
				}
				else
				{
					$line = $hold . $line;
					$hold = '';
				}

				// Get a list of defined local varibles
				$environment_before = array_keys(get_defined_vars());

				// Execute the variable definition
				eval($line);

				// Get the new list of defined local variables
				$environment_after = array_keys(get_defined_vars());

				// Import changed variables and unset them
				$different_vars = array_diff($environment_after, $environment_before);

				foreach ($different_vars as $varname)
				{
					if ($varname == 'environment_before')
					{
						continue;
					}

					$this->config[$varname] = $$varname;

					unset($varname);
				}

				unset($different_vars, $environment_before, $environment_after);
			}
		}

		// Finally, delete the temp file
		@unlink($localFile);

		return true;
	}

	private function saveConfig()
	{
		UUtilLogger::WriteLog("\t\tSaving configuration.php...");
		$joomlaVersion = $this->_getJoomlaVersion();
		$keyword = version_compare($joomlaVersion, '1.7', 'lt') ? 'var' : 'public';
		$localFile = tempnam(UConfig::tempDir, 'ucp');
		$out = "<?php\n";
		$out .= "class JConfig {\n";
		foreach ($this->config as $name => $value)
		{
			if (is_array($value))
			{
				$temp = '(';
				foreach ($value as $key => $data)
				{
					if (strlen($temp) > 1)
					{
						$temp .= ', ';
					}
					$temp .= '\'' . addslashes($key) . '\' => \'' . addslashes($data) . '\'';
				}
				$temp .= ')';
				$value = 'array ' . $temp;
			}
			else
			{
				$value = "'" . addslashes($value) . "'";
			}

			$out .= "\t" . $keyword . ' $' . $name . " = " . $value . ";\n";
		}

		$out .= '}' . "\n";
		$ret = @file_put_contents($localFile, $out);

		if ($ret === false)
		{
			UUtilLogger::WriteLog("\t\tSaving configuration.php failed: unable to create temp file");
			@unlink($localFile);

			return false;
		}

		if ($this->siteDef['siteInfo']['ftphost'] && $this->siteDef['siteInfo']['ftpuser'])
		{
			$ftp = UUtilFtp::getInstance();

			if (!$ftp->uploadAndDelete('configuration.php', $localFile))
			{
				UUtilLogger::WriteLog("\t\tSaving configuration.php failed: unable to upload");
				@unlink($localFile);

				return false;
			}
		}
		else
		{
			$remoteFileAbsolute = rtrim($this->siteDef['siteInfo']['absolutepath'], '/\\') . DIRECTORY_SEPARATOR . 'configuration.php';
			$result = @copy($localFile, $remoteFileAbsolute);
			@unlink($localFile);

			if (!$result)
			{
				UUtilLogger::WriteLog("\t\tSaving configuration.php failed: unable to copy");

				return false;
			}
		}

		return true;
	}

	private function changeAdminUser($uid, $username, $password)
	{
		$joomlaVersion = $this->_getJoomlaVersion();

		if (isset($this->siteDef['databaseInfo']['joomla']))
		{
			$dbdef = $this->siteDef['databaseInfo']['joomla'];
		}
		else
		{
			$dbdef = $this->siteDef['databaseInfo']['site'];
		}

		$config = array(
			'driver'   => $dbdef['dbdriver'],
			'host'     => $dbdef['dbhost'],
			'user'     => $dbdef['dbuser'],
			'password' => $dbdef['dbpass'],
			'database' => $dbdef['dbname'],
			'prefix'   => $dbdef['dbprefix'],
			'select'   => true
		);
		$db = UFactory::getDatabase($config);

		// Make sure there is no other user with the same username as the one chosen for the Super Administrator user
		$query = 'SELECT COUNT(*)  FROM `#__users` WHERE `username` = "' . $db->getEscaped($username) . '" AND NOT(`id` = ' . $uid . ')';
		$db->setQuery($query);
		$countsimilarusers = $db->loadResult();
		if ($countsimilarusers > 0)
		{
			UUtilLogger::WriteLog("\t\tThe username $username is already taken! Skipping...");

			return false;
		}

		// Generate encrypted password string
		$salt = $this->genRandomPassword(32);
		$crypt = md5($password . $salt);
		$cryptpass = $crypt . ':' . $salt;

		// Update database
		$query = 'UPDATE `#__users` SET `username` = "' . $db->getEscaped($username) . '", ' .
			'`password` = "' . $db->getEscaped($cryptpass) . '" WHERE `id` = ' . $uid;
		$db->setQuery($query);
		$res = $db->query();

		if (!$res)
		{
			UUtilLogger::WriteLog("\t\tUpdating the Super Administrator user has failed with database error:");
			UUtilLogger::WriteLog("\t\t" . $db->getError());
		}

		return $res;
	}

	private function genRandomPassword($length = 8)
	{
		$salt = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$len = strlen($salt);
		$makepass = '';

		$stat = @stat(__FILE__);

		if (empty($stat) || !is_array($stat))
		{
			$stat = array(php_uname());
		}

		mt_srand(crc32(microtime() . implode('|', $stat)));

		for ($i = 0; $i < $length; $i++)
		{
			$makepass .= $salt[mt_rand(0, $len - 1)];
		}

		return $makepass;
	}

	private function _getJoomlaVersion()
	{
		static $joomlaVersion = null;

		if (empty($joomlaVersion))
		{
			// File exists in Joomla! 1.5
			$j15 = $this->retrieveFile('libraries/bitfolge/feedcreator.php', $localFile);
			@unlink($localFile);

			// File exists only in Joomla! 1.7 and later
			$j17 = $this->retrieveFile('libraries/joomla/form/form.php', $localFile);
			@unlink($localFile);

			// File exists only in Joomla! 2.5 and later
			$j25 = $this->retrieveFile('libraries/cms/captcha/captcha.php', $localFile);
			@unlink($localFile);

			// File exists only in Joomla! 3.1 and later
			$j31 = $this->retrieveFile('libraries/cms/ucm/base.php', $localFile);
			@unlink($localFile);

			if ($j15 && !$j17 && !$j25 && !$j31)
			{
				$joomlaVersion = '1.5';
			}
			elseif ($j17 && !$j25 && !$j31)
			{
				$joomlaVersion = '1.7';
			}
			elseif ($j25 && !$j31)
			{
				$joomlaVersion = '2.5';
			}
			else
			{
				$joomlaVersion = '3.1';
			}
		}

		return $joomlaVersion;
	}
}