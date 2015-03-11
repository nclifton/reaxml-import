<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @package Component REAXML Import for Joomla! 3.3
 * @version 0.43.123: updatelog.php 2015-03-11T22:17:34.616
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/
class ReaXmlImportControllersUpdatelog extends JControllerBase {
	public function execute() {
		$return = array (
				"success" => false 
		);
		
		$model = new ReaXmlImportModelsImport ();
		if (($content = $model->getLatestLog ()) !== null) {
			$return ['success'] = true;
			$return ['content'] = $content;
		}
		echo json_encode ( $return );
	}
}