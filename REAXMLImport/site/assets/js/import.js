/**
 * request an import run through JQuery AJAX
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
						jQuery('#importWidget').unbind('click');
					} else {

					}
					jQuery('#importWidget').bind('click', requestImportRun).toggleClass('disabled');;
				}
			});
	jQuery('#importWidget').bind('click', requestImportRun).toggleClass('disabled');
}
/**
 * request a log update
 */
function updateLogDisplay(event) {

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

					}
					jQuery('#updateWidget').bind('click', updateLogDisplay).toggleClass('disabled');;
				}
			});
	jQuery('#updateWidget').unbind('click', updateLogDisplay).toggleClass('disabled');;
}
/**
 * bind some JQuery functions to some elements in the page
 */
jQuery(document).ready(function() {
	jQuery('#importWidget').bind('click', requestImportRun);
	jQuery('#updateWidget').bind('click', updateLogDisplay);
})
