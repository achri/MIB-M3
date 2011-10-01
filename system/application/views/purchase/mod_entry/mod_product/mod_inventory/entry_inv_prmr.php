<script language="javascript">
<?=set_calendar('.kalender',0,'dd-mm-yy');?>
/*
function saveToPRMR(type) {
	$('#'+type+'_form').ajaxSubmit({
		url: 'index.php/prc/entry/product/inventory/trans_product_prosesPRorMR/'+type,
		type: 'POST',
		data: $(this).serialize,
		success: function(data) {
			alert(data);
		}
	});
	return false;
}
*/

function del_rows(prormr,rows,prmr_id,pro_id) {
	var num_row = $('#'+prormr+'_table tr').length;
	$('.dialog_konfirmasi').dialog({
		title:'<?=$dlg_title_confirm?>',
		autoOpen: false,
		bgiframe: true,
		width: 'auto',
		height: 'auto',
		resizable: false,
		draggable: false,
		modal:true,
		position:['right','top'],
		buttons : { 
			'<?=$dlg_btn_back?>' : function() {
				$(this).dialog('close');
			},
			'<?=$dlg_btn_ok?>' : function() {
				$.post('index.php/<?=$link_controller?>/del_prmr_row/'+prormr+'/'+prmr_id+'/'+pro_id, function(data) {
					//alert(data);
					if (data) {
						if (num_row <= 2) {
							//tabs_awal();
							location.href = 'index.php/<?=$link_controller?>/index';
						}
						else {
							$('#'+rows).remove();
						}
					}
				}); 		
				$(this).dialog('close');
			}
		}
	}).html('').html('<?=$dlg_info_delete?>').dialog('open');;	
	return false;
}

function add_sup(sup_row_id,pro_id) {
	$('#supplier_add').load('index.php/<?=$link_controller?>/sup_add/'+sup_row_id+'/'+pro_id,function(data) {
		if (data) {
			$('div#supplier_add_dialog').dialog('open');
		}
	});
	return false;
}

$(document).ready(function(){
	
	$('.required').attr('title',' ');
	
	masking('.number');
	
	$('form#<?=$type?>_form').validate({
		submitHandler: function(form) {
			$('#saving').attr('disabled',true);
			$('#tabs').data('disabled.tabs',[0]);
			$('#tabs').tabs('disable', 0); 
			// KONFIRMASI
			$('.dialog_konfirmasi').dialog({
				title:'<?=$dlg_title_confirm?>',
				autoOpen: false,
				bgiframe: true,
				width: 'auto',
				height: 'auto',
				resizable: false,
				//draggable: false,
				modal:true,
				position:['right','top'],
				buttons : { 
					'<?=$dlg_btn_back?>' : function() {
						$('#tabs').tabs('enable', 0); 
						$('#saving').attr('disabled',false);
						$(this).dialog('close');
					},
					'<?=$dlg_btn_ok?>' : function() {
						unmasking('.number');
						$(form).ajaxSubmit({
							type : 'POST'
							,url : 'index.php/<?=$link_controller?>/prosesPRorMR/<?=$type?>'
							,success : function(data) {
								$("#dlg_confirm").append(data).bind('dialogclose', function(event, ui) {
									location.href='index.php/<?=$link_controller?>/index';
								}).dialog('open');
							}
						});
						$(this).dialog('close');
					}
				}
			}).html('').html('<?=$dlg_info_confirm?>').dialog('open');
			return false;
		},
		focusInvalid: true,
		focusCleanup: true,
		highlight: function(element, errorClass) {
			$(element).addClass('ui-state-error');
		},
		unhighlight: function(element, errorClass) {
			$(element).removeClass('ui-state-error');
		},
		rules : {
			buy_via : "required",
			qty: { 
				required : true,
				number : true
			},
			delivery_date : "required"
		},
		messages : {

		}		
	});

	//$('.inp_kalender').mask("9999-99-99",{placeholder:"_"});

	//$('.chk_qty,.cek_satuan').change(function() {
	

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
			'<?=$dlg_btn_close?>': function() {
				$(this).dialog('close');
			}
		}
	});

	$('.overlay').watermark('pilih supplier');
	/*
	$('.set_digit').change(function(){
		var digit = $(this).attr('digit');
		//alert(digit);
	});
	*/
});
function cek_stok(row) {
	//var row = $(this).attr('row');
	var sat = $('#um_'+row).val();
	var qty = $('#qty_'+row).val();
	var join = $('#qty_'+row).attr('join');
	var sup_id = $('#qty_'+row).attr('sup_id');
	var pro_id = $('#qty_'+row).attr('pro_id');
	
	qty = parseFloat(qty.replace(/,/g,''));
	//alert(row);
	//alert('sat='+sat+'|qty='+qty+'|join='+join+'|sup='+sup_id+'|pro='+pro_id);
	//alert(join+'/'+pro_id+'/'+qty+'/'+sat+'/'+sup_id);
	
	if (qty != '') {
		if (join==0) {	
			if (sup_id > 0){
				$.post('index.php/<?=$link_controller?>/cek_stok/'+join+'/'+pro_id+'/'+qty+'/'+sat+'/'+sup_id,function(data){
					if (data) {
						alert('<?=$this->lang->line('qty_limit')?>'+data);
						$('#qty_'+row).val('');
					}
					return false;
				});
			}
			else {
				alert('<?=$this->lang->line('select_sup')?>');
				$('#qty_'+row).val('');
			}	
		}
		else {
			$.post('index.php/<?=$link_controller?>/cek_stok/'+join+'/'+pro_id+'/'+qty+'/'+sat,function(data){
				if (data) {
					alert('<?=$this->lang->line('qty_limit')?>'+data);
					$('#qty_'+row).val('');
				}
				return false;
			});
		}
	}
	
	return false;
//});
}
</script>
<div id="supplier_add_dialog" title="Daftar suplier">
	<div id="supplier_add"></div>
