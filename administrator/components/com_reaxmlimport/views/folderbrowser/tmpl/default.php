<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );
/**
 *
 * @package Component REAXML Import for Joomla! 3.4
 * @version 1.3.71: default.php 2015-05-31T02:28:53.911
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014, 2015 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html       
 *         
 */
 
$document = JFactory::getDocument();
$document->addScriptDeclaration('
function reaxml_folderbrowser_useThis(inputid,urlinputid) {
	parent.reaxml_folderbrowser_useThis(inputid,urlinputid);
}');

JHtml::stylesheet('com_reaxmlimport/fields.css',false,true);
JHtml::stylesheet('com_reaxmlimport/jquery.ui.theme.css',false,true);
JHtml::stylesheet('com_reaxmlimport/jquery-ui-1.9.2.custom.min.css',false,true);


?>
<div id="folderbrowser-container">
	<div class="folderbrowser-controls-container">
		<form id="adminForm" class="inline-form" name="adminForm" method="get" action="index.php">
			<input type="hidden" name="option" value="com_reaxmlimport" />
		 	<input type="hidden" name="view" value="folderbrowser" /> 
			<input type="hidden" name="tmpl" value="component" /> 
			<input type="hidden" name="controller" value="config" />
			<input type="hidden" name="inputid" value="<?php echo $this->getInputid()?>" />
			<input type="hidden" name="urlinputid" value="<?php echo $this->getUrlinputid()?>" />
			<input id="url" type="hidden" name="url" value="<?php echo $this->getUrl() ?>" />
			<input id="folder" name="folder" class="input-xlarge" type="text" value="<?php echo $this->getFolder()?>" /> 
			<button class="btn btn-primary" onclick="document.form.adminForm.submit(); return false;"><div class=" ui-icon ui-icon-arrowreturnthick-1-e">&nbsp;</div><div>Go</div></button> 
			<button class="btn btn-success" onclick="reaxml_folderbrowser_useThis('<?php echo $this->getInputid()?>','<?php echo $this->getUrlinputid()?>'); return false;"><div class=" ui-icon ui-icon-check">&nbsp;</div><div>Use</div></button> 
		</form>
	</div>
	<div class="folderbrowser-breadcrumb-container">
		<ul class="breadcrumb">
				<?php foreach ($this->getFolderList() as $key => $folder) { ?>
			<li class="<?php echo $key; ?>">
					<?php if (!strpos($key,'active')){ ?>
				<a href="<?php echo $this->getSelectFolderUrl( $key );?>"><?php echo $folder;?></a>
					<?php } else { 
				echo $folder;
						} ?>
			</li>
				<?php } ?>
		</ul>
	</div>
	<div class="folderbrowser-folders-container">
		<ul class="folders">
				<?php foreach ($this->getSubFolderList() as $key => $folder) { ?>
			<li class="<?php echo $key; ?>">
				<span class="ui-icon ui-icon-folder-collapsed" >&nbsp;</span><a href="<?php echo $this->getSelectSubFolderUrl( $key );?>"><?php echo $folder;?></a>
			</li>
				<?php } ?>
		</ul>
	</div>
</div>