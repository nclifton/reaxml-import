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
 * Perform a remote backup and download the backup archives to the inbox
 * directory, if such a remote backup was requested.
 */
class UStepRemote extends UAbstractPart
{
	var $jobKey = null;

	var $siteDef = null;

	protected function _prepare()
	{
		$this->jobKey = $this->_parametersArray['jobkey'];
		$this->siteDef = $this->state['JobDefinitions'][$this->jobKey]['definition'];

		$this->setState('prepared');
	}

	protected function _run()
	{
		if ($this->getState() == 'postrun')
		{
			return;
		}

		$this->setState('running');

		$packageFrom = $this->siteDef['siteInfo']['packageFrom'];
		if ($packageFrom != 'remote')
		{
			// The user didn't specify a remote backup; return
			$this->setState('postrun');

			return;
		}

		// Setup a JSON API connector object
		UUtilLogger::WriteLog('Setting up a JSON API object', true);
		$api = UUtilJsonapi::getInstance();
		$api->host = $this->siteDef['remote']['host'];
		$api->secret = $this->siteDef['remote']['secret'];
		$api->verb = $this->siteDef['remote']['verb'];
		$api->format = $this->siteDef['remote']['format'];

		// Initialise
		UUtilLogger::WriteLog('Initialising remote backup loop', true);
		if (array_key_exists('profile', $this->siteDef['remote']))
		{
			$profile = (int)($this->siteDef['remote']['profile']);
		}
		else
		{
			$profile = 1;
		}
		$description = 'Remote backup';
		$comment = 'Initiated by UNiTE';
		$backupId = 0;
		$archive = '';
		$progress = 0;

		// Start the backup
		UUtilLogger::WriteLog('Backing up the remote site');
		$config = array(
			'profile'     => $profile,
			'description' => $description,
			'comment'     => $comment
		);
		$data = $api->doQuery('startBackup', $config);
		if ($data->body->status != 200)
		{
			$this->setState('error', 'Could not start backup; Error ' . $data->body->status . ": " . $data->body->data);

			return false;
		}

		// Loop till the backup is over
		while ($data->body->data->HasRun)
		{
			if (isset($data->body->data->BackupID))
			{
				$backupId = $data->body->data->BackupID;
			}

			if (isset($data->body->data->Archive))
			{
				$archive = $data->body->data->Archive;
			}

			if (isset($data->body->data->Progress))
			{
				$progress = $data->body->data->Progress;
			}

			UUtilLogger::WriteLog('Got backup tick');
			UUtilLogger::WriteLog("Progress: {$progress}%");
			UUtilLogger::WriteLog("Domain  : {$data->body->data->Domain}");
			UUtilLogger::WriteLog("Step    : {$data->body->data->Step}");
			UUtilLogger::WriteLog("Substep : {$data->body->data->Substep}");

			if (!empty($data->body->data->Warnings))
			{
				foreach ($data->body->data->Warnings as $warning)
				{
					UUtilLogger::WriteLog("Warning : $warning");
				}
			}
			UUtilLogger::WriteLog("");

			$data = $api->doQuery('stepBackup');
			if ($data->body->status != 200)
			{
				$this->setState('error', 'Backup failed; Error ' . $data->body->status . ": " . $data->body->data);

				return false;
			}
		}
		UUtilLogger::WriteLog('Finished backing up the remote site');

		$this->setState('postrun');

		// Download the backup archive
		$dlmode = array_key_exists('dlmode', $this->siteDef['remote']) ? $this->siteDef['remote']['dlmode'] : 'http';
		$method = '_download' . ucfirst($dlmode);
		UUtilLogger::WriteLog("Downloading backup with $dlmode method");
		$res = $this->$method($backupId);
		if ($res === false)
		{
			return false;
		}
		else
		{
			$this->state['JobDefinitions'][$this->jobKey]['definition']['siteInfo']['package'] =
				UConfig::inboxDir . DIRECTORY_SEPARATOR .
				$this->state['JobDefinitions'][$this->jobKey]['definition']['siteInfo']['package'];
			UUtilLogger::WriteLog("Downloaded file is " . $this->state['JobDefinitions'][$this->jobKey]['definition']['siteInfo']['package']);
		}

		// Delete the backup from the remote server
		$delete = array_key_exists('delete', $this->siteDef['remote']) ? $this->siteDef['remote']['delete'] : true;
		if ($delete)
		{
			$data = $api->doQuery('delete', array('backup_id' => $backupId));
		}
	}

	protected function _finalize()
	{
		$this->setState('finished');
	}

