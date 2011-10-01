<?php 
if($status=='EDIT'):
	if ($pro_data->num_rows()>0):
		$where['pro_id']= $pro_data->row()->pro_id;
		$where['satuan_id']= $pro_data->row()->um_id;
		$unit_sub = $this->tbl_unit->get_unit_satuan($where);
	endif;
endif;
?>
<script language="javascript">
<?php
if ($status=='EDIT'):
?>
masking('.number');
masking_reload('.number');
<?php
endif;
?>
var sat_row_num=<?=($status=='EDIT')?($unit_sub->num_rows()+1):(1)?>;
var sat_row_id,sat_table = $('#TblSatuan'),i=1;
function um_id_change(val) {
	var sat,digit;
	sat = val.split('_');
	val = sat[0];
	digit = sat[1];
	
	$('.satuan_id2').val(val);
	
	$('.number').each(function() {
		$(this).removeAttr('digit_decimal');
		$(this).attr('digit_decimal',digit);
		//unmasking('.number');
		var ids = $(this).attr('id');
		var vals = $(this).val();
		if (vals != ''){
			masking('.number');
			$(this).val($.fn.autoNumeric.Format(ids,vals));
		}
	});
	return false;
}

function calc_um_sat() {
	var validate=true;
	var divs = $("select.um_sub").get() ;
	$('tr',sat_table).each(function(i){
		var um_sub = $('select.um_sub',this);
		var um_sub_val = $('input.um_sub_val',this);
		
		validate = validate && validation_value(um_sub,'Jenis Satuan row '+(i+1),'0','harus dipilih',2);
		validate = validate && validation_value(um_sub_val,'Nilai Satuan row '+(i+1),'0','harus dipilih',2,'number');
	});
	
	if (validate) {
		return true;
	}else {
		return false;
	}
	return false;
}

function addRowSatToTable() {
	var um_val = $('#um_id').val();
	var digit = um_val.split('_');
	digit = digit[1];
	
	if (um_val != '0'){
		if (sat_table.hide()) {
			sat_table.show();
		}
		if(sat_row_num <= 5) {
			$('#TblSatuan').append(tr_sat_content(sat_row_num,digit));	
			sat_row_num = sat_row_num + 1;
			var mas_unit = $('#um_id').val();
			um_id_change(mas_unit);
			masking('.number');
		}
	} else {
		alert('Unit satuan belum dipilih');
	}
	return false;
}

function removeRowSatFromTable(sat_row_id) {
	$('#sat_row_'+sat_row_id).remove();
	if (sat_row_num > 0){
		sat_row_num = sat_row_num - 1;
	}else {
		sat_row_num = 1;
		sat_table.hide();
	}
	return false;			
}

function tr_sat_content(sat_row_id,digit) {
	var row_content = '<tr id="sat_row_'+sat_row_id+'"><td width="150" align="center" class="fieldcell">'+
  	'<select name="um_sub[]" id="um_sub" class="um_sub">'+
  	'<option value="0">--Pilih Satuan--</option>'+
  	<?php if ($unit_data->num_rows()>0): foreach ($unit_data->result() as $row_unit):?>
   	'<option value="<?=$row_unit->satuan_id?>"><?=$row_unit->satuan_name?></option>'+
   	<?php endforeach;endif;?>
    '</select></td>'+
  	'<td width="20" align="center">=</td>'+
  	'<td width="60" align="center" class="fieldcell">'+
  	'<input digit_decimal='+digit+' id="um_sub_val_'+sat_row_id+'" name="um_sub_val[]" class="um_sub_val number" type="text" size="10"></td>'+
  	'<td class="fieldcell">'+
  	'<select disabled="disabled" class="satuan_id2"><option value="0">--Pilih Unit--</option>'+
  	<?php if ($unit_data->num_rows()>0): 
  		foreach ($unit_data->result() as $row_unit):?>
   	'<option value="<?=$row_unit->satuan_id?>" <?=(($status=='EDIT')&&($pro_data->row()->um_id==$row_unit->satuan_id))?('SELECTED'):('')?>><?=$row_unit->satuan_name?></option>'+
    <?php 
    	endforeach;
    endif;?>
    '</select></td>'+
	'<td><INPUT TYPE="button" value="Hapus Satuan" onclick="removeRowSatFromTable('+sat_row_id+')"></td>'+
    '</tr>';
   return row_content;
}


