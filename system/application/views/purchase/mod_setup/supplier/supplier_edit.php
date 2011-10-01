<?php
			$listbank = '';
				if ($list_bank->num_rows() > 0){
					foreach ($list_bank->result() as $bank):
						$listbank .= "<option value='$bank->bank_id'>$bank->bank_name_singkat</option>";
					endforeach;
				}
?>
<script type="text/javascript">
function deactive_supplier() {
	var sup_id = $('#idsupp').val();
	var sup_name = $('#namesupp').val();
	$('.saving').attr('disabled',true);
	$('#sup_ids').val(sup_id);
	$('#sup_names').val(sup_name);
	$("#dialog_desupplier").dialog('open');
}

$(document).ready(function() {
		// Tabs
		$('#tabsedit').tabs();

		$('#negara').change(function() {
			var a = $('#negara').val();
			$.ajax({
				type: 'POST',
				url: 'index.php/<?php echo $link_controller;?>/get_provinsi',
				data: "id="+a,
				success: function(data) {
					$('#rprov').html(data);
					//alert (data);
				}
			});
			return false;
		});

// =============bwt dialognya ================
		$("#dialog_rubah").dialog({
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
			buttons: {
				"BATAL": function() {
					$(this).dialog('close');
					$('.saving').attr('disabled',false);
				},
				"USULKAN" : function() {
					// 
					
					var infos;
					$.ajax({
						type: 'POST',
						url: 'index.php/<?php echo $link_controller;?>/deactive_supplier',
						data: $('#desupplier_form').serialize(),
						success: function(data) {
							if (data) {
								$("#dialog_desupplier").dialog('close');
								infos = 'NON AKTIF PEMASOK '+data+' BERHASIL DIAJUKAN !!!';
								$(".dialog_informasi").html('').html(infos)
								.dialog('option','buttons',{
									"OK": function() {
										$(this).dialog('close');
										batal();
									}
								})
								.dialog('open');
							} else {
								$("#dialog_desupplier").dialog('close');
								infos = 'NON AKTIF PEMASOK TIDAK BERHASIL DIAJUKAN !!!';
								$(".dialog_informasi").html('').html(infos)
								.dialog('option','buttons',{
									"OK": function() {
										$(this).dialog('close');
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
		});
// ==akhir dialog nya========

		
		$(function(){
			// Tabs
		$('#tabssup').tabs();
		
		$("#cat2").sortable({	//2
				connectWith: '.dropcat',
				cursor: 'move'
		});

		$('#cat1').sortable({	//1
			connectWith: '.dropcat',
			update: function(){
			var order = $('#cat1').sortable('toArray'); //1
				//alert (order);
				$('#setcat').val(order);
			}
		});	
				
		$("#cat2").disableSelection(); //2
		});
			
		$('#frmeditsup').submit(function() {
			$('#bdesupplier').attr('disabled',true);
			$.ajax({
				type: 'POST',
				url: 'index.php/<?php echo $link_controller;?>/supplier_update',
				data: $(this).serialize(),
				success: function(data) {
					if (data == 'sukses'){
						$('#dialog_rubah').dialog('open');
						$('#bdesupplier').attr('disabled',false);
					}else{
						$('#dialog_kosong').dialog('open');
						$('#kesalahan').text(data); //nampilin apa aja yg masih kosong
						$('#bdesupplier').attr('disabled',false);
					}
			}
			});
			return false;
		});
	
});	

function removeEBank(id) {
	$('#'+id).remove();
	var x = $('#countid').val();
		x = x -1;
		$('#countid').val(x);
}

function removeFormField(id) {
	$('#row'+id).remove();
	var count = $('#countid').val();
		count = count - 1;
		$('#countid').val(count);
}

function addFormField() {
    var id = $("#bankid").val();
    var count = $('#countid').val();
    if (count == 5) {
        alert ('account maksimal 5');
    }else{
    	$("#Tabbank").append(
    	    "<tr id='row" + id + "'>"+
    	       	"<td>"+
    	           	"<select name='bank[]' id='cacat" + id + "' style='width: 180px;'>"+
    				"<option value=''>-Pilih Bank-</option>"+
    				"<?
					echo $listbank;
					?>"+
    				"</select>"+
    	       	"</td>"+
    	        "<td>"+
    	            "<input type='text' name='no_rekening[]'>"+
    	        "</td>"+
    	            "<td align='center' class='labelcell'>"+
    	            "<a href='#' onClick='removeFormField(" + id + "); return false;'>Remove</a>"+
    	        "</td>"+
    	     "</tr>"
    	 );
   	 
    	count = (count - 1) + 2;
        id = (id - 1) + 2;
        $("#bankid").val(id);
        $("#countid").val(count);
    }
}

function batal() {
	window.location = 'index.php/<?php echo $link_controller;?>/index';
}

(function($){
	   // call setMask function on the document.ready event
		  $(function(){
		       $('input:text').setMask();
		   }
		);
})(jQuery);
</script>
		<!-- Tabs -->
		<h2><?php echo ($this->lang->line('sup_judul_halaman')); ?></h2>
		<div id="tabsedit">
			<ul>
				<li><a href="#edit-2"><?php echo ($this->lang->line('sup_tab_edit')); ?></a></li>
			</ul>
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
			<form id="frmeditsup">
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
							<?php if($Erow->sup_status != 1): ?>
							<tr><td>&nbsp;</td></tr>
							<tr>
								<td colspan=3 class="labelcell">
									<div class="ui-widget-content ui-corner-all labelcell" style="border:2px solid red;padding:5px;overflow:auto">
									<i>Keterangan Non Aktif Pemasok</i> : <br><br>
									<div style="padding-left:25px"><?=$Erow->deactive_note?></div>
									<br>
									</div>
								</td>
							</tr>
							<?php endif;?>
						</table>
						</div>
						<div id="suppcat">
								<div style="float: left;"><font style="font: 13px Verdana;"><?php echo ($this->lang->line('sup_daftar_kategori1')); ?></font></div>
								<div style="float: left; width: 50px;">&nbsp;</div>
								<div><font style="font: 13px Verdana;"><?php echo ($this->lang->line('sup_daftar_kategori2')); ?></font></div><br>
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
								<ul id="cat2" class='dropcat'>
									<?php
										foreach ($getcat2->result() as $cat2): 
											echo "<li id='$cat2->cat_id' value='$cat2->cat_name'>$cat2->cat_name</li>";
										endforeach;
									?>
								</ul>
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
			<input type="hidden" name="bankid" id="bankid" value="1">
			<input type="hidden" name="countid" id="countid" value = "<?php echo $cid; ?>">
			<?php if($Erow->sup_status == 1): ?>
			<input type="submit" id="submit" value="<?php echo ($this->lang->line('sup_button_edit')); ?>" class="saving"/>
			<input type="button" id="bdesupplier" value="Non Aktif Pemasok" onclick="deactive_supplier()"/>
			<?php endif; ?>
			<input type="button" id="button" value="<?php echo ($this->lang->line('sup_button_batal')); ?>" onclick="batal()"/>
			</center>
			</form>
		</div>
	</div>
	
	
	<!-- ==== bwt dialog konfirmasinya ===== -->

<div id="dialog_kosong" title="<?=($this->lang->line('jquery_dialog_peringatan'));?>">
	<p id="kesalahan"></p>
</div>

<div id="dialog_rubah" title="<?=($this->lang->line('jquery_dialog_konfirmasi'));?>">
	<?php echo ($this->lang->line('contact_tmbh_judul_input'));?> <FONT COLOR="red"><b><p id="name"> </p> </b></FONT><?php echo ($this->lang->line('jquery_dialog_rubah_berhasil'));?>
</div>

<div id="dialog_desupplier" title="NON AKTIF PEMASOK (ALASAN)">
<form id="desupplier_form">
	<input type="hidden" name="sup_id" id="sup_ids">
	<input type="hidden" name="sup_name" id="sup_names">
	<textarea name="alasan_deactive" rows="5"></textarea>
</form>
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
			},
			baseZ: 1
		}
		$('div#general,div#suppcat,div#suppbank').block(block_opt);
	});
	</script>
<?php 
	endif;
?>