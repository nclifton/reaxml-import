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
 * Prints out the status banner at the end of a restoration run
 */
class UStepPostbanner extends UAbstractPart
{

	protected function _prepare()
	{
		$this->setState('prepared');
	}

	protected function _run()
	{
		/*
		 * $globalState['TotalJobs']
		 * $globalState['FailedJobs']++;
		 * $globalState['ExecutedJobs']++;
		 */
		$globalState = $this->getGlobalState();
		UUtilLogger::WriteLog('===============================================================================');
		UUtilLogger::WriteLog('UNITE finished its run cycle');
		UUtilLogger::WriteLog('Total definitions found                : ' . $globalState['TotalJobs']);
		UUtilLogger::WriteLog('Total definitions executed successfuly : ' . $globalState['ExecutedJobs']);
		UUtilLogger::WriteLog('Total definitions failed to run        : ' . $globalState['FailedJobs']);

		$this->setState('postrun');
	}

	protected function _finalize()
	{
		$globalState = $this->getGlobalState();
		if (UConfig::globalLogging && ($globalState['ExecutedJobs'] > 0))
		{
			$to = UConfig::sysop_email;
			$subject = 'Finished UNITE run';
			$body = "Hello,\n\nUNITE has finished a run cycle. It has processed {$globalState['TotalJobs']} site definitions, of which\n" .
				"{$globalState['ExecutedJobs']} have been successfully processed.\n\n--- LOG START --\n";
			$body .= file_get_contents(UUtilLogger::logName());
			$body .= "\n--- END LOG ---";
			$headers = 'From: ' . UConfig::sysop_email . "\r\n" .
				'X-Mailer: AkeebaUNITE/' . UNITE_VERSION;
			@mail($to, $subject, $body, $headers);
		}

		$this->setState('finished');
	}
}