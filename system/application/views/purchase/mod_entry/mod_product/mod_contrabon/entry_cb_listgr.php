<script language="javascript">
$(document).ready(function() {
	$('#dialog_info').dialog({
		autoOpen: false,
		bgiFrame: true,
		draggable: false,
		sizeable: false
	});
	
	$('#buat_bon').click(function(){
		var cek_box = $('#form_entry :checked').length;
		var penerima = $('#con_penerima').val();
		if (cek_box > 0 && penerima != '') {
			// KONFIRMASI
			$('#buat_bon').attr('disabled',true);
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
						$('#buat_bon').attr('disabled',false);
						$(this).dialog('close');
					},
					'<?=$this->lang->line('ok')?>' : function() {
						$('#form_entry').ajaxSubmit({
							url:'index.php/<?=$link_controller?>/buat_contra',
							type:'POST',
							success: function(data) {
								if (data) {
									info = "<strong>Data Kontra bon supplier <br> <font color='red'><?=$sup_name?><br> ( <?=$po_no?> ) <br> </font>No Kontra bon : <font color='red'>"+data+" </font><br><?=$this->lang->line('data_insert')?>";
									$('#dialog_info').text('').append(info).dialog('option','buttons',{
										'Keluar':function() {
										$(this).dialog('close');
										location.href = 'index.php/<?=$link_controller?>/index';
										}
									}).dialog('open');
								}else {
									info = '<STRONG>Maaf... Faktur BPB Tidak Berhasil dibuat</STRONG>';
									$('#dialog_info').text('').append(info).dialog('option','buttons',{
										'Keluar':function() {
										$(this).dialog('close');
										}
									}).dialog('open');
								}
							}
						});
						$(this).dialog('close');
					}
				}
			}).html('').html('<?=$this->lang->line('confirm')?>').dialog('open');
			
		}else {
			info = '<STRONG>Maaf... BPB belum di pilih atau Penerima Faktur belum di isi</STRONG>';
			$('#dialog_info').text('').append(info).dialog('option','buttons',{
				'Keluar':function() {
				$(this).dialog('close');
				}
			}).dialog('open');
		}
		return false;
	});
});
</script>
<div id="dialog_info" title="INFORMASI"></div>
<H3><?=$page_title?> <?=$page_title_next?></H3>
<div class="ui-widget-content ui-corner-all">
<form id="form_entry">
<table width="100%"  border="0" cellpadding="1" cellspacing="1" class="text_gudang">
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
		$no_po = 1;
		foreach($list_po->result() as $row_po):?>
		<tr bgcolor="lightgray">
		  <td valign="top" align="right" class="ui-state-active"><?=$no_po?>.</td>
		  <td valign="top" align="left"><?=$row_po->pro_name?> (<?=$row_po->pro_code?>)</td>
		  <td valign="top" align="center"><?=$row_po->satuan_name?></td>
		  <td valign="top" align="right"><?=number_format($row_po->qty,$row_po->satuan_format)?></td>
		  <td valign="top" align="right"><?=number_format($row_po->qty_terima,$row_po->satuan_format)?></td>
		  <td valign="top" align="right"><font color="red"><?=number_format($row_po->qty_retur,$row_po->satuan_format)?></font></td>
		  <td valign="top" align="right"><div style="float:left"><?=$row_po->qty_status?></div><?=number_format($row_po->qty_remain,$row_po->satuan_format)?></td>
		</tr>
		<?php 
		$no_po++;
		endforeach;?>
</table>
<? if ($list_po->num_rows() < 0)://($list_retur->num_rows() > 0):?>
<table align="center" width="100%"  border="0" cellpadding="1" cellspacing="1">
        <tr class="ui-widget-header">
		  <td colspan="9">Daftar Barang akan di Retur</td>
		</tr>
		<tr class="ui-widget-header">
		  <td width="5%" align="center">No</td>
		  <td width="38%" align="center">Barang/Kode</td>
		  <td width="10%" align="center">No BPB</td>
		  <td width="10%" align="center">Jml.Terima</td>
		  <td width="10%" align="center">Jml.Retur</td>
		  <td width="10%" align="center">Satuan</td>
		  <td width="10%" align="center">Harga <?=$cur_symbol?></td>
		  <td width="45%" align="center">Keterangan</td>
	    </tr>
		<?php 
		if ($list_retur->num_rows()>0):
		$ret_no = 1;
		foreach ($list_retur->result() as $row_ret):
		?>
		<tr bgcolor="lightgray">
		  <td valign="top" align="right" class="ui-state-active"><?=$ret_no?>.</td>
		  <td valign="top" align="left">
		  <?=$row_ret->pro_name?> (<?=$row_ret->pro_code?>)</td>
		  <td valign="top" align="center"><?=$row_ret->ret_no?></td>
		  <td valign="top" align="right"><?=$row_ret->qty_terima?></td>
		  <td valign="top" align="right"><?=$row_ret->qty?></td>
		  <td valign="top" align="center"><?=$row_ret->satuan_name?></td>
		  <td valign="top" align="center"><?=$row_ret->price?></td>
		  <td valign="top" align="left"><?=$row_ret->keterangan?></td>
		</tr>
		<?php 
		$ret_no++;
		endforeach;
		else:
		?>
		<tr>
		 <td align="center" colspan="8"><font color="red">--Tidak ada data--</font></td>
		</tr>
		<?php endif;?>
		</table>
