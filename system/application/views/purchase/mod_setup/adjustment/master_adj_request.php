<?=$this->load->view('validasi')?>
<script language="javascript">
<?=set_calendar('.kalender','null','dd-mm-yy');?>
var sel_id, stat_join, info, valid, limit_text = 30;

function del_rows(row_id,adj_id,pro_id) {
	var tot_row = $('.tr_master').size();
	//alert(tot_row+' '+row_id+' '+adj_id+' '+pro_id);
	
	if (tot_row > 1){
		$.ajax({
			url : 'index.php/<?=$link_controller?>/hapusAdjDet/'+adj_id+'/'+pro_id,
			type: 'POST',
			success: function(data) {
				if (data) {
					$('#pro_row_'+row_id).remove();
					//num_row = num_row - 1;
				}
			}
		});
	}	
	
	return false;
}

function del_sup_rows(rows,row_id,adj_id,pro_id,sup_id) {
	var tot_row = $('.tr_master_sup_'+rows).size();
	//alert(tot_row+'|'+row_id+'|'+adj_id+'|'+pro_id+'|'+sup_id);
	info = '<STRONG>Semua Data Produk yg belum disesuaikan akan di hapus !!!</STRONG>';
	$('#dlg_confirm').html('').html(info).dialog('option','buttons',{
		"<?=$this->lang->line('cancel')?>": function() {
			$(this).dialog('close');
			$('#setup').attr('disabled',false);
		},
		"<?=$this->lang->line('agree')?>": function() {
			if (tot_row > 1){
				$.ajax({
					url : 'index.php/<?=$link_controller?>/hapusAdjDetSup/'+adj_id+'/'+pro_id+'/'+sup_id,
					type: 'POST',
					success: function(data) {
						if (data) {
							$('#pro_row_'+row_id).remove();
							//num_row = num_row - 1;
						}
					}
				});
			}
			$(this).dialog('close');
		}
	}).dialog('open');
	
	return false;
}

function del_all(adj_id) {
	info = '<STRONG>Semua Data Produk yg belum disesuaikan akan di hapus !!!</STRONG>';
	$('#dlg_confirm').html('').html(info).dialog('option','buttons',{
		"<?=$this->lang->line('cancel')?>": function() {
			$(this).dialog('close');
			$('#setup').attr('disabled',false);
		},
		"<?=$this->lang->line('agree')?>": function() {
			$.ajax({
				url : 'index.php/<?=$link_controller?>/hapusAdjustment/'+adj_id,
				type: 'POST',
				success: function(data) {
					if (data) {
						location.href = 'index.php/<?=$link_controller?>/index';
					}
				}
			});
			$(this).dialog('close');
		}
	}).dialog('open');
		
	return false;
}

function clear_alasan() {
	$("#salasan_"+stat_join+'_'+sel_id).html('');
	$("#alasan_"+stat_join+'_'+sel_id).val('');
}

function show_alasan() {
	var sget_alasan = $("#alasan_"+stat_join+'_'+sel_id).val();
	var sview_alasan = '<div style="overflow:auto; width:200px">'+sget_alasan+'</div>'; 
	$('#dlg_info').html('').html(sview_alasan).dialog('open');
}

