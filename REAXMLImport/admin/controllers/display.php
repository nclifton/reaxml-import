<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @package Component REAXML Import for EZ Realty for Joomla! 3.3
 * @version 0.43.119: display.php 2014-09-12T13:54:30.355
 * @author Neil Clifton
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/
class ReaXmlImportControllersDisplay extends JControllerBase {
	public function execute() {
		
		// Get the application
		$app = $this->getApplication ();
		
		// Get the document object.
		$document = JFactory::getDocument ();
		
		$viewName = $app->input->getWord ( 'view', 'display' );
		$viewFormat = $document->getType ();
		$layoutName = $app->input->getWord ( 'layout', 'default' );
		
		$app->input->set ( 'view', $viewName );
		
		// Register the layout paths for the view
		$paths = new SplPriorityQueue ();
		$paths->insert ( JPATH_COMPONENT . '/views/' . $viewName . '/tmpl', 'normal' );
		
		$viewClass = 'ReaXmlImportViews' . ucfirst ( $viewName ) . ucfirst ( $viewFormat );
		$modelClass = 'ReaXmlImportModels' . ucfirst ( $viewName );
		
		$view = new $viewClass ( new $modelClass (), $paths );
		
		$view->setLayout ( $layoutName );
		
		// Render our view.
		echo $view->render ();
		
		return true;
	}
}