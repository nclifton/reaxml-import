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
 * Loads the scripting definition. The SD defines which steps and by
 * which order will be loaded. Think of it as programmable dispatcher
 * logic.
 */
class UCoreScripting extends UAbstractObject
{
	/** @var array The scripting definition as nested arrays */
	private $lists = array();

	/**
	 * The constructor also loads the SD
	 *
	 * @return UCoreScripting
	 */
	public function __construct()
	{
		parent::__construct();

		$dir = dirname(__FILE__) . '/../scripting';
		$scanner = new UUtilDirscanner;

		try
		{
			$iniFiles = $scanner->getFiles($dir, 'ini', false);
		}
		catch (Exception $e)
		{
			$iniFiles = array();
			$this->setError($e->getMessage());
		}

		if (!empty($iniFiles))
		{
			if (defined('UNITE_PREFERRED_SCRIPTING'))
			{
				$preferred = UNITE_PREFERRED_SCRIPTING;
			}
			else
			{
				// Do we have a command line option --script?
				$preferred = UUtilCli::getOption('script');

				if (empty($preferred))
				{
					$preferred = '01_default';
				}
			}

			$preferred .= '.ini';

			if (!in_array($preferred, $iniFiles))
			{
				$preferred = array_shift($iniFiles);
			}

			$parsedData = parse_ini_file($dir . '/' . $preferred);
			$lists = array();

			foreach ($parsedData as $key => $imploded)
			{
				if (strstr($imploded, ','))
				{
					$this->lists[$key] = explode(',', $imploded);
				}
				else
				{
					$this->lists[$key] = array($imploded);
				}
			}
		}
	}

	/**
	 * Gets a specific part of a scripting definition
	 *
	 * @param $part string
	 *
	 * @return array
	 */
	private function getListPart($part)
	{
		if (array_key_exists($part, $this->lists))
		{
			return $this->lists[$part];
		}
		else
		{
			return array();
		}
	}

	/**
	 * Get the pre-steps, running at the application's startup
	 *
	 * @return array
	 */
	public function getPreSteps()
	{
		return $this->getListPart('presteps');
	}

	/**
	 * Get a list of job providers. After they run, they append a
	 * set of job definitions to the global state array
	 *
	 * @return array
	 */
	public function getJobProviders()
	{
		return $this->getListPart('jobproviders');
	}

	/**
	 * Get a list of job steps. All of these steps are executed
	 * once for every job definition.
	 *
	 * @return array
	 */
	public function getJobSteps()
	{
		return $this->getListPart('jobsteps');
	}

	/**
	 * Get a list of steps to run during the tear down of the
	 * application.
	 *
	 * @return array
	 */
	public function getPostSteps()
	{
		return $this->getListPart('poststeps');
	}
}