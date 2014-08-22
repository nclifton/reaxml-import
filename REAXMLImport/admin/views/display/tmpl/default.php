<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );
/**
 *
 * @copyright Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license GNU General Public License version 2 or later; see LICENSE.txt
 *         
 */
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
				echo $this->inputRelUrl . '/' . $file;
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
				echo $this->errorRelUrl . '/' . $file;
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
				echo $this->logRelUrl . '/' . $file;
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