<script type="text/javascript">
	function print_po(id,count,tgl,status){
		$.ajax({
			type: 'POST',
			url: 'index.php/<?php echo $link_controller;?>/print_update/'+id+'/'+count+'/'+tgl+'/'+status,
			success: function(data) {
				window.print();
				if (data == 'ok'){
					$('#print').hide();
				}else {
					$('#pocontent').html(data);
				}
			}
		});
		return false;
	}

	function godaftar(){
		window.location = 'index.php/mod_data_list/list_po/index';
	}

	function goreport(){
		window.location = 'index.php/<?php echo $link_controller;?>/index/<?=$print_status?>';
	}
</script>
<?php
$head = $content['head']->row();
?>
<div>
<table width="870" border="0" cellpadding="1" cellspacing="1" bgcolor="#CCCCCC">
	<tr bgcolor="#FFFFFF">
		<td>
		 <table width="100%" border="0" cellpadding="0" cellspacing="0">
			<tr>
			 <td colspan="2" valign="top" nowrap><span style="font-size:18pt;font-weight:bold;text-decoration:underline;">PURCHASE ORDER</span></td>
			 <td align="right" valign="middle" nowrap rowspan="2">
			   <table width="70%" border="0" cellspacing="0" cellpadding="0">
			    <tr> 
				 <td width="30%" align="left">No PO</td>
				 <td width="5" align="right">:&nbsp;</td>
				 <td width="40%" align="left"><span style="font-size:12pt;font-weight:bold;"><?php echo $head->po_no; 
				 	if ($head->po_printCounter != '0'){
				 		echo " (r".$head->po_printCounter.")";
				 	}?>
				 </span></td>
				</tr>
				<tr> 
				 <td align="left">Tanggal PO</td>
				 <td align="right">:&nbsp;</td>
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
						<td nowrap align="left">Nama pemasok</td>
						<td nowrap>:&nbsp;</td>
						<td nowrap align="left"><?php echo $head->legal_name;?>. <?php echo $head->sup_name;?></td>
					</tr>
					<tr>
						<td nowrap align="left">Kontak person</td>
						<td nowrap>:&nbsp;</td>
						<td nowrap align="left"><?php echo $head->per_Fname." ".$head->per_Lname;?></td>
					</tr>
					<tr>
						<td nowrap align="left">No telp</td>
						<td nowrap>:&nbsp;</td>
						<td nowrap align="left"><?php echo $head->sup_phone1;?></td>
					</tr>
					<tr>
						<td nowrap align="left">No fax</td>
						<td nowrap>:&nbsp;</td>
						<td nowrap align="left"><?php echo $head->sup_fax;?></td>
					</tr>
					<tr>
						<td nowrap align="left">Jangka waktu kredit&nbsp;</td>
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
						<td rowspan="2" width="23%" align="center" nowrap><strong>Nama barang</strong><br/><strong>(Kode)</strong></td>
						<td rowspan="2" width="10%" align="center" nowrap><strong>Kuantitas</strong></td>
						<td rowspan="2" width="15%" align="center" nowrap><strong>Harga satuan<br>( <?=$content['detail']->row()->cur_symbol?> )</strong></td>
						<td colspan="2" width="10%" align="center" nowrap><strong>Diskon</strong></td>
						<td rowspan="2" width="20%" align="center" nowrap><strong>Harga total<br>( <?=$content['detail']->row()->cur_symbol?> )</strong></td>
						<td rowspan="2" width="20%" colspan="2" align="center" nowrap><strong>Harus<br/>diterima</strong></td>
					</tr>
					<tr align="center" bgcolor="#FFFFFF"> 
						<td align="center" width="5%" valign="middle" nowrap><strong>%</strong></td>
						<td align="center" width="5%" valign="middle" nowrap><strong>Nilai<br>( <?=$content['detail']->row()->cur_symbol?> )</strong></td>
					</tr>
					<?php 
					$i = 0;
					foreach ($content['detail']->result() as $detail):
						$i = $i + 1;
					//if ($detail->cur_symbol == 'Rp'){
						$price = number_format($detail->price,$detail->cur_digit);
						$disc = number_format($detail->discount_val,$detail->cur_digit);
						$amount = number_format($detail->amount,$detail->cur_digit);
					/*}else{
						$price = number_format($detail->price,5);
						$disc = number_format($detail->discount_val,5);
						$amount = number_format($detail->amount,5);
					}*/
					
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
						<td align='right' nowrap>
						<table border='0' cellpadding='0' cellspacing='0' width='100%'>
								<tr>
									<td align='left'>".$detail->satuan_name."</td>
									<td>&nbsp;</td>
									<td align='right'>".number_format($detail->qty,$detail->satuan_format)."</td>
								</tr>
						</table></td>
						<td align='right' nowrap='nowrap'>".$price."</td>
						<td align='right' nowrap='nowrap'>".$detail->discount."</td>
						<td nowrap='nowrap' align='right'>".$disc."</td>
						<td align='right' nowrap='nowrap'>".$amount."</td>
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
						<td colspan="2"><strong><?=number_format($content['footer']->row()->tot_discount,$content['footer']->row()->cur_digit)?></strong></td>
						<td colspan="3">&nbsp;</td>
					</tr>
					<tr bgcolor="#FFFFFF" align="right"> 
						<td colspan="4"><STRONG>TOTAL KESELURUHAN</STRONG></td>
						<td colspan="3"><strong><?=number_format($content['footer']->row()->tot_price,$content['footer']->row()->cur_digit)?></strong>
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
									<td align="left"><?php echo $notes; ?></td>
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
									<td align="center"><u><?php $user = $usr->row(); echo $user->usr_name; ?></u></td>
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
<input type="button" onclick="print_po('<?php echo $head->po_id."','".$head->po_printCounter."','".$head->po_date."','".$print_status; ?>')" value="Cetak" class="noprint" id="print">
<?php 
	//if ($head->po_printCounter != '0'){
		//echo "<input type='button' onclick=godaftar() value='Kembali' class='noprint'>";
	//}else{
		echo "<input type='button' onclick=goreport() value='Kembali' class='noprint'>";
	//}
?>