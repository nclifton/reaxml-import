<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

class ReaXmlImportHelpersImport
{
	static function load()
	{
		$document = JFactory::getDocument();

		//stylesheets
		$document->addStylesheet(JUri::base().'components/com_reaxmlimport/assets/css/import.css');

		//javascripts
		$document->addScript(JUri::base().'components/com_reaxmlimport/assets/js/import.js');
	}
}