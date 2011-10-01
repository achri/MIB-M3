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

	$('#negara2').change(function() {
		var a = $('#negara2').val();
		$.ajax({
			type: 'POST',
			url: 'index.php/<?php echo $link_controller;?>/get_provinsi',
			data: "id="+a,
			success: function(data) {
				$('#resprov').html(data);
			}
		});
		return false;
	});
	
	$('#frmkota').submit(function() {
	var negara = $('#negara2').val();
	var provinsi = $('#prov').val();
	var kota = $('#kota1').val().toUpperCase();;
	var kode = $('#code').val();
	var stat = $('#stat').val();
		if (negara == ''){
			if (stat == '1'){
				$('#err_rest1').html('Silahkan pilih nama Negara yang akan diinput <?php //echo ($this->lang->line('dep_form_error')); ?>');
				$('#dialog_tambah_gagal').dialog('open');
			}else{
				$('#err_rest2').html('Silahkan pilih nama Negara yang akan diinput <?php //echo ($this->lang->line('dep_form_error')); ?>');
			}
		return false;
		}else if (provinsi == ''){
			if (stat == '1'){
				$('#err_rest1').html('Pilih nama Provinsi yg akan ditambahkan <?php //echo ($this->lang->line('dep_form_error')); ?>');
				$('#dialog_tambah_gagal').dialog('open');
			}else{
				$('#err_rest2').html('Pilih nama Provinsi yg akan ditambahkan <?php //echo ($this->lang->line('dep_form_error')); ?>');
			}
		return false;
		}else if (kota == ''){
			if (stat == '1'){
				$('#err_rest1').html('Input nama Kota yg akan ditambahkan <?php //echo ($this->lang->line('dep_form_error')); ?>');
				$('#dialog_tambah_gagal').dialog('open');
			}else{
				$('#err_rest2').html('Input nama Kota yg akan ditambahkan <?php //echo ($this->lang->line('dep_form_error')); ?>');
			}
		return false;
		}else if (kode == ''){
			if (stat == '1'){
				$('#err_rest1').html('Input kode area untuk Kota yg akan ditambahkan <?php //echo ($this->lang->line('dep_form_error')); ?>');
				$('#dialog_tambah_gagal').dialog('open');
			}else{
				$('#err_rest2').html('Input kode area untuk Kota yg akan ditambahkan <?php //echo ($this->lang->line('dep_form_error')); ?>');
			}
		return false;
		}else{
		$.ajax({
			type: 'POST',
			url: 'index.php/<?php echo $link_controller;?>/kota_add',
			data: $(this).serialize(),
			success: function(data) {	
				if (data == 'ada'){
					if (stat == '1'){
						$('#err_rest1').html('Kota '+kota+' Sudah Terdaftar');
						$('#dialog_tambah_gagal').dialog('open');
					}else{
						$('#err_rest2').html('Kota '+kota+' Sudah Terdaftar');
					}
				}else{
					if (stat == '1'){
						$('#err_rest3').html('Kota '+kota+' berhasil ditambahkan');
						$('#dialog_tambah').dialog('open');
					}else{
						$('#add_shortcut').dialog('close');
						$('#kota').append($("<option value="+data+">"+kota+"</option>"));
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
<form id="frmkota">
<table>
	<tr>
		<td class="labelcell">Negara <?php //echo ($this->lang->line('dep_input_dep')); ?> </td>
		<td class="fieldcell">: <select name="negara2" id="negara2">
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
		<td class="labelcell">Provinsi <?php //echo ($this->lang->line('dep_input_dep')); ?> </td>
		<td class="fieldcell"><div id="resprov">: <select name="prov" id = "prov">
				<option value=''><?=$this->lang->line('combo_box_provinsi')?></option>
				</select></div>
		</td>
	</tr>
	<tr>
		<td class="labelcell">Kota</td>
		<td class="fieldcell">: <input type="text" name="kota1" id="kota1" maxlength="100" style="text-transform: uppercase" />
			<input type="hidden" id="stat" value="1" />
		</td>
	</tr>
	<tr>
		<td class="labelcell">Code Area</td>
		<td class="fieldcell">: <input type="text" name="code" id="code" maxlength="100" /></td>
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