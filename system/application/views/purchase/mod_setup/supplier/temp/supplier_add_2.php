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

	$('#negara').change(function() {
		var a = $('#negara').val();
		$.ajax({
			type: 'POST',
			url: 'index.php/Supplier/get_provinsi',
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
		
	$('#frmsupplier').submit(function() {
		$.ajax({
			type: 'POST',
			url: 'index.php/Supplier/add_supplier',
			data: $(this).serialize(),
			success: function(data) {
				if (data == 'sukses'){
					window.location = 'index.php/Supplier/index';
					//$('#main_content').html(data);
					/*$.ajax({
						type: 'POST',
						url: 'index.php/Supplier/supplier_flexigrid',
						data: $(this).serialize(),
						success: function(response) {
						$('#datacontent').html(response);
						}
					});*/
				}else{
					alert (data);
				}
		}
		});
		return false;
	});
});

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
				"<option value=''>-pilih-</option>"+
				"<?
					echo $listbank;
				?>"+
				+"</select>"+
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

function removeFormField(id) {
	$('#row'+id).remove();
	var count = $('#countid').val();
		count = count - 1;
		$('#countid').val(count);
}

(function($){
   // call setMask function on the document.ready event
	  $(function(){
	       $('input:text').setMask();
	   }
	);
})(jQuery);


function getcat(obj){
	var id = obj.value;
	var name = document.getElementById('n'+id).value;
		$('#'+id).remove();
		$('#cat1').append("<li id='" + id + "'><input type='hidden' id='n" + id +"' value='" + name + "'><input type='checkbox' value='" + id + "' onclick='remcat(this)'>" + name + "</li>");
}

function remcat(obj){
	var id = obj.value;
	var name = document.getElementById('n'+id).value;
		$('#'+id).remove();
		$('#cat2').append("<li id='" + id + "'><input type='hidden' id='n" + id +"' value='" + name + "'><input type='checkbox' value='" + id + "' onclick='getcat(this)'>" + name + "</li>");
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
								<option value=''>[pilih - Legalitas]</option>
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
							<td class="fieldcell">: <input type="text" name="npwp" id="npwp" maxlength="19" alt="npwp" /></td>
						</tr>
						<tr>
							<td class="labelcell"><?php echo ($this->lang->line('sup_input_alamat')); ?></td>
							<td class="fieldcell">: <textarea rows="2" cols="18" name='alamat' id='alamat'></textarea></td>
						</tr>
						<tr>
							<td class="labelcell"><?php echo ($this->lang->line('sup_input_negara')); ?></td>
							<td class="fieldcell">: <select name="negara" id="negara">
							<option value=''>[pilih - Negara]</option>
								<?php if ($list_neg->num_rows() > 0):
										foreach ($list_neg->result() as $rowz): 
											echo "<option value='$rowz->negara_id'>$rowz->negara_name</option>";
										endforeach;
									else:
										echo "Empty";
									endif; ?>
							</select></td>
						</tr>
						<tr>
							<td class="labelcell"><?php echo ($this->lang->line('sup_input_provinsi')); ?></td>
							<td class="fieldcell"><div id="rprov" style="padding: 0px;">: <select><option value="">[pilih - Provinsi]</option></select> 
							</div> </td>
						</tr>
						<tr>
							<td class="labelcell"><?php echo ($this->lang->line('sup_input_kota')); ?></td>
							<td class="fieldcell"><div id="rkota" style="margin-left: 0px;">: <select>
							<option value=''>[pilih - Kota]</option>
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
					<ul id="cat2" class='dropcat'>
						<?php if ($list_cat->num_rows() > 0):
							foreach ($list_cat->result() as $cat): 
								echo "<li id='$cat->cat_id'><input type='hidden' id='n".$cat->cat_id."' value='$cat->cat_name'><input type='checkbox' value='$cat->cat_id' onClick='getcat(this)'>$cat->cat_name</li>";
							endforeach;
							else:
								echo "Empty";
							endif; ?>
					</ul>
					<ul id="space"><input type="button" value=">>" oncli></ul>
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
					<td width="75%" class="fieldcell"><select name="term">
					<option value=''>[pilih - Term]</option>
								<?php if ($list_term->num_rows() > 0):
										foreach ($list_term->result() as $term): 
											echo "<option value='$term->term_id'>$term->term_id</option>";
										endforeach;
									else:
										echo "Empty";
									endif; ?>
					</select></td>
				</tr>
				<tr>
					<td class="labelcell" valign="top"><?php echo ($this->lang->line('sup_input_bank')); ?></td>
					<td valign="top">:</td>
					<td class="fieldcell" ><input type="button" name="term" value="+" onclick="addFormField(); return false;"><br>
						<table width="60%" id="Tabbank">
							<tr class='ui-widget-header' align="center">
								<td width="45%"><?php echo ($this->lang->line('sup_table_bank_name')); ?></td>
								<td width="45%"><?php echo ($this->lang->line('sup_table_bank_account')); ?></td>
								<td widht="10%"><?php echo ($this->lang->line('sup_table_bank_opsi')); ?></td>
							<tr>
							<tr id="row1">
								<td><select name="bank[]" style="width: 180px;"><option value=''>-Pilih Bank-</option><?php echo $listbank; ?></select></td>
								<td><input type="text" name="no_rekening[]"></td>
							<tr>
						</table>
					</td>
				</tr>
			</table>
			</div>
		</div>
<center>
<input type="hidden" name="bankid" id="bankid" value="1">
<input type="hidden" name="countid" id="countid" value = "1">
<input type="submit" id="submit" value="<?php echo ($this->lang->line('sup_button_submit')); ?>"/>
</center>
</form>
