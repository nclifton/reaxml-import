<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @copyright Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license GNU General Public License version 2 or later; see LICENSE.txt
 *         
 */
class ReaXmlImportModelsDisplay extends JModelBase {
	private $logsModel;
	private $inputsModel;
	private $errorsModel;
	private $importModel;
	function __construct($state = null) {
		parent::__construct ( $state );
		$this->logsModel = new ReaXmlImportModelsLogs ();
		$this->inputsModel = new ReaXmlImportModelsInputs ();
		$this->errorsModel = new ReaXmlImportModelsErrors ();
		$this->importModel = new ReaXmlImportModelsImport ();
	}
	public function __call($method, $args) {
		if (method_exists ( $this->logsModel, $method )) {
			return $this->logsModel->$method ( $args );
		} else if (method_exists ( $this->inputsModel, $method )) {
			return $this->inputsModel->$method ( $args );
		} else if (method_exists ( $this->errorsModel, $method )) {
			return $this->errorsModel->$method ( $args );
		} else if (method_exists ( $this->importModel, $method )) {
			return $this->importModel->$method ( $args );
		}
	}
}