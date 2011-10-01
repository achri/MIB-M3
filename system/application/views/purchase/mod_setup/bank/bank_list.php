<script typr="text/javascript"> 
$("#dialog_hapus").dialog({
	autoOpen: false,
	modal: true,
	buttons: {		
	'<?=$this->lang->line('jquery_button_cancel');?>': function() {
		$(this).dialog('close');
	},
	'<?=$this->lang->line('jquery_button_hapus');?>': function() {	
		var tmp_id = $('#tmp').val();
		$(this).dialog('close');	
		$.ajax({
			type: 'POST',
			url: 'index.php/<?php echo $link_controller;?>/delete_bank',
			data: "id="+tmp_id,				
  			dataType:"html"
		});						
		location.href='index.php/<?php echo $link_controller;?>/index';
	}
}
});


$("#dialog_hapus_error").dialog({
	autoOpen: false,
	modal: true,
	buttons: {
	'<?=$this->lang->line('jquery_button_close');?>': function() {
		$(this).dialog('close');
	}
}
});



	function edit1(row, id){
		$(row).click(function(){
		$('> span',this).editable('index.php/<?php echo $link_controller;?>/bank_update1',{
			indicator : '<?=$this->lang->line('jquery_indicator_save')?>',
			tooltip   : '<?=$this->lang->line('jquery_tooltip_clik')?>',
			width : '200px'
		});
		});
	}

	function edit2(row, id){
		$(row).click(function(){
			$('> span',this).editable('index.php/<?php echo $link_controller;?>/bank_update2',{
				indicator : '<?=$this->lang->line('jquery_indicator_save')?>',
				tooltip   : '<?=$this->lang->line('jquery_tooltip_clik')?>',
			width : '200px'
		});
		});
	}

	function deletebank(id) {
		$('#dialog_hapus').dialog('open');									
		$('#tmp').val(id);		
		
	}
</script>
<table id="flex1" style="display:none"></table>
	<?php
		echo $js_grid;
		echo '<i>'.$this->lang->line('keterangan_bawah').'</i>';	
	?>
	

	
<!-- ==== bwt dialog konfirmasinya ===== -->
<input type="hidden" id="tmp"> <!-- bwt nyimpen id sementaun, idnnya bwt dimasukin ke dialog_hapus -->
<div id="dialog_hapus" title="<?=($this->lang->line('jquery_dialog_konfirmasi'));?>">
	<p><?=($this->lang->line('ajax_dialog_hapus'));?></p><p id="name"></p>
</div>

<div id="dialog_hapus_error" title="<?=($this->lang->line('jquery_dialog_peringatan'));?>">
	<p><?=($this->lang->line('ajax_dialog_hapus_dipakai'));?></p>
</div>

<div id="dialog_hapus_behasil" title="<?=($this->lang->line('jquery_dialog_konfirmasi'));?>">
	<p><?=($this->lang->line('ajax_hapus_berhasil'));?></p>
</div>