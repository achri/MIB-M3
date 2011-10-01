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

function batal_ubah() {
	$('.rets').each(function(){
		var qty = $(this).attr('qty');
		$(this).val(qty);
	});
	$('.ubah').hide();
	$('.default').show();
	return false;
}

function clear_alasan() {
	$("#salasan").html('');
	$("#alasan").val('');
}

function show_alasan() {
	var sget_alasan = $("#alasan").val();
	var sview_alasan = '<div style="overflow:auto; width:200px">'+sget_alasan+'</div>'; 
	$('#dlg_info').html('').html(sview_alasan).dialog('open');
}

$(document).ready(function(){
	masking('.number');
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
				var get_alasan = $('#text_alasan').val();
				if (get_alasan != '') {
					$("#alasan").val(get_alasan);
					$(this).dialog('close');
				}
			}
		}
	});

	$('.stats').change(function() {
		var ret_stat = $(this).val();
		if (ret_stat == 0 || ret_stat == 1) {
			clear_alasan();
			batal_ubah();
		} else if (ret_stat == 2) {
			$('.ubah').show();
			$('.default').hide();
		} else if (ret_stat == 3) {
			batal_ubah();
			$('#text_alasan').val('');
			$('#dlg_alasan').dialog('open');
		} else { sel_id = 0; }
	});
	
	//var form = $('#app_ret');
	$('#app_ret').validate({
		submitHandler: function(form) {
			valid = false;
			$('.stats').each(function(i){
				var vals = $(this).val();
				if (vals == '0') {
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
							unmasking('.number');
							$(form).ajaxSubmit({
								url: 'index.php/<?=$link_controller?>/set_approve',
								type: 'POST',
								data: $(form).formSerialize(),
								success: function (data) {
									if (data) {
										info = 'Selamat ... Proses Izin Retur Barang <br> berhasil di lakukan ... <br> No Retur : <b><font color="red">'+data+'</font></b>';
										$('#dlg_info').html('').html(info).dialog('option','buttons',{
											"<?=$this->lang->line('close')?>": function() {
												location.href='index.php/<?=$link_controller?>';
												$(this).dialog('close');
											}
										}).dialog('open');
									}
									else {
										info = 'Maaf ... Proses Izin Retur Barang <br> tidak berhasil di lakukan !!!';
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
<div id="dlg_info" title="INFORMASI"></div>
<div id="dlg_history" title="History Penyesuaian GR"></div>
<div id="dlg_alasan" title="Alasan Ditolak">
<textarea rows="5" cols="10" id="text_alasan" style="overflow: auto"></textarea>
</div>
<form id="app_ret" class="cmxform">
<center>
<div class="ui-corner-all headers">
<table width="90%" border="0" cellpadding="2" cellspacing="0">
	<tr>
		<td class="labelcell" width="150px">Nomor Retur</td>
		<td class="labelcell2">: <?=$list_ret->row()->ret_no?></td>
		<td width="5%"></td>
		<td class="labelcell" width="150px">Pemasok</td>
		<td class="labelcell2">: <?=$list_ret->row()->sup_name?></td>
	</tr>
	<tr>
		<td class="labelcell">Nomor PO</td>
		<td class="labelcell2">: <?=$list_ret->row()->po_no?></td>
		<td></td>
		<td class="labelcell">Departemen</td>
		<td class="labelcell2">: <?=$list_ret->row()->dep_name?></td>
	</tr>
	<tr>
		<td class="labelcell">Tanggal Retur</td>
		<td class="labelcell2">: <?=$list_ret->row()->ret_date?></td>
		<td></td>	
		<td class="labelcell">PEMOHON</td>
		<td class="labelcell2">: <?=$list_ret->row()->usr_name?></td>
	</tr>
</table>
</div>
<br>
<input type="hidden" name="ret_id" value="<?=$list_ret->row()->ret_id?>">
<input type="hidden" name="ret_no" value="<?=$list_ret->row()->ret_no?>">
<input type="hidden" name="po_id" value="<?=$list_ret->row()->po_id?>">
<table width="90%" id="dataview" class="table">
	<tr class='ui-widget-header' align="center">
		<td rowspan="2">No</td>
		<td rowspan="2" width="30%">Nama Produk (kode)</td>
		<td colspan="2" width="30%">Kuantitas</td>
		<td rowspan="2" width="10%">Satuan</td>
		<td rowspan="2" width="40%">Keterangan</td>
	</tr>
	<tr class='ui-widget-header' align="center">
		<td width="10%">Terima</td>
		<td width="10%">Retur</td>
	</tr>
<?php 
	$i = 1;
	foreach ($list_ret_det->result() as $rows):
?>
		<tr class='x'>
			<td valign='top'><?=$i?>. </td>
			<td valign='top'>
						<?=$rows->pro_name?> 
						<br>Kode: <?=$rows->pro_code?>
						<br><a href='javascript:void(0)' onclick="open_history('<?=$rows->pro_id?>');">[lihat History]</a>
						<input type="hidden" name="pro_id[]" value="<?=$rows->pro_id?>">
					</td>
					
					<td valign='top' align="right">
						<?=number_format($rows->qty_terima,$rows->satuan_format)?>
					</td>
					
					<td valign='top' align="right">
						<div class="default"><?=number_format($rows->qty,$rows->satuan_format)?></div>
						<div class="ubah" style="display:none"><input digit_decimal="<?=$rows->satuan_format?>" size="5" id="ubah_<?=$i?>" type="text" name="qty_ubah[]" value="<?=$this->general->digit_number($rows->um_id,$rows->qty)?>" class="number rets" qty="<?=$this->general->digit_number($rows->um_id,$rows->qty)?>"></div>
					</td>					
					
					<td valign='top' align="center">
						<?=$rows->satuan_name?>
					</td>
					
					<td valign='top' align="left">
						<?php
						echo $rows->keterangan;
						?>
					</td>
					
		</tr>
<?php 
	$i++;
	endforeach;
?>
	<tr class='ui-widget-header' align="center">
	<td colspan="7"><table>
	<tr class='ui-widget-header'>
	<td>Status</td><td>:</td>
	<td valign='top' align="center">	
		<select name='stats' id='stats' class='stats required' validation='required:true'>
		<option value='0'>-Pilih Status-</option>
		<option value='1'>-Disetujui</option>
		<option value='2'>-Diubah dan disetujui</option>
		<option value='3'>-Ditolak</option>
		</select>
		<input type="hidden" name="alasan" id="alasan" class="wew">
	</td>
	</tr>
	</table>
	</td></tr>
</table>
<br>
<input type="submit" id="save" value="<?php echo $this->lang->line('save');?>" id="saving">
<input type="button" value="<?php echo $this->lang->line('cancel');?>" onclick="location.href='index.php/<?=$link_controller?>';">
</form>
</center>
