<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @package Component REAXML Import for Joomla! 3.4
 * @version 1.3.122: html.php 2015-06-01T08:16:26.590
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014, 2015 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 *
 **/

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