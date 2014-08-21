<div id="reaxmlimportAdminContainer">

	<div id="accordion" class="filelistouter">
		<div class='filelistlabel'><?php echo JText::_('COM_REAXMLIMPORT_VIEW_DISPLAY_LABEL_INPUTFILES');?></div>
		<div id="inputFileList" class="filelistinner">
			<?php foreach ($this->inputFiles as $file):?>
			<a href="<?php echo $this->inputRelUrl.'/'.$file;  ?>"><?php echo $file; ?></a><br />
			<?php endforeach; ?>
		</div>
		<div class='filelistlabel'><?php echo JText::_('COM_REAXMLIMPORT_VIEW_DISPLAY_LABEL_ERRORFILES');?></div>
		<div id="errorFileList" class="filelistinner">
			<?php foreach ($this->errorFiles as $file):?>
			<a href="<?php echo $this->errorRelUrl.'/'.$file;  ?>"><?php echo $file; ?></a><br />
			<?php endforeach; ?>
		</div>
		<div class='filelistlabel'><?php echo JText::_('COM_REAXMLIMPORT_VIEW_DISPLAY_LABEL_LOGFILES');?></div>
		<div id="logFileList" class="filelistinner">
			<?php foreach ($this->logFiles as $file):?>
			<a href="<?php echo $this->logRelUrl.'/'.$file;  ?>"><?php echo $file; ?></a><br />
			<?php endforeach; ?>
		</div>
	</div>
	<div class="logcontentouter">
		<div class='logcontentlabel'><?php echo JText::_('COM_REAXMLIMPORT_VIEW_DISPLAY_LABEL_LOG');?></div>
		<div id="logContent" class="logcontentinner"><?php echo $this->latestLog; ?></div>
	</div>
</div>