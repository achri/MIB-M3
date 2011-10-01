<script language="javascript">
var sel_id, info, valid, limit_text = 30;

function open_history(pro_id) {
	$.ajax({
		url: 'index.php/<?=$link_controller?>/get_history/'+pro_id,
		type: 'post',
		success: function(data) {
			$('#dlg_history').html('').append(data).dialog('open');
		}
	});
}

function clear_alasan() {
	//$("#stats_"+sel_id).val('');
	$("#salasan_"+sel_id).html('');
	$("#alasan_"+sel_id).val('');
}

function show_alasan(row_id) {
	var sget_alasan = $("#alasan_"+row_id).val();
	var sview_alasan = '<div style="overflow:auto; width:200px">'+sget_alasan+'</div>'; 
	$('#dlg_info').html('').html(sview_alasan).dialog('open');
}

$(document).ready(function(){
	$('#dlg_history,#dlg_info').dialog({
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

	$('#dlg_alasan').dialog({
		autoOpen: false,
		bgiframe: true,
		width: 'auto',
		height: 'auto',
		resizable: false,
		position: 'center',
		modal: true,
		buttons: {
			"<?=$this->lang->line('cancel')?>": function() {
				clear_alasan();
				$("#stats_"+sel_id).val('');
				$(this).dialog('close');
			},
			"<?=$this->lang->line('ok')?>": function() {
				var show_batas = '<a href="javascript:void(0)" onclick="show_alasan('+sel_id+');"> ...</a>';
				var get_alasan = $('#text_alasan').val();
				if (get_alasan != '') {
					$("#alasan_"+sel_id).val(get_alasan);
					var batas = get_alasan.substr(0,limit_text);	
					$("#salasan_"+sel_id).html(batas+show_batas);
					$(this).dialog('close');
				}
			}
		}
	});

	$('.stats').change(function() {
		var adj_stat = $(this).val();
		if (adj_stat == 2) {
			sel_id = $(this).attr('row_no');
			$('#text_alasan').val('');
			$('#dlg_alasan').dialog('open');
		} else if (adj_stat == 1) {
			sel_id = $(this).attr('row_no');
			clear_alasan();
		} else { sel_id = 0; }
	});
	
	$('#app_adj').validate({
		submitHandler: function(form) {
			valid = false;
			$('.stats').each(function(i){
				var vals = $(this).val();
				if (vals == '') {
					valid = true;
				}
			});
			
			if (valid == false) {
				$('#saving').attr('disabled','disabled');
				
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
							$('#saving').attr('disabled',false);
							$(this).dialog('close');
						},
						'<?=$this->lang->line('ok')?>' : function() {
							$(form).ajaxSubmit({
								url: 'index.php/<?=$link_controller?>/set_approve',
								type: 'POST',
								success: function (data) {
									if (data) {
										info = 'Selamat ... Proses Izin Penyesuaian <br> berhasil di lakukan ... <br> No Penyesuaian : <b><font color="red">'+data+'</font></b>';
										$('#dlg_info').html('').html(info).dialog('option','buttons',{
											"<?=$this->lang->line('close')?>": function() {
												location.href='index.php/<?=$link_controller?>';
												$(this).dialog('close');
											}
										}).dialog('open');
									}
									else {
										info = 'Maaf ... Proses Izin Penyesuaian <br> tidak berhasil di lakukan !!!';
										$('#dlg_info').html('').html(info).dialog('option','buttons',{
											"<?=$this->lang->line('close')?>": function() {
												location.href='index.php/<?=$link_controller?>';
												$(this).dialog('close');
											}
										}).dialog('open');
									}
									//$('.informasi').html(data);
								}
							});
							$(this).dialog('close');
						}
					}
				}).html('').html('<?=$this->lang->line('confirm')?>').dialog('open');
			}
		},
		focusInvalid: true,
		focusCleanup: true,
		highlight: function(element, errorClass) {
			$(element).addClass('ui-state-active');
		},
		unhighlight: function(element, errorClass) {
			$(element).removeClass('ui-state-active');
		},
		rules: {
			
		},
		messages: {
			
		}
	});

});
</script>
<div id="dlg_history" title="Penyesuaian History"></div>
<div id="dlg_alasan" title="Alasan Ditolak">
<textarea rows="5" cols="10" id="text_alasan" style="overflow: auto"></textarea>
</div>
<div id="dlg_info" title="Informasi"></div>
<form id="app_adj" class="cmxform">
<center>
<div class="ui-corner-all headers">
<table width="600px">
	<tr>
		<td class="labelcell" width="150">Penyesuaian NO</td>
		<td class="labelcell2">: <?=$list_adj->row()->adj_no?></td>
		<td width="20%"></td>
		<td class="labelcell" width="100">Departemen</td>
		<td class="labelcell2">: <?=$list_adj->row()->dep_name?></td>
	</tr>
	<tr>
		<td class="labelcell">Tanggal Penyesuaian</td>
		<td class="labelcell2">: <?=$list_adj->row()->adj_date?></td>
		<td></td>
		<td class="labelcell">PEMOHON</td>
		<td class="labelcell2">: <?=$list_adj->row()->usr_name?></td>
	</tr>