</div>
<?php 
if ($type=='PR'):
?>
<form id="PR_form">
<div class="ui-widget-content ui-corner-all" align="center">
	<table id="PR_table" border='1' cellpadding="5" cellspacing="0" width="100%" height="100%" >
	<tr class="ui-state-default">
		<td align="center">&nbsp;</td>
		<td align="center"><?=$this->lang->line('pr_pro_name')?></td>
		<td align="center"><?=$this->lang->line('qty')?></td>
		<td align="center"><?=$this->lang->line('unit')?></td>
		<td align="center"><?=$this->lang->line('status')?></td>
		<td align="center"><?=$this->lang->line('use_for')?></td>
		<td align="center"><?=$this->lang->line('date_need')?></td>
		<td align="center"><?=$this->lang->line('description')?></td>
	</tr>
	<?php
	if ($pr_list->num_rows() > 0):
	$i=1;
	foreach ($pr_list->result()as $rows):
	?>
	<tr id="row_<?=$i?>">
		<td align="center" class="ui-widget-header ui-priority-primary"><a style="cursor: pointer" onclick="del_rows('PR','row_<?=$i?>','<?=$rows->pr_id?>','<?=$rows->pro_id?>');"><img border='0' src='<?=base_url()?>asset/img_source/button_empty.png'></a></td>
		<td align="left">
		<input type="hidden" name="pr_id" value="<?=$rows->pr_id?>">
		<input type="hidden" name="pro_id[]" value="<?=$rows->pro_id?>">
		<?=$rows->pro_name?> (<?=$rows->pro_code?>)<br>
			<select name="buy_via[]" id="buy_via_<?=$i?>" validate="required:true" class="required">
				<option value="">--Pilih beli lewat--</option>
				<option value="po">--Lewat PO--</option>
				<option value="pcv">--Lewat PCV--</option>
			</select>
		</td>
		<td align="center"><input type="text" digit_decimal="<?=$rows->satuan_format?>" name="qty[]" size="5" id="qty_<?=$i?>" class="required number" validation="required"></td>
		<td align="center"><select name="um_id[]" class="set_digit">
			<option value="<?=$rows->satuan_id?>" digit="<?=$rows->satuan_format?>"><?=$rows->satuan_name?></option>
			<?php 
			$where[$this->config->item('tbl_unit_satuan').'.pro_id']= $rows->pro_id;
			$um_list = $this->tbl_unit->get_unit_satuan($where,true);
			foreach ($um_list->result() as $um_rows):
			?>
				<option value="<?=$um_rows->satuan_id?>" digit="<?=$um_rows->satuan_format?>"><?=$um_rows->satuan_name?></option>
			<?php 
			endforeach;
			?>
			</select>
		</td>
		<td align="center">
			<select name="emergencyStat[]">
				<option value="0">Normal</option>
				<option value="1">Emergency</option>
			</select>
		</td>
		<td align="center">
			<select name="pty_id[]">
			<?php foreach ($pty_list->result() as $pty_rows):?>
				<option value="<?=$pty_rows->pty_id?>">- <?=$pty_rows->pty_name?> -</option>
			<?php endforeach;?>
			</select>
		</td>
		<td align="center"><input readonly="readonly" type="text" name="delivery_date[]" size="10" id="tanggal_<?=$i?>" class="kalender inp_kalender required"></td>
		<td align="center"><textarea cols="5" rows="1" name="description[]"></textarea></td>
	</tr>
	<?php 
	$i++;
	endforeach;
	endif;
	?>
	</table>
</div>
	<br>
	<div class="ui-widget-content ui-corner-all" align="center">
		<input type="submit" value="<?=$this->lang->line('process')?> <?=$type?>" id="saving">
	</div>	
