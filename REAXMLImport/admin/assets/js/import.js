/**
 * request an import run through JQuery AJAX
 *   
 * Copyright (C) 2005 - 2014 Clifton IT Foundries Pty Ltd. All rights reserved.
 * GNU General Public License version 3 or later.
 */

function requestImportRun(event) {
	jQuery('#toolbar-download > button').unbind('click').toggleClass('disabled');
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
						jQuery('#logContent').html(data.error);
					}
					jQuery('#toolbar-download > button').bind('click',
							requestImportRun).toggleClass('disabled');
				},
				error : function(jqXHR, textStatus, errorThrown) {
					if (jqXHR.responseText.length > 0) {
						jQuery('#content').html(jqXHR.responseText);
					} else {
						jQuery('#content')
								.html(
										"<p><h1>Server Error</h1><p>Unexpected response, probably a server error.</p><p>Turn on Error Reporting in the Joomla Global Configuration and try again to see what's happening.</p><p>You may need to move the REAXML file or zip back into your input directory from your work directory first.</p>")
					}
					jQuery('#toolbar-refresh > button').unbind('click')
							.toggleClass('disabled');
				}
			});
}
/**
 * request a log update
 */
function updateLogDisplay(event) {
	jQuery('#toolbar-refresh > button').unbind('click').toggleClass('disabled');
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
						jQuery('#logContent').html(data.error);
					}
					jQuery('#toolbar-refresh > button').bind('click',
							updateLogDisplay).toggleClass('disabled');
				},
				error : function(jqXHR, textStatus, errorThrown) {
					if (jqXHR.responseText.length > 0) {
						jQuery('#content').html(jqXHR.responseText);
					} else {
						jQuery('#content')
								.html(
										"<p><h1>Server Error</h1><p>Unexpected response, probably a server error.</p><p>Turn on Error Reporting in the Joomla Global Configuration and try again to see what's happening.</p><p>You may need to move the REAXML file or zip back into your input directory from your work directory first.</p>")
					}
					jQuery('#toolbar-refresh > button').unbind('click')
							.toggleClass('disabled');
				}
			});

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
