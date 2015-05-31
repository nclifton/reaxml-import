<?php
/**
 * UNITE
 * The automated site restoration system
 *
 * @copyright Copyright (c)2009-2014 Nicholas K. Dionysopoulos
 * @license   GNU GPL version 3 or, at your option, any later version
 * @package   unite
 */
// ensure this file is being included by a parent file

defined('UNITE') or die();

/**
 * Scans a directory for files of a sepcific extension
 */
class UUtilFtp extends UAbstractObject
{
	private $error;

	private $ftphost;

	private $ftpport;

	private $ftpuser;

	private $ftppass;

	private $ftpdir;

	private $_handle;

	/**
	 * Class constructor
	 *
	 * @param $host string The FTP hostname
	 * @param $port int The FTP port number
	 * @param $user string The FTP user name
	 * @param $pass string The FTP password
	 * @param $dir  string The FTP initial directory
	 *
	 * @return UUtilFtp
	 */
	public function __construct($host, $port, $user, $pass, $dir)
	{
		$this->ftphost = $host;
		$this->ftpport = $port;
		$this->ftpuser = $user;
		$this->ftppass = $pass;
		if (substr($dir, 0, 1) != '/')
		{
			$dir = '/' . $dir;
		}
		$this->ftpdir = $dir;
	}

	/**
	 * Destructor. Tries to disconnect from the FTP server.
	 */
	public function __destruct()
	{
		$this->disconnect();
	}

	/**
	 * Singleton implementation
	 *
	 * @param $host string The FTP hostname. If it's non empty, it forcibly creates a new instance
	 * @param $port int The FTP port number
	 * @param $user string The FTP user name
	 * @param $pass string The FTP password
	 * @param $dir  string The FTP initial directory
	 *
	 * @return UUtilFtp
	 */
	public static function &getInstance($host = '', $port = '', $user = '', $pass = '', $dir = '')
	{
		static $instance;

		// Forcibly create new instance if host parameter exists
		if (!empty($host))
		{
			if (is_object($instance))
			{
				$instance->disconnect();
			}
			$instance = null;
		}

		// Create a new instance if it doesn't exist
		if (!is_object($instance) || (!empty($host)))
		{
			UUtilLogger::WriteLog('New FTP object', true);
			$instance = new UUtilFtp($host, $port, $user, $pass, $dir);
		}

		return $instance;
	}

	/**
	 * Returns the last error message
	 *
	 * @return string The error message
	 */
	public function getError()
	{
		return $this->error;
	}

	/**
	 * Tries to connect to the FTP server
	 *
	 * @return bool True on success
	 */
	public function connect()
	{
		// Connect to server
		$this->_handle = @ftp_connect($this->ftphost, $his->ftpport);
		if ($this->_handle === false)
		{
			$this->setError('Wrong FTP host');

			return false;
		}

		// Login
		if (!@ftp_login($this->_handle, $this->ftpuser, $this->ftppass))
		{
			$this->setError('Wrong FTP username and/or password');
			@ftp_close($this->_handle);

			return false;
		}

		// Change to initial directory
		if (!@ftp_chdir($this->_handle, $this->ftpdir))
		{
			$this->setError('Wrong FTP initial directory');
			@ftp_close($this->_handle);

			return false;
		}

		// Use passive mode
		@ftp_pasv($this->_handle, true);

		return true;
	}

	/**
	 * Disconnects from the FTP server
	 */
	public function disconnect()
	{
		@ftp_close($this->_handle);
	}

	/**
	 * Returns true if the given FTP directory exists
	 *
	 * @param $dir string The directory to check for
	 *
	 * @return bool True if the directory exists
	 */
	public function is_dir($dir)
	{
		return @ftp_chdir($this->_handle, $dir);
	}

