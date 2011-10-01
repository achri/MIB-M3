<script language="javascript">
function print_this(gr_id) {
	window.print();
	$.ajax({
		type:'POST',
		url:'index.php/<?=$link_controller?>/set_print/'+gr_id,
		success: function(data) {
			$('#content_print').html(data);
		}
	});
	return false;
}
</script>
<div class="noprint">
<div align="right">
<input type="button" id="print" value="Cetak" onclick="print_this('<?=$gr_list->row()->gr_id?>');">&nbsp;
<input type="button" id="cancel" value="Cancel" onclick="document.location='index.php/<?=$link_controller?>/index'">
</div>
<hr>
</div>
<div align="center">
<table width="756" border="0" cellpadding="1" cellspacing="1">
	<tr  >
		<td>
		 <table width="100%" border="0" cellpadding="0" cellspacing="0">
			<tr>
			 <td colspan="2" valign="top" nowrap>
			 <span style="font-size:18pt;font-weight:bold;text-decoration:underline;">BON PENERIMAAN BARANG</span></td>
			 <td align="right" valign="middle" nowrap rowspan="2">
			   <table width="90%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td colspan="3">&nbsp;</td>
					</tr>
					<tr>
						<td nowrap align="left">No Good Received</td>
						<td nowrap>:&nbsp;</td>
						<td nowrap align="left"><span style="font-size:12pt;font-weight:bold;"><?=$gr_list->row()->gr_no?> <? if ($gr_data->row()->gr_printCount > 0) echo '(r'.$gr_data->row()->gr_printCount.')';?></span></td>
					</tr>
					<tr>
						<td nowrap align="left">Tgl Terima</td>
						<td nowrap>:&nbsp;</td>
						<td nowrap align="left"><span style="font-size:12pt;font-weight:bold;"><?=$gr_list->row()->gr_date?></span></td>
					</tr>
			   </table>
			 </td>
			</tr>
			<tr valign="top"> 
			 <td valign="middle" align="left" nowrap>
				<table width="70%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td colspan="3">&nbsp;</td>
					</tr>
					<tr> 
					 <td width="50%" align="left">Nama Supplier</td>
					 <td width="10">:</td>
					 <td width="40%" align="left"><?=$gr_list->row()->sup_name?></td>
					</tr>
					<tr> 
					 <td align="left">Surat Jalan Supplier</td>
					 <td>:</td>
					 <td align="left"><?=$gr_list->row()->gr_suratJalan?></td>
					</tr>
					<tr> 
					 <td align="left">Nomor PO</td>
					 <td>:</td>
					 <td align="left"><?=$gr_list->row()->po_no?></td>
					</tr>
				</table>
			 </td>
			 <td>&nbsp;</td>
			</tr>
			<tr>
			 <td colspan="3">&nbsp;</td>
			</tr>
			<tr class="noscreen"  >
			 <td colspan="3">
				<table width="100%" cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td><img src="./image/black.gif" width="100%" height="1" border="0" alt="" />						</td>
					</tr>
				</table>
			 </td>
			</tr>
			<tr> 
			 <td colspan="3" valign="top" nowrap>
				<table class="tbl_outline" id="ItemPO" width="100%" border="0" cellspacing="1" cellpadding="2">
					<tr align="center" valign="middle"  > 
						<td class="tbl_outline" width="2%" align="center" nowrap><strong>No</strong></td>
						<td class="tbl_outline" width="23%" align="center" nowrap><strong>Nama Barang</strong><br/><strong>(Kode)</strong></td>
						<td class="tbl_outline" width="10%" align="center" nowrap><strong>Kuantitas</strong></td>
						<td class="tbl_outline" width="15%" align="center" nowrap><strong>Keterangan</strong></td>
					</tr>
					<tr class="noscreen"  >
						<td colspan="4" class="tbl_outline">
							<table width="100%" cellpadding="0" cellspacing="0" border="0">
								<tr>
									<td bgcolor="#999999">
										<img src="./image/black.gif" width="100%" height="1" border="0" alt="" />
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
						<td align="center" nowrap="nowrap" class="tbl_outline"><?=$no?>.</td>
						<td nowrap="nowrap" class="tbl_outline">
							<table border="0" cellpadding="0" cellspacing="0" width="100%">
								<tr>
									<td><?=$row_po->pro_name?></td>
								</tr>
								<tr>
									<td>(<?=$row_po->pro_code?>)</td>
								</tr>
							</table>
						</td>
						<td align="right" nowrap="nowrap" class="tbl_outline"><?=$row_po->qty?>&nbsp;<?=$row_po->satuan_name?></td>
						<td align="center" class="tbl_outline"></td>
					</tr>
					<?php 
						$no++;
						endforeach;
					endif;
					?>
					<tr class="noscreen"  >
						<td colspan="9">
							<table width="100%" cellpadding="0" cellspacing="0" border="0">
								<tr>
									<td>
										<img src="./image/black.gif" width="100%" height="1" border="0" alt="" />									</td>
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
						<td valign="baseline">
							<table width="40%" border="0" cellspacing="0" cellpadding="0">
								<tr> 
									<td align="center">Dicetak Oleh</td>
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
	<tr   class="noscreen">
	 <td>
		<table width="100%" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td bgcolor="#999999"><img src="./image/black.gif" width="100%" height="1" border="0" alt="" /></td>
			</tr>
		</table>
	 </td>
	</tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" align="center">
	<tr>
		<td>&nbsp;&nbsp;&nbsp;</td>
		<td>&nbsp;&nbsp;&nbsp;</td>
		<td>&nbsp;White</td>
		<td>:</td>
		<td>Accounting</td>
		<td>&nbsp;&nbsp;&nbsp;</td>
		<td>&nbsp;Red</td><td>:</td>
		<td>Purchasing</td>
	</tr>
</table>
</div>
</div>