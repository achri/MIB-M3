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

	$('#frmsat').submit(function() {
	var sat = document.getElementById('satuan').value;
		if (sat == ''){
			$('#dialog_kosong').dialog('open');
		return false;
		}else{
		$.ajax({
			type: 'POST',
			url: 'index.php/<?php echo $link_controller;?>/add_satuan',
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

<form id="frmsat">
<table>
	<tr>
		<td class="labelcell"><?php echo($this->lang->line('satuan_input_satuan'));?></td>
		<td class="fieldcell">: <input type="text" name="satuan" id="satuan" maxlength="100" /></td>
	</tr>
	<tr>
		<td class="labelcell"><?php echo($this->lang->line('satuan_input_format'));?></td>
		<td class="fieldcell">: <input type="text" name="format" id="format" maxlength="1" /></td>
	</tr>
</table>
<input type="submit" id="submit" value="<?php echo($this->lang->line('satuan_button_submit'));?>"/>
</form>

<!-- ============= buat dialognya ============ -->
<div id="dialog_kosong" title="<?=($this->lang->line('jquery_dialog_peringatan'));?>">
	<p><?php echo ($this->lang->line('satuan_form_error'));?></p>
</div>
<div id="dialog_tambah_gagal" title="<?=($this->lang->line('jquery_dialog_peringatan'));?>">
	<p><?php echo ($this->lang->line('satuan_form_error1'));?></p>
</div>

<div id="dialog_tambah" title="<?=($this->lang->line('jquery_dialog_konfirmasi'));?>">
	<?php echo ($this->lang->line('satuan_tmbh_judul_input'));?> <FONT COLOR="red"><b><p id="dep_nama"> </p> </b></FONT><?php echo ($this->lang->line('jquery_dialog_tambah_berhasil'));?>

</div>