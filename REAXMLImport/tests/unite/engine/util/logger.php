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
 * A class to generate a run log for the whole UNITE's process (debug log)
 */
class UUtilLogger
{
	private static $fp;

	private static $localfp = false;

	private static $logName;

	/**
	 * Clears the logfile
	 */
	public static function ResetLog()
	{
		// Do nothing if global logging is off
		if (!UConfig::globalLogging)
		{
			return;
		}

		self::logName(); // Init log filename
		if (file_exists(self::$logName))
		{
			@unlink(self::$logName);
		}

		@touch(self::$logName);

		if (empty(self::$fp))
		{
			self::$fp = @fopen(self::$logName, "at");
		}
		if (self::$fp !== false)
		{
			@fwrite(self::$fp, '<?php die(); // Protect from prying eyes! ?>');
		}
	}

	/**
	 * Writes a line to the log
	 *
	 * @param string $message The message to write to the log
	 */
	public static function WriteLog($message, $isDebug = false)
	{
		// Echo log contents to the terminal, except for debug-level messages
		if (!$isDebug && UConfig::verboseOutput)
		{
			echo $message . "\n";
		}

		$message = trim($message, "\t\n");
		$message = str_replace("\n", ' \n ', $message);
		$string = strftime("%Y%m%d|%T") . "|$message\r\n";

		// Write to local log
		if (!(self::$localfp === false))
		{
			@fwrite(self::$localfp, $string);
		}

		// Do not write to global log file if global logging is off
		if (UConfig::globalLogging)
		{
			if (empty(self::$fp))
			{
				self::$fp = @fopen(self::$logName, "at");
			}

			if (!(self::$fp === false))
			{
				@fwrite(self::$fp, $string);
			}
		}
	}

	/**
	 * Calculates the absolute path to the log file
	 */
	public static function logName()
	{
		$logDir = UConfig::logDir;
		if (!is_dir($logDir))
		{
			$logDir = dirname(__FILE__) . '/../../' . $logDir;
		}
		if (!is_dir($logDir))
		{
			$logDir = dirname(__FILE__) . '/../../logs';
		}
		self::$logName = $logDir . "/unite.log.php";

		return self::$logName;
	}

	public static function setLocalLog($filename)
	{
		$logDir = UConfig::logDir;
		if (!is_dir($logDir))
		{
			$logDir = dirname(__FILE__) . '/../../' . $logDir;
		}
		if (!is_dir($logDir))
		{
			$logDir = dirname(__FILE__) . '/../../logs';
		}

		$filename = $logDir . '/' . $filename;

		if (substr($filename, -4) != '.php')
		{
			$filename .= '.php';
		}

		if (file_exists($filename))
		{
			@unlink($filename);
		}

		@touch($filename);

		self::$localfp = @fopen($filename, "at");
		if (self::$localfp !== false)
		{
			@fwrite(self::$localfp, '<?php die(); // Protect from prying eyes! ?>');
		}
	}

	public static function unsetLocalLog()
	{
		if (!empty(self::$localfp) && (self::$localfp !== false))
		{
			@fclose(self::$localfp);
		}

		self::$localfp = false;
	}

	function __destruct()
	{
		if (!empty(self::$fp) && (self::$fp !== false))
		{
			@fclose(self::$fp);
			self::$fp = false;
		}
	}
}