$(document).ready(function() {
	var form = $('form#adjustment');
	$(form).submit(function(){
		//$('#saving').attr('disabled','disabled');
		//$('#tabs').tabs('disable', 0); 
			
		// KONFIRMASI
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
					//$('#saving').attr('disabled',false);
					//$('#tabs').tabs('enable', 0); 
					$(this).dialog('close');
				},
				'<?=$this->lang->line('ok')?>' : function() {
					$(form).ajaxSubmit({
						type : 'POST'
						,url : 'index.php/<?=$link_controller?>/buatAdjustment'
						,data: $(form).formSerialize()
						,beforeSubmit: validasi_spesifik2
						,success : function(data) {
							var info;	
							if(data) {
								info = '<strong>Selamat... Produk berhasil di Sesuaikan <br> <font color="red">Adjustment NO : '+data+' </font></strong>';
								$('#dlg_confirm').text('').append(info).dialog('option','buttons',{
									"<?=$this->lang->line('close')?>" : function() {
										$(this).dialog('close');
										$('#setup').attr('disabled',false);
										location.href = 'index.php/<?=$link_controller?>/index';
									}
								}).dialog('open');
								//alert(data);
							}else {
								info = '<STRONG><font color="red">Maaf... Data Produk Tidak Berhasil di Sesuaikan</font></STRONG>';
								$('#dlg_confirm').text('').append(info).dialog('option','buttons',{
									"<?=$this->lang->line('close')?>" : function() {
										$(this).dialog('close');
										$('#setup').attr('disabled',false);
									}
								}).dialog('open');
							}
							return false;
						}
					});
					$(this).dialog('close');
				}
			}
		}).html('').html('<?=$this->lang->line('confirm')?>').dialog('open');
		return false;
		
	});

	$('#dlg_alasan').dialog({
		autoOpen: false,
		bgiframe: true,
		width: 'auto',
		height: 'auto',
		resizable: false,
		draggable: false,
		modal:true,
		position:'center',
		buttons : {
			"<?=$this->lang->line('ok')?>": function() {
				var show_batas = "<a href=\"javascript:void(0)\" onclick=\"show_alasan('"+sel_id+"');\"> ...</a>";
				var get_alasan = $('#text_alasan').val();
				if (get_alasan != '') {
					$("#alasan_"+stat_join+'_'+sel_id).val(get_alasan);
					var batas = get_alasan.substr(0,limit_text);	
					$("#salasan_"+stat_join+'_'+sel_id).html(batas+show_batas);
					$(this).dialog('close');
				}
			},
			"<?=$this->lang->line('cancel')?>": function() {
				clear_alasan();
				$("#galasan_"+stat_join+'_'+sel_id).val('');
				$(this).dialog('close');
			}			
		}
	});
	
	$('#dlg_info').dialog({
		autoOpen: false,
		bgiframe: true,
		width: 'auto',
		height: 'auto',
		resizable: false,
		position: 'center',
		buttons: {
			"<?=$this->lang->line('close')?>": function() {
				$(this).dialog('close');
			}
		}
	});

	$('.alasan').change(function() {
		var adj_stat = $(this).val();
		sel_id = $(this).attr('row_no');
		stat_join = $(this).attr('stats');
		if (adj_stat == 'box') {
			$('#text_alasan').val('');
			$('#dlg_alasan').dialog('open');
		} 
		else if (adj_stat != ''){
			$('#alasan_'+stat_join+'_'+sel_id).val(adj_stat);
		}
		else {
			clear_alasan();
		}
	});

	<?=remove_close_dialog(array('#supplier_add_dialog','#dlg_alasan'))?>
});

</script>
<div id="dlg_alasan" title="Alasan Lainnya">
<textarea rows="5" cols="10" id="text_alasan" style="overflow: auto"></textarea>
</div>
<div id="dlg_info" title="Informasi"></div>

<form id="adjustment" class="cmxform">
<?php if ($adj_list->num_rows() > 0):?>

