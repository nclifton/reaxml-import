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
 * Download a backup stored on Amazon S3
 */
class UStepS3 extends UAbstractPart
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

		if ($this->getError())
		{
			return;
		}

		$this->setState('running');

		$packageFrom = $this->siteDef['siteInfo']['packageFrom'];

		if ($packageFrom != 's3')
		{
			// The user didn't specify a remote backup; return
			$this->setState('postrun');

			return;
		}

		// Get the S3 connection parameters
		$options = array(
			'access'   => $this->siteDef['s3']['accesskey'],
			'secret'   => $this->siteDef['s3']['secretkey'],
			'bucket'   => $this->siteDef['s3']['bucket'],
			'filename' => $this->siteDef['s3']['filename'],
			'ssl'      => isset($this->siteDef['s3']['ssl']) ? $this->siteDef['s3']['ssl'] : false,
			'endpoint' => isset($this->siteDef['s3']['endpoint']) ? $this->siteDef['s3']['endpoint'] : null,
		);

		UUtilLogger::WriteLog("\t\tPreparing to download s3://" . $options['bucket'] . '/' . $options['filename']);

		// Create the S3 connection object
		$s3 = new UUtilAmazons3($options['access'], $options['secret'], $options['ssl']);

		// Apply a custom endpoint, if specified
		if (!empty($options['endpoint']))
		{
			$s3->defaultHost = $options['endpoint'];
		}

		// Scan S3 for files matching the filename given
		$filePrefix = substr($options['filename'], 0, -3);
		$allFiles = $s3->getBucket($options['bucket'], $filePrefix);
		$totalsize = 0;

		if (count($allFiles))
		{
			foreach ($allFiles as $name => $file)
			{
				$totalsize += $file['size'];
			}
		}

		$totalParts = count($allFiles);

		// Update the site definition
		$this->siteDef['siteInfo']['package'] = basename($options['filename']);
		$this->state['JobDefinitions'][$this->jobKey]['definition']['siteInfo']['package'] = basename($options['filename']);

		// Start the download
		$part = 0;
		$doneSize = 0;

		while ($part < $totalParts)
		{
			// Get the remote and local filenames
			$basename = substr(basename($options['filename']), 0, -4);
			$extension = strtolower(substr($options['filename'], -3));

			if ($part > 0)
			{
				$new_extension = substr($extension, 0, 1) . sprintf('%02u', $part);
			}
			else
			{
				$new_extension = $extension;
			}

			$filename = $basename . '.' . $new_extension;
			UUtilLogger::WriteLog("\t\t\tDownloading part #$part -- $filename");

			$remote_filename = substr($options['filename'], 0, -3) . $new_extension;

			// Figure out where on Earth to put that file
			$local_file = UConfig::inboxDir . '/' . basename($remote_filename);

			// Reset the local file
			@unlink($local_file);
			$fp = @fopen($local_file, 'wb');

			if ($fp !== false)
			{
				@fclose($fp);
			}

			$result = $s3->getObject($options['bucket'], $remote_filename, $local_file);

			if (!$result)
			{
				UUtilLogger::WriteLog("\t\tDownload from S3 failed");
				$this->setState('error', $s3->getError());
				$this->setState('postrun');

				return false;
			}
			else
			{
				// Get the size of the file
				clearstatcache();
				$filesize = (int)@filesize($local_file);
				$doneSize += $filesize;

				$unit = array('b', 'Kb', 'Mb', 'Gb', 'Tb', 'Pb');
				$msg = @round($filesize / pow(1024, ($i = floor(log($filesize, 1024)))), 2) . ' ' . $unit[$i];

				UUtilLogger::WriteLog("\t\t\tSuccessful download of $filename [$msg]");
			}

			$part++;
		}

		$unit = array('b', 'Kb', 'Mb', 'Gb', 'Tb', 'Pb');
		$msg = @round($totalsize / pow(1024, ($i = floor(log($totalsize, 1024)))), 2) . ' ' . $unit[$i];

		UUtilLogger::WriteLog("\t\tDownload from S3 complete. Total downloaded size: $msg");

		$this->setState('postrun');
	}

	protected function _finalize()
	{
		$this->setState('finished');
	}
}