<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );
/**
 *
 * @package Component REAXML Import for Joomla! 3.4
 * @version 1.4.3: default.php 2015-06-10T01:14:12.284
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014, 2015 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 *
 **/
?>
<div id="reaxmlimportAdminContainer">

	<div id="accordion" class="filelistouter">
		<div class='filelistlabel'><?php
		defined ( '_JEXEC' ) or die ( 'Restricted access' );
		echo JText::_ ( 'COM_REAXMLIMPORT_VIEW_DISPLAY_LABEL_INPUTFILES' );
		?></div>
		<div id="inputFileList" class="filelistinner">
			<?php
			defined ( '_JEXEC' ) or die ( 'Restricted access' );
			foreach ( $this->inputFiles as $file ) :
				?>
			<a
				href="<?php
				defined ( '_JEXEC' ) or die ( 'Restricted access' );
				echo $this->inputUrl . '/' . $file;
				?>"><?php
				defined ( '_JEXEC' ) or die ( 'Restricted access' );
				echo $file;
				?></a><br />
			<?php
				defined ( '_JEXEC' ) or die ( 'Restricted access' );
			endforeach
			;
			?>
		</div>
		<div class='filelistlabel'><?php
		defined ( '_JEXEC' ) or die ( 'Restricted access' );
		echo JText::_ ( 'COM_REAXMLIMPORT_VIEW_DISPLAY_LABEL_ERRORFILES' );
		?></div>
		<div id="errorFileList" class="filelistinner">
			<?php
			defined ( '_JEXEC' ) or die ( 'Restricted access' );
			foreach ( $this->errorFiles as $file ) :
				?>
			<a
				href="<?php
				defined ( '_JEXEC' ) or die ( 'Restricted access' );
				echo $this->errorUrl . '/' . $file;
				?>"><?php
				defined ( '_JEXEC' ) or die ( 'Restricted access' );
				echo $file;
				?></a><br />
			<?php
				defined ( '_JEXEC' ) or die ( 'Restricted access' );
			endforeach
			;
			?>
		</div>
		<div class='filelistlabel'><?php
		defined ( '_JEXEC' ) or die ( 'Restricted access' );
		echo JText::_ ( 'COM_REAXMLIMPORT_VIEW_DISPLAY_LABEL_LOGFILES' );
		?></div>
		<div id="logFileList" class="filelistinner">
			<?php
			defined ( '_JEXEC' ) or die ( 'Restricted access' );
			foreach ( $this->logFiles as $file ) :
				?>
			<a
				href="<?php
				defined ( '_JEXEC' ) or die ( 'Restricted access' );
				echo $this->logUrl . '/' . $file;
				?>"><?php
				defined ( '_JEXEC' ) or die ( 'Restricted access' );
				echo $file;
				?></a><br />
			<?php
				defined ( '_JEXEC' ) or die ( 'Restricted access' );
			endforeach
			;
			?>
		</div>
	</div>
	<div class="logcontentouter">
		<div class='logcontentlabel'><?php
		defined ( '_JEXEC' ) or die ( 'Restricted access' );
		echo JText::_ ( 'COM_REAXMLIMPORT_VIEW_DISPLAY_LABEL_LOG' );
		?></div>
		<div id="logContent" class="logcontentinner"><?php
		defined ( '_JEXEC' ) or die ( 'Restricted access' );
		echo $this->latestLog;
		?></div>
	</div>
</div>