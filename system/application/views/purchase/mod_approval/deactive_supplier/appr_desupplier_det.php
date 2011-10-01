<?php
			$listbank = '';
				if ($list_bank->num_rows() > 0){
					foreach ($list_bank->result() as $bank):
						$listbank .= "<option value='$bank->bank_id'>$bank->bank_name_singkat</option>";
					endforeach;
				}
?>
<script type="text/javascript">
function batal() {
	$('#tabs').tabs('enable',0);
	$('#tabs').tabs('select',0);
	$('#tabs').tabs('remove',1);
	$('#supplier_list').flexReload();
	return false;
}
function deactive_supplier() {
	$("#dialog_desupplier").dialog('open');
}

$(document).ready(function() {
		// Tabs
		$('#tabssup').tabs();
		
		$(".dialog_informasi").dialog({
			autoOpen: false,
			bgiFrame: true,
			draggable: false,
			resizable: false,
			buttons: {
				"OK": function() {
					$(this).dialog('close');
				}
			}
		});
		
		// DEACTIVE SUPPLIER
		$("#dialog_desupplier").dialog({
			autoOpen: false,
			modal: true,
			resizable: false,
			draggable: false,
			height: 'auto',
			width: 'auto',
			position:['right','top'],
			buttons: {
				"TIDAK": function() {
					$(this).dialog('close');
					$('.saving').attr('disabled',false);
				},
				"YA" : function() {
					// 
					$.ajax({
						type: 'POST',
						url: 'index.php/<?=$link_controller?>/appr_deactive',
						data: $('#frmeditsup').serialize(),
						success: function(data) {
							$("#dialog_desupplier").dialog('close');
							if ((data == 'kosong')||(data == 'gagal')) {
								infos = 'Persetujuan non aktif pemasok tidak berhasil !!!';
								$(".dialog_informasi").html('').html(infos)
								.dialog('option','buttons',{
									"OK": function() {
										$(this).dialog('close');
									}
								})
								.dialog('open');
							} else {
								infos = data;
								$(".dialog_informasi").html('').html(infos)
								.dialog('option','buttons',{
									"OK": function() {
										$(this).dialog('close');
										batal();
									}
								})
								.dialog('open');
							}
						}
					});
					return false;
					//$('.saving').attr('disabled',false);
				}
			}
		}).css('color','red');

});
</script>
<form id="frmeditsup">
<center>
<div class="ui-corner-all headers" style="width:100%">
<table>
	<tr>
		<td class="labelcell" width="100">Pemohon</td>
		<td class="labelcell2">:</td>
		<td class="labelcell2"><?=$data_sup->usr_name?></td>
		<td width="20%"></td>
		<td class="labelcell" width="100">Departemen</td>
		<td class="labelcell2">:</td>
		<td class="labelcell2"><?=$data_sup->dep_name?></td>
	</tr>
	<tr>
		<td class="labelcell">Tanggal</td>
		<td class="labelcell2">:</td>
		<td class="labelcell2"><?=$data_sup->deactive_date?></td>
		<td></td>
		<td class="labelcell">Alasan</td>
		<td class="labelcell2">:</td>
		<td class="labelcell2">
			<div><?=$data_sup->deactive_note?></div>
		</td>
	</tr>
	<tr>
		<td class="labelcell">Status</td>
		<td class="labelcell2">:</td>
		<td class="labelcell2">
		<select name="deactive_status">
			<option value="">--Pilih Status--</option>
			<option value="0">Disetujui</option>
			<option value="2">Ditunda</option>
			<option value="1">Ditolak</option>
		</select>
		</td>
	</tr>
