<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @package Component REAXML Import for EZ Realty for Joomla! 3.3
 * @version 0.43.119: inputs.php 2014-09-12T13:54:30.355
 * @author Neil Clifton
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