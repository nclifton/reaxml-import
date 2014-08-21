<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );
class ReaXmlImportControllersImport extends JControllerBase {
	public function execute() {
		$return = array (
				"success" => false 
		);
		$model = new ReaXmlImportModelsDisplay ();
		if (($runtag = $model->import ()) !== null) {
			$return ['success'] = true;
			$return ['runtag'] = $runtag;
			$return ['log'] = $model->getLatestLog ();
			$return ['logfilesHtml'] = ReaXmlImportHelpersAdmin::getLogFilesHtml ( $model );
			$return ['inputfilesHtml'] = ReaXmlImportHelpersAdmin::getInputFilesHtml ( $model );
			$return ['errorfilesHtml'] = ReaXmlImportHelpersAdmin::getErrorFilesHtml ( $model );
		}
		echo json_encode ( $return );
	}
}