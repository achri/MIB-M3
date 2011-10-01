<script language="javascript">
function win_alasan() {
	$('#dialog_acc').dialog('open');	
}

function close_po() {
	
	return false;
	//$('#dialog_acc').dialog('close');
}

$(document).ready(function(){
	$('#dialog_acc').dialog({
		bgiFrame : true,
		autoOpen : false,
		width : 'auto',
		height : 'auto',
		resizable: false,
		draggable: false
	});

	$('#dialog_form').validate({
		submitHandler: function(form){
			$(form).ajaxSubmit({
				url : 'index.php/<?=$link_controller?>/close_po/',
				type: 'POST',
				success: function(data) {
					if (data) {
						//$('#dialog_acc').dialog('close');
						location.href = 'index.php/<?=$link_controller?>/index';
					}
				}
			});
		},
		focusInvalid: true,
		focusCleanup: true,
		highlight: function(element, errorClass) {
			$(element).addClass('ui-state-error');
		},
		unhighlight: function(element, errorClass) {
			$(element).removeClass('ui-state-error');
		}	
	});

});
</script>
<H3>
MENU DAFTAR PO MASIH BUKA : DETAIL PO <strong><?=$po_id?></strong>
</H3>
<div id="dialog_acc" title="PO NOTE" align="center">
<form id="dialog_form">
<INPUT type="hidden" name="po_id" value="<?=$po_id?>">
<textarea name="po_note" rows="5" cols="20" class="required" title=" "></textarea><br/>
<input type="submit" value="CLOSE PO">
</form>
</div>
<?php 
if ($data_po_det->num_rows() > 0):
$po_row = $data_po_det->row();
?>
<table width="100%"  border="0" cellspacing="2" cellpadding="2" class="ui-corner-all ui-widget-content">
  <tr>
    <td width="10%" class="head_title">PO No</td>
	<td width="5%" class="head_title">:</td>
    <td width="30%" class="head_title_content"><?=$po_row->po_no?></td>
    <td width="15%">&nbsp;</td>
    <td width="11%" class="head_title">Supplier</td>
	<td width="5%" class="head_title">:</td>
    <td width="30%" class="head_title_content"><?=$po_row->sup_name?></td>
  </tr>
  <tr>
    <td class="head_title">Tgl PO</td>
	<td width="5%" class="head_title">:</td>
    <td class="head_title_content"><?=$po_row->po_date?></td>
    <td>&nbsp;</td>
    <td></td>
    <td></td>
	<td></td>
  </tr>
  <tr>
    <td colspan="7">&nbsp;</td>
  </tr>
</table>
<br>
<table width="100%"  border="0" cellpadding="1" cellspacing="1" class="ui-widget-content ui-corner-all">
        <tr class="ui-widget-header">
		  <td colspan="7">Daftar Pesanan (PO)</td>
		</tr>
		<tr class="ui-widget-header">
		  <td width="5%" align="center">No</td>
		  <td width="45%" align="center">Barang/Kode</td>
		  <td width="8%" align="center">Satuan</td>
		  <td width="8%" align="center">Pesan</td>
		  <td width="8%" align="center">Terima</td>
		  <td width="8%" align="center">Retur</td>
		  <td width="18%" align="center">+/-</td>
	    </tr>
		<?php 
		$no = 1;
		if ($data_po_det_list->num_rows() > 0):
		foreach ($data_po_det_list->result() as $po_det_row):
		?>
		<tr bgcolor="lightgray">
		  <td valign="top" align="right" class="ui-state-active"><?=$no?></td>
		  <td valign="top" align="left"><?=$po_det_row->pro_name?> (<?=$po_det_row->pro_code?>)</td>
		  <td valign="top" align="center"><?=$po_det_row->satuan_name?></td>
		  <td valign="top" align="right"><?=$po_det_row->qty?></td>
		  <td valign="top" align="right"><?=$po_det_row->qty_terima?></td>
		  <td valign="top" align="right"><font color="red"><?=$po_det_row->qty_retur?></font></td>
		  <td valign="top" align="right"><div style="float:left"><?=$po_det_row->qty_status?></div><?=$po_det_row->qty_remain?></td>
		</tr>
		<?php
		$no++;
		endforeach;
		else:
		?>
		<tr><td colspan="7"><?=$this->lang->line('data_empty')?></td></tr>
		<?php 
		endif;
		?>
</table>
<br />
<table width="100%"  border="0" cellpadding="1" cellspacing="1" class="ui-corner-all ui-widget-content">
        <tr class="ui-widget-header">
		  <td colspan="8">Daftar Good Receive/Good Return</td>
		</tr>
		<tr class="ui-widget-header">
		  <td width="5%" align="center">No</td>
		  <td width="45%" align="center">Barang/Kode</td>
		  <td width="15%" align="center">Tgl Good Receive/Good Return</td>
		  <td width="8%" align="center">Receive/Return</td>
		  <td width="8%" align="center">Nomor</td>
		  <td width="10%" align="center">S.Jalan</td>
		  <td width="10%" align="center">Jml.Terima</td>
		  <td width="10%" align="center">Jml.Retur</td>
	    </tr>
		<?php 
		$no = 1;
		if ($data_gr_grl_list->num_rows() > 0):
		foreach ($data_gr_grl_list->result() as $gr_grl_row):
		?>
		<tr bgcolor="lightgray">
		  <td valign="top" align="right" class="ui-state-active"><?=$no?>.</td>
		  <td valign="top" align="left"><?=$gr_grl_row->pro_name?> (<?=$gr_grl_row->pro_code?>)</td>
		  <td valign="top" align="center"><?=$gr_grl_row->gr_date?></td>
		  <td valign="top" align="center"><?=($gr_grl_row->gr_type=='rec')?('Receive'):('Return')?></td>
		  <td valign="top" align="center"><?=$gr_grl_row->gr_no?></td>
		  <td valign="top" align="center"><?=$gr_grl_row->gr_suratJalan?></td>
		  <td valign="top" align="right"><?=($gr_grl_row->gr_type=='rec')?($gr_grl_row->qty):('-')?></td>
		  <td valign="top" align="right"><?=($gr_grl_row->gr_type=='ret')?($gr_grl_row->qty):('-')?></td>
		</tr>
		<?php 
		$no++;
		endforeach;
		else:
		?>
		<tr>
		 <td align="center" colspan="8"><font color="red">--Tidak ada data--</font></td>
		</tr>
		<?php 
		endif;
		?>
</table>
<br>
<div align="center" class="ui-widget-content ui-corner-all">
		<INPUT TYPE="button" value="TUTUP PO INI" onclick="win_alasan();">
		<INPUT TYPE="button" value="KEMBALI KE DAFTAR" onclick="document.location='index.php/mod_list/list_po'">
</div>
<?php else:?>

<?php endif;?>