<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @package Component REAXML Import for Joomla! 3.4
 * @version 1.2.19: display.php 2015-03-28T06:33:12.028
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/
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
