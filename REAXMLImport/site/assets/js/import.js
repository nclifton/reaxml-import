/**
 *
 * @package Component REAXML Import for Joomla! 3.4
 * @version 1.5.26: import.js 2016-08-15T02:12:57.600
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014, 2015, 2016 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 *
 **/

/**
 * request an import run through JQuery AJAX
 */
function requestImportRun(event) {
	jQuery('#importWidget').unbind('click').toggleClass('disabled');
	jQuery
			.ajax({
				url : 'index.php?option=com_reaxmlimport&controller=import&format=raw&tmpl=component',
				type : 'POST',
				data : '',
				dataType : 'JSON',
				success : function(data) {
					if (data.success) {
						jQuery('#logContent').html(data.log);
						jQuery('#importWidget').unbind('click');
					} else {
						jQuery('#logContent').html(data.error);
					}
					jQuery('#importWidget').bind('click', requestImportRun).toggleClass('disabled');;
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
	jQuery('#updateWidget').unbind('click').toggleClass('disabled');
	jQuery
			.ajax({
				url : 'index.php?option=com_reaxmlimport&controller=updatelog&format=raw&tmpl=component',
				type : 'POST',
				data : '',
				dataType : 'JSON',
				success : function(data) {
					if (data.success) {
						jQuery('#logContent').html(data.content);
					} else {
						jQuery('#logContent').html(data.error);
					}
					jQuery('#updateWidget').bind('click', updateLogDisplay).toggleClass('disabled');;
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
	jQuery('#importWidget').bind('click', requestImportRun);
	jQuery('#updateWidget').bind('click', updateLogDisplay);
})
