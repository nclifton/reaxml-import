<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @copyright Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license GNU General Public License version 2 or later; see LICENSE.txt
 *         
 */
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