<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @package Component REAXML Import for Joomla! 3.4
 * @version 1.3.122: config.php 2015-06-01T08:16:26.590
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014, 2015 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 *
 **/
class ReaXmlImportControllersConfig extends JControllerBase {
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

		$model = new $modelClass ();
		if (strtolower($viewName) == 'folderbrowser'){
			$model->setInputid($app->input->getWord('inputid',''));
			$model->setUrlinputid($app->input->getWord('urlinputid',''));
			$model->setFolder(urldecode($app->input->getPath('folder','')));
		}
		
		$view = new $viewClass ( $model, $paths );
		$view->setLayout ( $layoutName );
		
		// Render our view.
		echo $view->render ();
		
		return true;
	}
}