<? endif;?>
<br>
<table width="100%"  border="0" cellpadding="1" cellspacing="1" class="text_gudang">
		<tr class="ui-widget-header">
		  <td colspan="7">Daftar BPB Belum Tukar Kontrabon</td>
		</tr>
		<tr class="ui-widget-header">
		  <td width="5%" align="center">Pilih</td>
		  <td width="20%" align="center">BPB / Retur No</td>
		  <td width="25%" align="center">Faktur Sup</td>
		  <td width="25%" align="center">PO</td>
		  <td width="25%" align="center">Tgl BPB / Retur</td>
		  <td width="25%" align="center">Nilai</td>
	    </tr>
		<?php 
		$gr_list = $this->tbl_gr->get_gr_bon($po_id);
		if ($gr_list->num_rows() > 0):
		$no_gr = 1;
		foreach ($gr_list->result() as $row_gr):
		?>
		<tr bgcolor="lightgray" <?php if($row_gr->gr_type=='ret'):?>class="text_merah_miring"<?php endif;?>>
		  <td valign="top" align="center">
		  <!--{if $gr_list[x].gr_type=='rec' && ($gr_list[x].gr_fakturSup!='')}-->
		  	<INPUT TYPE="checkbox" NAME="gr_id[]" ID="gr_id_<?=$no_gr?>"  value="<?=$row_gr->gr_id?>_<?=$row_gr->gr_value?>_<?=$row_gr->cur_id?>">
		  <!--{elseif $gr_list[x].gr_type=='rec'}-->
		  <img src="./image/button_edit.png" border="0" />
		  <!--{/if}-->
		  </td>
		  <td valign="top"><?=$row_gr->gr_no?></td>
		  <td valign="top" align="center"><?=$row_gr->gr_fakturSup?></td>
		  <td valign="top" align="center"><?=$row_gr->po_no?></td>
		  <td valign="top" align="center"><?=$row_gr->gr_date?></td>
		  <td valign="top" align="left">
		   <table width="100%" border="0" cellspacing="0" cellpadding="0">
		     <tr>
			  <td align="left"><?=$row_gr->cur_symbol?>.</td>
			  <td align="right"><?=number_format($row_gr->gr_value,$row_gr->cur_digit)?></td>
			 </tr>
		   </table>
		  </td>
		</tr>
		<?php 
		$no_gr++;
		endforeach;
		else:
		?>
		<tr>
		  <td colspan="7">&nbsp;</td>
		</tr>
		<tr>
		  <td colspan="7" align="center">--Tidak ada data--</td>
		</tr>
		<?php endif;?>
</table>
<?php if ($list_retur->row()->ret_no != ''):?>
<br>
<table align="left" width="70%"  border="0" cellpadding="1" cellspacing="1">
        <tr class="ui-widget-header">
		  <td colspan="9">Daftar Retur Barang</td>
		</tr>
		<tr class="ui-widget-header">
		 <td width="7%" align="center"></td>
		  <td width="20%" align="center">No Retur</td>
		  <td width="20%" align="center">Tgl Retur</td>
		  <td width="30%" align="center">Total Harga <br> <?=$cur_symbol?></td>
	    </tr>
		<?php 
		if ($list_retur->num_rows()>0):
		$ret_no = 1;
		foreach ($list_retur->result() as $row_ret):
		?>
		<tr bgcolor="lightgray">
			<td align="center">
			<INPUT TYPE="checkbox" NAME="ret_id[]" value="<?=$row_ret->ret_id?>" ID="ret_id_<?=$ret_no?>"></td>
			<td valign="top" align="center"><?=$row_ret->ret_no?></td>
			<td valign="top" align="center"><?=$row_ret->ret_date?></td>
			<td valign="top" align="right">
			<?=number_format($row_ret->total,$row_ret->cur_digit)?></td>
			</td>
		</tr>
		<?php 
		$ret_no++;
		endforeach;
		else:
		?>
		<tr>
		 <td align="center" colspan="8"><font color="red">--Tidak ada data--</font></td>
		</tr>
		<?php endif;?>
		</table>
<? endif;?>
<br>
<table width="100%"  border="0" cellpadding="1" cellspacing="1">
        <tr>
		  <td>&nbsp;<INPUT TYPE="hidden" name="jum_row" value="<?=sizeof($gr_list)?>">
		  <INPUT TYPE="hidden" name="po_id" value="<?=$po_id?>">
		  </td>
		</tr>
		<tr>
		  <td>Nama Penerima Kontra Bon : <INPUT TYPE="text" NAME="con_penerima" ID="con_penerima" style="width:200px;">
		  <INPUT TYPE="button" id="buat_bon" value="Buat Kontra Bon">
		  <INPUT TYPE="button" value="Batal" onclick="location.href='index.php/<?=$link_controller?>/index'">		  
		  </td>
		</tr>
</table>
</form>
</div>