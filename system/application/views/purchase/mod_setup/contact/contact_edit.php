<script type="text/javascript">
$(document).ready(function() {
	// Tabs
	$('#contact').tabs();
	$('#tabscontanct').tabs();


	$("#dialog_rubah").dialog({
		autoOpen: false,
		modal: true,
		buttons: {
	'<?=$this->lang->line('jquery_button_close');?>': function() {
			
			location.href='index.php/<?php echo $link_controller;?>/index';
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

	
	$('#frmupdcp').submit(function() {
		$('.saving').attr('disabled',true);
		$.ajax({
			type: 'POST',
			url: 'index.php/<?php echo $link_controller;?>/upd_contact',
			data: $(this).serialize(),
			success: function(data) {
			if (data == 'sukses'){
				$('#dialog_rubah').dialog('open');
			}else{
				$('#dialog_kosong').dialog('open');
				$('#kesalahan').text(data); //nampilin apa aja yg masih kosong
				$('.saving').attr('disabled',false);
			}
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

function batal(){
	window.location = 'index.php/<?php echo $link_controller;?>/index';
}
</script>
<?php 
	$contact = $list_contact->row();
	$getsup = $this->tbl_supplier->get_supplier($contact->sup_id)->row();
	//$rsupplier = $getsup->row();
	$leg = $this->tbl_legal->get_legal($getsup->legal_id)->row();
	
	if ($contact->dep_id == 0){
		$Tdept = '-[pilih departemen]-';
	}else{
		$getdep = $this->tbl_departemen->get_departemen($contact->dep_id);
		$rdept = $getdep->row();
		$departemen = $rdept->dep_name; 
	}
	
	if ($contact->ttl_id == 0){
		$Tjab = '-[pilih jabatan]-';
	}else{
		$getjab = $this->tbl_jabatan->get_jabatan($contact->ttl_id);
		$rjab = $getjab->row();	
		$jabatan = $rjab->jab_name;
	}
	
	if ($contact->per_city == 0){
		$rcity->kota_name = '-[pilih kota]-';
	}else{
		$getcity = $this->tbl_kota->get_kota($contact->per_city);
		$rcity = $getcity->row();	
	}
?>
<h2><?php echo ($this->lang->line('contact_judul_halaman')); ?></h2>
<form id="frmupdcp">
<div id="tabscontanct">
	<ul>
		<li><a href="#editcont"><?php echo ($this->lang->line('contact_tab_edit')); ?></a></li>
	</ul>
	<div id="editcont">
	<table>
		<tr>
			<td class="labelcell" width="120"><?php echo ($this->lang->line('contact_input_name1')); ?></td>
			<td class="fieldcell">: <input type="text" name="nama_depan" id="nama_depan" maxlength="100" value="<?php echo $contact->per_Fname; ?>"/></td>
			<td width="40"><input type="hidden" name="idper" id="idper" value="<?php echo $contact->per_id; ?>"/></td>
			<td class="labelcell"><?php echo ($this->lang->line('contact_input_name3')); ?></td>
			<td class="fieldcell">: <input type="text" name="nama_panggilan" id="nama_panggilan" maxlength="100" value="<?php echo $contact->per_Nickname; ?>"/></td>
		</tr>
		<tr>
			<td class="labelcell" width="120"><?php echo ($this->lang->line('contact_input_name2')); ?></td>
			<td class="fieldcell">: <input type="text" name="nama_belakang" id="nama_belakang" maxlength="100" value="<?php echo $contact->per_Lname; ?>"/></td>
			<td width="40"></td>
			<td class="labelcell"><?php echo ($this->lang->line('contact_input_perusahaan')); ?></td>
			<td class="fieldcell">: <select name="perusahaan" id="perusahaan">
			<option value="<?php echo $contact->sup_id; ?>"><?php echo $leg->legal_name.". ".$getsup->sup_name; ?></option>
			<?php 
			if ($list_sup->num_rows() > 0):
			foreach ($list_sup->result() as $sup): 
			$legs = $this->tbl_legal->get_legal($sup->legal_id)->row();
				echo "<option value='$sup->sup_id'>".$legs->legal_name.". ".$sup->sup_name."</option>";
			endforeach;
			else:
				echo "Empty";
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
					<td class="fieldcell">: <select name="departemen"><option value="<?php echo $contact->dep_id;?>"><?php echo $departemen;?></option>
						<?php 
						if ($list_dep->num_rows() > 0):
						foreach ($list_dep->result() as $dep): 
							echo "<option value='$dep->dep_id'>$dep->dep_name</option>";
						endforeach;
						else:
							echo "Empty";
						endif;
						?>
					</select></td>
				</tr>
				<tr>
					<td class="labelcell"><?php echo ($this->lang->line('contact_input_jabatan')); ?></td>
					<td class="fieldcell">: <select name="jabatan"><option value="<?php echo $contact->ttl_id;?>"><?php echo $jabatan;?></option>
						<?php 
						if ($list_jab->num_rows() > 0):
						foreach ($list_jab->result() as $jab): 
							echo "<option value='$jab->jab_id'>$jab->jab_name</option>";
						endforeach;
						else:
							echo "Empty";
						endif;
						?>
					</select></td>
				</tr>
				<tr>
					<td class="labelcell"><?php echo ($this->lang->line('contact_input_alamat')); ?></td>
					<td class="fieldcell">: <textarea name="alamat" rows="2" cols="18"><?php echo $contact->per_address;?></textarea></td>
				</tr>
				<tr>
					<td class="labelcell"><?php echo ($this->lang->line('contact_input_kota')); ?></td>
					<td class="fieldcell">: <select name="kota" id="kota">
					<option value="<?php echo $contact->per_city;?>"><?php echo $rcity->kota_name;?></option>
						<?php 
						if ($list_kota->num_rows() > 0):
						foreach ($list_kota->result() as $kota): 
							echo "<option value='$kota->kota_id'>$kota->kota_name</option>";
						endforeach;
						else:
							echo "Empty";
						endif;
						?>
					</select></td>
				</tr>
				<tr>
					<td class="labelcell"><?php echo ($this->lang->line('contact_input_tlp')); ?></td>
					<td class="fieldcell">: <input type="text" name="tlp" id="tlp" maxlength="100" value="<?php echo $contact->per_phone;?>"/></td>
				</tr>
				<tr>
					<td class="labelcell"><?php echo ($this->lang->line('contact_input_handphone')); ?></td>
					<td class="fieldcell">: <input type="text" name="handphone" id="handphone" maxlength="100" value="<?php echo $contact->per_handphone;?>"/></td>
				</tr>
				<tr>
					<td class="labelcell"><?php echo ($this->lang->line('contact_input_fax')); ?></td>
					<td class="fieldcell">: <input type="text" name="fax" id="fax" maxlength="100" value="<?php echo $contact->per_fax;?>"/></td>
				</tr>
			</table>
		</div>
	</div>
	<center>
	<input type="submit" id="submit" value="<?php echo ($this->lang->line('contact_button_edit')); ?>" class="saving"/>
	<input type="button" id="button" value="<?php echo ($this->lang->line('contact_button_batal')); ?>" onclick='batal()'/>
	</center>
	</div>
</div>
</form>

<!-- ==== bwt dialog konfirmasinya ===== -->

<div id="dialog_kosong" title="<?=($this->lang->line('jquery_dialog_peringatan'));?>">
	<p id="kesalahan"></p>
</div>

<div id="dialog_rubah" title="<?=($this->lang->line('jquery_dialog_konfirmasi'));?>">
	<?php echo ($this->lang->line('contact_tmbh_judul_input'));?> <FONT COLOR="red"><b><p id="name"> </p> </b></FONT><?php echo ($this->lang->line('jquery_dialog_rubah_berhasil'));?>
</div>