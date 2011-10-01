<script type="text/javascript">

function test(com,grid)
{
    if (com=='Select All')
    {
		$('.bDiv tbody tr',grid).addClass('trSelected');
    }
    
    if (com=='DeSelect All')
    {
		$('.bDiv tbody tr',grid).removeClass('trSelected');
    }       
} 



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
				url: 'index.php/<?php echo $link_controller;?>/dep_delete',
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


$("#dialog_hapus_berhasil").dialog({
		autoOpen: false,
		modal: true,
		buttons: {
		'<?=$this->lang->line('jquery_button_close');?>': function() {
			$(this).dialog('close');
		}
	}
	});

	function deletedep(id){
		$.ajax({
			type: 'POST',
			url: 'index.php/<?php echo $link_controller;?>/cek_delete/'+ id,		
			data: $(this).serialize(),
			success: function(data) {
							
			
							if (data == '0'){
								$('#dialog_hapus').dialog('open');									
								$('#tmp').val(id);		

							}else{
								$('#dialog_hapus_error').dialog('open');
								
							}	
		}
		});
	}

	function editdep(row, id){
		$(row).click(function(){
		$('> span',this).editable('index.php/<?php echo $link_controller;?>/dep_update',{
			indicator : '<?=$this->lang->line('jquery_indicator_save')?>',
			tooltip   : '<?=$this->lang->line('jquery_tooltip_clik')?>',
			width : '200px'
		});
		});
	}
</script>

<table id="flex1" style="display:none"></table>
	<?php
		if ($kosong == ''){		
			echo $js_grid;
			echo '<i>'.$this->lang->line('keterangan_bawah').'</i>';			
		}else{
			echo "<center><div class='ui-corner-all headers' align='center'><font color='red'>$kosong</font></div></center>";
		}
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