</table>
</div>
</center>
<br>
			<div id="edit-2">
			<?php 
				
				$Erow = $list_supp->row();
				$get_leg = $this->tbl_legal->get_legal($Erow->legal_id);
				$Lrow = $get_leg->row();
				$get_city = $this->tbl_kota->get_kota($Erow->sup_city);
				$Crow = $get_city->row();
				$get_prov = $this->tbl_provinsi->get_prov($Erow->sup_city);
				$Prow = $get_prov->row();
				$get_negara = $this->tbl_negara->get_negara($Prow->negara_id);
				$Nrow = $get_negara->row();
				$get_tc = $this->tbl_term->get_term($Erow->term_id);
				$Trow = $get_tc->row();
				
			?>
					<!-- Tabs -->
					<div id="tabssup">
						<ul>
							<li><a href="#general"><?php echo ($this->lang->line('sup_tab_tambah_general')); ?></a></li>
							<li><a href="#suppcat"><?php echo ($this->lang->line('sup_tab_tambah_kategori')); ?></a></li>
							<li><a href="#suppbank"><?php echo ($this->lang->line('sup_tab_tambah_pay')); ?></a></li>
						</ul>
						<div id="general">
						<input type="hidden" name="idsupp" id="idsupp" maxlength="100" value="<?php echo $Erow->sup_id; ?>" />
						<table>
							<tr>
								<td width="48%">
								<table>
									<tr>
										<td class="labelcell"><?php echo ($this->lang->line('sup_input_nama')); ?></td>
										<td class="fieldcell">: <input type="text" name="namesupp" id="namesupp" maxlength="100" value="<?php echo $Erow->sup_name; ?>" /></td>
									</tr>
									<tr>
										<td class="labelcell"><?php echo ($this->lang->line('sup_input_legal')); ?></td>
										<td class="fieldcell">: <select name="legal" id="legal" >
											<option value='<?php echo $Erow->legal_id; ?>'><?php echo $Lrow->legal_name; ?></option>
											<?php if ($list_leg->num_rows() > 0):
													foreach ($list_leg->result() as $row): 
														echo "<option value='$row->legal_id'>$row->legal_name</option>";
													endforeach;
												else:
													echo "Empty";
												endif; ?>
										</select></td>
									</tr>
									<tr>
										<td class="labelcell"><?php echo ($this->lang->line('sup_input_npwp')); ?></td>
										<td class="fieldcell">: <input type="text" name="npwp" id="npwp" alt="npwp" value="<?php echo $Erow->sup_npwp; ?>"/></td>
									</tr>
									<tr>
										<td class="labelcell"><?php echo ($this->lang->line('sup_input_alamat')); ?></td>
										<td class="fieldcell">: <textarea rows="2" cols="18" name='alamat' id='alamat'><?php echo $Erow->sup_address; ?></textarea></td>
									</tr>
									<tr>
										<td class="labelcell"><?php echo ($this->lang->line('sup_input_negara')); ?></td>
										<td class="fieldcell">: <select name="negara" id="negara">
										<!--option value='<//?php echo $Nrow->negara_id; ?>'><//?php echo $Nrow->negara_name; ?></option-->
											<?php if ($list_neg->num_rows() > 0):
													foreach ($list_neg->result() as $rowz): ?>
														<option value='<?=$rowz->negara_id?>' <?=($rowz->negara_id==$Nrow->negara_id)?('SELECTED'):('')?>><?=$rowz->negara_name?></option>
											<?php	endforeach;
												else:
													echo "Empty";
												endif; ?>
										</select></td>
									</tr>
									<tr>
										<td class="labelcell"><?php echo ($this->lang->line('sup_input_provinsi')); ?></td>
										<td class="fieldcell"><div id="rprov" style="padding: 0px;">: <select name="provinsi">
										<option value="<?php echo $Prow->provinsi_id; ?>"><?php echo $Prow->provinsi_name;?></option></select> 
										</div> </td>
									</tr>
									<tr>
										<td class="labelcell"><?php echo ($this->lang->line('sup_input_kota')); ?></td>
										<td class="fieldcell"><div id="rkota" style="margin-left: 0px;">: <select name="kota">
										<option value='<?php echo $Prow->kota_id;?>'><?php echo $Prow->kota_name;?></option>
										</select></div></td>
									</tr>
								</table>
								</td>
								<td width="4%"></td>
								<td width="48%" valign ="top">
								<table>
									<tr>
										<td class="labelcell"><?php echo ($this->lang->line('sup_input_phone1')); ?></td>
										<td class="fieldcell">: <input type="text" name="phone1" id="phone1" maxlength="100" value="<?php echo $Erow->sup_phone1; ?>"/></td>
									</tr>
									<tr>
										<td class="labelcell"><?php echo ($this->lang->line('sup_input_phone2')); ?></td>
										<td class="fieldcell">: <input type="text" name="phone2" id="phone2" maxlength="100" value="<?php echo $Erow->sup_phone2; ?>"/></td>
									</tr>
									<tr>
										<td class="labelcell"><?php echo ($this->lang->line('sup_input_phone3')); ?></td>
										<td class="fieldcell">: <input type="text" name="phone3" id="phone3" maxlength="100" value="<?php echo $Erow->sup_phone3; ?>"/></td>
									</tr>
									<tr>
										<td class="labelcell"><?php echo ($this->lang->line('sup_input_fax1')); ?></td>
										<td class="fieldcell">: <input type="text" name="fax" id="fax" maxlength="100" value="<?php echo $Erow->sup_fax; ?>"/></td>
									</tr>
									<tr>
										<td class="labelcell"><?php echo ($this->lang->line('sup_input_hp')); ?></td>
										<td class="fieldcell">: <input type="text" name="handphone" id="handphone" maxlength="100" value="<?php echo $Erow->sup_handphone; ?>"/></td>
									</tr>
									<tr>
										<td class="labelcell"><?php echo ($this->lang->line('sup_input_email')); ?></td>
										<td class="fieldcell">: <input type="text" name="email" id="email" maxlength="100" value="<?php echo $Erow->sup_email; ?>"/></td>
									</tr>
								</table>
								</td>
							</tr>
						</table>
						</div>
						<div id="suppcat">
								<div style="float: left;"><font style="font: 13px Verdana;"><?php echo ($this->lang->line('sup_daftar_kategori1')); ?></font></div>
								<div style="float: left; width: 50px;">&nbsp;</div>
								<div><font style="font: 13px Verdana;"><?php //echo ($this->lang->line('sup_daftar_kategori2')); ?></font></div><br>
								<ul id="spaceleft"></ul>
								<ul id="cat1" class='dropcat'>
								<?php 
								foreach ($cat_sup->result() as $cat1):
									echo "<li id='$cat1->cat_id' value='$cat1->cat_name'>$cat1->cat_name</li>";
								$id[] = $cat1->cat_id;
								endforeach;
								$getcat2 = $this->tbl_category->get_sup_cat_rest($id);
								?>
								</ul>
								<ul id="space"></ul>
								<!--ul id="cat2" class='dropcat'>
									<//?php
										foreach ($getcat2->result() as $cat2): 
											echo "<li id='$cat2->cat_id' value='$cat2->cat_name'>$cat2->cat_name</li>";
										endforeach;
									?>
								</ul-->
								<input type="hidden" name="setcat" id="setcat">
						<div style="height: 250px;">
						&nbsp;
						</div>
						</div>
						<div id="suppbank">
						<table width="100%">
							<tr>
								<td width="20%" class="labelcell"><?php echo ($this->lang->line('sup_input_term')); ?></td>
								<td width="5%">:</td>
								<td width="75%" class="fieldcell"><select name="term">
								<option value='<?php echo $Erow->term_id; ?>'><?php echo $Trow->term_id_name; ?></option>
											<?php if ($list_term->num_rows() > 0):
													foreach ($list_term->result() as $term): 
														echo "<option value='$term->term_id'>$term->term_id_name</option>";
													endforeach;
												else:
													echo "Empty";
												endif; ?>
								</select></td>
							</tr>
							<tr>
								<td class="labelcell" valign="top"><?php echo ($this->lang->line('sup_input_bank')); ?></td>
								<td valign="top">:</td>
								<td class="fieldcell"><input type="button" name="term" value="+" onclick="addFormField(); return false;"><br>
									<table width="60%" id="Tabbank">
										<tr class='ui-widget-header' align="center">
											<td width="45%"><?php echo ($this->lang->line('sup_table_bank_name')); ?></td>
											<td width="45%"><?php echo ($this->lang->line('sup_table_bank_account')); ?></td>
											<td width="10%"><?php echo ($this->lang->line('sup_table_bank_opsi')); ?></td>
										</tr>
										<?php 
										$cid = 0; 
										foreach ($get_bank->result() as $bank):
										$cid = $cid + 1;
										echo "<tr id='$bank->bank_id'>
													<td><select name='bank[]' style='width: 180px;' disabled><option value='$bank->bank_id'>$bank->bank_name_singkat</option>$listbank</select></td>
													<td><input type='text' name='no_rekening[]' value='$bank->acc_no' readonly='1'></td>
													<td class='labelcell'><a href='#' onClick='removeEBank($bank->bank_id); return false;'>Remove</a></td>
											</tr>";
										endforeach;
										?>
									</table>
								</td>
							</tr>
						</table>
						</div>
					</div>
					<br>
			<center>
			
			
			<input type="button" id="bdesupplier" value="Proses" onclick="deactive_supplier()"/>
	
			<input type="button" id="button" value="<?php echo ($this->lang->line('sup_button_batal')); ?>" onclick="batal()"/>
			</center>
		</div>

</form>
	
<div id="dialog_desupplier" title="KONFIRMASI">
Yakin Data akan siap di proses ???
</div>

<?php 
	if ($Erow->sup_status != 1):
	?>
	<script language="javascript">
	$(document).ready(function() {
		var block_opt = {
			message: null,
			overlayCSS:  {
				backgroundColor: '#fff',
				opacity:	  	 0,
				cursor:		  	 'inherit'
			}
		}
		$('div#general,div#suppbank').block(block_opt);
	});
	</script>
<?php 
	endif;
?>