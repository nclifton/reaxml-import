<?php
// No direct access to this file
defined ( '_JEXEC' ) or die ( 'Restricted access' );

?>
<h1><img src="<?php echo JUri::base().'components/com_reaxmlimport/assets/images/Other-xml-icon.png';  ?>" /> REAXML Import</h1>
<a class="btn icon-upload" id="importWidget" href="#"> Run Import</a>
<a class="btn icon-repeat" id="updateWidget" href="#"> Update Log</a>
<pre id="logContent"><?php echo $this->latestLog; ?></pre>

<div id="error"></div>
