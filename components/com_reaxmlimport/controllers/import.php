<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @package Component REAXML Import for Joomla! 3.4
 * @version 1.5.26: import.php 2016-08-15T02:12:57.600
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014, 2015, 2016 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 *
 **/
class ReaXmlImportControllersImport extends JControllerBase {
	public function execute() {
		$return = array (
				"success" => false 
		);
		
		$model = new ReaXmlImportModelsImport ();
		if (($runtag = $model->import ()) !== null) {
			$return ['success'] = true;
			$return ['runtag'] = $runtag;
			$return ['log'] = $model->getLatestLog ();
		}
		echo json_encode ( $return );
	}
}