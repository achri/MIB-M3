<?php
if($status=='EDIT'):
	if ($pro_data->num_rows()>0):
		$where['prc_master_supplier_product.pro_id'] = $pro_data->row()->pro_id;
		$prosup_data = $this->tbl_produk->get_sup_pro($where);
	endif;
endif;
?>
<script language="javascript">
var sup_row_num=<?=($status=='EDIT')?($prosup_data->num_rows()+1):(1)?>;
var sup_row_id,sup_table = $('#TblSupCode');

function set_radio(rows,vals){
	if (vals == 0) {
		$("#sup_code_"+rows).attr("readonly","readonly").val('');
	}else{
		$("#sup_code_"+rows).removeAttr("readonly").val('');
	}
};

function calc_supp() {
	var validate=true,
		divs = $("select.is_stockJoin").get(),
		jon = $('#is_stockJoin').val();
	$('tr',sup_table).each(function(i){
		var sup_name = $('input.sup_name',this);
		//var sup_code = $('input.sup_code',this);
		
		validate = validate && validation_value(sup_name,'Supplier ke-'+(i),'','harus ditentukan',3);
		//validate = validate && validation_Value(sup_code,'Kode '+(i+1),'','harus dipilih',2);
	});
	
	if (jon == '0' && sup_row_num <= 1) {
		//alert('Kartu stok Supplier belum di daftar !!!');
		var infos = "Kartu stok pemasok belum di daftar !!!";
		$('#dlg_peringatan').html('').html(infos).dialog('open');
		$('#tabs_form').tabs('select',3);
		return false;
	} else {
	
		if (validate) {
			return true;
		}else {
			return false;
		}

	}
}

function cek_join(val) {
	if (val=='0') {
		addRowSupToTable();
	}
	else {
		for (i=1;i<sup_row_num;i++){
			$('#sup_row_'+i).remove();
		}
		sup_table.hide();
	}
	return false;
}

function addRowSupToTable(){
	var jon = $('#is_stockJoin').val();
	if (jon != '2') {
		if (jon != '1'){
			if (sup_table.hide()) {
				sup_table.show();
			}
			if(sup_row_num <= 5) {
				$('#TblSupCode').append(tr_sup_content(sup_row_num));	
				sup_row_num = sup_row_num + 1;
			}
		} else {
			//alert('Kartu stok Supplier harus Spesifik');
			var infos = "Kartu stok pemasok harus Spesifik !!!";
			$('#dlg_peringatan').html('').html(infos).dialog('open');
		}
	}
	else {
		//alert('Kartu Stok Supplier belum dipilih');
		var infos = "Kartu stok pemasok belum dipilih !!!";
		$('#dlg_peringatan').html('').html(infos).dialog('open');
	}
	return false;
}



function rem_supp(sup_row_id) {
	var totrow = $('.cek_tr').size();
	if (totrow > 1)
	{
		$('#sup_row_'+sup_row_id).remove();
		if (sup_row_num > 2){
			sup_row_num = sup_row_num - 1;
		}else {
			sup_row_num = 1;
			sup_table.hide();
		}
	}
	return false;	
}

function tr_sup_content(sup_row_id) {
	var row_content = 
	'<tr id="sup_row_'+sup_row_id+'" bgcolor="lightgray" class="cek_tr"><td width="5%" align="center">'+
	'<a class="add_supp" onclick="add_supp('+sup_row_id+');"><img src="<?=base_url()?>asset/img_source/icon_lkp.gif" border="0"/></a>'+ 
	'<a class="rem_supp" onclick="rem_supp('+sup_row_id+');"><img src="<?=base_url()?>asset/img_source/icon_del.gif" border="0"/></a>'+
	'</td><td width="40%">'+
	'<INPUT TYPE="text" NAME="sup_name[]" class="sup_name" style="width:300px;" value="" disabled="disabled">'+
	'<INPUT TYPE="hidden" NAME="sup_id[]" value="">'+
	'</td><td width="50%">'+
	'<small>'+
	'<input ID="sup_cek1_'+sup_row_id+'" checked type="radio" value="0" name="radio_'+sup_row_id+'" onclick="set_radio('+sup_row_id+',this.value)">Tidak'+ 
	'<input ID="sup_cek2_'+sup_row_id+'" type="radio" value="1" name="radio_'+sup_row_id+'" onclick="set_radio('+sup_row_id+',this.value)">Ada'+
	'</small>'+
	'<INPUT readonly TYPE="text" NAME="sup_code[]" ID="sup_code_'+sup_row_id+'" class="sup_code" style="width:300px;" value=""></td>'+
	'</tr>';
	return row_content;
}


