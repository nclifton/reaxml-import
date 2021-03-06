<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @package Component REAXML Import for Joomla! 3.4
 * @version 1.5.26: updatelog.php 2016-08-15T02:12:57.600
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014, 2015, 2016 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 *
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