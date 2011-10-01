<?php 
$listbank = '';
	if ($list_bank->num_rows() > 0){
		foreach ($list_bank->result() as $bank):
			$listbank .= "<option value='$bank->bank_id'>$bank->bank_name_singkat</option>";
		endforeach;
	}
?>
<script type="text/javascript">
$(document).ready(function() {

$("#add_shortcut_berhasil").dialog({
		autoOpen: false,
		modal: true,
		buttons: {
	'<?=$this->lang->line('jquery_button_close');?>': function() {
			$(this).dialog('close');
			
			}
		}
		});


	$("#add_shortcut").dialog({
		autoOpen: false,
		modal: true,
		width: 350	
		});
	
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
		
	$(function(){
		// Tabs
	$('#tabssup').tabs();
	
	$("#cat2").sortable({
			connectWith: '.dropcat',
			cursor: 'move'
	});

	$('#cat1').sortable({
		connectWith: '.dropcat',
		update: function(){
		var order = $('#cat1').sortable('toArray');
			//alert (order);
			$('#setcat').val(order);
		}
	});	
			
	$("#cat2").disableSelection();
	});

// ======= bwt dialognya ============
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
// ==================akhir dialog ==============

	// bwt  nambahin datanya
	$('#frmsupplier').submit(function() {
		$('.saving').attr('disabled',true);
		$.ajax({
			type: 'POST',
			url: 'index.php/<?php echo $link_controller;?>/add_supplier',
			data: $(this).serialize(),
			success: function(data) {
				if (data == 'sukses'){
					$('#dialog_tambah').dialog('open');
					$('#supp_name').text(data);
					$('.saving').attr('disabled',false);
				}else{
					$('#dialog_kosong').dialog('open');
					$('#kesalahan').text(data); //nampilin apa aja yg masih kosong
					$('.saving').attr('disabled',false);
				}
		}
		});
		return false;
	});
});

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
				"<option value=''><?=$this->lang->line('combo_box_bank')?></option>"+
				"<?
					echo $listbank;
				?>"+
				+"</select>"+
           	"</td>"+
            "<td>"+
                "<input type='text' name='no_rekening[]'>"+
            "</td>"+
            "<td align='center' class='labelcell'>"+
                "<a href='#' onClick='removeFormField(" + id + "); return false;'><img border=\'0\' src=\'./asset/img_source/button_empty.png\'></a>"+
            "</td>"+
          "</tr>"
      );
    
	count = (count - 1) + 2;
    id = (id - 1) + 2;
    $("#bankid").val(id);
    $("#countid").val(count);
    }
}

(function($){
   // call setMask function on the document.ready event
	  $(function(){
	       $('input:text').setMask();
	   }
	);
})(jQuery);

//====================== shortcut add term ====================
function add_term(){
	$.ajax({
		type: 'POST',
		url: 'index.php/<?php echo $link_controller_term;?>/term_frm',
		data: $(this).serialize(),
		success: function(data) {
			$('#frmview').html(data);
			$('#stat').val('2');
			$('#add_shortcut').dialog('open');
	}
	});
	return false;
}
</script>