<div class="ui-widget-content ui-corner-all" align="center">
	<table id="adjustment_table" class="table" border='1' cellpadding="5" cellspacing="0" width="100%" height="100%" >
	<tr class="ui-state-default">
		<td align="center">&nbsp;</td>
		<td align="center"><?=$this->lang->line('codename')?></td>
		<td align="center"><?=$this->lang->line('adjustment')?></td>
		<td align="center"><?=$this->lang->line('unit')?></td>
	</tr>
	<?php
	if ($adj_list->num_rows() > 0):
	$i=1;
	$x=0;
	foreach ($adj_list->result()as $rows):
	?>
	<tr id="pro_row_<?=$i?>" class='x tr_master validasi_tr_1'>
		<td align="center" class="ui-widget-header ui-priority-primary">
		<a style="cursor: pointer" onclick="del_rows('<?=$i?>','<?=$rows->adj_id?>','<?=$rows->pro_id?>');" href='javascript:void(0)'><img border='0' src='<?=base_url()?>asset/img_source/button_empty.png'></a></td>
		<td align="left">
		<input type="hidden" name="adj_id" value="<?=$rows->adj_id?>">
		<input type="hidden" name="pro_id[<?=$x?>]" value="<?=$rows->pro_id?>">
		<input type="hidden" name="join[<?=$x?>]" value="<?=$rows->is_stockJoin?>">
		
		<?=$rows->pro_name?><br>(<?=$rows->pro_code?>)
		</td>
		<td align="center">
		<table border="1" class="table" width="100%" class="ui-widget-content ui-corner-all" cellpadding="2" cellspacing="0">
		
		<?php 
		// KARTU STOK GENERAL
		if ($rows->is_stockJoin == 1):?>
		<tr class="ui-state-default">
			<td colspan="2" width="30%" align="center"><?=$this->lang->line('qty_stock')?></td>
			<td rowspan="2" width="" align="center"><?=$this->lang->line('date')?></td>
			<td rowspan="2" width="70%" align="center"><?=$this->lang->line('check_by')?></td>
			<td rowspan="2" width="" align="center"><?=$this->lang->line('alasan')?></td>
		</tr>
		<tr class="ui-state-default">
			<td align="center"><?=$this->lang->line('stock_now')?></td>
			<td align="center"><?=$this->lang->line('stock_opname')?></td>
		</tr>
		<?php 
		$sql = "select inv.inv_end
			from prc_inventory as inv 
			inner join prc_master_product as pro on pro.pro_id = inv.pro_id
			where pro.is_stockJoin = '1' and inv.pro_id = $rows->pro_id";
		$inv_end = $this->db->query($sql)->row()->inv_end;
		?>
		<tr class="x validasi_tr_2">
			<td align="center">
			<input type="hidden" name="qty[<?=$x?>]" value="<?=$inv_end?>">
			<?=$this->general->digit_number($rows->um_id,$inv_end)?>
			</td>
			<td align="center"><input id="qty_opname_join_<?=$i?>" name="qty_opname[<?=$x?>]" size="4" class="required number"></td>
			<td align="center"><input id="tgl_join_<?=$i?>" name="tgl[<?=$x?>]" size="12" readonly="readonly" class="kalender required"></td>
			<td align="center"><input id="cek_opname_join_<?=$i?>" name="cek_opname[<?=$x?>]" size="15" class="required"></td>
			<td>
			<select id="galasan_join_<?=$x?>" class="alasan" stats="join" row_no="<?=$x?>" class="required">
				<option>--[Alasan]--</option>
				<?php 
				$sql = 'select * from prc_master_adjustment_info order by info_id';
				$get_alasan = $this->db->query($sql);
				foreach ($get_alasan->result() as $row_info):
				?>
					<option value="<?=$row_info->description?>"><?=$row_info->description?></option>
				<?php
				endforeach;
				?>
				<option value="box">lain-lain</option>
			</select>
			<input type="hidden" name="alasan[<?=$x?>]" id="alasan_join_<?=$x?>">
			<div id="salasan_join_<?=$x?>" style="font-style: italic; color:red; text-align: left; width: 110px;"></div>
			</td>
		</tr>
		<?php 
		// KARTU STOK SPESIFIK
		else:?>
		<tr class="ui-state-default">
			<td rowspan="2" width="" align="center"><?=$this->lang->line('supp_name')?></td>
			<td colspan="2" width="" align="center"><?=$this->lang->line('qty_stock')?></td>
			<td rowspan="2" width="" align="center"><?=$this->lang->line('date')?></td>
			<td rowspan="2" width="" align="center"><?=$this->lang->line('check_by')?></td>
			<td rowspan="2" width="" align="center"><?=$this->lang->line('alasan')?></td>
			<td rowspan="2" width="" align="center"></td>
		</tr>
		<tr class="ui-state-default">
			<td align="center"><?=$this->lang->line('stock_now')?></td>
			<td align="center"><?=$this->lang->line('stock_opname')?></td>
		</tr>
		<?php 
		$sql = "select adj_det.pro_id,s.sup_id,s.sup_name,inv.inv_end
			from prc_adjustment_detail as adj_det
			inner join prc_inventory as inv on inv.sup_id = adj_det.sup_id and inv.pro_id = adj_det.pro_id
			inner join prc_master_supplier as s on s.sup_id = adj_det.sup_id
			where adj_det.pro_id = $rows->pro_id and adj_det.adj_id = $rows->adj_id";
		$rs = $this->db->query($sql);
		$xx=0;
		foreach ($rs->result() as $row_sp):?>
		<tr class="x tr_master_sup_<?=$x?> validasi_tr_2" id="pro_row_<?=$x?>_<?=$xx?>">
			<td valign="top" width="100%">
			<input type="hidden" name="sup_id[<?=$x?>][<?=$xx?>]" value="<?=$row_sp->sup_id?>">
			<?=$row_sp->sup_name?>
			</td>
			<td valign="top" align="center">
			<input type="hidden" name="qty[<?=$x?>][<?=$xx?>]" value="<?=$row_sp->inv_end?>">
			<?=$this->general->digit_number($rows->um_id,$row_sp->inv_end)?>
			</td>
			<td valign="top"><input id="qty_opname_njoin_<?=$xx?>" name="qty_opname[<?=$x?>][<?=$xx?>]" size="4" class="required number" title="<?=$this->lang->line('qty_stock')?> <?=$this->lang->line('stock_opname')?>"></td>
			<td valign="top"><input id="tgl_njoin_<?=$x?>_<?=$xx?>" name="tgl[<?=$x?>][<?=$xx?>]" size="12" readonly="readonly" class="kalender required" title="<?=$this->lang->line('date')?>"></td>
			<td valign="top"><input id="cek_opname_njoin_<?=$xx?>" name="cek_opname[<?=$x?>][<?=$xx?>]" size="15" class="required" title="<?=$this->lang->line('check_by')?>"></td>
			<td valign="top">
			<select id="galasan_njoin_<?=$x?>_<?=$xx?>" class="alasan required" stats="njoin" row_no="<?=$x?>_<?=$xx?>" title="<?=$this->lang->line('alasan')?>">
				<option value="">--[Alasan]--</option>
				<?php 
				$sql = 'select * from prc_master_adjustment_info order by info_id';
				$get_alasan = $this->db->query($sql);
				foreach ($get_alasan->result() as $row_info):
				?>
					<option value="<?=$row_info->description?>"><?=$row_info->description?></option>
				<?php
				endforeach;
				?>
				<option value="box">lain-lain</option>
			</select>
			<input type="hidden" name="alasan[<?=$x?>][<?=$xx?>]" id="alasan_njoin_<?=$x?>_<?=$xx?>">
			<div id="salasan_njoin_<?=$x?>_<?=$xx?>" style="font-style: italic; color:red; text-align: left; width: 110px;"></div>
			</td>
			<td>
			<a style="cursor: pointer" onclick="del_sup_rows('<?=$x?>','<?=$x?>_<?=$xx?>','<?=$rows->adj_id?>','<?=$row_sp->pro_id?>','<?=$row_sp->sup_id?>');" href='javascript:void(0)'><img border='0' src='<?=base_url()?>asset/img_source/button_empty.png'></a>
			</td>
		</tr>
		<?php 
		$xx++;
		endforeach;?>
		<?php endif;?>
		</table>
		</td>
		<td align="center"><?=$rows->satuan_name?></td>
	</tr>
	<?php 
	$i++;
	$x++;
	endforeach;
	endif;
	?>
	</table>
</div>

<br>
<div align="center" class="ui-widget-content ui-corner-all">
<input type="submit" value="<?=$this->lang->line('process')?>" id="saving">
<?php endif;?>
<input type="button" value="<?=$this->lang->line('cancel')?>" onclick="del_all('<?=$adj_id?>');">
</div>
</form>
