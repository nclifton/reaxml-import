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

class UStepXmlscanner extends UAbstractPart
{
	private $defs = array();

	private $totalDefs = 0;

	private $dropDir = null;

	protected function _prepare()
	{
		// Fetch from global state
		$this->defs = $this->state['JobDefinitions'];
		$this->totalDefs = $this->state['TotalJobs'];

		// Resolve the inbox location
		$inboxDir = UConfig::inboxDir;
		if (!is_dir($inboxDir))
		{
			// Try to prepend the installation root (one level above the engine directory)
			$newDropBox = dirname(__FILE__) . '/../../' . $inboxDir;
			if (!is_dir($newDropBox))
			{
				// We can't find the inbox directory. Log an error.
				$this->setError("Can not find the inbox directory $inboxDir");

				return;
			}
			else
			{
				$inboxDir = $newDropBox;
			}
		}
		$this->dropDir = $inboxDir;
		$this->setState('prepared');
	}

	protected function _run()
	{
		UUtilLogger::WriteLog("Scanning $this->dropDir for XML files...");

		$dirScanner = new UUtilDirscanner();
		try
		{
			$files = $dirScanner->getFiles($this->dropDir, 'xml');
		}
		catch (Exception $e)
		{
			$files = array();
			$this->setError($e->getMessage());
		}

		if (!empty($files))
		{
			foreach ($files as $file)
			{
				UUtilLogger::WriteLog("\tParsing $file");
				$parser = UFactory::getClassInstance('UCoreXmlparser');
				$def = $parser->parseFile($file);
				if ($def === false)
				{
					UUtilLogger::WriteLog($parser->getError());
				}
				else
				{
					$this->defs['xml-' . basename($file)] = array(
						'engine'     => 'xml',
						'source'     => $file,
						'definition' => $def
					);
					$this->totalDefs++;
				}
			}
		}
		else
		{
			UUtilLogger::WriteLog("No XML files were found in the inbox directory \"$this->dropDir\"");
		}

		$this->setState('postrun');
	}

	protected function _finalize()
	{
		$this->state['JobDefinitions'] = $this->defs;
		$this->state['TotalJobs'] = $this->totalDefs;

		$this->setState('finished');
	}
}