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
 * Writes a Stealth Mode .htaccess to the site's root
 */
class UStepStealth extends UAbstractPart
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
		$siteInfo = $this->siteDef['siteInfo'];
		if ($this->siteDef['siteInfo']['ftphost'] && $this->siteDef['siteInfo']['ftpuser'])
		{
			$ftp = UUtilFtp::getInstance($siteInfo['ftphost'], $siteInfo['ftpport'], $siteInfo['ftpuser'], $siteInfo['ftppass'], $siteInfo['ftpdir']);
			$ftp->connect();
			UUtilLogger::WriteLog("\t\tChanging to initial directory (FTP)");
			$ftp->changeToInitialDirectory();
			UUtilLogger::WriteLog("\t\tWriting a stealth .htaccess (FTP)");
			$localFile = UConfig::tempDir . '/stealth.txt';
			UUtilStealth::makeStealthHtaccess($localFile);
			$ftp->uploadAndDelete('.htaccess', $localFile);
		}
		else
		{
			UUtilLogger::WriteLog("\t\tWriting a stealth .htaccess (direct access)");
			$sitePath = $this->siteDef['siteInfo']['absolutepath'];
			$localFile = $sitePath . '/.htaccess';
			UUtilStealth::makeStealthHtaccess($localFile);
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