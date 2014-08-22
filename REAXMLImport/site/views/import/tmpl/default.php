<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @copyright Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license GNU General Public License version 2 or later; see LICENSE.txt
 *         
 */

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
