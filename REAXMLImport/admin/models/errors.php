<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @package Component REAXML Import for Joomla! 3.4
 * @version 1.5.11: errors.php 2015-07-24T00:42:53.638
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014, 2015 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 *
 **/
class ReaXmlImportModelsErrors extends JModelBase {
	public function getErrorFiles() {
		$params = JComponentHelper::getParams ( 'com_reaxmlimport' );
		$dir = $params->get ( 'error_dir' );
		$files = array ();
		if (isset ( $dir ) && JFolder::exists ( $dir )) {
			$files = JFolder::files ( $dir );
		}
		return $files;
	}
	public function getErrorUrl() {
		$params = JComponentHelper::getParams ( 'com_reaxmlimport' );
		return $params->get ( 'error_url' ) . '';
	}
}