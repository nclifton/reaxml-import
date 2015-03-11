<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @package Component REAXML Import for Joomla! 3.3
 * @version 0.43.127: html.php 2015-03-12T02:22:18.590
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/
class ReaXmlImportViewsImportHtml extends JViewHtml {
	function render() {
		
		// retrieve latest log from model
		$model = new ReaXmlImportModelsImport ();
		$this->latestLog = $model->getLatestLog ();
		
		// display
		return parent::render ();
	}
}