// ----------- SHORTCUT ADD SUPPLIER --------- //
function add_sup() {
	$.ajax({
		type: 'POST',
		url: 'index.php/supplier/supplier_frm',
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
	//sup_table.hide();
	//$('#supplier_add_dialog').dialog('destroy');
	
	
});
</script>

<table width="100%" border="0" cellspacing="2" cellpadding="2">
		  <tr>
		    <td>
			  <table border="0" cellspacing="0" cellpadding="0">
				 <tr>
					<td width="200" colspan="2" class="labelcell">
					<!-- INPUT TYPE="button" value="Del Row" onclick="removeRowSupFromTable();"-->
					Kartu Stok Pemasok : 
					</td>
					<td class="fieldcell">
					<SELECT NAME="is_stockJoin" id="is_stockJoin" class="required" onchange="cek_join(this.value);">
					<option value="2">--Pilih--</option>
					<option value="1" <?=(($status=='EDIT')&&($pro_data->row()->is_stockJoin=='1'))?('SELECTED'):('')?>>-GENERAL</option>
					<option value="0" <?=(($status=='EDIT')&&($pro_data->row()->is_stockJoin=='0'))?('SELECTED'):('')?>>-SPESIFIK</option>
					</SELECT>
					</td>
				 </tr>
				 
		      </table>
			</td>
		  </tr>
		  <tr>
		    <td>
			  <table class="ui-widget-content ui-corner-all" width="100%" border="0" cellspacing="1" cellpadding="0" id="TblSupCode" <?=(($status=='EDIT')&&($prosup_data->num_rows()>0))?(''):('style="display:none"')?>>
			  <thead>
			  	<tr>
			  		<th colspan="3">
			  		<INPUT TYPE="button" value="<?=$this->lang->line('add_pro_supplier')?>" onclick="addRowSupToTable();">
					<!--a href="javascript:void(0)" onclick="add_sup()" title="<//?=$this->lang->line('add_supplier')?>"><img border="0" src="./asset/img_source/add1.gif"></a-->
					</th>
			  	</tr>
			     <tr bgcolor="#cccccc" class="ui-state-default">
					<th width="5%"></th>
					<th width="40%"><small>Nama Pemasok</small></th>
					<th width="50%"><small>Kode Barang Pemasok</small></th>
				 </tr>
			  </thead>
			  <tbody>  
			  <?php if($status=='EDIT'):
			  $i=1;
			  if ($prosup_data->num_rows() > 0):
			  foreach ($prosup_data->result() as $prosup_row):
			  ?>
			  	<tr id="sup_row_<?=$i?>" bgcolor="lightgray" class="cek_tr"><td width="5%" align="center">
				<a class="add_supp" onclick="add_supp(<?=$i?>);"><img src="<?=base_url()?>asset/img_source/icon_lkp.gif" border="0"/></a> 
				<a class="rem_supp" onclick="rem_supp(<?=$i?>);"><img src="<?=base_url()?>asset/img_source/icon_del.gif" border="0"/></a>
				</td><td width="40%">
				<INPUT TYPE="text" NAME="sup_name[]" ID="sup_name" style="width:300px;" value="<?=$prosup_row->legal_name.'. '.$prosup_row->sup_name?>" disabled="disabled">
				<INPUT TYPE="hidden" NAME="sup_id[]" ID="sup_id" value="<?=$prosup_row->sup_id?>">
				</td><td width="50%">
				<small>
				<input ID="sup_cek1_<?=$i?>" <?=($prosup_row->sup_pro_code=='')?('checked'):('')?> type="radio" value="0" name="radio_<?=$i?>" onclick="set_radio('<?=$i?>',this.value)">Tidak 
				<input ID="sup_cek2_<?=$i?>" <?=($prosup_row->sup_pro_code!='')?('checked'):('')?> type="radio" value="1" name="radio_<?=$i?>" onclick="set_radio('<?=$i?>',this.value)">Ada
				</small>
				<INPUT <?=($prosup_row->sup_pro_code!='')?(''):('readonly')?> TYPE="text" NAME="sup_code[]" ID="sup_code_<?=$i?>" style="width:300px;" value="<?=$prosup_row->sup_pro_code?>"></td>
				</tr>
			<?php 
				$i++;
				endforeach;
				endif;
				endif;
			?>
			  </tbody>
		      </table>
			</td>
		  </tr>
		</table>