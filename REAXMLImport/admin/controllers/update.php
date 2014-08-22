<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @copyright Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license GNU General Public License version 2 or later; see LICENSE.txt
 *         
 */
class ReaXmlImportControllersUpdate extends JControllerBase {
	public function execute() {
		$return = array (
				"success" => false 
		);
		$model = new ReaXmlImportModelsDisplay ();
		if (($content = $model->getLatestLog ()) !== null) {
			$return ['success'] = true;
			$return ['log'] = $content;
			$return ['logfilesHtml'] = ReaXmlImportHelpersAdmin::getLogFilesHtml ( $model );
			$return ['inputfilesHtml'] = ReaXmlImportHelpersAdmin::getInputFilesHtml ( $model );
			$return ['errorfilesHtml'] = ReaXmlImportHelpersAdmin::getErrorFilesHtml ( $model );
		}
		echo json_encode ( $return );
	}
}