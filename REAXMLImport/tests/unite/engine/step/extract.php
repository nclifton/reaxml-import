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

class UStepExtract extends UAbstractPart
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
		// Required by restore.php
		if (!defined('KICKSTART'))
		{
			define('KICKSTART', 1);
		}

		// Make sure restore.php is loaded
		require_once dirname(__FILE__) . '/../extras/restore.php';

		// Basic configuration
		$extension = strtoupper(substr($this->siteDef['siteInfo']['package'], -3));
		$config = array(
			'kickstart.setup.restoreperms'   => false,
			'kickstart.setup.dryrun'         => false,
			'kickstart.setup.destdir'        => $this->siteDef['siteInfo']['absolutepath'],
			'kickstart.setup.ignoreerrors'   => false,
			'kickstart.tuning.max_exec_time' => 5000,
			'kickstart.tuning.run_time_bias' => 95,
			'kickstart.tuning.min_exec_time' => 0,
			'kickstart.procengine'           => 'direct',
			'kickstart.setup.sourcefile'     => $this->siteDef['siteInfo']['package'],
			'kickstart.security.password'    => null,
			'kickstart.setup.filetype'       => $extension,
			'kickstart.jps.password'         => $this->siteDef['siteInfo']['jpspassword'],
		);

		// Add FTP config if required
		if ($this->siteDef['siteInfo']['ftphost'] && $this->siteDef['siteInfo']['ftpuser'])
		{
			$siteInfo = $this->siteDef['siteInfo'];
			$extraconfig = array(
				'kickstart.ftp.ssl'     => false,
				'kickstart.ftp.passive' => true,
				'kickstart.ftp.host'    => $siteInfo['ftphost'],
				'kickstart.ftp.port'    => empty($siteInfo['ftpport']) ? '21' : $siteInfo['ftpport'],
				'kickstart.ftp.user'    => $siteInfo['ftpuser'],
				'kickstart.ftp.pass'    => $siteInfo['ftppass'],
				'kickstart.ftp.dir'     => $siteInfo['ftpdir'],
				'kickstart.ftp.tempdir' => UConfig::tempDir,
				'kickstart.procengine'  => 'ftp'
			);
			$config = array_merge($config, $extraconfig);
		}

		$overrides = array(
			'rename_files' => array('.htaccess' => 'htaccess.bak'),
			'skip_files'   => array(),
			'reset'        => true
		);

		// Start extraction
		AKFactory::nuke();
		foreach ($config as $key => $value)
		{
			AKFactory::set($key, $value);
		}
		AKFactory::set('kickstart.enabled', true);
		$engine = AKFactory::getUnarchiver($overrides);
		$engine->tick();
		$ret = $engine->getStatusArray();
		while ($ret['HasRun'] && !$ret['Error'])
		{
			UUtilLogger::WriteLog("\t\tExtractor tick");
			$timer = AKFactory::getTimer();
			$timer->resetTime();
			$engine->tick();
			$ret = $engine->getStatusArray();
		}

		if ($ret['Error'])
		{
			$this->setState('error', 'Extraction of package ' . $this->siteDef['siteInfo']['package'] . ' has failed.');

			return false;
		}

		// Do we have to delete the package?
		if ($this->siteDef['siteInfo']['deletePackage'])
		{
			$ret = @unlink($this->siteDef['siteInfo']['package']);
			if ($ret === false)
			{
				UUtilLogger::WriteLog('Could not delete package ' . $this->siteDef['siteInfo']['package'] . '. You\'ll have to do it manually.');
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