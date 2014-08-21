<?php
/**
 * @copyright   Copyright (C) 2014 Clifton IT Foundries Pty Ltd. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

defined('_JEXEC') or die;

define('REAXML_LIBRARIES',     JPATH_ROOT . '/libraries');
JLoader::registerPrefix('Reaxml', REAXML_LIBRARIES . '/reaxml');


/**
 * Reaxml plugin class.
 *
 * @package     Joomla.plugin
 * @subpackage  System.reaxml
 */
class plgSystemMylib extends JPlugin
{
	/**
	 * Method to register custom library.
	 *
	 * return  void
	 */
	public function onAfterInitialise()
	{
		JLoader::registerPrefix('Reaxml', JPATH_LIBRARIES . '/reaxml');
	}
}