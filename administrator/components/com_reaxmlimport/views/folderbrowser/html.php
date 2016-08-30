<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @package Component REAXML Import for Joomla! 3.4
 * @version 1.3.92: html.php 2015-05-31T18:50:09.607
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014, 2015 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 *         
 *         
 */
class ReaXmlImportViewsFolderbrowserHtml extends JViewHtml {
	function render() {
		// display
		return parent::render ();
	}
	public function __call($method, $args) {
		if (method_exists ( $this->model, $method )) {
			switch (count ( $args )) {
				case 0 :
					return $this->model->$method ();
					break;
				case 1 :
					return $this->model->$method ( $args [0] );
					break;
				case 2 :
					return $this->model->$method ( $args [0], $args [1] );
					break;
				case 3 :
					return $this->model->$method ( $args [0], $args [1], $args [2] );
					break;
			}
		}
		return '';
	}
}