	/**
	 * Performs a backup download through HTTP (as one file per part)
	 */
	private function _downloadHttp($id)
	{
		$api = UUtilJsonapi::getInstance();

		$path = UConfig::inboxDir;

		// Get the backup info
		$data = $api->doQuery('getBackupInfo', array('backup_id' => $id));
		$parts = $data->body->data->multipart;
		$filedefs = $data->body->data->filenames;
		$filedata = array();
		$i = 0;
		foreach ($filedefs as $def)
		{
			$i++;
			if ($i == 1)
			{
				// Update the package name with the name of the downloaded backup archive
				$this->siteDef['siteInfo']['package'] = $def->name;
				$this->state['JobDefinitions'][$this->jobKey]['definition']['siteInfo']['package'] = $def->name;
			}
			$filedata[$def->part] = (object)array('name' => $def->name, 'size' => $def->size);
		}
		if ($parts <= 0)
		{
			$parts = 1;
		}

		if (!count($filedefs))
		{
			$this->setState('error', 'Could not download backup; no backup files found');

			return false;
		}

		for ($part = 1; $part <= $parts; $part++)
		{
			// Open file pointer
			$name = $filedata[$part]->name;
			$size = $filedata[$part]->size;
			$fullpath = $path . DIRECTORY_SEPARATOR . $name;
			$fp = @fopen($fullpath, 'wb');

			if ($fp == false)
			{
				$this->setState('error', 'Could not download backup; the inbox directory is unwritable');

				return false;
			}

			// Get the signed URL
			$url = $api->getURL() . '?' . $api->prepareQuery('downloadDirect', array('backup_id' => $id, 'part_id' => $part));

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_FAILONERROR, true);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
			//curl_setopt($ch, CURLOPT_TIMEOUT, 180);
			curl_setopt($ch, CURLOPT_FILE, $fp);
			// Pretend we are Firefox, so that webservers play nice with us
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Linux x86_64; rv:2.0.1) Gecko/20110506 Firefox/4.0.1');
			$status = curl_exec($ch);
			@fclose($fp);
			$errno = curl_errno($ch);
			$errmessage = curl_error($ch);
			curl_close($ch);

			if ($status === false)
			{
				$this->setState('error', "Could not download $fullpath -- $errno : $errmessage");

				return false;
			}

