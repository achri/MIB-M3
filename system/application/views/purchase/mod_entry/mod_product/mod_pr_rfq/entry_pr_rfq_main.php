<?php 
	//echo $js_grid;
?>
<!-- table id="pr_list" style="display: none" class=""></table-->
<script language="javascript">
	/*
	$('#pr_rfq_form').validate({
		submitHandler: function (form) {
			$(form).ajaxSubmit({
				
			});
		}
	});
	*/

function cek_val() {
	var ret = false;
	var cek_box = $('#pr_rfq_form :checked').length;
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
	
	$("#buat").click(function() {
		var cek_box = $('#pr_rfq_form :checked').length;
		if (cek_box > 0) {
			// KONFIRMASI
			$('#buat').attr('disabled',true);
			$('.dialog_konfirmasi').dialog('destroy').dialog({
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
						$("#pr_rfq_form").ajaxSubmit({
							url : 'index.php/<?=$link_controller?>/buat_rfq',
							data : $(this).formSerialize(),
							type: 'POST',
							success: function (data) {
								var info;	
								if(data) {
									info = '<strong>Selamat... PR berhasil diubah ke RFQ <br> No RFQ : <font color="red">'+data+' </font></strong>';
									$('#dlg_confirm').html('').html(info).dialog('option','buttons', 
									{ "Keluar" : function() {
										$("#dlg_confirm").dialog('close');
										location.href = 'index.php/<?=$link_controller?>/index';
									}}).dialog('open');
								} else {
									info = '<STRONG>Maaf... Proses ubah PR ke RFQ tidak berhasil dilakukan</STRONG>';
									$('#dlg_confirm').html('').html(info).dialog('option','buttons', 
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
			info = '<STRONG>Maaf... Pilihlah PR yang akan diubah</STRONG>';
			$('#dlg_confirm').html('').html(info).dialog('option','buttons', 
			{ "Keluar" : function() {
				$('#dlg_confirm').dialog('close');
			}}).dialog('open');
		}
		return false;
	});
	
	$('#ubah').click(function() {
		//$('#pr_rfq_form :checkbox').attr('checked',false
		var cek_box = $('#pr_rfq_form :checked').length;
		if (cek_box > 0) {
			$('#buat').attr('disabled',true);
			$('#ubah').attr('disabled',true);
			$('.dialog_konfirmasi').dialog('destroy').dialog({
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
						$('#ubah').attr('disabled',false);
						$(this).dialog('close');
					},
					'<?=$this->lang->line('ok')?>' : function() {
						$("#pr_rfq_form").ajaxSubmit({
							url : 'index.php/<?=$link_controller?>/ubah_rfq',
							data : $(this).formSerialize(),
							type: 'POST',
							success: function (data) {
								var info;	
								if(data) {
									info = '<strong>Selamat... PR '+data+' berhasil dihapus !!!</strong>';
									$('#dlg_confirm').html('').html(info).dialog('option','buttons', 
									{ "Keluar" : function() {
										$("#dlg_confirm").dialog('close');
										location.href = 'index.php/<?=$link_controller?>/index';
									}}).css('color','red').dialog('open');
								} else {
									info = '<STRONG>Maaf... Proses tolak PR tidak berhasil dilakukan</STRONG>';
									$('#dlg_confirm').html('').html(info).dialog('option','buttons', 
									{ "Keluar" : function() {
										$('#dlg_confirm').dialog('close');
									}}).css('color','red').dialog('open');
								}
							}
						});
						$(this).dialog('close');
					}
				}
			}).html('').html('<?=$this->lang->line('confirm')?>').dialog('open');
		}else {
			info = '<STRONG>Maaf... Pilihlah PR yang akan dihapus</STRONG>';
			$('#dlg_confirm').html('').html(info).dialog('option','buttons', 
			{ "Keluar" : function() {
				$('#dlg_confirm').dialog('close');
			}}).dialog('open');
		}
		return false;
	});
});
</script>
<div id="dlg_confirm" title="INFORMASI"></div>
<h2><?=$page_title?></h2>
<?php if (!isset($empty)):?>
<form id="pr_rfq_form">
<div align="center">
<table class="ui-widget-content ui-corner-all" border='1' cellpadding="5" cellspacing="0" width="98%" height="100%" >
<tr class="ui-widget-header ui-state-default" align="center">
<td width="1%"><?=$this->lang->line('no')?></td>
<td width="1%"><?=$this->lang->line('ubah')?></td>
<td width="10%"><?=$this->lang->line('pr_no')?></td>
<td width=""><?=$this->lang->line('pr_date')?></td>
<td width="30%"><?=$this->lang->line('pro_name')?></td>
<td width=""><?=$this->lang->line('cat_id')?></td>
<td width=""><?=$this->lang->line('usr_name')?></td>
<td width=""><?=$this->lang->line('dep_name')?></td>
<td width=""><?=$this->lang->line('qty')?></td>
<td width=""><?=$this->lang->line('emergencyStat')?></td>
</tr>
<?php 
if ($pr_list->num_rows() > 0):
$i=1;
foreach($pr_list->result() as $row_rfq):
?>
<tr>
<td valign="top" class="ui-widget-header"><?=$i?></td>
<td valign="top" align="center">
	<input type="checkbox" name="chk_pr[]" value="<?=$row_rfq->pr_id?>_<?=$row_rfq->pro_id?>_<?=$row_rfq->pr_no?>_<?=$row_rfq->pro_name?>">
</td>
<td valign="top"><?=$row_rfq->pr_no?></td>
<td valign="top"><?=$row_rfq->pr_date?></td>
<td valign="top"><?=$row_rfq->pro_name?></td>
<td valign="top">
<?php 
//$cat_nam = $this->set_split_code();
//echo $row_rfq->cat_name;
echo implode($this->pro_code->set_split_code($row_rfq->pro_code,'cat_name'),'/');
?>
</td>
<td valign="top"><?=$row_rfq->usr_name?></td>
<td valign="top"><?=$row_rfq->dep_name?></td>
<td valign="top">
<table width="100%" border=0 cellspacing=0 cellpadding=0>
<tr>
<td align="left"><?=$row_rfq->satuan_name?>&nbsp;</td>
<td align="right">
<?=number_format($row_rfq->qty,$row_rfq->satuan_format)?>
</td>
</tr></table>
</td>
<td valign="top"><?=($row_rfq->emergencyStat=='0')?('Normal'):('<font color=red>Darurat</font>')?></td>
</tr>
<?php
$i++;
endforeach; 
else:
?>
<tr><td colspan="10" align="center"><?=$this->lang->line('data_empty')?></td></tr>
<?php 
endif;
?>
</table>
</div>
<br>
<?php if ($pr_list->num_rows() > 0): ?>
<div class="ui-widget-content ui-corner-all" align="center" style="width: 98%;margin:auto;">
<input type="button" id="buat" value="<?=$this->lang->line('buatRfq')?>">&nbsp;<input type="button" id="ubah" value="<?=$this->lang->line('hapusItem')?>">
</div>
<?php endif;?>
</form>
<?php else: echo $empty; endif;?>