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
 * Notifies the administrator and the client by email
 */
class UStepEmailnotify extends UAbstractPart
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
		UUtilLogger::WriteLog("\t\tProcessing email notifications");

		// Message to site owner
		$postrun = isset($this->siteDef['postrun']) ? $this->siteDef['postrun'] : null;
		if (!is_null($postrun))
		{
			$this->processPostrun($postrun);
		}

		// Message to administrator (copy of message to site owner)
		if ($this->siteDef['siteInfo']['emailSysop'])
		{
			$this->emailSysop($postrun);
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

	private function processPostrun($postrun)
	{
		$postrun = isset($this->siteDef['postrun']) ? $this->siteDef['postrun'] : null;

		return @mail(
			$postrun['emailto'], $postrun['emailsubject'], $postrun['emailbody'], 'From: ' . UConfig::sysop_email . "\r\n" .
			'X-Mailer: AkeebaUNITE/' . UNITE_VERSION
		);
	}

	private function emailSysop($postrun)
	{
		return @mail(
			UConfig::sysop_email, 'Copy of ' . $postrun['emailsubject'], "The message below was sent to " . $postrun['emailto'] . "\n\n----------\n\n" . $postrun['emailbody'], 'From: ' . UConfig::sysop_email . "\r\n" .
			'X-Mailer: AkeebaUNITE/' . UNITE_VERSION
		);
	}
}