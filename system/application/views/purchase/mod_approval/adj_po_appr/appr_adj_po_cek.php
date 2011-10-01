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
	$("#salasan").html('');
	$("#alasan").val('');
}

function show_alasan() {
	var sget_alasan = $("#alasan").val();
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
				$("#stats").val('');
				$(this).dialog('close');
			},
			"<?=$this->lang->line('ok')?>": function() {
				var show_batas = '<a href="javascript:void(0)" onclick="show_alasan();"> ...</a>';
				var get_alasan = $('#text_alasan').val();
				if (get_alasan != '') {
					$("#alasan").val(get_alasan);
					var batas = get_alasan.substr(0,limit_text);	
					$("#salasan").html(batas+show_batas);
					$(this).dialog('close');
				}
			}
		}
	});

	$('.stats').change(function() {
		var adj_stat = $(this).val();
		if (adj_stat == 0) {
			//sel_id = $(this).attr('row_no');
			$('#text_alasan').val('');
			$('#dlg_alasan').dialog('open');
		} else if (adj_stat == 3) {
			//sel_id = $(this).attr('row_no');
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
				$('.saving').attr('disabled',true);
				
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
							$('.saving').attr('disabled',false);
							$(this).dialog('close');
						},
						'<?=$this->lang->line('ok')?>' : function() {
							$(form).ajaxSubmit({
								url: 'index.php/<?=$link_controller?>/set_approve',
								type: 'POST',
								success: function (data) {
									if (data) {
										info = 'Selamat ... Proses Izin Penyesuaian <br> berhasil di lakukan ... <br> No GR : <b><font color="red">'+data+'</font></b>';
										$('#dlg_info').html('').html(info).dialog('option','buttons',{
											"<?=$this->lang->line('close')?>": function() {
												location.href='index.php/<?=$link_controller?>';
												$(this).dialog('close');
											}
										}).dialog('open');
									}
									else {
										info = 'Maaf ... Proses Izin Penyesuaian GR <br> tidak berhasil di lakukan !!!';
										$('#dlg_info').html('').html(info).dialog('option','buttons',{
											"<?=$this->lang->line('close')?>": function() {
												location.href='index.php/<?=$link_controller?>';
												$(this).dialog('close');
											}
										}).dialog('open');
									}
									//$('.informasi').html(data);
									$('.saving').attr('disabled',false);
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
<div id="dlg_history" title="History Penyesuaian Harga PO"></div>
<div id="dlg_alasan" title="Alasan Ditolak">
<textarea rows="5" cols="10" id="text_alasan" style="overflow: auto"></textarea>
</div>
<div id="dlg_info" title="Informasi"></div>
<form id="app_adj" class="cmxform">
<center>
<div class="ui-corner-all headers">
<table width="650px">
	<tr>
		<td class="labelcell" width="200px">Nomor BPB</td>
		<td class="labelcell2" width="200px">: <?=$list_adj->row()->gr_no?></td>
		<td width="5%"></td>
		<td class="labelcell">Pemasok</td>
		<td class="labelcell2">: <?=$list_adj->row()->sup_name?></td>
	</tr>
	<tr>
		<td class="labelcell">Tanggal BPB</td>
		<td class="labelcell2">: <?=$list_adj->row()->gr_date?></td>
		<td></td>
		<td class="labelcell" width="100px">Departemen</td>
		<td class="labelcell2" width="200px">: <?=$list_adj->row()->dep_name?></td>
		
	</tr>
	<tr>
		<td class="labelcell">Tanggal Penyesuaian</td>
		<td class="labelcell2">: <?=$list_adj->row()->date_edit?></td>
		<td></td>
		<td class="labelcell">PEMOHON</td>
		<td class="labelcell2">: <?=$list_adj->row()->usr_name?></td>
	</tr>
</table>
</div>
<br>
<input type="hidden" name="gr_id" value="<?=$list_adj->row()->gr_id?>">
<input type="hidden" name="gr_no" value="<?=$list_adj->row()->gr_no?>">
<input type="hidden" name="po_id" value="<?=$list_adj->row()->po_id?>">
<table width="90%" id="dataview" class="table">
	<tr class='ui-widget-header' align="center">
		<!--td rowspan="2">Status</td-->		
		<td rowspan="2" width="15%">Nama Produk (kode)</td>
		<td rowspan="2">Kuantitas</td>
		<td rowspan="2">Satuan</td>
		<td colspan="2">Harga Satuan</td>
		<td colspan="2">Total Harga</td>
	</tr>
	<tr class='ui-widget-header' align="center">
		<!--td>Sebelum</td>
		<td>Sesudah</td-->
		<td>Sebelum</td>
		<td>Sesudah</td>
		<td>Sebelum</td>
		<td>Sesudah</td>
	</tr>
<?php 
	$i = 1;
	foreach ($list_adj_det->result() as $rows):
		if ($rows->price_sebelum != $rows->price_sesudah):
?>
		<tr class='x'>
			<td valign='top'>
						<?=$rows->pro_name?> 
						<br>Kode: <?=$rows->pro_code?>
						<br><a href='javascript:void(0)' onclick="open_history('<?=$rows->pro_id?>');">[lihat History]</a>
						<input type="hidden" name="pro_id[]" value="<?=$rows->pro_id?>">
					</td>
					<!--td valign='top' align="center">	
						<input type="hidden" name="pro_id[]" value="<//?=$rows->pro_id?>">
						<select name='stats[]' id='stats_<//?=$i?>' class='stats required' row_no="<//?=$i?>" validation='required:true' titel=" ">
							<option value=''>-Pilih Status-</option>
							<option value='3'>-Disetujui</option>
							<option value='0'>-Ditolak</option>
						</select>
						<input type="hidden" name="alasan[]" id="alasan_<//?=$i?>" class="wew">
						<div id="salasan_<//?=$i?>" style="font-style: italic; color:red; text-align: left; width: 100px; overflow: auto" class="wew"></div>
					</td-->
					<td valign='top' align="center">
						<?=$this->general->digit_number($rows->um_id,$rows->qty_sebelum)?>
						<input type="hidden" name="qty_sebelum[]" value="<?=$rows->qty_sebelum?>">
					</td>
					
					<!--td valign='top' align="center">
						<//?=$this->general->digit_number($rows->um_id,$rows->qty_sesudah)?>
						<input type="hidden" name="qty_sesudah[]" value="<//?=$rows->qty_sesudah?>">
					</td-->
					
					<td valign='top' align="center">
						<?=$rows->satuan_name?>
					</td>
					
					<td valign='top' align="right">
						<?=$rows->cur_symbol.'. '.number_format($rows->price_sebelum,2)?>
						<input type="hidden" name="price_sebelum[]" value="<?=$rows->price_sebelum?>">
					</td>
					
					<td valign='top' align="right">
						<?=$rows->cur_symbol.'. '.number_format($rows->price_sesudah,2)?>
						<input type="hidden" name="price_sesudah[]" value="<?=$rows->price_sesudah?>">
					</td>
					
					<td valign='top' align="right">
						<?php
						$total_sebelum = $rows->qty_sebelum * $rows->price_sebelum;
						echo $rows->cur_symbol.'. '.number_format($total_sebelum,2);
						?>
					</td>
					
					<td valign='top' align="right">
						<?php
						$total_sesudah = $rows->qty_sesudah * $rows->price_sesudah;
						echo $rows->cur_symbol.'. '.number_format($total_sesudah,2);
						?>
					</td>
					
		</tr>
<?php 
		endif;
	$i++;
	endforeach;
?>
	<tr>
	<td colspan="7"><table>
	<tr class='ui-widget-header'>
	<td>Status</td><td>:</td>
	<td valign='top' align="center">	
		<select name='stats' id='stats' class='stats required' validation='required:true'>
		<option value=''>-Pilih Status-</option>
		<option value='3'>-Disetujui</option>
		<option value='0'>-Ditolak</option>
		</select>
		<input type="hidden" name="alasan" id="alasan" class="wew">
		<div id="salasan" style="font-style: italic; color:red; text-align: left; width: 100px; overflow: auto" class="wew"></div>
	</td>
	</tr>
	</table>
	</td></tr>
</table>
<br>
<input type="submit" id="save" value="<?php echo $this->lang->line('save');?>" class="saving">
<input type="button" value="<?php echo $this->lang->line('cancel');?>" onclick="location.href='index.php/<?=$link_controller?>';">
</form>
</center>
