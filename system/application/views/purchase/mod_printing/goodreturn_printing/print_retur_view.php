<script language="javascript">
	function print_this(ret_id,print_status,print_count) {
		window.print();
		$.ajax({
			url:'index.php/<?=$link_controller?>/after_print/'+ret_id+'/'+print_status+'/'+print_count,
			type:'POST',
			success: function(data) {
				if(data) {
					$('#content_print').html(data);
				}
			}
		});
		return false;
	}
	
	function back_this() {
		location.href = 'index.php/<?=$link_controller?>/index/'+<?=$print_status?>;
	}
	
</script>
<div class="noprint">
<h2><?=$page_title?> : <?=$this->lang->line('print_view')?></h2></div>
<div align="left">
<table width="756" border="0" cellpadding="1" cellspacing="1" bgcolor="#CCCCCC">
<tr bgcolor="#FFFFFF">
<td>
<?php
	$print_count = $ret_list->row()->ret_printCount + 1;
?>
<table width="100%" border="0" cellpadding="1" cellspacing="1">
	<tr  >
		<td>
		 <table width="100%" border="0" cellpadding="0" cellspacing="0">
			<tr>
			 <td colspan="2" valign="top" nowrap>
			 <span style="font-size:18pt;font-weight:bold;text-decoration:underline;">BON RETUR BARANG</span></td>
			 <td align="right" valign="middle" nowrap rowspan="2">
			   <table width="90%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td colspan="3">&nbsp;</td>
					</tr>
					<tr>
						<td nowrap align="left">Nomor Retur</td>
						<td nowrap>:&nbsp;</td>
						<td nowrap align="left"><span style="font-size:12pt;font-weight:bold;"><?=$ret_list->row()->ret_no?> <? if ($print_status != 0) echo '(r'.$print_count.')';?></span></td>
					</tr>
					<tr>
						<td nowrap align="left">Tanggal Retur</td>
						<td nowrap>:&nbsp;</td>
						<td nowrap align="left"><?=$ret_list->row()->ret_date?></td>
					</tr>
					<tr>
						<td nowrap align="left">Tanggal Cetak</td>
						<td nowrap>:&nbsp;</td>
						<td nowrap align="left"><?=date('d-m-Y')?></td>
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
					 <td width="40%" align="left"><?=$ret_list->row()->sup_name?></td>
					</tr>
					<tr> 
					 <td align="left">Nomor PO</td>
					 <td>:</td>
					 <td align="left"><?=$ret_list->row()->po_no?></td>
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
				<table id="ItemPO" width="100%" width="100%" border="1" cellspacing="0" cellpadding="2">
					<tr align="center" valign="middle"  > 
						<td   width="2%" align="center" nowrap><strong>No</strong></td>
						<td   width="23%" align="center" nowrap><strong>Nama Barang</strong><br/><strong>(Kode)</strong></td>
						<td   width="10%" align="center" nowrap><strong>Kuantitas</strong></td>
						<td   width="15%" align="center" nowrap><strong>Keterangan</strong></td>
					</tr>
					<tr class="noprint" >
						<td colspan="4"  >
							
						</td>
					</tr>
					<?php 
					if($ret_det_list->num_rows() > 0):
						$no=1;
						foreach($ret_det_list->result() as $row_po):
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
						<td align="right" nowrap="nowrap"  ><?=number_format($row_po->qty,2)?>&nbsp;<?=$row_po->satuan_name?></td>
						<td align="left"><?=$row_po->keterangan?></td>
					</tr>
					<?php 
						$no++;
						endforeach;
					endif;
					?>
					<tr class="noprint"  >
						<td colspan="9">
							
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
						//$notes = $note->row();
						//echo $notes->note;
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
</table>
</td></tr></table>
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
<?//=$row_po->sub_um_id.'|'.$row_po->pro_um_id?>
<br>
<div align="left" class="noprint">
<?php
	if ($print_status == 0):
		if ($ret_list->row()->ret_printStatus != 1):	
?>
<input type="button" id="print" value="<?=$this->lang->line('print')?>" onclick="print_this('<?=$ret_list->row()->ret_id?>','<?=$print_status?>','0');">&nbsp;
<?php 
		endif;
	else:
?>
<input type="button" id="print" value="<?=$this->lang->line('print')?>" onclick="print_this('<?=$ret_list->row()->ret_id?>','<?=$print_status?>','<?=$print_count?>');">&nbsp;
<?php endif; ?>
<input type="button" id="cancel" value="<?=$this->lang->line('back')?>" onclick="back_this();">
</div>