</table>
</div>
<br>
<input type="hidden" name="adj_id" value="<?=$list_adj->row()->adj_id?>">
<input type="hidden" name="adj_no" value="<?=$list_adj->row()->adj_no?>">
<table width="90%" id="dataview" class="table">
	<tr class='ui-widget-header' align="center">
		<td rowspan="2">Status</td>
		<td colspan="2">STOK</td>
		<td rowspan="2">Satuan</td>
		<td rowspan="2">Tanggal Opname</td>
		<td rowspan="2" width="15%">Pemeriksa</td>
		<td rowspan="2" width="20%">Alasan</td>
		<td rowspan="2" width="15%">Pemasok (kode)</td>
		<td rowspan="2" width="15%">Nama Produk (kode)</td>
	</tr>
	<tr class='ui-widget-header' align="center">
		<td>Reality</td>
		<td>Opname</td>
	</tr>
<?php 
	$i = 1;
	foreach ($list_adj_det->result() as $rows):
?>
		<tr class='x'>
					<td valign='top' align="center">	
						<input type="hidden" name="inv_id[]" id="inv_id_<?=$i?>" value="<?=$rows->inv_id?>">
						<input type="hidden" name="pro_id[]" id="pro_id_<?=$i?>" value="<?=$rows->adj_pro_id?>">
						<input type="hidden" name="sup_id[]" id="sup_id_<?=$i?>" value="<?=$rows->adj_sup_id?>">
						<select name='stats[]' id='stats_<?=$i?>' class='stats required' row_no="<?=$i?>" validation='required:true' titel=" ">
							<option value=''>-Pilih Status-</option>
							<option value='1'>-Disetujui</option>
							<option value='2'>-Ditolak</option>
						</select>
						<input type="hidden" name="alasan[]" id="alasan_<?=$i?>" class="wew">
						<div id="salasan_<?=$i?>" style="font-style: italic; color:red; text-align: left; width: 100px; overflow: auto" class="wew"></div>
					</td>
					<td valign='top' align="center">
						<input type="hidden" name="qty_stok[]" id="qty_stok_<?=$i?>" value="<?=$rows->qty_stock?>">
						<input type="hidden" name="inv_end[]" id="inv_end_<?=$i?>" value="<?=$rows->inv_end?>">
						<?=$this->general->digit_number($rows->um_id,$rows->qty_stock)?>
					</td>
					<td valign='top' align="center">
						<input type="hidden" name="qty_opname[]" id="qty_opname_<?=$i?>" value="<?=$rows->qty_opname?>">
						<?=$this->general->digit_number($rows->um_id,$rows->qty_opname)?>
					</td>
					
					<td valign='top' align="center">
						<?=$rows->satuan_name?>
					</td>
					
					<td valign='top' align="center">
						<?=$rows->adj_date?>
					</td>
					<td valign='top'>
						<?=$rows->check_opname?>
					</td>
					<td valign='top'>
						<?=$rows->description?>
					</td>
					<td valign="top">
						<?=($rows->sup_name!='')?($rows->sup_name):('-')?>
						<br />Kode: <?=($rows->sup_pro_code!='')?($rows->sup_pro_code):('-')?>
					</td>
					<td valign='top'>
						<?=$rows->pro_name?> 
						<br>Kode: <?=$rows->pro_code?>
						<br><a href='javascript:void(0)' onclick="open_history('<?=$rows->pro_id?>');">[lihat History]</a>
					</td>
		</tr>
<?php 
	$i++;
	endforeach;
?>
</table>
<br>
<input type="submit" id="save" value="<?php echo $this->lang->line('save');?>" id="saving">
<input type="button" value="<?php echo $this->lang->line('cancel');?>" onclick="location.href='index.php/<?=$link_controller?>';">
</form>
</center>
