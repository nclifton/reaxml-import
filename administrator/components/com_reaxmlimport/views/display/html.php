<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @package Component REAXML Import for Joomla! 3.4
 * @version 1.5.26: html.php 2016-08-15T02:12:57.600
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014, 2015, 2016 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 *
 **/

class ReaXmlImportViewsDisplayHtml extends JViewHtml {
	function render() {
		// retrieve task list from model
		$this->logFiles = $this->model->getLogFiles ();
		$this->logUrl = $this->model->getLogUrl ();
		$this->inputFiles = $this->model->getInputFiles ();
		$this->inputUrl = $this->model->getInputUrl ();
		$this->errorFiles = $this->model->getErrorFiles ();
		$this->errorUrl = $this->model->getErrorUrl ();
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