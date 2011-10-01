<script type="text/javascript">
	function print_grl(id,count,tgl,status){
		$.ajax({
			type: 'POST',
			url: 'index.php/mod_goodrelease_printing/goodrelease/print_update/'+id+'/'+count+'/'+tgl+'/'+status,
			success: function(data) {
				window.print();
				if (data == 'ok'){
					$('#print').hide();
				}else {
					$('#grlcontent').html(data);
				}
			}
		});
		return false;
	}

	function goreport(status){
		window.location = 'index.php/mod_goodrelease_printing/goodrelease/index/'+status;
	}
</script>
<?php
$head = $content['head']->row();
?>
<div>
<table width="756" border="0" cellpadding="1" cellspacing="1" bgcolor="#CCCCCC">
	<tr bgcolor="#FFFFFF">
		<td>
		 <table width="100%" border="0" cellpadding="1" cellspacing="1">
			<tr>
			 <td colspan="2" valign="top" nowrap><span style="font-size:18pt;font-weight:bold;text-decoration:underline;">FORM KELUAR BARANG</span></td>
			 <td align="right" valign="middle" nowrap rowspan="2">
			   <table width="90%" border="0" cellspacing="0" cellpadding="0">
			    <tr> 
				 <td width="50%" align="left">Nomor RF</td>
				 <td width="10">:</td>
				 <td width="40%" align="left"><span style="font-size:12pt;font-weight:bold;"> <?php echo $head->grl_no;
				 if ($head->grl_printCounter != '0'){
				 		echo " (r".$head->grl_printCounter.")";
				 	}?></span></td>
				</tr>
				<tr> 
				 <td align="left">Tanggal RF</td>
				 <td>:</td>
				 <td align="left"> <?php echo $head->grl_date;?></td>
				</tr>
				<tr> 
						 <td align='left'>Tanggal cetak</td>
						 <td>:</td>
						 <td align='left'><?=date('d-m-Y')?></td>
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
						<td nowrap align="left">Nama Pemohon</td>
						<td nowrap>:&nbsp;</td>
						<td nowrap align="left"><?php echo $head->usr_name;?></td>
					</tr>
					<tr>
						<td nowrap align="left">Departemen</td>
						<td nowrap>:&nbsp;</td>
						<td nowrap align="left"><?php echo $head->dep_name;?></td>
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
				<table id="ItemPO" width="100%" border="1" cellspacing="0" cellpadding="2" bgcolor="#C0C0C0">
					<tr align="center" valign="middle" bgcolor="#FFFFFF"> 
						<td width="2%" align="center" nowrap><strong>No</strong></td>
						<td width="30%" align="center" nowrap><strong>Nama Barang(Kode)</strong></td>
						<td width="30%" align="center" nowrap><strong>Pemasok</strong></td>
						<td width="10%" align="center" nowrap><strong>Kuantitas</strong></td>
						<td width="20%" align="center" nowrap><strong>Keterangan</strong></td>
					</tr>
					<?php 
					$i = 0;
					foreach ($content['detail']->result() as $detail):
						$i = $i + 1;
						echo "<tr bgcolor='#FFFFFF' valign='top'> 
								<td align='center' nowrap='nowrap'>".$i."</td>
								<td nowrap='nowrap'>
									<table border='0' cellpadding='0' cellspacing='0' width='100%'>
										<tr>
											<td>".$detail->pro_name." ".$detail->pro_code."</td>
										</tr>
										<!--tr>
											<td>detail->pro_code</td>
										</tr-->
									</table>
								</td>
								<td>".$detail->legal_name.". ".$detail->sup_name."</td>
								<td align='right' nowrap>".number_format($detail->qty,$detail->satuan_format)."&nbsp;".$detail->satuan_name."</td>
								<td align='left'>".$detail->description."</td>
							</tr>";
					endforeach;
					$usrapp = $usr->row();
					?>
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
						<td valign="baseline" width="40%">
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr> 
									<td align="center">Sudah Disetujui secara elektronik</td>
								</tr>
								<tr> 
									<td align="left">&nbsp;</td>
								</tr>
								<tr> 
									<td align="center"><br/>&nbsp;</td>
								</tr>
								<tr> 
									<td align="center">(<u>&nbsp;<?php echo $usrapp->usr_name; ?>&nbsp;</u>)</td>
								</tr>
							</table>
						</td>
						<td width="30%">
						  &nbsp;
						</td>
						<td valign="baseline" width="30%">
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr> 
									<td align="center">Pemohon</td>
								</tr>
								<tr> 
									<td align="left">&nbsp;</td>
								</tr>
								<tr> 
									<td align="center"><br/>&nbsp;</td>
								</tr>
								<tr> 
									<td align="center">(<u>&nbsp;<?php echo $head->usr_name; ?>&nbsp;</u>)</td>
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
</div>
<br>
<input type="button" onclick="print_grl('<?php echo $head->grl_id."','".$head->grl_printCounter."','".$head->grl_date."','".$print_status; ?>')" value="Cetak" class="noprint" id="print">

<?php 
		echo "<input type='button' onclick=goreport('".$print_status."') value='Kembali' class='noprint'>";
	
?>