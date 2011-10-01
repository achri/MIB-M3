<script language="javascript">
var notjoin_row_num=2;
var notjoin_row_id,notjoin_table = $('#TblIsNotJoin');

function add_rows(notjoin_row_id) {
	var pro_id = $('#pro_id').val();
	
	$('#supplier_add').load('index.php/<?=$link_controller_product?>/produk_supp_add/'+notjoin_row_id+'/notjoin/'+pro_id,function() {
		$('div#supplier_add_dialog').dialog('open');
	});

	return false;
}

function rem_rows(notjoin_row_id) {
	if (notjoin_row_id > 1){
		$('#notjoin_row_'+notjoin_row_id).remove();
		notjoin_row_num = notjoin_row_num - 1;
	}	
	return false;
}

$(document).ready(function() {
	masking('.number');
	masking_currency('.curr_select','.number');
	
	var form = $('form#isnotjoin');	
	$(form).submit(function() {
		if (validasi('form#isnotjoin')){
		// KONFIRMASI
			$('.saving').attr('disabled',true);
			$('#tabs').attr('disable',0);
			$('.dialog_konfirmasi').dialog({
				title:'KONFIRMASI',
				autoOpen: false,
				bgiframe: true,
				width: 'auto',
				height: 'auto',
				resizable: false,
				//draggable: false,
				modal:true,
				position:['right','top'],
				buttons : { 
					'<?=$this->lang->line('back')?>' : function() {
						$('.saving').attr('disabled',false);
						$('#tabs').attr('enable',0);
						$(this).dialog('close');
					},
					'<?=$this->lang->line('ok')?>' : function() {
						unmasking('.number');
						$(form).ajaxSubmit({
							type : 'POST'
							,url : 'index.php/<?=$link_controller?>/inventory_save/isnotjoin'
							,data: $(form).formSerialize()
							,success : function(data) {
								var info;	
								if(data) {
									info = '<strong>Selamat... Produk berhasil di Aktivasi <br> Produk kode :<font color="red"> '+data+' </font></strong>';
									$('#dlg_confirm').text('').append(info).dialog('open');
									$('.saving').attr('disabled',false);
									$('#tabs').attr('enable',0);
								}else {
									info = '<STRONG>Maaf... Data Produk Tidak Berhasil di Aktivasi</STRONG>';
									$('#dlg_confirm').text('').append(info).dialog('open');
									$('.saving').attr('disabled',false);
									$('#tabs').attr('enable',0);
								}
								return false;
							}
						});
						$(this).dialog('close');
					}
				}
			}).html('').html('<?=$this->lang->line('confirm')?>').dialog('open');
		}
		return false;
	});

	$('div#supplier_add_dialog').dialog({
		autoOpen: false,
		bgiframe: true,
		width: 'auto',
		height: 'auto',
		resizable: false,
		draggable: false,
		modal:true,
		position:'center',
		//show: 'drop',
		//hide: 'drop',
		buttons: {
			close: function(ev) {
				$(this).dialog('close');
				return false;
			}	
		}
	});
	
});

</script>
<div id="supplier_add_dialog" title="<?=$this->lang->line('add_supp')?>">
	<div id="supplier_add"></div>
</div>

<form id="isnotjoin" class="cmxform validasi_form">
<?php if ($pro_data->num_rows() > 0):?>
<input type="hidden" name="pro_id" value="<?=$pro_id?>">
<table border="0" width="100%">
<tr><td width="15%"><?=$this->lang->line('tree_root_category')?></td><td width="1%">:</td><td><?=$cat_name?></td><td rowspan="4" width="40%" align="center">
<div id="photo_prev" class="ui-corner-all ui-widget-content" style="width:130px;height:80%;padding:5px;overflow:auto;">
<?=$this->pictures->thumbs($pro_data->row()->pro_id,126,126)?>
</div>
</td></tr>
<tr><td><?=$this->lang->line('pro_code')?></td><td>:</td><td><?=$pro_data->row()->pro_code?></td></tr>
<tr><td><?=$this->lang->line('pro_name')?></td><td>:</td><td><?=$pro_data->row()->pro_name?></td></tr>
<tr><td><?=$this->lang->line('satuan')?></td><td>:</td><td><?=$satuan_name?></td></tr>
<tr><td colspan="4" align="left"><!-- INPUT TYPE="button" value="<?//=$this->lang->line('add_supp')?>" onclick="addRowToTable();"--></td></tr>
</table>

<table border="0" width="100%" align="center" id="TblIsNotJoin" style="display:block" class="ui-widget-content ui-corner-all">
<tr class="ui-state-default">
<td align="center" width="25%"><?=$this->lang->line('supp_name')?></td>
<td align="center" width="20%"><?=$this->lang->line('date_min')?></td>
<td align="center" width="20%"><?=$this->lang->line('saldo')?></td>
<!--td align="center" width="12%"><?//=$this->lang->line('opname')?></td-->
<td align="center" width="35%"><?=$this->lang->line('price')?></td>
</tr>
<?php //for ($i = 1;$i <= $get_row_supplier->num_rows();$i++):
	$i = 1;
	foreach ($get_row_supplier->result() as $rows):
?>
	<tr id="notjoin_row_<?=$i?>" bgcolor="lightgray" baris="<?=$i?>">
	<td align="left">
	<?=$rows->sup_name?>
	<input type="hidden" name="inv_id[]" value="<?=$rows->inv_id?>">
	<INPUT TYPE="hidden" id="sup_name_<?=$i?>" NAME="sup_name[]" readonly="readonly" class="required" value="<?=$rows->sup_name?>" readonly title="<?=$this->lang->line('supp_name')?>">
	<INPUT TYPE="hidden" NAME="sup_id[]" id="sup_id_<?=$i?>" value="<?=$rows->sup_id?>" readonly>
	</td>
	<td align="center">
	<?=$rows->date_setup?>
	<input type="hidden" id="tgl_<?=$i?>" name="tgl[]" class="kalender required" size="10" readonly="readonly" value="<?=$rows->date_setup?>" title="<?=$this->lang->line('date_min')?>">
	</td>
	<td align="right">
	<?=$this->general->digit_number($sat_id,$rows->inv_begin)?>
	<input type="hidden" id="saldo_<?=$i?>" name="saldo[]" class="number kosong" size="10" value="<?=$this->general->digit_number($sat_id,$rows->inv_begin)?>" readonly title="<?=$this->lang->line('saldo')?>">
	<td align="left">
	<select input_id="inv_price_<?=$i?>" name="cur_id[]" id="cur_id_<?=$i?>" class="required select curr_select" title="<?=$this->lang->line('cur')?>">
	<option value=""><?=$this->lang->line('selection')?></option>
	<?php if ($curr_data->num_rows()>0):
		foreach ($curr_data->result() as $rows):?>
			<option value="<?=$rows->cur_id?>"><?=$rows->cur_symbol?>. <?//=$rows->cur_name?></option>
	<?php endforeach;
		endif;?>
	</select>
	<input name="inv_price[]" id="inv_price_<?=$i?>" class="required number kosong currency" size="10" title="<?=$this->lang->line('price')?>"> / <?=$this->lang->line('satuan')?>
	</td>
	</tr>
<?php 
	$i++;
	endforeach;
?>
</table>

<br>
<div align="center" class="ui-widget-content ui-corner-all">
<input type="submit" value="<?=$this->lang->line('save')?>" class="saving">
<?php endif;?>
<input type="button" value="<?=$this->lang->line('cancel')?>" onclick="location.href='index.php/<?=$link_controller?>/index'">
</div>
</form>

<div id="fw"></div>