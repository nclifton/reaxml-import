/**
 *
 * @package Component REAXML Import for Joomla! 3.4
 * @version 1.4.3: fields.js 2015-06-10T01:14:12.284
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014, 2015 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 *
 **/
/**
 * Providing support for some custom form fields used by the REAXML Import component
 */
jQuery(document).ready(function(){
	//inject modal popup iframe div to receive dynamic component configuration form
	jQuery("body").append('<div id="reaxmlimport-config-panel"><iframe id="reaxmlimport-config-frame" src=""/></div>');
	jQuery(".folder-browser-button").click(function(e){
		e.preventDefault();
		e.stopImmediatePropagation();
		var inputid = jQuery(this).attr("inputId");
		var urlinputid = jQuery(this).attr("urlinputId");
		var value = encodeURIComponent(jQuery("#"+inputid).val());
		var url = "/administrator/index.php?option=com_reaxmlimport&controller=config&view=folderbrowser&tmpl=component&inputid="+inputid+"&urlinputid="+urlinputid+"&folder="+value;
		jQuery("#reaxmlimport-config-panel").dialog("open");
		jQuery("#reaxmlimport-config-frame").attr("src",url);	
		return false;
	});	
	jQuery("#reaxmlimport-config-panel").dialog({
		autoOpen:false,
		position: "center",
		modal: true,
		height: 300,
		width: 500,
		title: 'Browse Folders',
		close: function(){
			jQuery("#reaxmlimport-config-frame").attr("src","");
		}
	});
	
});

function reaxml_folderbrowser_useThis(inputid,urlinputid){
	var folder = jQuery("#reaxmlimport-config-frame").contents().find("#folder").val();
	var url = jQuery("#reaxmlimport-config-frame").contents().find("#url").val();
	jQuery("#"+inputid).val(folder);
	jQuery("#"+urlinputid).val(url);
	jQuery("#reaxmlimport-config-panel").dialog("close");
}
