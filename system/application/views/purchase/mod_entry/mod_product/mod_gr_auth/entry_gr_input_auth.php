<script language="javascript">
function create_auth(pro_id,pr_id,numb) {
	$('#crow_id').val(numb);
	$('#pro_id').val(pro_id);
	$('#pr_id').val(pr_id);
	$('#dialog_auth').dialog('open');
	$(".ui-dialog-buttonpane button:first").css('float','right');
	$(".ui-dialog-buttonpane button:last").css('float','left');	
	/*
	$.ajax({
		url:'index.php/entry_good_receive/create_auth/'+pro_id+'/'+pr_id,
		success: function(data) {
			alert(data);
		}
	});
	*/
	return false;
}

$(document).ready(function() {
	$('#dialog_auth').dialog({
		autoOpen:false,
		bgiFrame:true,
		modal:true,
		width:'auto',
		height:'auto',
		resizable:false,
		draggable:false,
		buttons : {
			'Cancel' :function() {
				$(this).dialog('close');
			},
			'Create' : function() {
				var dlg = $(this);
				$.ajax({
					url:'index.php/<?=$link_controller?>/create_auth',
					type:'POST',
					data: $('#form_auth').serialize(),
					success:function(data){
						var info;	
						if(data) {
							info = '<strong>Selamat... Otorisasi BPB berhasil di buat <br> <font color="red">Kode otorisasi : '+data+' </font></strong>';
							$('#dlg_confirm').text('').append(info).dialog('option','buttons', 
							{ "Keluar" : function() {
								$("#dlg_confirm").dialog('close');
								location.href = 'index.php/<?=$link_controller?>/list_po_det/<?=$po_list->row()->po_id?>/gr_auth';
							}}).dialog('open');
						} else {
							info = '<STRONG>Maaf... Otorisasi BPB tidak berhasil dibuat</STRONG>';
							$('#dlg_confirm').text('').append(info).dialog('option','buttons', 
							{ "Keluar" : function() {
								$('#dlg_confirm').dialog('close');
							}}).dialog('open');
						}
					}
				});
				return false;
			}
		}
	});

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
});
</script>
<div id="dlg_confirm" title="INFORMASI"></div>
<div id="dialog_auth" title="BUAT OTORISASI BPB" align="left">
	<form id="form_auth">
	<input type="hidden" name="crow_id" id="crow_id">
	<input type="hidden" name="pro_id" id="pro_id">
	<input type="hidden" name="pr_id" id="pr_id">
	<table border="0">
	<tr>
		<td>Batas kuantitas</td><td>:</td>
		<td><input type="text" name="auth_qty"></td>
	</tr>
	<tr>
		<td valign="top">Keterangan</td><td valign="top">:</td>
		<td><textarea rows="5" cols="30" name="auth_note"></textarea></td>
	</tr>
	</table>
	</form>
</div>
<h3>MENU OTORISASI DETIL PO <strong><?=$po_list->row()->po_no?></strong></h3>
<form name="form_entry" id="form_entry" action="index.php/<?=$link_controller?>/create_auth" method="post">
<div align="center" class="ui-widget-content ui-corner-all">
<br>
<table align="center" width="90%"  border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td width="10%" class="ui-widget-header">No PO</td>
    <td width="34%"><?=$po_list->row()->po_no?></td>
    <td width="25%">&nbsp;</td>
    <td width="11%" class="ui-widget-header">Pemasok</td>
    <td width="20%"><?=$po_list->row()->sup_name?></td>
  </tr>
  <tr>
    <td class="ui-widget-header">Tanggal PO</td>
    <td><?=$po_list->row()->po_date?></td>
    <td>&nbsp;</td>
    <td colspan="2"></td>
  </tr>
</table>
<br>
<table align="center" width="90%"  class="ui-widget-content ui-corner-all" border="0" cellpadding="2" cellspacing="1">
		<tr class="ui-widget-header">
		  <td width="10%" align="center">No</td>
		  <td width="50%" align="center">Kode Produk</td>
		  <td width="35%" align="center">Nama Produk</td>
		  <td width="5%" align="center">Otorisasi</td>
	    </tr>
		<?php 
		$pdet_no = 1;
		foreach ($po_det->result() as $row_pdet):?>
		<tr bgcolor="lightgray">
		  <td valign="top" align="right" class="ui-state-active"><?=$pdet_no?></td>
		  <td valign="top" align="center"><?=$row_pdet->pro_code?>&nbsp;</td>
		  <td valign="top" align="left"><?=$row_pdet->pro_name?></td>
		  <td>
		  <div id="auth_code_<?=$pdet_no?>">
		  <?php if ($row_pdet->auth_no == ''):?>
		  <INPUT TYPE="button" value="otorisasi" onclick="create_auth('<?=$row_pdet->pro_id?>','<?=$row_pdet->pr_id?>','<?=$pdet_no?>')">
		  <?php else: echo $row_pdet->auth_no;
		  endif;?>
		  </div>
		  </td>
		</tr>
		<?php 
		$pdet_no++;
		endforeach;
		?>
</table>
<table width="90%" border="0">
		<tr>
		  <td align="center"><INPUT TYPE="button" value="Keluar" onclick="location.href='index.php/<?=$link_controller?>/index/<?=$page_stats?>'"></td>
		</tr>
</table>
</div>
</form>
<div id="calendar-container"></div>