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
 * Cleans up at the end of the restoration
 */
class UStepCleanup extends UAbstractPart
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
		UUtilLogger::WriteLog("\t\tCleaning up");
		$siteInfo = $this->siteDef['siteInfo'];

		if ($this->siteDef['siteInfo']['ftphost'] && $this->siteDef['siteInfo']['ftpuser'])
		{
			// Use FTP mode to clean up

			$ftp = UUtilFtp::getInstance($siteInfo['ftphost'], $siteInfo['ftpport'], $siteInfo['ftpuser'], $siteInfo['ftppass'], $siteInfo['ftpdir']);
			$ftp->connect();
			UUtilLogger::WriteLog("\t\t\tChanging to initial directory (FTP)");
			$ftp->changeToInitialDirectory();
			UUtilLogger::WriteLog("\t\t\tRecursively removing installation directory (FTP)");
			$ftp->recursiveDelete('installation');

			// Unstealth
			UUtilLogger::WriteLog("\t\t\tRemoving old .htaccess and restoring the original (if any)");
			$ftp->changeToInitialDirectory();
			$ftp->delete('.htaccess');
			$ftp->downloadToTemp('htaccess.bak', $localTempName);
			$ftp->delete('htaccess.bak');
			$ftp->uploadAndDelete('.htaccess', $localTempName);
		}
		else
		{
			// Use direct file writes to clean up
			UUtilLogger::WriteLog("\t\t\tRecursively removing installation directory (direct)");
			$this->recursive_remove_directory($this->siteDef['siteInfo']['absolutepath'] . DIRECTORY_SEPARATOR . 'installation');

			UUtilLogger::WriteLog("\t\t\tRemoving stealth .htaccess and restoring the original");
			$sitePath = $this->siteDef['siteInfo']['absolutepath'];
			@unlink($sitePath . '/.htaccess');
			@rename($sitePath . '/htaccess.bak', $sitePath . '/.htaccess');
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

	private function recursive_remove_directory($directory, $empty = false)
	{
		// if the path has a slash at the end we remove it here
		if (substr($directory, -1) == '/')
		{
			$directory = substr($directory, 0, -1);
		}

		// if the path is not valid or is not a directory ...
		if (!file_exists($directory) || !is_dir($directory))
		{
			// ... we return false and exit the function
			return false;
			// ... if the path is not readable
		}
		elseif (!is_readable($directory))
		{
			// ... we return false and exit the function
			return false;
			// ... else if the path is readable
		}
		else
		{

			// we open the directory
			$handle = opendir($directory);

			// and scan through the items inside
			while (false !== ($item = readdir($handle)))
			{
				// if the filepointer is not the current directory
				// or the parent directory
				if ($item != '.' && $item != '..')
				{
					// we build the new path to delete
					$path = $directory . '/' . $item;

					// if the new path is a directory
					if (is_dir($path))
					{
						// we call this function with the new path
						$this->recursive_remove_directory($path);
						// if the new path is a file
					}
					else
					{
						// we remove the file
						unlink($path);
					}
				}
			}
			// close the directory
			closedir($handle);

			// if the option to empty is not set to true
			if ($empty == false)
			{
				// try to delete the now empty directory
				if (!rmdir($directory))
				{
					// return false if not possible
					return false;
				}
			}

			// return success
			return true;
		}
	}
}