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
 * Makes sure that we are being called from the command line,
 * not from the web
 */
class UStepClicheck extends UAbstractPart
{

	protected function _prepare()
	{
		$this->setState('prepared');
	}

	protected function _run()
	{
		if (array_key_exists('REQUEST_METHOD', $_SERVER))
		{
			die('You are not supposed to access this script from the web. You have to run it from the command line. If you don\'t understand what this means, you must not try to use this file before reading the documentation. Thank you.');
		}

		$this->setState('postrun');
	}

	protected function _finalize()
	{
		$this->setState('finished');
	}
}