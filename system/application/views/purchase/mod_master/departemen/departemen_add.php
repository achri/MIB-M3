<script type="text/javascript">
$(document).ready(function() {


	$("#dialog_tambah").dialog({
		autoOpen: false,
		modal: true,
		buttons: {
		'<?=$this->lang->line('jquery_button_close');?>': function() {
			$(this).dialog('close');
			location.href='index.php/<?php echo $link_controller;?>/index';
			}
		}
		});

		
$("#dialog_kosong").dialog({
	autoOpen: false,
	modal: true,
	buttons: {
	'<?=$this->lang->line('jquery_button_close');?>': function() {
		$(this).dialog('close');
	}
}
});

$("#dialog_tambah_gagal").dialog({
	autoOpen: false,
	modal: true,
	buttons: {
	'<?=$this->lang->line('jquery_button_close');?>': function() {
		$(this).dialog('close');
		}
	}
	});
	
	$('#frmdep').submit(function() {
	var dep = $('#departemen').val().toUpperCase();
	var stat = $('#stat').val();
	
		if (dep == ''){
			if (stat == '1'){
				$('#err_rest1').html('<?php echo ($this->lang->line('dep_form_error')); ?>');
				$('#dialog_kosong').dialog('open');
			}else{
				$('#err_rest2').html('<?php echo ($this->lang->line('dep_form_error')); ?>');
			}
		return false;
		}else{
		$.ajax({
			type: 'POST',
			url: 'index.php/<?php echo $link_controller;?>/dep_add',
			data: $(this).serialize(),
			success: function(data) {			
			if (data == 'ada'){
				if (stat == '1'){
					$('#err_rest1').html('<?php echo ($this->lang->line('dep_form_error1')); ?>');
					$('#dialog_kosong').dialog('open');
				}else{
					$('#err_rest2').html('<?php echo ($this->lang->line('dep_form_error1')); ?>');
				}
			}else{
				if (stat == '1'){
					$('#err_rest3').html('<?php echo ($this->lang->line('departemen_tmbh_judul_input'));?> <FONT COLOR="red"><b>'+ dep +' </b></FONT><?php echo ($this->lang->line('jquery_dialog_tambah_berhasil'));?>');				
					$('#dialog_tambah').dialog('open');				
					$('#dep_nama').text(data); 
				}else{
				$('#add_shortcut').dialog('close');
				$('#deplist').append($("<option value="+data+" selected='selected'>"+dep+"</option>"));
						$('#add_shortcut_berhasil').dialog('open');
						$('#shortcut_behasil').html('<?=($this->lang->line('departemen_tmbh_judul_input'));?> <FONT COLOR=red><b>'+ dep +' </b></FONT><?php echo ($this->lang->line('jquery_dialog_tambah_berhasil'));?>');
					
				}
			}
			
		}
		});
		return false;
		}
	});
})
</script>

<form id="frmdep">
<?php echo ($this->lang->line('dep_input_dep')); ?> : 
<input type="text" name="departemen" id="departemen" maxlength="100" style="text-transform: uppercase" /> <br />
<input type="hidden" id="stat" value="1" />
<div id="err_rest2"></div>
<br />
<input type="submit" id="submit" value="Simpan"/>
</form>
<div id="dialog_kosong" title="<?=($this->lang->line('jquery_dialog_peringatan'));?>">
	<div id="err_rest1"></div>
</div>

<div id="dialog_tambah" title="<?=($this->lang->line('jquery_dialog_konfirmasi'));?>">
	<div id="err_rest3"></div>
</div>

