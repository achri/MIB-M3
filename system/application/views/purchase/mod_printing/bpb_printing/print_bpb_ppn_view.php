<script language="javascript">
	function print_this(gr_id,count,status) {
		window.print();
		$.ajax({
			url:'index.php/<?=$link_controller?>/after_print/'+gr_id+'/'+status+'/'+count,
			type:'POST',
			success: function(data) {
				//alert(data);
				if(data == 'ok') {
					$('#print').hide();
					//alert(data);
					//back_this();
				}else {
					$('#content_print').html(data);
				}
			}
		});
		return false;
	}
	
	function back_this() {
		location.href = 'index.php/<?=$link_controller?>/index/<?=$print_status?>';
	}
	
</script>
<?
	$print_count = $gr_list->row()->gr_printCount + 1;
?>
<div class="noprint">
<h2><?=$page_title?> : <?=$this->lang->line('print_view')?></h2></div>
<div align="left">
<table width="756" border="0" cellpadding="1" cellspacing="1" bgcolor="#CCCCCC">
<tr bgcolor="#FFFFFF">
<td>
<table width="100%" border="0" cellpadding="1" cellspacing="1">
	<tr bgcolor="#FFFFFF">
		<td>
		 <table width="100%" border="0" cellpadding="0" cellspacing="0">
			<tr>
			 <td colspan="2" valign="top" nowrap>
			 <span style="font-size:18pt;font-weight:bold;text-decoration:underline;">BON PENERIMAAN BARANG</span></td>
			</tr>
			<tr valign="top"> 
			 <td valign="middle" align="left" nowrap>
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td colspan="7">&nbsp;</td>
					</tr>
					<tr> 
					 <td width="20%" align="left">Nama pemasok</td>
					 <td width="2%">:</td>
					 <td width="10%" align="left"><?=$gr_list->row()->legal_name?>. <?=$gr_list->row()->sup_name?></td>
					 <td nowrap>&nbsp;</td>
					 <td width="15%" nowrap align="left">No BPB</td>
					 <td width="2%" nowrap>:&nbsp;</td>
					 <td width="10%" nowrap align="left"><span style="font-size:12pt;font-weight:bold;"><?=$gr_list->row()->gr_no?> <? if ($print_status != 0) echo '(r'.$print_count.')';?></span></td>
					</tr>
					<tr> 
					 <td align="left">Surat jalan pemasok</td>
					 <td nowrap>:</td>
					 <td nowrap rowspan=2 align="left" valign="top" style="width:100px"><?=$gr_list->row()->gr_suratJalan?></td>
					 <td nowrap>&nbsp;</td>
					 <td nowrap align="left">No PO</td>
					 <td>:</td>
					 <td nowrap align="left"><?=$gr_list->row()->po_no?></td>
					 
					</tr>
					<tr>
					 <td colspan="2">&nbsp;</td>
					 <td nowrap>&nbsp;</td>
					 <td nowrap align="left">Tanggal terima</td>
					 <td nowrap>:&nbsp;</td>
					 <td nowrap align="left"><?=$gr_list->row()->gr_date?></td>
					</tr>
				</table>
			 </td>
			 <td>&nbsp;</td>
			</tr>
			<tr>
			 <td colspan="3">&nbsp;</td>
			</tr>
			<tr> 
			 <td colspan="3" valign="top" nowrap>
				<table id="ItemPO" width="100%" border="1" cellspacing="0" cellpadding="2">
					<tr align="center" valign="middle"  > 
						<td   width="2%" align="center" nowrap><strong>No</strong></td>
						<td   width="23%" align="center" nowrap><strong>Nama barang</strong><br/><strong>(Kode)</strong></td>
						<td   width="10%" align="center" nowrap><strong>Satuan</strong></td>
						<td   width="10%" align="center" nowrap><strong>Kuantitas</strong></td>
						<td   width="15%" align="center" nowrap><strong>Keterangan</strong></td>
					</tr>
					<tr class="noscreen"  >
						<td colspan="5"  >
							<table width="100%" cellpadding="0" cellspacing="0" border="0">
								<tr>
									<td bgcolor="#999999">
										
									<td>
								</tr>
							</table>
						</td>
					</tr>
					<?php 
					if($po_det_list->num_rows() > 0):
						$no=1;
						foreach($po_det_list->result() as $row_po):
					?>
					<tr   valign="top"> 
						<td align="center" nowrap="nowrap"  ><?=$no?>.</td>
						<td nowrap="nowrap"  >
							<table border="0" cellpadding="0" cellspacing="0" width="100%">
								<tr>
									<td><?=$row_po->pro_name?></td>
								</tr>
								<tr>
									<td>(<?=$row_po->pro_code?>)</td>
								</tr>
							</table>
						</td>
						<td align="center" nowrap><?=$row_po->satuan_name?></td>
						<td align="right" nowrap="nowrap"><?=number_format($row_po->qty,$row_po->satuan_format)?></td>
						<td align="center" ><?=$row_po->keterangan?></td>
					</tr>
					<?php 
						$no++;
						endforeach;
					endif;
					?>
					<tr class="noprint"  >
						<td colspan="9">
							<table width="100%" cellpadding="0" cellspacing="0" border="0">
								<tr>
									<td>
										
										</td>
								</tr>
							</table>
						</td>
					</tr>
			   </table>
			</td>
		</tr>
		<tr> 
			<td colspan="3" nowrap> 
				<table width="100%" border="0" cellpadding="0" cellspacing="0">
					<tr> 
						<td valign="baseline">&nbsp;</td>
					</tr>
					<tr>
						<td>
						<?php 
						echo $notes;
						?>
						</td>
					</tr>
					<tr> 
						<td valign="baseline">&nbsp;</td>
					</tr>
					<tr> 
						<td valign="baseline">
							<table width="40%" border="0" cellspacing="0" cellpadding="0">
								<tr> 
									<td align="center">Dicetak oleh</td>
								</tr>
								<tr> 
									<td align="center">&nbsp;</td>
								</tr>
								<tr> 
									<td align="center">&nbsp;</td>
								</tr>
								<tr> 
									<td align="center"><br/>&nbsp;</td>
								</tr>
								<tr> 
									<td align="center">(<u>&nbsp;<?=$usr_name?>&nbsp;</u>)</td>
								</tr>
								<tr><td>&nbsp;</td></tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	  </table>
	 </td>
	</tr>
