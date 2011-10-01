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
	
	$('#frmprov').submit(function() {
	var negara = $('#negara1').val();
	var provinsi = $('#i_provinsi').val().toUpperCase();
	var stat = $('#stat').val();
	
		if (negara == ''){
			if (stat == '1'){
				$('#err_rest1').html('Silahkan pilih nama negara yang akan diinput <?php //echo ($this->lang->line('dep_form_error')); ?>');
				$('#dialog_tambah_gagal').dialog('open');
			}else{
				$('#err_rest2').html('Silahkan pilih nama negara yang akan diinput <?php //echo ($this->lang->line('dep_form_error')); ?>');
			}
		return false;
		}else if (provinsi == ''){
			if (stat == '1'){
				$('#err_rest1').html('Input nama provinsi yg akan ditambahkan <?php //echo ($this->lang->line('dep_form_error')); ?>');
				$('#dialog_tambah_gagal').dialog('open');
			}else{
				$('#err_rest2').html('Input nama provinsi yg akan ditambahkan <?php //echo ($this->lang->line('dep_form_error')); ?>');
			}
		return false;
		}else{
		$.ajax({
			type: 'POST',
			url: 'index.php/<?php echo $link_controller;?>/provinsi_add',
			data: $(this).serialize(),
			success: function(data) {			
			if (data == 'ada'){
				if (stat == '1'){
					$('#err_rest1').html('Provinsi '+provinsi+' Sudah Terdaftar');
					$('#i_provinsi').val('');
					$('#dialog_tambah_gagal').dialog('open');
				}else{
					$('#err_rest2').html('Negara Sudah Terdaftar <?php echo ($this->lang->line('dep_form_error1')); ?>');
				}
			}else{
				if (stat == '1'){
					$('#err_rest3').html('Provinsi '+provinsi+' berhasil ditambahkan');
					$('#dialog_tambah').dialog('open');
				}else{
					$('#add_shortcut').dialog('close');
					$('#provinsi').append($("<option value="+data+">"+provinsi+"</option>"));
				}
			}
			}
		});
		return false;
		}
	});
})
</script>
<?php 
$list_neg = $this->tbl_negara->list_negara();
?>
<form id="frmprov">
<table>
	<tr>
		<td class="labelcell">Negara <?php //echo ($this->lang->line('dep_input_dep')); ?> </td>
		<td class="fieldcell">: <select name="negara1" id="negara1">
				<option value=''><?=$this->lang->line('combo_box_negara')?></option>
					<?php if ($list_neg->num_rows() > 0):
					foreach ($list_neg->result() as $rowz): 
						echo "<option value='$rowz->negara_id'>$rowz->negara_name</option>";
					endforeach;
					else:
						echo $this->lang->line('data_empty');
					endif; ?>
				</select></td>
	</tr>
	<tr>
		<td class="labelcell">Provinsi</td>
		<td class="fieldcell">: <input type="text" name="i_provinsi" id="i_provinsi" maxlength="100" style="text-transform: uppercase" /> <br />
			<input type="hidden" id="stat" value="1" />
		</td>
	</tr>
</table>
<div id="err_rest2"></div>
<input type="submit" id="submit" value="Simpan"/>
</form>
<div id="dialog_tambah_gagal" title="<?=($this->lang->line('jquery_dialog_peringatan'));?>">
	<div id="err_rest1"></div>
</div>

<div id="dialog_tambah" title="<?=($this->lang->line('jquery_dialog_konfirmasi'));?>">
	<div id="err_rest3"></div>
</div>