			// Check file size
			$sizematch = true;
			clearstatcache();
			$filesize = @filesize($fullpath);
			if ($filesize !== false)
			{
				if ($filesize != $size)
				{
					echo "!!! Filesize mismatch on $fullpath; your backup archove is corrupt";
					$sizematch = false;
				}
			}
		}

		UUtilLogger::WriteLog("Archive downloaded successfully");
	}

	/**
	 * Downloads a backup archive in chunked mode. WARNING: THIS DOES NOT WORK
	 * CORRECTLY.
	 */
	private function _downloadChunk($id)
	{
		$api = UUtilJsonapi::getInstance();
		$path = UConfig::inboxDir;
		$chunk_size = 1;

		// Get the backup info
		$data = $api->doQuery('getBackupInfo', array('backup_id' => $id));
		$parts = $data->body->data->multipart;
		$filedefs = $data->body->data->filenames;
		$filedata = array();
		$i = 0;
		foreach ($filedefs as $def)
		{
			$i++;
			if ($i == 1)
			{
				// Update the package name with the name of the downloaded backup archive
				$this->siteDef['siteInfo']['package'] = $def->name;
				$this->state['JobDefinitions'][$this->jobKey]['definition']['siteInfo']['package'] = $def->name;
			}
			$filedata[$def->part] = (object)array('name' => $def->name, 'size' => $def->size);
		}
		if ($parts <= 0)
		{
			$parts = 1;
		}

		if (!count($filedefs))
		{
			$this->setState('error', 'Could not download backup; no backup files found');

			return false;
		}

		for ($part = 1; $part <= $parts; $part++)
		{
			// Open file pointer
			$name = $filedata[$part]->name;
			$size = $filedata[$part]->size;
			$fullpath = $path . DIRECTORY_SEPARATOR . $name;
			$fp = @fopen($fullpath, 'wb');

			if ($fp == false)
			{
				$this->setState('error', 'Could not download backup; the inbox directory is unwritable');

				return false;
			}

			$frag = 0;
			$done = false;
			while (!$done)
			{
				$frag++;
				$data = $api->doQuery('download', array(
					'backup_id'  => $id,
					'part'       => $part,
					'segment'    => $frag,
					'chunk_size' => $chunk_size
				));

				switch ($data->body->status)
				{
					case 200:
						$rawData = base64_decode($data->body->data);
						$len = strlen($rawData); //echo "\tWriting $len bytes\n";
						fwrite($fp, $rawData);
						unset($rawData);
						unset($data);
						break;

					case 404:
						if ($frag == 1)
						{
							$this->setState('error', "Could not download $fullpath -- 404 : Not Found");

							return false;
						}
						else
						{
							$done = true;
						}
						break;

					default:
						$this->setState('error', "Could not download $fullpath -- {$data->body->status} : {$data->body->data}");

						return false;
				}
			}

			@fclose($fp);

			// Check file size
			$sizematch = true;
			clearstatcache();
			$filesize = @filesize($fullpath);
			if ($filesize !== false)
			{
				if ($filesize != $size)
				{
					RemoteUtilsRender::warning("Filesize mismatch on $fullpath");
					$sizematch = false;
				}
			}
		}

		UUtilLogger::WriteLog("Archive downloaded successfully");
	}

	/**
	 * Performs a backup download using cURL
	 */
	private function _downloadCurl($id)
	{
		$api = UUtilJsonapi::getInstance();
		$path = UConfig::inboxDir;

		$dlurl = array_key_exists('dlurl', $this->siteDef['remote']) ? $this->siteDef['remote']['dlurl'] : '';
		$dlurl = rtrim($dlurl, '/');

		$authentication = '';

		$doubleSlash = strpos($dlurl, '//');
		if ($doubleSlash != false)
		{
			$offset = $doubleSlash + 2;
			$atSign = strpos($dlurl, '@', $offset);
			$colon = strpos($dlurl, ':', $offset);
			if (($colon !== false) && ($atSign !== false))
			{
				$offset = $colon + 1;
				while ($atSign !== false)
				{
					$atSign = strpos($dlurl, '@', $offset);
					if ($atSign !== false)
					{
						$offset = $atSign + 1;
					}
				}
				$atSign = $offset - 1;

				$authentication = substr($dlurl, $doubleSlash + 2, $atSign - $doubleSlash - 2);
				$protocol = substr($dlurl, 0, $doubleSlash + 2);
				$restOfURL = substr($dlurl, $atSign + 1);
				$dlurl = $protocol . $restOfURL;
			}
		}

		// Get the backup info
		$data = $api->doQuery('getBackupInfo', array('backup_id' => $id));
		$parts = $data->body->data->multipart;
		$filedefs = $data->body->data->filenames;
		$filedata = array();
		$i = 0;
		foreach ($filedefs as $def)
		{
			$i++;
			if ($i == 1)
			{
				// Update the package name with the name of the downloaded backup archive
				$this->siteDef['siteInfo']['package'] = $def->name;
				$this->state['JobDefinitions'][$this->jobKey]['definition']['siteInfo']['package'] = $def->name;
			}
			$filedata[$def->part] = (object)array('name' => $def->name, 'size' => $def->size);
		}
		if ($parts <= 0)
		{
			$parts = 1;
		}

		if (!count($filedefs))
		{
			$this->setState('error', 'Could not download backup; no backup files found');

			return false;
		}

		for ($part = 1; $part <= $parts; $part++)
		{
			// Open file pointer
			$name = $filedata[$part]->name;
			$size = $filedata[$part]->size;
			$fullpath = $path . DIRECTORY_SEPARATOR . $name;
			$fp = @fopen($fullpath, 'wb');

			if ($fp == false)
			{
				$this->setState('error', 'Could not download backup; the inbox directory is unwritable');

				return false;
			}

			// Get the target path
			$url = $dlurl . '/' . $name;

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_FAILONERROR, true);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
			//curl_setopt($ch, CURLOPT_TIMEOUT, 180);
			curl_setopt($ch, CURLOPT_FILE, $fp);
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Linux x86_64; rv:2.0.1) Gecko/20110506 Firefox/4.0.1');
			if (!empty($authentication))
			{
				curl_setopt($ch, CURLOPT_USERPWD, $authentication);
			}
			$status = curl_exec($ch);
			@fclose($fp);
			$errno = curl_errno($ch);
			$errmessage = curl_error($ch);
			curl_close($ch);

			if ($status === false)
			{
				$this->setState('error', "Could not download $fullpath -- $errno : $errmessage");

				return false;
			}

			// Check file size
			$sizematch = true;
			clearstatcache();
			$filesize = @filesize($fullpath);
			if ($filesize !== false)
			{
				if ($filesize != $size)
				{
					UUtilLogger::WriteLog("Filesize mismatch on $fullpath; your archive file is corrupt.");
					$sizematch = false;
				}
			}
		}

		UUtilLogger::WriteLog("Archive downloaded successfully");
	}
}