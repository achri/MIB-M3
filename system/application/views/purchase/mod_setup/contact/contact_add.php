<script type="text/javascript">
$(document).ready(function() {
	// Tabs
	$('#contact').tabs();

	$("#add_shortcut").dialog({
		autoOpen: false,
		modal: true});

	$("#dialog_tambah").dialog({
		autoOpen: false,
		modal: true,
		buttons: {
		'<?=$this->lang->line('jquery_button_close');?>': function() {
			
				location.href='index.php/<?php echo $link_controller;?>/index/5';
				$('#tabs').tabs('select',0);
				$(this).dialog('close');
			}
		}
		});

	$("#dialog_kosong").dialog({
		autoOpen: false,
		modal: true,
		buttons: {
		'<?=$this->lang->line('jquery_button_close');?>': function() {
			$(this).dialog('close');
			batal();
		}
	}
	});


	$('#frmcp').submit(function() {
		$('.saving').attr('disabled',true);
		$.ajax({
			type: 'POST',
			url: 'index.php/<?php echo $link_controller;?>/add_contact',
			data: $(this).serialize(),
			success: function(data) {
			if (data == 'sukses'){
				$('#dialog_tambah').dialog('open');
			}else{
				$('#dialog_kosong').dialog('open');
				$('#kesalahan').text(data); //nampilin apa aja yg masih kosong
				$('.saving').attr('disabled',false);
			}
			//$('.saving').attr('disabled',false);
		}
		});
		return false;
	});

	$('#kota').change(function() {
		var a = $('#kota').val();
		//alert (a);
		$.ajax({
			type: 'POST',
			url: 'index.php/<?php echo $link_controller;?>/get_code',
			data: "id="+a,
			success: function(data) {
				$('#tlp').val(data);
				$('#fax').val(data);

			}
		});
		return false;
	});
})

//============== shortcut add departemen ===============
function add_dep(){
	$.ajax({
		type: 'POST',
		url: 'index.php/<?php echo $link_controller_departement;?>/dep_frm',
		data: $(this).serialize(),
		success: function(data) {
			$('#frmview').html(data);
			$('#stat').val('2');
			$('#add_shortcut').dialog('open');
	}
	});
	return false;
}

//============== shortcut add jabatan ===============
function add_jab(){
	$.ajax({
		type: 'POST',
		url: 'index.php/<?php echo $link_controller_jabatan;?>/jabatan_frm',
		data: $(this).serialize(),
		success: function(data) {
			$('#frmview').html(data);
			$('#stat').val('2');
			$('#add_shortcut').dialog('open');
	}
	});
	return false;
}

function batal(){
	window.location = 'index.php/<?php echo $link_controller;?>/index';
}
</script>

<form id="frmcp">
<table>
	<tr>
		<td class="labelcell" width="120"><?php echo ($this->lang->line('contact_input_name1')); ?></td>
		<td class="fieldcell">: <input type="text" name="nama_depan" id="nama_depan" maxlength="100" /></td>
		<td width="40"></td>
		<td class="labelcell"><?php echo ($this->lang->line('contact_input_name3')); ?></td>
		<td class="fieldcell">: <input type="text" name="nama_panggilan" id="nama_panggilan" maxlength="100" /></td>
	</tr>
	<tr>
		<td class="labelcell" width="120"><?php echo ($this->lang->line('contact_input_name2')); ?></td>
		<td class="fieldcell">: <input type="text" name="nama_belakang" id="nama_belakang" maxlength="100" /></td>
		<td width="40"></td>
		<td class="labelcell"><?php echo ($this->lang->line('contact_input_perusahaan')); ?></td>
		<td class="fieldcell">: <select name="perusahaan" id="perusahaan">
		<option value=""><?=$this->lang->line('combo_box_supplier')?></option>
		<?php 
		if ($list_sup->num_rows() > 0):
		foreach ($list_sup->result() as $sup): 
			$leg = $this->tbl_legal->get_legal($sup->legal_id)->row();
			echo "<option value='$sup->sup_id'>".$leg->legal_name.". ".$sup->sup_name."</option>";
		endforeach;
		else:
			echo $this->lang->line('data_empty'); // klo kosong ga data
		endif;
		?>
		</select></td>
	</tr>
