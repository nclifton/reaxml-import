<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @package Component REAXML Import for Joomla! 3.4
 * @version 1.2.19: default.php 2015-03-28T06:33:12.028
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/

?>
<h1>
	<img
		src="<?php
		defined ( '_JEXEC' ) or die ( 'Restricted access' );
		echo JUri::base () . 'components/com_reaxmlimport/assets/images/Other-xml-icon.png';
		?>" />
	REAXML Import
</h1>
<a class="btn icon-upload" id="importWidget" href="#"> Run Import</a>
<a class="btn icon-repeat" id="updateWidget" href="#"> Update Log</a>
<pre id="logContent"><?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );
echo $this->latestLog;
?></pre>

<div id="error"></div>