<form id="frmsupplier">
		<!-- Tabs -->
		<div id="tabssup">
			<ul>
				<li><a href="#general"><?php echo ($this->lang->line('sup_tab_tambah_general')); ?></a></li>
				<li><a href="#suppcat"><?php echo ($this->lang->line('sup_tab_tambah_kategori')); ?></a></li>
				<li><a href="#suppbank"><?php echo ($this->lang->line('sup_tab_tambah_pay')); ?></a></li>
			</ul>
			<div id="general">
			<table>
				<tr>
					<td width="48%">
					<table>
						<tr>
							<td class="labelcell"><?php echo ($this->lang->line('sup_input_nama')); ?></td>
							<td class="fieldcell">: <input type="text" name="idsupp" id="idsupp" maxlength="100" /></td>
						</tr>
						<tr>
							<td class="labelcell"><?php echo ($this->lang->line('sup_input_legal')); ?></td>
							<td class="fieldcell">: <select name="legal" id="legal" >
								<option value=''><?=$this->lang->line('combo_box_legalitas')?></option>
								<?php if ($list_leg->num_rows() > 0):
										foreach ($list_leg->result() as $row): 
											echo "<option value='$row->legal_id'>$row->legal_name</option>";
										endforeach;
									else:
										echo $this->lang->line('data_empty');
									endif; ?>
							</select></td>
						</tr>
						<tr>
							<td class="labelcell"><?php echo ($this->lang->line('sup_input_npwp')); ?></td>
							<td class="fieldcell">: <input type="text" name="npwp" id="npwp" maxlength="19" alt="npwp" /></td>
						</tr>
						<tr>
							<td class="labelcell"><?php echo ($this->lang->line('sup_input_alamat')); ?></td>
							<td class="fieldcell">: <textarea rows="2" cols="18" name='alamat' id='alamat'></textarea></td>
						</tr>
						<tr>
							<td class="labelcell"><?php echo ($this->lang->line('sup_input_negara')); ?></td>
							<td class="fieldcell">: <select name="negara" id="negara">
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
							<td class="labelcell"><?php echo ($this->lang->line('sup_input_provinsi')); ?></td>
							<td class="fieldcell"><div id="rprov" style="padding: 0px;">: <select><option value=""><?=$this->lang->line('combo_box_provinsi')?></option></select> 
							</div> </td>
						</tr>
						<tr>
							<td class="labelcell"><?php echo ($this->lang->line('sup_input_kota')); ?></td>
							<td class="fieldcell"><div id="rkota" style="margin-left: 0px;">: <select>
							<option value=''><?=$this->lang->line('combo_box_kota')?></option>
							</select></div></td>
						</tr>
					</table>
					</td>
					<td width="4%"></td>
					<td width="48%" valign ="top">
					<table>
						<tr>
							<td class="labelcell"><?php echo ($this->lang->line('sup_input_phone1')); ?></td>
							<td class="fieldcell">: <input type="text" name="phone1" id="phone1" maxlength="100" /></td>
						</tr>
						<tr>
							<td class="labelcell"><?php echo ($this->lang->line('sup_input_phone2')); ?></td>
							<td class="fieldcell">: <input type="text" name="phone2" id="phone2" maxlength="100" /></td>
						</tr>
						<tr>
							<td class="labelcell"><?php echo ($this->lang->line('sup_input_phone3')); ?></td>
							<td class="fieldcell">: <input type="text" name="phone3" id="phone3" maxlength="100" /></td>
						</tr>
						<tr>
							<td class="labelcell"><?php echo ($this->lang->line('sup_input_hp')); ?></td>
							<td class="fieldcell">: <input type="text" name="handphone" id="handphone" /></td>
						</tr>
						<tr>
							<td class="labelcell"><?php echo ($this->lang->line('sup_input_fax1')); ?></td>
							<td class="fieldcell">: <input type="text" name="fax" id="fax" maxlength="100" /></td>
						</tr>
						<tr>
							<td class="labelcell"><?php echo ($this->lang->line('sup_input_email')); ?></td>
							<td class="fieldcell">: <input type="text" name="email" id="email" maxlength="100" /></td>
						</tr>
					</table>
					</td>
				<tr>
			</table>
			</div>
			<div id="suppcat">
					<div style="float: left;"><font style="font: 13px Verdana;"><?php echo ($this->lang->line('sup_daftar_kategori2')); ?></font></div>
					<div style="float: left; width: 50px;">&nbsp;</div>
					<div><font style="font: 13px Verdana;"><?php echo ($this->lang->line('sup_daftar_kategori1')); ?></font></div><br>	
					<ul id="spaceleft"></ul>
					<ul id="cat2" class='dropcat'>
						<?php if ($list_cat->num_rows() > 0):
							foreach ($list_cat->result() as $cat): 
								echo "<li id='$cat->cat_id' value='$cat->cat_name'>$cat->cat_name</li>";
							endforeach;
							else:
								echo $this->lang->line('data_empty');
							endif; ?>
					</ul>
					<ul id="space"></ul>
					<ul id="cat1" class='dropcat'></ul>
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
					<td width="75%" class="fieldcell"><select name="term" id="termlist">
					<option value=''><?=$this->lang->line('combo_box_lama_kredit')?></option>
								<?php if ($list_term->num_rows() > 0):
										foreach ($list_term->result() as $term): 
											echo "<option value='$term->term_id'>$term->term_id_name</option>";
										endforeach;
									else:
										echo $this->lang->line('data_empty');
									endif; ?>
					</select>
					<a href="javascript:void(0)" onclick="add_term()"><img border="0" src="./asset/img_source/add1.gif"></a>
					</td>
				</tr>
				<tr>
					<td class="labelcell" valign="top"><?php echo ($this->lang->line('sup_input_bank')); ?></td>
					<td valign="top">:</td>
					<td class="fieldcell" ><input type="button" name="term" value="+" onclick="addFormField(); return false;"><br>
						<table width="60%" id="Tabbank">
							<tr class='ui-widget-header' align="center">
								<td width="45%"><?php echo ($this->lang->line('sup_table_bank_name')); ?></td>
								<td width="45%"><?php echo ($this->lang->line('sup_table_bank_account')); ?></td>
								<td width="10%"><?php echo ($this->lang->line('sup_table_bank_opsi')); ?></td>
							</tr>
							<tr id="row0">
								<td><select name="bank[]" style="width: 180px;"><option value=''><?=$this->lang->line('combo_box_bank')?></option><?php echo $listbank; ?></select></td>
								<td><input type="text" name="no_rekening[]"></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			</div>
		</div>
<center>
<br>
<input type="hidden" name="bankid" id="bankid" value="3">
<input type="hidden" name="countid" id="countid" value = "1">
<input type="submit" id="submit" class="saving" value="<?php echo ($this->lang->line('sup_button_submit')); ?>"/>
</center>
</form>


<!-- ==== bwt dialog konfirmasinya ===== -->

<div id="dialog_kosong" title="<?=($this->lang->line('jquery_dialog_peringatan'));?>">
	<p id="kesalahan"></p>
</div>

<div id="dialog_tambah" title="<?=($this->lang->line('jquery_dialog_konfirmasi'));?>">
	<p><?php echo ($this->lang->line('supplier_tmbh_judul_input'));?> </p><!--FONT COLOR="red"><b><p id="supp_name"> </p> </b></FONT--><p><?php echo ($this->lang->line('jquery_dialog_tambah_berhasil'));?></p>
</div>

<div id="add_shortcut" title="<?=($this->lang->line('jquery_dialog_shortcut'));?>">
	<?php echo ($this->lang->line('sup_input_term')); ?><br /><br />
	<div id="frmview"></div>
</div>

<div id="add_shortcut_berhasil" title="<?=($this->lang->line('jquery_dialog_konfirmasi'));?>">
	<div id="shortcut_behasil"></div>
</div>