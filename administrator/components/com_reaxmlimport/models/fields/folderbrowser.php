<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('text');


// The class name must always be the same as the filename (in camel case)
class JFormFieldFolderbrowser extends JFormFieldText {

	//The field class must know its own type through the variable $type.
	protected $type = 'Folderbrowser';

	public function getInput() {
	    JHtml::stylesheet('com_reaxmlimport/jquery-ui-1.9.2.custom.css',false,true);
        JHtml::stylesheet('com_reaxmlimport/jquery.ui.theme.css',false,true);
        JHtml::stylesheet('com_reaxmlimport/fields.css',false,true);
        JHtml::script('com_reaxmlimport/jquery-ui-1.9.2.custom.min.js',false,true);
        JHtml::script('com_reaxmlimport/fields.js',false,true);

        $document = JFactory::getDocument();
        $document->setMetaData('root',JUri::root(true));
		
		// html for the directory browser button
		$button = '<button class="btn folder-browser-button" title="Browse..." inputid="'.$this->id.'" urlinputid="jform_'.$this->getAttribute('urlinputid').'"><i class="icon-folder-open"></i></button>';
		
		// code that returns HTML that will be shown as the form field
		return parent::getInput().$button;
	}
}