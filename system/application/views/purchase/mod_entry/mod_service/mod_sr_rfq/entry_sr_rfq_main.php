<?php 
	//echo $js_grid;
?>
<!-- table id="sr_list" style="display: none" class=""></table-->
<script language="javascript">
	/*
	$('#sr_rfq_form').validate({
		submitHandler: function (form) {
			$(form).ajaxSubmit({
				
			});
		}
	});
	*/

function cek_val() {
	var ret = false;
	var cek_box = $('#sr_rfq_form :checked').length;
	if (cek_box > 0) {
		ret = true;
	}
	return ret;
	alert(cek_box);
}

$(document).ready(function(){

	$('#dlg_confirm').dialog({
		autoOpen: false,
		bgiframe: true,
		width: 'auto',
		height: 'auto',
		resizable: false,
		draggable: false,
		modal:true,
		position:'center'
	});
	
	$("#sr_rfq_form").submit(function() {
		var cek_box = $('#sr_rfq_form :checked').length;
		if (cek_box > 0) {
			// KONFIRMASI
			$('#buat').attr('disabled',true);
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
						$('#buat').attr('disabled',false);
						$(this).dialog('close');
					},
					'<?=$this->lang->line('ok')?>' : function() {
						$("#sr_rfq_form").ajaxSubmit({
							url : 'index.php/<?=$link_controller?>/buat_srfq',
							data : $(this).formSerialize(),
							type: 'POST',
							success: function (data) {
								var info;	
								if(data) {
									info = '<strong>Selamat... SR berhasil di ubah ke RFQ Servis <br> RFQ kode : <font color="red">'+data+' </font></strong>';
									$('#dlg_confirm').text('').append(info).dialog('option','buttons', 
									{ "Keluar" : function() {
										$("#dlg_confirm").dialog('close');
										location.href = 'index.php/<?=$link_controller?>/index';
									}}).dialog('open');
								} else {
									info = '<STRONG>Maaf... Proses ubah SR ke RFQ Servis Tidak Berhasil Di Lakukan</STRONG>';
									$('#dlg_confirm').text('').append(info).dialog('option','buttons', 
									{ "Keluar" : function() {
										$('#dlg_confirm').dialog('close');
									}}).dialog('open');
								}
							}
						});
						$(this).dialog('close');
					}
				}
			}).html('').html('<?=$this->lang->line('confirm')?>').dialog('open');
			
		}else {
			info = '<STRONG>Maaf... Pilih SR yang akan di ubah</STRONG>';
			$('#dlg_confirm').text('').append(info).dialog('option','buttons', 
			{ "Keluar" : function() {
				$('#dlg_confirm').dialog('close');
			}}).dialog('open');
		}
		return false;
	});
	
	$('#ubah').click(function() {
		$('#sr_rfq_form :checkbox').attr('checked',false);
	});
});
</script>
<div id="dlg_confirm" title="Konfirmasi"></div>
<h2><?=$page_title?></h2>
<?php if (!isset($empty)):?>
<form id="sr_rfq_form">
<div align="center">
<table class="ui-widget-content ui-corner-all" width="90%" height="100%" border="1" cellpadding="5" cellspacing="0">
	<tr class="ui-widget-header ui-state-default">
		<td width="5%" align="center">No</td>
		<td width="5%" align="right">&nbsp;</td>
		<td width="10%" align="center">No SR</td>
		<td width="20%" align="center">Nama Produk</td>
		<td width="25%" align="center">Kategori</td>
		<td width="10%" align="center">SR Date</td>
		<td width="10%" align="center">Qty</td> 
		<td width="10%" align="center">Kategori Service</td>
		<td width="10%" align="center">Tipe Service</td>
	</tr>
	<?php 
	if ($sr_list->num_rows() > 0):
	$i=1;
	foreach($sr_list->result() as $row_rfq):
	?>
	<tr>
		<td class="ui-widget-header" align="right"><?=$i?></td>
		<td valign="top" align="left">
			<input type="checkbox" name="chk_sr[]" value="<?=$row_rfq->sr_id?>_<?=$row_rfq->pro_id?>">
		</td>
		<td valign="top" align="left"><?=$row_rfq->sr_no?></td>
		<td valign="top" align="left"><?=$row_rfq->pro_name?></td>
		<td valign="top" align="left"><?=implode($this->pro_code->set_split_code($row_rfq->pro_code,'cat_name'),'/')?></td>
		<td valign="top" align="left"><?=$row_rfq->sr_date?></td>
		<td valign="top" align="right"><?=number_format($row_rfq->qty,$row_rfq->satuan_format)?>&nbsp;<?=$row_rfq->satuan_name?></td>
		<td valign="top" align="center"><?=($row_rfq->service_cat=='in')?('Inside'):('Outside')?></td>
		<td valign="top" align="center"><?=($row_rfq->service_type=='repair')?('Perbaikan'):('Perawatan')?></td>
	</tr>
	<?php
	$i++;
	endforeach; 
	else:
	?>
	<tr>
		<td colspan="8" align="center"><?=$this->lang->line('data_empty')?></td>
	</tr>
	<? endif;?>
</table>
</div>
<br>
<?php if ($sr_list->num_rows() > 0): ?>
<div class="ui-widget-content ui-corner-all" align="center" style="width: 90%;margin:auto;">
<input type="submit" id="buat" value="Buat RFQ Servis">
</div>
<?php endif;?>
</form>
<?php else: echo $empty; endif;?>