</form>
<?php 
else:
?>
<form id="MR_form">
<div class="ui-widget-content ui-corner-all" align="center">
	<table id="MR_table" border='1' cellpadding="5" cellspacing="0" width="100%" height="100%" >
	<tr class="ui-state-default">
		<td align="center">&nbsp;</td>
		<td align="center"><?=$this->lang->line('code')?></td>
		<td align="center"><?=$this->lang->line('pro_name')?></td>
		<td align="center"><?=$this->lang->line('qty')?></td>
		<td align="center"><?=$this->lang->line('unit')?></td>
		<td align="center"><?=$this->lang->line('date_need')?></td>
		<td align="center"><?=$this->lang->line('description')?></td>	
	</tr>
	<?php
	if ($mr_list->num_rows() > 0):
	$i=1;
	foreach ($mr_list->result()as $rows):
		if ($rows->is_stockJoin==0):
			// GET inventory
			$where_inv['pro_id'] = $rows->pro_id;
			$stok_list = $this->tbl_inventory->get_inventory($where_inv);
			$sup_list = $this->tbl_inventory->get_inv_sup($where_inv);
		else:
			// GET inventory
			$where_inv['pro_id'] = $rows->pro_id;
			$stok_list = $this->tbl_inventory->get_inventory($where_inv)->last_row();
		endif;
	?>
	<tr id="row_<?=$i?>">
		<td align="center" class="ui-widget-header ui-priority-primary">
		
		<a style="cursor: pointer" onclick="del_rows('MR','row_<?=$i?>','<?=$rows->mr_id?>','<?=$rows->pro_id?>');"><img border='0' src='<?=base_url()?>asset/img_source/button_empty.png'></a>
		</td>
		<td align="center"><?=$rows->pro_code?>
		<input type="hidden" name="mr_id" value="<?=$rows->mr_id?>">
		<input type="hidden" name="pro_id[]" value="<?=$rows->pro_id?>">
		<input type="hidden" name="sup_id[]" id="sup_id_<?=$i?>" value="<?=$rows->sup_id?>">
		</td>
		<td align="left">
		<?=$rows->pro_name?>
		<br>
		<?php if ($rows->is_stockJoin==0):?>
			<div style="width:200px">
			<input id="supps_<?=$i?>" type="text" class="required overlay" readonly="readonly" size="15">
			&nbsp;<a onclick="add_sup('<?=$i?>','<?=$rows->pro_id?>')"><img src="asset/img_source/icon_lkp.gif"></a>
			&nbsp;<a onclick="rem_sup('<?=$i?>')"><img src="asset/img_source/button_empty.png"></a>
			</div>
		<?php endif;?>
		</td>
		<td align="center">
			<input row="<?=$i?>" id="qty_<?=$i?>" type="text" name="qty[]" size="5" sup_id="" pro_id="<?=$rows->pro_id?>" join="<?=$rows->is_stockJoin?>" class="required number" validation="required" onkeyup="cek_stok('<?=$i?>');" autocomplete="off">
			
		</td>
		<td align="center">
		<select digit_decimal="<?=$rows->satuan_format?>" id="um_<?=$i?>" name="um_id[]" row="<?=$i?>" class="set_digit" onblur="cek_stok('<?=$i?>');">
			<option value="<?=$rows->satuan_id?>" digit="<?=$rows->satuan_format?>"><?=$rows->satuan_name?></option>
			<?php 
			$where_um[$this->config->item('tbl_unit_satuan').'.pro_id']= $rows->pro_id;
			$um_list = $this->tbl_unit->get_unit_satuan($where_um,true);
			foreach ($um_list->result() as $um_rows):
			?>
				<option value="<?=$um_rows->satuan_id?>" digit="<?=$um_rows->satuan_format?>"><?=$um_rows->satuan_name?></option>
			<?php 
			endforeach;
			?>
			</select>
		</td>
		<td align="center">
			<input readonly="readonly" name="delivery_date[]" size="10" class="kalender inp_kalender required"> 
			<?php 
			$get_parent = $this->pro_code->set_split_code($rows->pro_code,'parent');
			$where['cat_code'] = $get_parent[1];
			$mct_list = $this->tbl_mr->get_mr_category($where,$like=true);	
			if ($mct_list->num_rows() > 0):
			?>
			<br>
			<select name="mct_id[]">
				<option value="">- pilih Untuk Keperluan -</option>
				<?php
				foreach ($mct_list->result() as $mct_rows):?>
				<option value="<?=$mct_rows->mct_id?>">- <?=$mct_rows->mct_name?> -</option>
				<?php endforeach;?>
			</select>
			<?php else:?>
			<input type="hidden" name="mct_id[]" value="0">
			<?php endif;?>
		</td>		
		<td align="center">
			<textarea name="description[]"></textarea>
		</td>
	</tr>
	<?php 
	$i++;
	endforeach;
	endif;
	?>
	</table>	
</div>
	<br>
	<div class="ui-widget-content ui-corner-all" align="center">
		<input type="submit" value="<?=$this->lang->line('process')?> <?=$type?>" id="saving">
	</div>
</form>
<?php 
endif;
?>
