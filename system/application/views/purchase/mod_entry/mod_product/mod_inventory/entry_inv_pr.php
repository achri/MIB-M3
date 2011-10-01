
<script language="javascript">
<?=set_calendar('.kalender',0,'dd-mm-yy');?>
/*
function set_digit(row_id,digit_decimal) {
	//var digit_length = '6';
	var $item = $('#pr_qty_'+row_id);
	$item.attr('alt','p'+digit_length+'c3p'+digit_decimal+'S').autoNumeric().blur(function() {
		var strip = $.fn.autoNumeric.Strip(this.id);
		if ($item.val() != '') {
			$item.val($.fn.autoNumeric.Format(this.id,strip));
		}
	});
	
	$item.val('');
	
	return false;
}
*/

$(document).ready(function() {
	// SIMPAN DATA
	this_tab = $('#tabs').tabs('option', 'selected');
	this_status = 'PR';
	
	masking('.number');
	masking_select('.select_number','.number');
	masking_reload('.number');
	
	$('.pr_check_service').change(function() {
		var row_id = $(this).attr('row_id');
		var pty_id = $(this).val();
		if (pty_id == 6) {
			$('div#supplier_add').load('index.php/<?=$link_controller?>/list_so/'+row_id+'/pr',function(data){
				if (data) {
					$('div#supplier_add_dialog')
					.dialog('option','title','DAFTAR SERVICE ORDER')
					.dialog('option','buttons',{
						'Keluar': function() {
							$(this).dialog('close');
							$('.pr_check_service').val('1');
							$('#pr_so_id_'+row_id).val('0');
							$('#pr_so_no_'+row_id).val('');
							$('#pr_so_no_'+row_id).hide();
						}
					})
					.dialog('open');
					$('#pr_so_no_'+row_id).show();
				}	
			});
		}else{
			$('#pr_so_id_'+row_id).val('0');
			$('#pr_so_no_'+row_id).val('');
			$('#pr_so_no_'+row_id).hide();
		}
		return false;
	});
	/*
	$('.set_digit').change(function() {
		var satuan = $(this).val();
		var satuan = satuan.split("_");
		var sat_id = satuan[0];
		var sat_format = satuan[2];
		var row_id = $(this).attr('row_id');
		$('#pr_qty_'+row_id).attr('qty_satuan',satuan[1]);
		
		if (sat_format != '') {
			set_digit(row_id,sat_format);
		}
		
		return false;
	});
	*/
	var form_pr = $('#PR_form');
	form_pr.submit(function() {
		if (validasi('#PR_form')) {
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
						form_pr.ajaxSubmit({
							type : 'POST'
							,url : 'index.php/<?=$link_controller?>/prosesPR'
							,data: form_pr.formSerialize()
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

<form id="PR_form">
<div class="" align="center">
	<table id="PR_table" border='1' cellpadding="5" cellspacing="1" width="100%" height="100%" class="ui-widget-content ui-corner-all">
	<tr class="ui-state-default">
		<td align="center"><?=$this->lang->line('no')?></td>
		<td align="center">&nbsp;</td>
		<td align="center"><?=$this->lang->line('inv_pro_name')?> (<?=$this->lang->line('code')?>)</td>
		<td align="center"><?=$this->lang->line('unit')?></td>
		<td align="center"><?=$this->lang->line('qty')?></td>
		<td align="center"><?=$this->lang->line('status')?></td>
		<td>&nbsp;</td>
	</tr>
	<?php
	if ($pr_list->num_rows() > 0):
	$i=1;
	foreach ($pr_list->result()as $rows):
	?>
	<tr id="pr_row_<?=$i?>" class="pr_rows" baris="<?=$i?>">	
		<td align="center" class="ui-state-default">
		<?=$i?>
		</td>
		<td align="center" bgcolor="lightgray">
			<!-- class="pr_del_rows" row_id="<//?=$i?>" pr_id="<//?=$rows->pr_id?>" pro_id="<//?=$rows->pro_id?>" -->
			<a class="del_rows" id="pr_del_row_<?=$i?>" onclick="del_rows('pr','<?=$i?>','<?=$rows->pr_id?>','<?=$rows->pro_id?>');"><img border='0' src='<?=base_url()?>asset/img_source/button_empty.png'></a>
		</td>
		<td align="left" valign="top">
		<table border='0' cellpadding="1" cellspacing="1" width="100%" height="100%">
		<tr><td valign="top">
		<input type="hidden" name="pr_id" value="<?=$rows->pr_id?>">
		<input type="hidden" name="pr_pro_id[]" value="<?=$rows->pro_id?>">
		<?=$rows->pro_name?> (<?=$rows->pro_code?>)
		</td></tr>
		<tr><td valign="bottom">
			<select name="pr_buy_via[]" id="buy_via_<?=$i?>" validate="required:true" class="required" title="Pilih Pembelian">
				<option value="" <?=($rows->buy_via=='')?('SELECTED'):('')?>>- usul pembelian -</option>
				<option value="po" <?=($rows->buy_via=='po')?('SELECTED'):('')?>>- lewat PO -</option>
				<option value="pcv" <?=($rows->buy_via=='pcv')?('SELECTED'):('')?>>- lewat PCV -</option>
			</select>
		</td></tr></table>
		</td>
		
		<td align="left" valign="top">
			<select input_id="pr_qty_<?=$i?>" id="pr_um_id_<?=$i?>" name="pr_um_id[]" class="select_number" row_id="<?=$i?>">
				<option value="<?=$rows->satuan_id?>"><?=$rows->satuan_name?></option>
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
					<option value="<?=$um_rows->satuan_id?>" <?=($rows->satuan_id==$um_rows->satuan_id)?('SELECTED'):('')?>><?=$um_rows->satuan_name?></option>
				<?php 
				endforeach;
				?>
			</select>
		</td>
		
		<td align="center" valign="top">
			<input id="pr_qty_<?=$i?>" type="text" name="pr_qty[]" size="10" maxlength="10" class="required number" autocomplete="off" digit_decimal="<?=$rows->satuan_format?>" row_id="<?=$i?>" qty_satuan="1" title="<?=$this->lang->line('qty')?>" value="<?=($rows->qty!='0')?($rows->qty):('')?>">
		</td>
		
		<td align="center" valign="top">
			<select name="pr_emergencyStat[]">
				<option value="0" <?=($rows->emergencyStat=="0")?('SELECTED'):('')?>>Normal</option>
				<option value="1" <?=($rows->emergencyStat=="1")?('SELECTED'):('')?>>Darurat</option>
			</select>
		</td>
		
		<td valign="top">
			<table border='0' cellpadding="3" cellspacing="0" width="100%" height="100%" style="border:1px solid grey">
			<tr>
				<td><?=$this->lang->line('use_for')?></td>
				<td>:</td>
				<td>
					<select name="pr_pty_id[]" class="pr_check_service" row_id="<?=$i?>">
					<?php foreach ($pty_list->result() as $pty_rows):?>
						<option value="<?=$pty_rows->pty_id?>" <?=($rows->pty_id==$pty_rows->pty_id)?('SELECTED'):('')?>>- <?=$pty_rows->pty_name?> -</option>
					<?php endforeach;?>
					</select>
					<input type="hidden" name="pr_so_id[]" value="0" id="pr_so_id_<?=$i?>">
					<input type="text" id="pr_so_no_<?=$i?>" size="12" readonly style="display:none">
				</td>
			</tr>
			<tr >
				<td><?=$this->lang->line('date_need')?></td>
				<td>:</td>
				<td><input readonly="readonly" type="text" name="pr_delivery_date[]" style="width:95%" id="pr_tanggal_<?=$i?>" class="kalender inp_kalender required" title="<?=$this->lang->line('date_need')?>" value="<?=($rows->delivery_date!='')?($rows->delivery_date):('')?>"></td>
			</tr>
			<tr>
				<td><?=$this->lang->line('description')?></td>
				<td>:</td>
				<td><textarea rows="1" name="pr_description[]" style="width:95%"><?=$rows->description?></textarea></td>
			</tr>
			</table>
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
		<input id="pr_submit" type="submit" value="<?=$this->lang->line('process')?> <?=$type?>" class="saving">
	</div>	
</form>