</table>
<br>
<div id="contact">
	<ul>
		<li><a href="#general"><?=($this->lang->line('tab_umum'))?></a></li>
	</ul>
	<div id="general">
		<table>
			<tr>
				<td class="labelcell" width="120"><?php echo ($this->lang->line('contact_input_departemen')); ?></td>
				<td class="fieldcell">: <select name="departemen" id="deplist"><option value=""><?=$this->lang->line('combo_box_departemen')?></option>
					<?php 
					if ($list_dep->num_rows() > 0):
					foreach ($list_dep->result() as $dep): 
						echo "<option value='$dep->dep_id'>$dep->dep_name</option>";
					endforeach;
					else:
						echo $this->lang->line('data_empty');
					endif;
					?>
				</select>
				<a href="javascript:void(0)" onclick="add_dep()"><img border="0" src="./asset/img_source/add1.gif"></a>
				</td>
			</tr>
			<tr>
				<td class="labelcell"><?php echo ($this->lang->line('contact_input_jabatan')); ?></td>
				<td class="fieldcell">: <select name="jabatan" id="jablist"><option value=""><?=$this->lang->line('combo_box_jabatan')?></option>
					<?php 
					if ($list_jab->num_rows() > 0):
					foreach ($list_jab->result() as $jab): 
						echo "<option value='$jab->jab_id'>$jab->jab_name</option>";
					endforeach;
					else:
						echo $this->lang->line('data_empty');
					endif;
					?>
				</select>
				<a href="javascript:void(0)" onclick="add_jab()"><img border="0" src="./asset/img_source/add1.gif"></a>
				</td>
			</tr>
			<tr>
				<td class="labelcell"><?php echo ($this->lang->line('contact_input_alamat')); ?></td>
				<td class="fieldcell">: <textarea name="alamat" rows="2" cols="18"></textarea></td>
			</tr>
			<tr>
				<td class="labelcell"><?php echo ($this->lang->line('contact_input_kota')); ?></td>
				<td class="fieldcell">: <select name="kota" id="kota">
				<option value=" "><?=$this->lang->line('combo_box_kota')?></option>
					<?php 
					if ($list_kota->num_rows() > 0):
					foreach ($list_kota->result() as $kota): 
						echo "<option value='$kota->kota_id'>$kota->kota_name</option>";
					endforeach;
					else:
						echo $this->lang->line('data_empty');
					endif;
					?>
				</select></td>
			</tr>
			<tr>
				<td class="labelcell"><?php echo ($this->lang->line('contact_input_tlp')); ?></td>
				<td class="fieldcell">: <input type="text" name="tlp" id="tlp" maxlength="100" /></td>
			</tr>
			<tr>
				<td class="labelcell"><?php echo ($this->lang->line('contact_input_handphone')); ?></td>
				<td class="fieldcell">: <input type="text" name="handphone" id="handphone" maxlength="100" /></td>
			</tr>
			<tr>
				<td class="labelcell"><?php echo ($this->lang->line('contact_input_fax')); ?></td>
				<td class="fieldcell">: <input type="text" name="fax" id="fax" maxlength="100" /></td>
			</tr>
		</table>
	</div>
</div>
<center>
<input type="submit" id="submit" value="<?php echo ($this->lang->line('contact_button_submit')); ?>" class="saving"/>
</center>
</form>


<!-- ==== bwt dialog konfirmasinya ===== -->

<div id="dialog_kosong" title="<?=($this->lang->line('jquery_dialog_peringatan'));?>">
	<p id="kesalahan"></p>
</div>

<div id="dialog_tambah" title="<?=($this->lang->line('jquery_dialog_konfirmasi'));?>">
	<?php echo ($this->lang->line('contact_tmbh_judul_input'));?> <FONT COLOR="red"><b><p id="name"> </p> </b></FONT><?php echo ($this->lang->line('jquery_dialog_tambah_berhasil'));?>
</div>

<div id="add_shortcut" title="shortcut">
	<div id="frmview"></div>
</div>