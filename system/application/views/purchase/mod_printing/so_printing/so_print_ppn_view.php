<script type="text/javascript">
	function print_po(id,count,tgl){
		$.ajax({
			type: 'POST',
			url: 'index.php/mod_po_printing/po/print_update/'+id+'/'+count+'/'+tgl,
			success: function() {
			window.print();
			$('#print').hide();
			}
		});
		return false;
	}

	function godaftar(){
		window.location = 'index.php/mod_data_list/list_po/index';
	}

	function goreport(){
		window.location = 'index.php/mod_po_printing/po/index';
	}
</script>
<?php
$head = $content['head']->row();
$notes = $note->row();
?>
<div>
<table width="870" border="0" cellpadding="1" cellspacing="1" bgcolor="#CCCCCC">
	<tr bgcolor="#FFFFFF">
		<td>
		 <table width="100%" border="0" cellpadding="0" cellspacing="0">
			<tr>
			 <td colspan="2" valign="top" nowrap><span style="font-size:18pt;font-weight:bold;text-decoration:underline;">PURCHASE ORDER</span></td>
			 <td align="right" valign="middle" nowrap rowspan="2">
			   <table width="90%" border="0" cellspacing="0" cellpadding="0">
			    <tr> 
				 <td width="50%" align="left">Nomor PO</td>
				 <td width="10">:</td>
				 <td width="40%" align="left"><span style="font-size:12pt;font-weight:bold;"><?php echo $head->po_no; 
				 	if ($head->po_printCounter != '0'){
				 		echo " (r".$head->po_printCounter.")";
				 	}?>
				 </span></td>
				</tr>
				<tr> 
				 <td align="left">Tanggal PO</td>
				 <td>:</td>
				 <td align="left"><?php echo $head->po_date;?></td>
				</tr>
				
				<?php 
				$now = date('d-m-Y');
				if ($head->po_printCounter != '0'){
					echo "<tr> 
						 <td align='left'>Tanggal cetak</td>
						 <td>:</td>
						 <td align='left'>".$now."</td>
					</tr>";
				}
				?>
				
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
						<td nowrap align="left">Nama Supplier</td>
						<td nowrap>:&nbsp;</td>
						<td nowrap align="left"><?php echo $head->sup_name;?></td>
					</tr>
					<tr>
						<td nowrap align="left">Contact Person</td>
						<td nowrap>:&nbsp;</td>
						<td nowrap align="left"><?php echo $head->per_Fname." ".$head->per_Lname;?></td>
					</tr>
					<tr>
						<td nowrap align="left">No Telp</td>
						<td nowrap>:&nbsp;</td>
						<td nowrap align="left"><?php echo $head->sup_phone1;?></td>
					</tr>
					<tr>
						<td nowrap align="left">No Fax</td>
						<td nowrap>:&nbsp;</td>
						<td nowrap align="left"><?php echo $head->sup_fax;?></td>
					</tr>
					<tr>
						<td nowrap align="left">Jangka Waktu Kredit&nbsp;</td>
						<td nowrap>:&nbsp;</td>
						<td nowrap align="left"><?php echo $head->term_days;?>&nbsp;hari</td>
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
					<tr align="center" valign="middle" bgcolor="#FFFFFF"> 
						<td rowspan="2" width="2%" align="center" nowrap><strong>No</strong></td>
						<td rowspan="2" width="23%" align="center" nowrap><strong>Nama Barang</strong><br/><strong>(Kode)</strong></td>
						<td rowspan="2" width="10%" align="center" nowrap><strong>Kuantitas</strong></td>
						<td rowspan="2" width="15%" align="center" nowrap><strong>Harga Satuan</strong></td>
						<td colspan="2" width="10%" align="center" nowrap><strong>Diskon</strong></td>
						<td rowspan="2" width="15%" align="center" nowrap><strong>Harga PPN<br>(10%)</strong></td>
						<td rowspan="2" width="20%" align="center" nowrap><strong>Harga Total</strong></td>
						<td rowspan="2" width="20%" colspan="2" align="center" nowrap><strong>Harus<br/>Diterima</strong></td>
					</tr>
					<tr align="center" bgcolor="#FFFFFF"> 
						<td align="center" width="5%" valign="middle" nowrap><strong>%</strong></td>
						<td align="center" width="5%" valign="middle" nowrap><strong>Nilai</strong></td>
					</tr>
					<?php 
					$i = 0;
					foreach ($content['detail']->result() as $detail):
						$i = $i + 1;
					if ($detail->cur_symbol == 'Rp'){
						$price = number_format($detail->price,2,',','.');
						$disc = number_format($detail->discount_val,2,',','.');
						$amount = number_format($detail->amount,2,',','.');
						$ppn = number_format($detail->ppn,2,',','.');
						$hrg_ppn = number_format($detail->sum_ppn,2,',','.');
					}else{
						$price = number_format($detail->price,4,'.',',');
						$disc = number_format($detail->discount_val,4,'.',',');
						$amount = number_format($detail->amount,4,'.',',');
						$ppn = number_format($detail->ppn,4,'.',',');
						$hrg_ppn = number_format($detail->sum_ppn,4,'.',',');
					}
					
					if ($detail->sup_pro_code != ''):
						$pro_codes = $detail->sup_pro_code.' (pemasok)';
					else:
						$pro_codes = '';
					endif;
						
					echo "<tr bgcolor='#FFFFFF' valign='top'> 
						<td align='center' nowrap='nowrap'>".$i."</td>
						<td>
							<table border='0' cellpadding='0' cellspacing='0' width='100%'>
								<tr>
									<td>".$detail->pro_name."</td>
								</tr>
								<tr>
									<td>".$detail->pro_code."</td>
								</tr>
								<tr>
									<td>".$pro_codes."</td>
								</tr>
							</table>
						</td>
						<td align='right' nowrap>".$detail->qty." ".$detail->satuan_name."</td>
						<td align='right' nowrap='nowrap'>
							<table border='0' cellpadding='0' cellspacing='0' width='100%'>
								<tr>
									<td>&nbsp;</td>
									<td>".$detail->cur_symbol."</td>
									<td>&nbsp;</td>
									<td align='right'>".$price."</td>
								</tr>
							</table>
						</td>
						<td align='center' nowrap='nowrap'>&nbsp;&nbsp;&nbsp;".$detail->discount."</td>
						<td nowrap='nowrap' align='right'>".$detail->cur_symbol."&nbsp;".$disc."</td>
						<td align='right' nowrap='nowrap'>
							<table border='0' cellpadding='0' cellspacing='0' width='100%'>
								<tr>
									<td>&nbsp;</td>
									<td>".$detail->cur_symbol."</td>
									<td>&nbsp;</td>
									<td align='right'>".$ppn."</td>
								</tr>
							</table>
						</td>
						<td align='right' nowrap='nowrap'>
							<table border='0' cellpadding='0' cellspacing='0' width='100%'>
								<tr>
									<td>&nbsp;</td>
									<td>".$detail->cur_symbol."</td>
									<td>&nbsp;</td>
									<td align='right'>".$hrg_ppn."</td>
								</tr>
							</table>
						</td>
						<td align='center' colspan='2'>".$detail->rfq_delivery_date."</td>
					</tr>";
					endforeach;
					/*$footer = $content['footer']->row();
					$user = $usr->row();
					if ($detail->cur_symbol == 'Rp'){
						$tot_price = number_format($footer->tot_price,2,',','.');
						$tot_disc = number_format($footer->tot_discount,2,',','.');
					}else{
						$tot_price = number_format($footer->tot_price,4,'.',',');
						$tot_disc = number_format($footer->tot_discount,4,'.',',');
					}*/
					?>
					<tr bgcolor="#FFFFFF" align="right">
						<td colspan="4"><STRONG>TOTAL&nbsp;DISKON</STRONG></td>
						<td>&nbsp;</td>
						<td><table border="0" cellpadding="0" cellspacing="1" width="100%">
						<?php 
						foreach ($content['footer']->result() as $foot1):
							if ($foot1->cur_id == '1'){
								$tot_disc = number_format($foot1->tot_discount,2);
							}else{
								$tot_disc = number_format($foot1->tot_discount,2);
							}	
							echo "<tr>
									<td>&nbsp;</td>
									<td><STRONG>$foot1->cur_symbol. </STRONG></td>
									<td>&nbsp;</td>
									<td align='right'><STRONG> $tot_disc </STRONG></td>
								</tr>";
						endforeach;
						?>
							</table>
						</td>
						<td colspan="4">&nbsp;</td>
					</tr>
					<tr bgcolor="#FFFFFF" align="right"> 
						<td colspan="4"><STRONG>TOTAL PPN</STRONG></td>
						<td colspan="2">&nbsp;</td>
						<td>
						<table border='0' cellpadding='0' cellspacing='1' width='100%'>
						<?php 
						$user = $usr->row();
						foreach ($content['footer']->result() as $foot):
							if ($foot->cur_id == '1'){
								$tot_ppn_price = number_format($foot->hrg_ppn,2);
							}else{
								$tot_ppn_price = number_format($foot->hrg_ppn,2);
							}	
							echo "<tr>
									<td>&nbsp;</td>
									<td><STRONG>$foot->cur_symbol. </STRONG></td>
									<td>&nbsp;</td>
									<td align='right'><STRONG> $tot_ppn_price </STRONG></td>
								</tr>";
						endforeach;
						?>
						</table>
						</td>
						
						<td colspan="3">&nbsp;</td>
					</tr>
					<tr bgcolor="#FFFFFF" align="right"> 
						<td colspan="4"><STRONG>TOTAL KESELURUHAN</STRONG></td>
						<td colspan="3">&nbsp;</td>
						<td>
						<table border='0' cellpadding='0' cellspacing='1' width='100%'>
						<?php 
						$user = $usr->row();
						foreach ($content['footer']->result() as $foot):
							if ($foot->cur_id == '1'){
								$tot_price = number_format($foot->tot_ppn,2);
							}else{
								$tot_price = number_format($foot->tot_ppn,2);
							}	
							echo "<tr>
									<td>&nbsp;</td>
									<td><STRONG>$foot->cur_symbol. </STRONG></td>
									<td>&nbsp;</td>
									<td align='right'><STRONG> $tot_price </STRONG></td>
								</tr>";
						endforeach;
						?>
						</table>
						</td>
						<td colspan="2">&nbsp;</td>
					</tr>
					
				</table>
			</td>
		</tr>
		<tr> 
			<td colspan="3"> 
				<table width="100%" border="0" cellpadding="0" cellspacing="0">
					<tr valign="top"> 
						<td colspan="3" align="left">
							<table width="100%" border="0" cellpadding="0" cellspacing="0">
								<tr> 
									<td align="left"><?php echo $notes->note; ?></td>
								</tr>
							</table>
						</td>
					</tr>
					<tr> 
						<td colspan="3" valign="baseline">&nbsp;</td>
					</tr>
					<tr> 
						<td valign="baseline" width="30%">
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr> 
									<td align="center">&nbsp;</td>
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
									<td align="center">&nbsp;</td>
								</tr>
							</table>
						</td>
						<td valign="baseline" width="30%">
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr> 
									<td align="center">&nbsp;</td>
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
									<td align="center">&nbsp;</td>
								</tr>
							</table>
						</td>
						<td valign="baseline" width="40%">
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr> 
									<td align="center">Dicetak dan Disetujui Oleh</td>
								</tr>
								<tr> 
									<td align="center">Supervisor Purchasing</td>
								</tr>
								<tr> 
									<td align="center">&nbsp;</td>
								</tr>
								<tr> 
									<td align="center"><br/>&nbsp;</td>
								</tr>
								<tr> 
									<td align="center"><u><?php echo $user->usr_name; ?></u></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	  </table>
	 </td>
	</tr>
	<tr bgcolor="#FFFFFF" class="noscreen">
	 <td>
		<table width="100%" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td bgcolor="#999999"><img src="./image/black.gif" width="100%" height="1" border="0" alt="" /></td>
			</tr>
		</table>
	 </td>
	</tr>
</table>
<table border="0" cellpadding="0" cellspacing="0">
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
<input type="button" onclick="print_po('<?php echo $head->po_id."','".$head->po_printCounter."','".$head->po_date; ?>')" value="Cetak" class="noprint" id="print">
<?php 
	if ($head->po_printCounter != '0'){
		echo "<input type='button' onclick=godaftar() value='Kembali' class='noprint'>";
	}else{
		echo "<input type='button' onclick=goreport() value='Kembali' class='noprint'>";
	}
?>