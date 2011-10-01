<script type="text/javascript">
	function print_pcv(id, count, tgl){
		$.ajax({
			type: 'POST',
			url: 'index.php/mod_pcv_printing/pcv_print/print_update/'+id+'/'+count+'/'+tgl,
			success: function() {
			window.print();
			$('#print').hide();
			//$('#pocontent').html(data);
			}
		});
		return false;
	}

	function godaftar(){
			window.location = 'index.php/mod_data_list/list_pettycash/index';
		}

	function goprint(){
		window.location = 'index.php/mod_pcv_printing/pcv_print/index';
	}
	
</script>
<?php
$head2 = $content['head2']->row();
$head1 = $content['head1']->row();
$total = $content['total']->row();
?>
<div>
<table width="650px" border="0" cellpadding="1" cellspacing="1" bgcolor="#CCCCCC">
	<tr bgcolor="#FFFFFF">
		<td>
		 <table width="100%" border="0" cellpadding="0" cellspacing="0">
			<tr>
			 <td colspan="2" valign="top" nowrap><span style="font-size:18pt;font-weight:bold;text-decoration:underline;">PETTY CASH VOUCHER</span></td>
			 <td align="right" valign="middle" nowrap rowspan="2">
			   <table width="90%" border="0" cellspacing="0" cellpadding="0">
			    <tr> 
				 <td width="50%" align="left">Nomor PCV</td>
				 <td width="10">:</td>
				 <td width="40%" align="left"><span style="font-size:12pt;font-weight:bold;">
				 		<?php echo $head2->pcv_no; 
				 			if ($head2->pcv_printCounter != 0){
				 				echo " (r".$head2->pcv_printCounter.")";
				 			}
				 		?> </span>
				 </td>
				</tr>
				<tr> 
				 <td align="left">Tanggal Dicetak</td>
				 <td>:</td>
				 <td align="left">
				 <?php 
					$now = date('d-m-Y');
					if ($head2->pcv_printCounter != 0){
						echo $head2->tgl_print;
					}else{
						echo $now;
					}
				?>
				 </td>
				</tr>
				<?php 
				if ($head2->pcv_printCounter != 0){
					echo "<tr> 
							 <td align='left'>Tanggal Cetak terakhir</td>
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
						<td nowrap align="left">Dicetak Oleh</td>
						<td nowrap>:&nbsp;</td>
						<td nowrap align="left"><?php echo $head1->usr_name;?></td>
					</tr>
					<tr>
						<td nowrap align="left">Departemen</td>
						<td nowrap>:&nbsp;</td>
						<td nowrap align="left"><?php echo $head1->dep_name;?></td>
					</tr>
				</table>
			 </td>
			 <td>&nbsp;</td>
			</tr>
			<tr>
			 <td colspan="3">&nbsp;</td>
			</tr>
			<tr class="noscreen" bgcolor="#FFFFFF">
			 <td colspan="3">
				<table width="100%" cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td bgcolor="#999999"><img src="./image/black.gif" width="100%" height="1" border="0" alt="" />
						</td>
					</tr>
				</table>
			 </td>
			</tr>
			<tr> 
			 <td colspan="3" valign="top" nowrap>
				<table id="ItemPO" width="100%" border="1" cellspacing="0" cellpadding="2" bgcolor="#C0C0C0">
					<tr align="center" valign="middle" bgcolor="#FFFFFF"> 
						<td width="2%" align="center" nowrap><strong>No</strong></td>
						<td width="50%" align="center" nowrap><strong>Nama Barang</strong><br/><strong>(Kode)</strong></td>
						<td width="20%" align="center" nowrap><strong>Kuantitas</strong></td>
						<td width="20%" align="center" nowrap><strong>Harga Perkiraan</strong></td>
					</tr>
					<tr class="noscreen" bgcolor="#FFFFFF">
						<td colspan="4">
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
					$i = 0;
					foreach ($content['detail']->result() as $detail):
					$i = $i+1;
						echo "<tr bgcolor='#FFFFFF' valign='top'> 
						<td align='center' nowrap='nowrap'>".$i."</td>
						<td nowrap='nowrap'>
							<table border='0' cellpadding='0' cellspacing='0' width='100%'>
								<tr>
									<td>".$detail->pro_name."</td>
								</tr>
								<tr>
									<td>".$detail->pro_code."</td>
								</tr>
							</table>
						</td>
						<td align='right' nowrap>".$detail->qty."&nbsp;".$detail->satuan_name."</td>
						<td align='right' nowrap>
							<table width='100%' cellspacing='0' cellpadding='0'>
								<tr>
									<td width='20%'>".$detail->cur_symbol."</td>
									<td width='80%' align='right'>".number_format($detail->price_pre,2,',','.')."</td>
								</tr>
							</table>
							
						</td>
					</tr>";
					endforeach;
					?>
					<tr class="noscreen" bgcolor="#FFFFFF">
						<td colspan="4">
							<table width="100%" cellpadding="0" cellspacing="0" border="0">
								<tr>
									<td bgcolor="#999999">
										<img src="./image/black.gif" width="100%" height="1" border="0" alt="" />
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr class="noscreen" bgcolor="#FFFFFF">
						<td colspan="4">
							<table width="100%" cellpadding="0" cellspacing="0" border="0">
								<tr>
									<td bgcolor="#999999">
										<img src="./image/black.gif" width="100%" height="1" border="0" alt="" />
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr bgcolor="#FFFFFF">
						<td colspan="2" align="center">
						 TOTAL PERMINTAAN :
						</td>
						<td colspan="2"><span style="font-size:10pt;font-weight:bold">Rp. <?php echo number_format($total->total,2,',','.');?></span></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="3">
				<?php 
					$notes = $note->row();
					echo $notes->note;
				?>
			</td>
		</tr>
		<tr> 
			<td colspan="3" nowrap> 
				<table width="100%" border="0" cellpadding="0" cellspacing="0">
					<tr> 
						<td colspan="3" valign="baseline">&nbsp;</td>
					</tr>
					<tr> 
						<td valign="baseline">
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr> 
									<td align="left">Sudah Disetujui secara elektronik</td>
								</tr>
								<tr> 
									<td align="left">&nbsp;</td>
								</tr>
								<tr> 
									<td align="center"><br/>&nbsp;</td>
								</tr>
								<tr> 
									<td align="left">(<u>&nbsp;<?php echo $head1->usr_name;?>&nbsp;</u>)</td>
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
</div>
<input type="button" onclick="print_pcv('<?php echo $head2->pcv_id."','".$head2->pcv_printCounter."','".$head2->tgl_print; ?>')" value="print" class="noprint" id="print">

<?php 
	if ($head2->pcv_printCounter != 0){
		echo "<input type='button' onclick=godaftar() value='Kembali' class='noprint'>";
	}else{
		echo "<input type='button' onclick=goprint() value='Kembali' class='noprint'>";
	}
?>