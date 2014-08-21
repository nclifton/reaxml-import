<?php defined( '_JEXEC' ) or die( 'Restricted access' ); 
 
class ReaXmlImportViewsImportHtml extends JViewHtml
{
  function render()
  {
    
    //retrieve latest log from model
    $model = new ReaXmlImportModelsImport();
    $this->latestLog = $model->getLatestLog();
    
    //display
    return parent::render();
  } 
}
