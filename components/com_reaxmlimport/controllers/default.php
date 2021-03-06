<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @package Component REAXML Import for Joomla! 3.4
 * @version 1.5.26: default.php 2016-08-15T02:12:57.600
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014, 2015, 2016 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 *
 **/
class ReaXmlImportControllersDefault extends JControllerBase {
	public function execute() {
		// Get the application
		$app = $this->getApplication ();
		
		// Get the document object.
		$document = $app->getDocument ();
		
		$viewName = $app->input->getWord ( 'view', 'import' );
		$viewFormat = $document->getType ();
		$layoutName = $app->input->getWord ( 'layout', 'default' );
		
		$app->input->set ( 'view', $viewName );
		
		// Register the layout paths for the view
		$paths = new SplPriorityQueue ();
		$paths->insert ( JPATH_COMPONENT_SITE . '/views/' . $viewName . '/tmpl', 'normal' );
		
		$viewClass = 'ReaXmlImportViews' . ucfirst ( $viewName ) . ucfirst ( $viewFormat );
		$modelClass = 'ReaXmlImportModels' . ucfirst ( $viewName );
		
		if (false === class_exists ( $modelClass )) {
			$modelClass = 'ReaXmlImportModelsImport';
		}
		
		$view = new $viewClass ( new $modelClass (), $paths );
		$view->setLayout ( $layoutName );
		
		// Render our view.
		echo $view->render ();
		
		return true;
	}
}