	/**
	 * Recursively creates an FTP directory if it doesn't exist
	 *
	 * @param $dir The directory to create
	 *
	 * @return bool True on success, false if creation failed
	 */
	public function makeDirectory($dir)
	{
		$check = '/' . trim($this->ftpdir, '/') . '/' . $dir;
		if ($this->is_dir($check))
		{
			return true;
		}

		$alldirs = explode('/', $dir);
		$previousDir = '/' . trim($this->ftpdir);
		foreach ($alldirs as $curdir)
		{
			$check = $previousDir . '/' . $curdir;
			if (!$this->is_dir($check))
			{
				if (@ftp_mkdir($this->_handle, $check) === false)
				{
					$this->setError('Could not create FTP directory ' . $dir);

					return false;
				}
				@ftp_chmod($this->_handle, 0755, $check);
			}
			$previousDir = $check;
		}

		return true;
	}

	/**
	 * Uploads a local file to the FTP server under a different name and removes the original
	 *
	 * @param $remoteName string The file path to the remote file, relative to FTP root
	 * @param $localName  string The absolute path to the local file to be uploaded and then removed
	 *
	 * @return bool True on success, false if upload failed
	 */
	public function uploadAndDelete($remoteName, $localName)
	{
		$remoteName = '/' . trim($this->ftpdir, '/') . '/' . $remoteName;
		$ret = @ftp_put($this->_handle, $remoteName, $localName, FTP_BINARY);
		@unlink($localName);
		if (!$ret)
		{
			$this->setError('Could not upload file ' . $remoteName);
		}
		else
		{
			@ftp_chmod($this->_handle, 0755, $remoteName);
		}

		return $ret;
	}

	public function downloadToTemp($remoteName, &$localName)
	{
		$localName = tempnam(UConfig::tempDir, 'usd');
		$ret = @ftp_get($this->_handle, $localName, $remoteName, FTP_BINARY);

		if (!$ret)
		{
			$this->setError('Could not download file ' . $remoteName);
		}

		@unlink($localName);

		return $ret;
	}

	public function changeToInitialDirectory()
	{
		@ftp_chdir($this->_handle, $this->ftpdir);
	}

	public function recursiveDelete($directory)
	{
		# here we attempt to delete the file/directory
		UUtilLogger::WriteLog('  Trying to remove ' . $directory, true);
		@ftp_chdir($this->_handle, $this->ftpdir . '/' . $directory);
		if (!(@ftp_rmdir($this->_handle, $directory) || @ftp_delete($this->_handle, $directory)))
		{
			UUtilLogger::WriteLog('  Can\'t remove (this is OK); getting list of contents', true);
			UUtilLogger::WriteLog('    CWD=' . ftp_pwd($this->_handle), true);
			# if the attempt to delete fails, get the file listing
			$filelist = @ftp_rawlist($this->_handle, $this->ftpdir . '/' . $directory);

			# loop through the file list and recursively delete the FILE in the list
			foreach ($filelist as $current)
			{
				if (empty($current))
				{
					continue;
				}
				$split = preg_split('[ ]', $current, 9, PREG_SPLIT_NO_EMPTY);
				$file = $this->ftpdir . '/' . $directory . '/' . $split[8];
				$isdir = ($split[0]{0} === 'd') ? true : false;

				if ($isdir)
				{
					$this->recursiveDelete($file);
				}
				else
				{
					@ftp_chdir($this->_handle, $this->ftpdir . '/' . $directory);
					@ftp_delete($this->_handle, $file);
				}
			}

			#if the file list is empty, delete the DIRECTORY we passed
			UUtilLogger::WriteLog('  Retrying to remove ' . $directory, true);
			@ftp_chdir($this->_handle, $this->ftpdir . '/' . $directory);
			if (!(@ftp_rmdir($this->_handle, $directory)))
			{
				@ftp_delete($this->_handle, $directory);
			}
		}
	}

	public function delete($remoteName)
	{
		return @ftp_delete($this->_handle, $remoteName);
	}
}