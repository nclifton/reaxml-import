<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

class ReaXmlImportControllersImport extends JControllerBase
{
	public function execute()
	{

		$return = array("success"=>false);

		$model = new ReaXmlImportModelsImport();
		if ( ($runtag = $model->import()) !== null )
		{
			$return['success'] = true;
			$return['runtag'] = $runtag;
			$return['log'] = $model->getLatestLog();

		}
		echo json_encode($return);
	}
}