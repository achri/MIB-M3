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


	$('#frmjab').submit(function() {
	var jab = $('#jabatan').val().toUpperCase();
	var stat = $('#stat').val();
		if (jab == ''){
			if (stat == '1'){
				$('#err_rest1').html('<?php echo ($this->lang->line('jabatan_form_error')); ?>');
				$('#dialog_tambah_gagal').dialog('open');
			}else{
				$('#err_rest2').html('<?php echo ($this->lang->line('jabatan_form_error')); ?>');
			}
		return false;
		}else{
		$.ajax({
			type: 'POST',
			url: 'index.php/<?php echo $link_controller;?>/jabatan_add',
			data: $(this).serialize(),
			success: function(data) {
				if (data == 'ada'){
					if (stat == '1'){
						$('#err_rest1').html('<?php echo ($this->lang->line('jabatan_form_error1')); ?>');
						$('#dialog_tambah_gagal').dialog('open');
					}else{
						$('#err_rest2').html('<?php echo ($this->lang->line('jabatan_form_error1')); ?>');
					}
				}else{
					if (stat == 1){
						$('#err_rest3').html('<?php echo ($this->lang->line('jabatan_tmbh_judul_input'));?> <FONT COLOR="red"><b>'+ jab +' </b></FONT><?php echo ($this->lang->line('jquery_dialog_tambah_berhasil'));?>');
						$('#dialog_tambah').dialog('open');
						$('#name').text(data);
					}else{
						$('#add_shortcut').dialog('close');
											
						$('#jablist').append($("<option value="+data+" selected='selected'>"+jab+"</option>"));
						$('#add_shortcut_berhasil').dialog('open');					
						$('#shortcut_behasil').html('<?=($this->lang->line('jabatan_tmbh_judul_input'));?> <FONT COLOR=red><b>'+ jab +' </b></FONT><?php echo ($this->lang->line('jquery_dialog_tambah_berhasil'));?>');		
					}
				}
			}
		});
		return false;
		}
	});
})
</script>

<form id="frmjab">
Jabatan : <input type="text" name="jabatan" id="jabatan" maxlength="100" style="text-transform: uppercase"/> <br />
<input type="hidden" id="stat" value="1" />
<div id="err_rest2"></div>
<br />
<input type="submit" id="submit" value="Simpan"/>
</form>

<div id="dialog_tambah_gagal" title="<?=($this->lang->line('jquery_dialog_peringatan'));?>">
	<div id="err_rest1"></div>
</div>

<div id="dialog_tambah" title="<?=($this->lang->line('jquery_dialog_konfirmasi'));?>">
	<div id="err_rest3"></div>
</div>

