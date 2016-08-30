<?php


trait ReaxmlLanguageTrait
{
    /**
     * @before
     */
    public function loadLanguage(){
        $lang = JFactory::getLanguage();
        $lang->load('lib_reaxml', REAXML_LIBRARIES.'/reaxml' , 'en-GB', true);
        $lang->load('lib_reaxml', REAXML_ADMIN_COMPONENTS.'/com_reaxmlimport' , 'en-GB', true);
    }


}