$(document).ready(function() {
	var $dlg_info = $('.dialog_informasi');
	$dlg_info.dialog({
		autoOpen: false,
		bgiframe: true,
		width: 'auto',
		height: 'auto',
		resizable: false,
		//draggable: false,
		modal:true,
		/*position:['right','top'],*/
		position:'center',
		buttons : { 
			'OK': function() {
				$(this).dialog('close');
			}
		}
	});
});