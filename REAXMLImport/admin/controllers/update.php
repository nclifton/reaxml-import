<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @package Component REAXML Import for EZ Realty for Joomla! 3.3
 * @version 0.43.119: update.php 2014-09-12T13:54:30.355
 * @author Neil Clifton
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/
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