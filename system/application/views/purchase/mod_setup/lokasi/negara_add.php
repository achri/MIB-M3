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


$("#dialog_tambah_gagal").dialog({
	autoOpen: false,
	modal: true,
	buttons: {
	'<?=$this->lang->line('jquery_button_close');?>': function() {
		$(this).dialog('close');
		}
	}
	});
	
	$('#frmneg').submit(function() {
	var negara = $('#i_negara').val().toUpperCase();;
	var stat = $('#stat').val();

		if (negara == ''){
			if (stat == '1'){
				$('#err_rest1').html('Silahkan isi nama negara yang akan diinput <?php //echo ($this->lang->line('dep_form_error')); ?>');
				$('#dialog_tambah_gagal').dialog('open');
			}else{
				$('#err_rest2').html('Silahkan isi nama negara yang akan diinput <?php //echo ($this->lang->line('dep_form_error')); ?>');
			}
		return false;
		}else{
		$.ajax({
			type: 'POST',
			url: 'index.php/<?php echo $link_controller;?>/negara_add',
			data: $(this).serialize(),
			success: function(data) {			
			if (data == 'ada'){
				if (stat == '1'){
					$('#err_rest1').html('Negara '+negara+' Sudah Terdaftar <?php //echo ($this->lang->line('dep_form_error1')); ?>');
					$('#i_negara').val('');
					$('#dialog_tambah_gagal').dialog('open');
				}else{
					$('#err_rest2').html('Negara '+negara+' Sudah Terdaftar <?php //echo ($this->lang->line('dep_form_error1')); ?>');
				}
			}else{
				if (stat == '1'){
					$('#err_rest3').html('Negara <?php //echo ($this->lang->line('departemen_tmbh_judul_input'));?> <FONT COLOR="red"><b>'+ negara +' </b></FONT> berhasil diinputkan <?php //echo ($this->lang->line('jquery_dialog_tambah_berhasil'));?>');				
					$('#dialog_tambah').dialog('open');				
					$('#dep_nama').text(data); 
				}else{
					$('#add_shortcut').dialog('close');
					$('#negara').append($("<option value="+data+">"+negara+"</option>"));
				}
			}
		}
		});
		return false;
		}
	});
})
</script>

<form id="frmneg">
Negara <?php //echo ($this->lang->line('dep_input_dep')); ?> : 
<input type="text" name="negara" id="i_negara" maxlength="100" style="text-transform: uppercase" /> <br />
<input type="hidden" id="stat" value="1" />
<br/>
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