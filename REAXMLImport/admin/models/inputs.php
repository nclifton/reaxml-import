<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @package Component REAXML Import for Joomla! 3.3
 * @version 0.43.129: inputs.php 2015-03-12T03:46:25.454
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/
class ReaXmlImportModelsInputs extends JModelBase {
	public function getInputFiles() {
		$params = JComponentHelper::getParams ( 'com_reaxmlimport' );
		$inputDir = $params->get ( 'input_dir' );
		
		$inputFiles = array ();
		if (isset ( $inputDir ) && JFolder::exists ( $inputDir )) {
			$inputFiles = JFolder::files ( $inputDir );
		}
		
		return $inputFiles;
	}
	public function getInputRelUrl() {
		$params = JComponentHelper::getParams ( 'com_reaxmlimport' );
		return $params->get ( 'input_rel_url' ) . '';
	}
}