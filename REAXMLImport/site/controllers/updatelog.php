<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

class ReaXmlImportControllersUpdatelog extends JControllerBase
{
	public function execute()
	{

		$return = array("success"=>false);

		$model = new ReaXmlImportModelsImport();
		if ( ($content = $model->getLatestLog()) !== null )
		{
			$return['success'] = true;
			$return['content'] = $content;

		}
		echo json_encode($return);
	}
}