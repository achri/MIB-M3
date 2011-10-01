// JavaScript Document
$('.dialog_konfirmasi,.informasi').dialog({
	title:"<?=$this->lang->line('confirm')?>",
	autoOpen: false,
	bgiframe: true,
	width: 'auto',
	height: 'auto',
	resizable: false,
	//draggable: false,
	modal:true,
	position:['right','top'],
	buttons: {
		"<?=$this->lang->line('ok')?>": function() {
			$(this).dialog('close');	
		}
	}
});