// ----------- SHORTCUT ADD SATUAN --------- //
function add_sat() {
	$.ajax({
		type: 'POST',
		url: 'index.php/satuan/satuan_frm',
		success: function(data) {
			$('.dialog_shortcut').html('').html(data).dialog({
				title : '<?=$this->lang->line('satuan_tab_tambah')?>',
				width: 'auto',
				height: 'auto',
				resizable: false,
				modal:true,
				position:'center',
				buttons: {
					"<?=$this->lang->line('close')?>": function(){
						$(this).dialog('destroy');
					}
				}
			});
		}
	});
	return false;
}

$(document).ready(function() {
	//um_table.hide();
	
});
</script>
<p id="aa"></p>
<table border="0" cellspacing="2" cellpadding="2">
			<tr>
			  <td width="150" class="labelcell">Satuan Terkecil</td>
			  <td valign="top">:</td>
			  <td class="fieldcell">
			  <SELECT NAME="um_id" ID="um_id"  class="required" onkeyup="um_id_change(this.value);" onchange="um_id_change(this.value);">
			   <option value="0">--Pilih Unit--</option>
			   <?php if ($unit_data->num_rows()>0):
			   		foreach ($unit_data->result() as $row_unit):
			   ?>
			   		<option value="<?=$row_unit->satuan_id?>_<?=$row_unit->satuan_format?>" <?=(($status=='EDIT')&&($pro_data->row()->um_id==$row_unit->satuan_id))?('SELECTED'):('')?>><?=$row_unit->satuan_name?></option>
			   <?php 
					if ($pro_data->row()->um_id == $row_unit->satuan_id):
						$digit = $row_unit->satuan_format;
					endif;
					endforeach;
			   endif;?>
			  </SELECT>
			  <!--a href="javascript:void(0)" onclick="add_sat()" title="<//=$this->lang->line('satuan_tab_tambah')?>"><img border="0" src="./asset/img_source/add1.gif"></a-->
			  </td>
			</tr>
			<tr>
			  <td>&nbsp;</td>
			  <td>&nbsp;</td>
			  <td>
			  <table width="100%" border="0" cellspacing="0" cellpadding="0">
				 <tr>
					<td><INPUT TYPE="button" value="Tambah Satuan" onclick="addRowSatToTable();"> 
					</td>
				 </tr>
		      </table>
			  </td>
			</tr>
			<tr>
			  <td>&nbsp;</td>
			  <td>&nbsp;</td>
			  <td>
			  
			  <table width="80%" border="0" cellspacing="0" cellpadding="0" id="TblSatuan" <?=($status=='EDIT')?(''):('style="display:none"')?>>
		<?php if($status=='EDIT'):
			  	if ($unit_sub->num_rows() > 0):
			  	$i=1;
			  	foreach ($unit_sub->result() as $pro_data_sub):
				if ($pro_data->row()->um_id != $pro_data_sub->satuan_unit_id):
			  ?>
			  <tr id="sat_row_<?=$i?>">
			  	<td width="150" align="center" class="fieldcell">
			  	<select name="um_sub[]" id="um_sub" class="um_sub"><option value="0">--Pilih Satuan--</option>
			  	 <?php 
			  	 	if ($unit_data->num_rows()>0):
			   		foreach ($unit_data->result() as $row_unit):
			   ?>
			   		<option value="<?=$row_unit->satuan_id?>" <?=($pro_data_sub->satuan_unit_id==$row_unit->satuan_id)?('SELECTED'):('')?>><?=$row_unit->satuan_name?></option>
			   <?php endforeach;
			   		endif;?>
			   </select>			  	</td>
			  	<td width="20" align="center">=</td>
			  	<td width="70" align="center" class="fieldcell"><input id="um_sub_val_<?=$i?>" digit_decimal="<?=$digit?>" name="um_sub_val[]" class="um_sub_val number" type="text" size="10" value="<?=($status=='EDIT')?($pro_data_sub->value):('')?>"></td>
			  	<td class="fieldcell">
			  	<select disabled="disabled" class="satuan_id2">
				<option value="0">--Pilih Unit--</option>
			  	 <?php 
			  	 	if ($unit_data->num_rows()>0):
			   		foreach ($unit_data->result() as $row_unit):
			   ?>
			   		<option value="<?=$row_unit->satuan_id?>" <?=(($status=='EDIT')&&($pro_data->row()->um_id==$row_unit->satuan_id))?('SELECTED'):('')?>><?=$row_unit->satuan_name?></option>
			   <?php endforeach;
			   		endif;?>
			   </select>
			  	</td>
			  	<td><INPUT TYPE="button" value="Hapus Satuan" onclick="removeRowSatFromTable(<?=$i?>)"></td>
			  </tr>
			  <?php 
			  	$i++;
				endif;
			  	endforeach;
			  	endif;
			  endif;?>
			  </table>
	
			  </td>
			</tr>
		</table>