</table>
</td></tr></table>
<table border="0" cellpadding="0" cellspacing="0" align="center">
	<tr>
		<td>&nbsp;&nbsp;&nbsp;</td>
		<td>&nbsp;&nbsp;&nbsp;</td>
		<td>&nbsp;White</td>
		<td>:</td>
		<td>Purchasing</td>
		<td>&nbsp;&nbsp;&nbsp;</td>
		<td>&nbsp;Red</td><td>:</td>
		<td>Accounting</td>
	</tr>
</table>
</div>
<?//=$row_po->sub_um_id.'|'.$row_po->pro_um_id?>
<br>
<div align="left" class="noprint">
<?php 
	if ($print_status == 0):
		if ($gr_list->row()->gr_printStatus != 1):?>
<input type="button" id="print" value="<?=$this->lang->line('print')?>" onclick="print_this('<?=$gr_list->row()->gr_id?>','<?=$print_count?>','<?=$print_status?>');">&nbsp;
<?php endif;
	else:
?>
<input type="button" id="print" value="<?=$this->lang->line('print')?>" onclick="print_this('<?=$gr_list->row()->gr_id?>','<?=$print_count?>','<?=$print_status?>');">&nbsp;
<?php endif;?>
<input type="button" id="cancel" value="<?=$this->lang->line('back')?>" onclick="back_this();">
</div>