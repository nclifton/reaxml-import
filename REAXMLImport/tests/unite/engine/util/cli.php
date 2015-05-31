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
 * A class to handle command line options
 */
class UUtilCli
{
	private static $options = null;

	/**
	 * Parses POSIX command line options and populates an associative array. Each array item contains
	 * a single dimensional array of values. Arguments without a dash are silently ignored.
	 */
	private static function parseCLI()
	{
		global $argc, $argv;

		$len = sizeof($argv);
		$currentName = "";
		$options = array();

		for ($i = 1; $i < $argc; $i++)
		{
			$argument = $argv[$i];
			if (strpos($argument, "-") === 0)
			{
				$argument = ltrim($argument, '-');
				if (strstr($argument, '='))
				{
					list($name, $value) = explode('=', $argument, 2);
				}
				else
				{
					$name = $argument;
					$value = null;
				}
				$currentName = $name;
				if (!isset($options[$currentName]) || ($options[$currentName] == null))
				{
					$options[$currentName] = array();
				}
			}
			else
			{
				$value = $argument;
			}
			if ((!is_null($value)) && (!is_null($currentName)))
			{
				if (strstr($value, '='))
				{
					$parts = explode('=', $value, 2);
					$key = $parts[0];
					$value = $parts[1];
				}
				else
				{
					$key = null;
				}

				$values = $options[$currentName];
				if (is_null($key))
				{
					array_push($values, $value);
				}
				else
				{
					$values[$key] = $value;
				}
				$options[$currentName] = $values;
			}
		}

		if (empty($options))
		{
			$options = array();
		}

		self::$options = $options;
	}

	/**
	 * Fetches a command-line option
	 *
	 * @param $key             string The key parameter to fetch
	 * @param $default         The default value to return if the key is not defined
	 * @param $first_item_only bool True to fetch only the first value, false to fetch them all as an array
	 *
	 * @return mixed
	 */
	public static function getOption($key, $default = null, $first_item_only = true)
	{
		if (is_null(self::$options))
		{
			self::parseCLI();
		}

		if (!array_key_exists($key, self::$options))
		{
			return $default;
		}
		else
		{
			if ($first_item_only)
			{
				return self::$options[$key][0];
			}
			else
			{
				return self::$options[$key];
			}
		}
	}
}