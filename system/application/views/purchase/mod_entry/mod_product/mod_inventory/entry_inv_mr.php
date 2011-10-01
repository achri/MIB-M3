<script language="javascript">
<?=set_calendar('.kalender',0,'dd-mm-yy');?>

function set_digit(row_id,digit_decimal) {
	var digit_length = '6';
	var $item = $('#mr_qty_'+row_id);
	$item.attr('alt','p'+digit_length+'c3p'+digit_decimal+'S').autoNumeric().blur(function() {
		var strip = $.fn.autoNumeric.Strip(this.id);
		if ($item.val() != '') {
			$item.val($.fn.autoNumeric.Format(this.id,strip));
		}
	});
	
	$item.val('');
	
	return false;
}

$(document).ready(function() {

	masking('.number');
	masking_select('.select_number','.number');
	masking_reload('.number');

	// SIMPAN DATA
	this_tab = $('#tabs').tabs('option', 'selected');
	this_status = 'MR';
	
	$('.mr_check_service').change(function() {
		var row_id = $(this).attr('row_id');
		var mrt_id = $(this).val();
		if (mrt_id == 2) {
			$('div#supplier_add').load('index.php/<?=$link_controller?>/list_so/'+row_id+'/mr',function(data){
				if (data) {
					$('div#supplier_add_dialog')
					.dialog('option','title','DAFTAR SERVICE ORDER')
					.dialog('option','buttons',{
						'Keluar': function() {
							$(this).dialog('close');
							$('.mr_check_service:select[id="mr_type_'+row_id+'"]').val('1');
							$('#mr_so_id_'+row_id).val('0');
							$('#mr_so_no_'+row_id).val('');
							$('#mr_so_no_'+row_id).hide();
						}
					})
					.dialog('open');
					$('#mr_so_no_'+row_id).show();
				}	
			});
		}else{
			$('#mr_so_id_'+row_id).val('0');
			$('#mr_so_no_'+row_id).val('');
			$('#mr_so_no_'+row_id).hide();
		}
		return false;
	});
	
	var peringatan = $('div.dialog_notice');
	
	$('.set_digit').change(function() {
		var satuan = $(this).val();
		var satuan = satuan.split("_");
		var sat_id = satuan[0];
		var sat_format = satuan[2];
		var row_id = $(this).attr('row_id');
		$('#mr_qty_'+row_id).attr('qty_satuan',satuan[1]);
		
		if (sat_format != '') {
			set_digit(row_id,sat_format);
		}
		
		return false;
	});
	
	$('.cek_stok').keyup(function(){
		var row_id = $(this).attr('row_id');
		var pro_satuan_name = $(this).attr('pro_satuan_name');
		var get_inv = $('#mr_pro_stok_'+row_id).val();
		var get_inv = get_inv.split("_");
		var qty_inv = get_inv[0];
		var qty_digit = get_inv[1];
		
		var qty_satuan = $(this).attr('qty_satuan');
		var qty = $(this).val();
		var satuan = $('#mr_um_id_'+row_id).val();
		var satuan = satuan.split("_");
		var satuan_name = satuan[3];
		var sat_format = satuan[2];
		
		qty = parseFloat(qty.replace(/,/g,''));
		
		if (qty_inv=='') {
			$('#mr_qty_'+row_id).val('');
			peringatan.html('Pilih Pemasok Barang !!!').dialog('open').css({'color':'red','font-weight':'bold'});
		}else if (qty!=''||qty!='0'){
			if ((qty * qty_satuan) > qty_inv){
				$('#mr_qty_'+row_id).val('');
				//var qty_request = qty + qty_satuan;
				sat = ' '+satuan_name;
				if (qty_satuan > 1) {
					sat = ' ('+qty_satuan+'/'+satuan_name+')';
				}
				peringatan.html('<table border="0"><tr><td colspan="3">Permintaan Kuantitas Ditolak !!! </td></tr><tr><td> - Permintaan </td><td> = </td><td>'+Number(qty).toFixed(sat_format)+sat+'</td></tr><tr><td> - Stok </td><td> = </td><td>'+Number(qty_inv).toFixed(qty_digit)+' '+pro_satuan_name+'</td></tr></table>').dialog('open').css({'color':'red','font-weight':'bold'});
			}
		}
		else { $('#mr_qty_'+row_id).val(''); }
		return false;
	});
	
	$('.add_sup').click(function() {
		var row_id = $(this).attr('row_id');
		var pro_id = $(this).attr('pro_id');
		$('div#supplier_add').load('index.php/<?=$link_controller?>/sup_add/'+row_id+'/'+pro_id,function(data){
			if (data) {
				$('div#supplier_add_dialog')
				.dialog('option','title','DAFTAR PEMASOK')
				.dialog('option','buttons',{
					'Keluar': function() {
						$(this).dialog('close');
					}
				})
				.dialog('open');
			}	
		});
		return false;
	}).css('cursor','pointer');

	$('.overlay').watermark('pilih pemasok >>>');
	
	var form_mr = $('#MR_form');
	form_mr.submit(function() {
		if (validasi('#MR_form')) {
			//$('#tabs').tabs('disabled', [0,1,2]); 
			$('.saving').attr('disabled',true);
			$('.dialog_konfirmasi').dialog('option','buttons',{
					'<?=$dlg_btn_back?>' : function() {
						//$('#tabs').tabs('enable', [0,1,2]); 
						$('.saving').attr('disabled',false);
						$(this).dialog('close');
					},
					'<?=$dlg_btn_ok?>' : function() {
						unmasking('.number');
						form_mr.ajaxSubmit({
							type : 'POST'
							,url : 'index.php/<?=$link_controller?>/prosesMR'
							,data: form_mr.formSerialize()
							,success : function(data) {
								$("#dlg_confirm").html('').html(data).bind('dialogclose', function(event, ui) {
									location.href='index.php/<?=$link_controller?>/index';
									$('.saving').attr('disabled',false);
								}).dialog('open');
							}
						});
						$(this).dialog('close');
					}
			}).html('').html('<?=$dlg_info_confirm?>').dialog('open');
		}
		return false;
	});
	
});
</script>
<form id="MR_form">
<div class="ui-widget-content ui-corner-all" align="center">
	<table id="MR_table" border='1' cellpadding="5" cellspacing="0" width="100%" height="100%" >
	<tr class="ui-state-default">
		<td align="center">&nbsp;</td>
		<td align="center"><?=$this->lang->line('inv_pro_code')?></td>
		<td align="center"><?=$this->lang->line('inv_pro_name')?></td>
		<td align="center"><?=$this->lang->line('unit')?></td>
		<td align="center"><?=$this->lang->line('qty')?></td>
		<td align="center"><?=$this->lang->line('use_for')?></td>
		<td align="center"><?=$this->lang->line('date_need')?></td>
		<td align="center"><?=$this->lang->line('description')?></td>	
	</tr>
	<?php
	if ($mr_list->num_rows() > 0):
	$i=1;
	foreach ($mr_list->result()as $rows):
		$stok_end = '';
		if ($rows->is_stockJoin==1):
			// GET inventory
			$stok_end = $this->db->query("select inv_end from prc_inventory where pro_id = $rows->pro_id")->row()->inv_end;
		endif;
	?>
	<tr id="mr_row_<?=$i?>" class="mr_rows" baris="<?=$i?>">
		<td align="center" class="ui-widget-header ui-priority-primary">
			<!--a id="mr_del_row_<?=$i?>" class="mr_del_rows" row_id="<//?=$i?>" mr_id="<//?=$rows->mr_id?>" pro_id="<//?=$rows->pro_id?>"><img border='0' src='<//?=base_url()?>asset/img_source/button_empty.png'></a-->
			<a class="del_rows" id="mr_del_row_<?=$i?>" onclick="del_rows('mr','<?=$i?>','<?=$rows->mr_id?>','<?=$rows->pro_id?>');"><img border='0' src='<?=base_url()?>asset/img_source/button_empty.png'></a>
		</td>
		<td align="center"><?=$rows->pro_code?>
			<input type="hidden" name="mr_id" id="mr_id_<?=$i?>" value="<?=$rows->mr_id?>">
			<input type="hidden" name="mr_pro_id[]" id="mr_pro_id_<?=$i?>" value="<?=$rows->pro_id?>">
			<input type="hidden" name="mr_sup_id[]" id="mr_sup_id_<?=$i?>" value="<?=$rows->sup_id?>">
			<input type="hidden" name="mr_pro_stok[]" id="mr_pro_stok_<?=$i?>" value="<?=$stok_end?>">
			<input type="hidden" name="mr_is_join[]" id="mr_is_join_<?=$i?>" value="<?=$rows->is_stockJoin?>">
		</td>
		<td align="left">
			<?=$rows->pro_name?>
			<br>
			<?php if ($rows->is_stockJoin==0):?>
				<div style="width:180px">
				<input id="mr_sup_name_<?=$i?>" type="text" class="required overlay" readonly="readonly" size="15" title="Pemasok" value="<?=($rows->sup_name!='')?($rows->sup_name.', '.$rows->legal_name):('')?>">
				&nbsp;<a id="add_sup_<?=$i?>" class="add_sup" row_id="<?=$i?>" pro_id="<?=$rows->pro_id?>"><img src="asset/img_source/icon_lkp.gif"></a>
				<!--&nbsp;<a id="rem_sup_<//?=$i?>" onclick="rem_sup('<//?=$i?>')"><img src="asset/img_source/button_empty.png"></a!-->
				</div>
			<?php endif;?>
		</td>
		
		<td align="left">
			<select input_id="mr_qty_<?=$i?>" id="mr_um_id_<?=$i?>" name="mr_um_id[]" class="set_digit select_number" row_id="<?=$i?>">
				<option value="<?=$rows->satuan_id?>_1_<?=$rows->satuan_format?>_<?=$rows->satuan_name?>"><?=$rows->satuan_name?></option>
				<?php 
				// SATUAN
				$um_list = $this->db->query("
				select sat.satuan_id,sat.satuan_name,sat.satuan_format,sat_pro.value 
				from prc_master_satuan as sat 
				inner join prc_satuan_produk as sat_pro on sat_pro.pro_id = $rows->pro_id and sat_pro.satuan_unit_id = sat.satuan_id 
				where sat_pro.satuan_unit_id <> $rows->satuan_id 
				order by sat.satuan_id ");
				foreach ($um_list->result() as $um_rows):
				?>
					<option value="<?=$um_rows->satuan_id?>_<?=$um_rows->value?>_<?=$um_rows->satuan_format?>_<?=$um_rows->satuan_name?>" <?=($rows->um_id == $um_rows->satuan_id)?('SELECTED'):('')?>><?=$um_rows->satuan_name?></option>
				<?php 
				endforeach;
				?>
			</select>
		</td>
		
		<td align="center">
			<input id="mr_qty_<?=$i?>" type="text" name="mr_qty[]" size="5" class="required number cek_stok" autocomplete="off" digit_decimal="<?=$rows->satuan_format?>" row_id="<?=$i?>" qty_satuan="1" pro_satuan_name="<?=$rows->satuan_name?>" title="<?=$this->lang->line('qty')?>" value="<?=($rows->qty!='0')?($rows->qty):('')?>">
		</td>
		
		<td align="center">
			<select name="mr_mrt_id[]" class="mr_check_service" id="mr_type_<?=$i?>" row_id="<?=$i?>">
			<?php foreach ($mrt_list->result() as $mrt_rows):?>
				<option value="<?=$mrt_rows->mrt_id?>">- <?=$mrt_rows->mrt_name?> -</option>
			<?php endforeach;?>
			</select>
			<input type="hidden" name="mr_so_id[]" value="0" id="mr_so_id_<?=$i?>">
			<input type="text" id="mr_so_no_<?=$i?>" size="12" readonly style="display:none">
		</td>
		
		<td align="center">
			<input readonly="readonly" name="mr_delivery_date[]" size="10" class="kalender inp_kalender required" title="<?=$this->lang->line('date_need')?>" value="<?=($rows->delivery_date!='')?($rows->delivery_date):('')?>"> 
			<?php 
			$get_parent = $this->pro_code->set_split_code($rows->pro_code,'parent');
			$where['cat_code'] = $get_parent[1];
			$mct_list = $this->tbl_mr->get_mr_category($where,$like=true);	
			if ($mct_list->num_rows() > 0):
			?>
			<br>
			<select name="mr_mct_id[]">
				<option value="">- pilih Untuk Keperluan -</option>
				<?php
				foreach ($mct_list->result() as $mct_rows):?>
				<option value="<?=$mct_rows->mct_id?>" <?=($mct_rows->mct_id==$rows->mct_id)?('SELECTED'):('')?>>- <?=$mct_rows->mct_name?> -</option>
				<?php endforeach;?>
			</select>
			<?php else:?>
			<input type="hidden" name="mr_mct_id[]" value="<?=$rows->mct_id?>">
			<?php endif;?>
		</td>		
		<td align="center">
			<textarea name="mr_description[]" cols="5" rows="1" ><?=$rows->description?></textarea>
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
		<input id="mr_submit" type="submit" value="<?=$this->lang->line('process')?> <?=$type?>" class="saving">
	</div>
</form>
