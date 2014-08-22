/**
 * request an import run through JQuery AJAX
 *   
 * Copyright (C) 2005 - 2014 Clifton IT Foundries Pty Ltd. All rights reserved.
 * GNU General Public License version 3 or later.
 */

function requestImportRun(event) {

	jQuery
			.ajax({
				url : 'index.php?option=com_reaxmlimport&controller=import&format=raw&tmpl=component',
				type : 'POST',
				data : '',
				dataType : 'JSON',
				success : function(data) {
					if (data.success) {
						jQuery('#logContent').html(data.log);
						jQuery('#inputFileList').html(data.inputfilesHtml);
						jQuery('#errorFileList').html(data.errorfilesHtml);
						jQuery('#logFileList').html(data.logfilesHtml);
					} else {

					}
					jQuery('#toolbar-download > button').bind('click',
							requestImportRun).toggleClass('disabled');
					jQuery('#toolbar-refresh > button').bind('click',
							updateLogDisplay).toggleClass('disabled');
				}
			});
	jQuery('#toolbar-download > button').bind('click', requestImportRun)
			.toggleClass('disabled');
	jQuery('#toolbar-refresh > button').unbind('click', updateLogDisplay)
			.toggleClass('disabled');
}
/**
 * request a log update
 */
function updateLogDisplay(event) {

	jQuery
			.ajax({
				url : 'index.php?option=com_reaxmlimport&controller=update&format=raw&tmpl=component',
				type : 'POST',
				data : '',
				dataType : 'JSON',
				success : function(data) {
					if (data.success) {
						jQuery('#logContent').html(data.log);
						jQuery('#inputFileList').html(data.inputfilesHtml);
						jQuery('#errorFileList').html(data.errorfilesHtml);
						jQuery('#logFileList').html(data.logfilesHtml);
					} else {

					}
					jQuery('#toolbar-download > button').bind('click',
							requestImportRun).toggleClass('disabled');
					jQuery('#toolbar-refresh > button').bind('click',
							updateLogDisplay).toggleClass('disabled');
				}
			});
	jQuery('#toolbar-refresh > button').unbind('click', updateLogDisplay)
			.toggleClass('disabled');
	jQuery('#toolbar-download > button').bind('click', requestImportRun)
			.toggleClass('disabled');

}
/**
 * bind some JQuery functions to some elements in the page
 */
jQuery(document).ready(function() {
	jQuery('#toolbar-download > button').bind('click', requestImportRun);
	jQuery('#toolbar-refresh > button').bind('click', updateLogDisplay);
	jQuery('#accordion').accordion({
		collapsible : true,
		active : 0,
		animate : 200,
		heightStyle : "content"
	});
})
