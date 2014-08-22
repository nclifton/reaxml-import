<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @copyright Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license GNU General Public License version 2 or later; see LICENSE.txt
 *         
 */
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