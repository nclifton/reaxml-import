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
 * A class to generate Stealth Mode .htaccess files
 */
class UUtilStealth
{

	/**
	 * Returns the IP of the server (or, at least, tries to)
	 *
	 * @return string The server's IP address
	 */
	public static function getMyIP()
	{
		$hostname = '';
		if (function_exists('gethostname'))
		{
			$hostname = gethostname();
		}
		if (empty($hostname))
		{
			$hostname = php_uname('n');
		}

		$ip = gethostbyname($hostname);

		// Uh... couldn't resolve the hostname to an IP address. Let's try the hard way on *NIX hosts.
		if (($ip == $hostname) && (substr(strtoupper(PHP_OS), 0, 2) != 'WIN'))
		{
			preg_match_all('/inet addr: ?([^ ]+)/', `ifconfig`, $ips);
			if (!empty($ips))
			{
				foreach ($ips as $item)
				{
					if ($item != '127.0.0.1')
					{
						$ip = $item;
						break;
					}
				}
			}
		}

		if (empty($ip) || ($ip == $hostname))
		{
			$ip = '127.0.0.1';
		}

		return $ip;
	}

	public static function makeStealthHtaccess($htaccess_filename)
	{
		$ip = self::getMyIP();
		$contents = <<<ENDHTACCESS
Order Deny,Allow
Deny From All
Allow From $ip 127.0.0.1 localhost
ENDHTACCESS;
		file_put_contents($htaccess_filename, $contents);
	}
}