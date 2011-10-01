<script language="javascript">
$(document).ready(function(){
	$('#dialog_info').dialog({
		title: 'Konfirmasi',
		autoOpen: false,
		bgiframe: true,
		width: 'auto',
		height: 'auto',
		resizable: false,
		draggable: false,
		modal:true,
		position:'center',
		buttons: {
			'OK':function() {
				$(this).dialog('close');
				location.href = 'index.php/<?=$link_controller?>/index_cbpb';
			}
		}
	});
	
	$('#form_entry').validate({
		submitHandler : function(form) {
			// KONFIRMASI
			$('#saving').attr('disabled',true);
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
							url : 'index.php/<?=$link_controller?>/create_faktur',
							type: 'POST',
							data: $(form).serialize(),
							success: function(data) {
								/*
								if (data) {
									//alert(data);
									$('#dialog_info').dialog('open');
								}
								*/
								var info;	
								if(data) {
									info = '<strong>Selamat... Faktur berhasil di buat <br> NO BPB :<font color="red"> <?=$gr_no?> </font> <br> NO FAKTUR :<font color="red"> '+data+' </font></strong>';
									$('#dialog_info').text('').append(info).dialog('option','buttons', 
									{ "Keluar" : function() {
										location.href = 'index.php/<?=$link_controller?>/index_cbpb';
										$("#dialog_info").dialog('close');
									}}).dialog('open');
								} else {
									info = '<STRONG>Maaf... Faktur Tidak Berhasil dibuat</STRONG>';
									$('#dialog_info').text('').append(info).dialog('option','buttons', 
									{ "Keluar" : function() {
										$('#dialog_info').dialog('close');
									}}).dialog('open');
								}
							}
						});
						$(this).dialog('close');
					}
				}
			}).html('').html('<?=$this->lang->line('confirm')?>').dialog('open');
		}
	});
});
</script>
<div id="dialog_info">
</div>
<H3><?=$page_title?> : DETAIL BPB <strong><?=$gr_no?></strong></H3>
<form id="form_entry"><!--  action="index.php/entry_good_receive/create_faktur" method="post"-->
<table width="100%"  border="0" cellspacing="2" cellpadding="2">
	<tr>
	  <td valign="top" width="50%">
	    <table width="100%">
		  <tr>
		    <td width="30%" class="head_title">Nomor BPB</td>
			<td width="5%" class="head_title">:</td>
			<td class="head_title_content"><?=$gr_no?></td>
		  </tr>
		  <tr>
		    <td class="head_title">Surat Jalan</td>
			<td class="head_title">:</td>
			<td class="head_title_content"><?=$list_gr->row()->gr_suratJalan?></td>
		  </tr>
		  <tr>
		    <td class="head_title">Nomor PO</td>
			<td class="head_title">:</td>
			<td class="head_title_content"><?=$list_gr->row()->po_no?></td>
		  </tr>
	    </table>
	  </td>
	  <td valign="top">
	    <table width="100%">
		  <tr>
		    <td class="head_title" width="40%">Supplier</td>
			<td class="head_title" width="5%">:</td>
			<td class="head_title_content"><?=$list_gr->row()->sup_name?></td>
		  </tr>
		  <tr>
		    <td class="head_title">Tanggal terima</td>
			<td class="head_title">:</td>
			<td class="head_title_content"><?=$list_gr->row()->gr_date?></td>
		  </tr>
		  <tr>
		    <td class="head_title">Faktur Supplier</td>
			<td class="head_title">:</td>
			<td class="head_title_content"><INPUT TYPE="text" NAME="faktur_sup" class="required" title="*" validate="required: true"></td>
		  </tr>
		   <tr>
		    <td class="head_title">Kurs</td>
			<td class="head_title">:</td>
			<td class="head_title_content"><INPUT TYPE="text" NAME="kurs" class="required" title="*" validate="required: true"></td>
		  </tr>
		</table>
	  </td>
	</tr>
	<tr>
	  <td colspan="2">&nbsp;</td>
	</tr>
</table>

<table align="center" width="99%"  border="0" cellpadding="1" cellspacing="1" class="ui-widget-content ui-corner-all">
	<tr class="ui-widget-header">
	  <td width="5%" align="center">No</td>
	  <td width="35%" align="center">Barang/Kode</td>
	  <td width="8%" align="center">Terima</td>
	  <td width="8%" align="center">Harga Satuan</td>
	  <td width="8%" align="center">Jumlah</td>
	</tr>
	<?php 
	if ($list_gr_det->num_rows() > 0):
		$gr_num = 1;
		$total_all = 0;
		foreach ($list_gr_det->result() as $row_gr):
		$total_price = $row_gr->qty*$row_gr->price;
	?>
	<tr bgcolor="lightgray">
	  <td valign="top" align="right" class="ui-state-active"><?=$gr_num?>.</td>
	  <td valign="top" align="left"><?=$row_gr->pro_name?> (<?=$row_gr->pro_code?>) 
		<INPUT TYPE="hidden" name="pro_id_<?=$gr_num?>" value="<?=$row_gr->pro_id?>">
	  </td>
	  <td valign="top" align="right"><?=number_format($row_gr->qty,2)?>&nbsp;<?=$row_gr->satuan_name?>
		<INPUT TYPE="hidden" name="receive_<?=$gr_num?>" value="<?=$row_gr->qty?>">
		<INPUT TYPE="hidden" name="price_<?=$gr_num?>" value="<?=$row_gr->price?>">
		<INPUT TYPE="hidden" name="cur_id_<?=$gr_num?>" value="<?=$row_gr->cur_id?>">
	  </td>
	  <td valign="top" align="right"><?=$row_gr->cur_symbol?>. 
	  <?=number_format($row_gr->price,2)?>
	  </td>
	  <td valign="top" align="right"><?=$row_gr->cur_symbol?>. <?=number_format($total_price,2)?>
		<INPUT TYPE="hidden" name="price_<?=$gr_num?>" value="<?=$row_gr->price?>">
	  </td>
	</tr>
	<?php 
		$total_all = $total_all + $total_price;
		$gr_num++;
		endforeach;
	?>
	<tr>
	<td colspan=3 class="ui-widget-header">&nbsp;</td>
	<td align="right" bgcolor="lightgray">Total Harga : </td>
	<td  bgcolor="lightgray" align="right"><?=$row_gr->cur_symbol?>. <?=number_format($total_all,2)?></td>
	</tr>
	<?php endif;?>
	</table>
	<br>
	<div align="center">
	  <INPUT TYPE="hidden" name="gr_id" value="<?=$list_gr->row()->gr_id?>">
	  <INPUT TYPE="hidden" name="gr_no" value="<?=$gr_no?>">
	  <INPUT TYPE="hidden" name="sup_id" value="<?=$list_gr->row()->sup_id?>">
	  <INPUT TYPE="hidden" name="jum_product" value="<?=$jml_product?>">
	  <INPUT TYPE="button" value="<?=$btn_adjust_po?>" id="adjust">
	  <INPUT TYPE="submit" value="<?=$btn_save?>" id="saving"> 
	  <INPUT TYPE="button" onclick="location.href='index.php/<?=$link_controller?>/index_cbpb'" value="<?=$btn_cancel?>">
	 </div>
</form>
