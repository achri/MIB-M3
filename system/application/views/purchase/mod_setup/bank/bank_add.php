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

	$('#frmbank').submit(function() {
	var bank = document.getElementById('name_1').value;
		if (bank == ''){
			$('#dialog_kosong').dialog('open');
		return false;
		}else{
		$.ajax({
			type: 'POST',
			url: 'index.php/<?php echo $link_controller;?>/add_bank',
			data: $(this).serialize(),
			success: function(data) {
			if (data == 'ada'){
				$('#dialog_tambah_gagal').dialog('open');
			}else{				
				$('#dialog_tambah').dialog('open');				
				$('#dep_nama').text(data); 
				
			}
			}
		});
		return false;
		}
	});
})
</script>

<form id="frmbank">
<table>
	<tr>
		<td class="labelcell"><?php echo($this->lang->line('bank_form_label1'));?> </td><td class="fieldcell">: <input type="text" name="name_1" id="name_1" maxlength="100" /></td>
	</tr>
	<tr>
		<td class="labelcell"><?php echo($this->lang->line('bank_form_label2'));?> </td><td class="fieldcell">: <input type="text" name="name_2" id="name_2" maxlength="100" /></td>
	</tr>
</table>
<input type="submit" id="submit" value="<?php echo($this->lang->line('bank_form_submit'));?>"/>
</form>
<div id="dialog_kosong" title="<?=($this->lang->line('jquery_dialog_peringatan'));?>">
	<p><?php echo ($this->lang->line('bank_form_error'));?></p>
</div>
<div id="dialog_tambah_gagal" title="<?=($this->lang->line('jquery_dialog_peringatan'));?>">
	<p><?php echo ($this->lang->line('bank_form_error1'));?></p>
</div>

<div id="dialog_tambah" title="<?=($this->lang->line('jquery_dialog_konfirmasi'));?>">
	<?php echo ($this->lang->line('bank_tmbh_judul_input'));?> <FONT COLOR="red"><b><p id="dep_nama"> </p> </b></FONT><?php echo ($this->lang->line('jquery_dialog_tambah_berhasil'));?>
</div>