<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @copyright Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license GNU General Public License version 2 or later; see LICENSE.txt
 *         
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );
class ReaXmlImportViewsDisplayHtml extends JViewHtml {
	function render() {
		// retrieve task list from model
		$this->logFiles = $this->model->getLogFiles ();
		$this->logRelUrl = $this->model->getLogRelUrl ();
		$this->inputFiles = $this->model->getInputFiles ();
		$this->inputRelUrl = $this->model->getInputRelUrl ();
		$this->errorFiles = $this->model->getErrorFiles ();
		$this->errorRelUrl = $this->model->getErrorRelUrl ();
		$this->latestLog = $this->model->getLatestLog ();
		
		$this->addToolbar ();
		
		// display
		return parent::render ();
	}
	
	/**
	 * Add the page title and toolbar.
	 *
	 * @since 1.6
	 */
	protected function addToolbar() {
		
		// Get the toolbar object instance
		JToolbar::getInstance ( 'toolbar' );
		
		JToolbarHelper::title ( JText::_ ( 'COM_REAXMLIMPORT_VIEW_DEFAULT_TITLE' ), 'download' );
		
		$canDo = ReaXmlImportHelpersAdmin::getActions ();
		if ($canDo->get ( 'core.admin' )) {
			JToolbarHelper::preferences ( 'com_reaxmlimport' );
		}
		JToolbarHelper::custom ( 'import', 'download', 'download', 'Import', false );
		JToolbarHelper::custom ( 'refresh', 'refresh', 'refresh', 'Refresh', false );
	}
}