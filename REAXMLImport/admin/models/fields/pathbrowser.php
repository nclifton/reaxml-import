<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('text');

// The class name must always be the same as the filename (in camel case)
class JFormFieldPathbrowser extends JFormFieldText {

	//The field class must know its own type through the variable $type.
	protected $type = 'Pathbrowser';



	public function getInput() {
		$document = JFactory::getDocument();
		$document->addScript('/administrator/components/com_reaxmlimport/assets/js/fields.js');
		$document->addStyleSheet('/administrator/components/com_reaxmlimport/assets/css/fields.css');
		
		// html for the directory browser button
		$button = '<button class="btn path-browser-button" title="Browse..."><i class="icon-folder-open"></i></button>';
		
		// code that returns HTML that will be shown as the form field
		return parent::getInput().$button;
	}
}