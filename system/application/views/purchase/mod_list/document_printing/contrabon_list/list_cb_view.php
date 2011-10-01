<script language="javascript">
function print_this(con_id) {
	window.print();
	$.ajax({
		type:'POST',
		url:'index.php/<?=$link_controller?>/set_print/'+con_id,
		success: function(data) {
			$('#content_print').html(data);
		}
	});
	//location.href = 'index.php/<?=$link_controller?>/set_print/'+con_id;
	return false;
}
</script>
<?php 
if ($print_con->num_rows() > 0):
$data_print = $print_con->row();
?>
<div class="noprint">
<div align="right">
<input type="button" id="print" value="Cetak" onclick="print_this('<?=$con_id?>');">&nbsp;
<input type="button" id="cancel" value="Cancel" onclick="document.location='index.php/<?=$link_controller?>/index'">
</div>
<hr>
</div>
<div align="center">
<table width="600" cellpadding="1" cellspacing="0" border="0" bgcolor="#CCCCCC">
	<tr bgcolor="#FFFFFF">
		<td>
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td><span style="font-size: 16pt; font-weight:bold;">KONTRA BON</span></td>
					<td rowspan="2" align="center">
						<table border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td class="result">KB Nomor</td>
								<td>&nbsp;:&nbsp;</td>
								<td class="result"><strong><?=$data_print->con_no?> <? if ($con_get->row()->con_printCount > 0) echo '(r'.$con_get->row()->con_printCount.')';?></strong></td>
							</tr>
							<tr>
								<td>Tanggal Cetak</td>
								<td>&nbsp;:&nbsp;</td>
								<td><?=$data_print->con_date?></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr bgcolor="#FFFFFF" class="txt_print_prev">
	  <td>
		<table cellpadding="2" cellspacing="0" border="0" width="100%">
			<tr>
				<td width="20%">Telah diterima dari</td>
				<td width="2%">:</td>
				<td width="78%"><?=$data_print->sup_name?></td>
			</tr>
			<tr>
				<td>Surat jalan/Faktur</td>
				<td>:</td>
				<td><?=$data_print->jumlah_gr?> lembar</td>
			</tr>
			<tr>
				<td>Perincian</td>
				<td>:</td>
				<td>&nbsp;</td>
			</tr>
		</table>
		<table width="100%" cellpadding="2" cellspacing="1" border="0" class="tbl_outline">
			<tr valign="top" bgcolor="#FFFFFF">
				<td width="5%" nowrap class="tbl_outline">
					<strong>No</strong>
				</td>
				<td width="35%" nowrap class="tbl_outline">
					<strong>No Faktur Supplier</strong>
				</td>
				<td width="35%" nowrap class="tbl_outline">
					<strong>Surat Jalan</strong>
				</td>
				<td width="35%" nowrap class="tbl_outline">
					<strong>No PO</strong>
				</td>
				<td width="15%" nowrap class="tbl_outline">
					<strong>Tgl Barang Tiba</strong>
				</td>
				<td width="1%" nowrap class="tbl_outline"></td>
				<td width="25%" nowrap class="tbl_outline">
					<strong>Nilai</strong>
				</td>
			</tr>
			<?php 
			if($print_gr->num_rows() > 0):
			$no = 1;
			foreach ($print_gr->result() as $row_gr):
			?>
			<?php if($row_gr->gr_type=='rec'):?>
			<tr valign="top" bgcolor="#FFFFFF">
				<td class="tbl_outline"><?=$no?>.</td>
				<td class="tbl_outline"><?=$row_gr->gr_fakturSup?></td>
				<td class="tbl_outline"><?=$row_gr->gr_suratJalan?></td>
				<td class="tbl_outline"><?=$row_gr->po_no?></td>
				<td class="tbl_outline"><?=$row_gr->gr_date?></td>
				<td class="tbl_outline"><?=$row_gr->cur_symbol?></td>
				<td align="right" class="tbl_outline"><?=number_format($row_gr->gr_value,2)?></td>
			</tr>
			<?php else:?>
			<tr bgcolor="#FFFFFF">
				<td>&nbsp;</td>
				<td colspan="4" class="tbl_outline">&nbsp;&nbsp;&nbsp;<i>Retur-<?=$row_gr->gr_no?>&nbsp;-&nbsp;<?=$row_gr->gr_date?></i></td>
				<td class="tbl_outline"><?=$row_gr->cur_symbol?></td>
				<td align="right" class="tbl_outline">(<?=number_format($row_gr->gr_value,2)?>)</td>
			</tr>
			<?php 
			endif;
			$no++;
			endforeach;
			endif;
			?>
			<tr valign="top" bgcolor="#FFFFFF">
				<td colspan="5" align="right">
					<strong>Total</strong>
				</td>
				<td><?=$data_print->cur_symbol?></td>
				<td align="right"><?=number_format($data_print->con_value,2)?></td>
			</tr>
			<tr valign="top" bgcolor="#FFFFFF">
				<td colspan="7"> 
					<table cellpadding="2" cellspacing="0" border="0" width="100%">
					    <!--{if $sudah}-->
						<!-- tr align="top">
							<td width="10%" valign="top" nowrap>Terbilang Rp</td>
							<td width="1%" valign="top">:</td>
							<td colspan="2"><i>
							<!--{$CON_VALUE_BILANGAN}->
							</i></td>
						</tr-->
						<!--{/if}-->
						<tr>
							<td width="10%" nowrap>Tanggal Kembali</td>
							<td width="1%">:</td>
							<td width="60%">
							<?php 
							$term_credit = $data_print->term_days;
							$status_po   = $data_print->po_status;
							if($status_po == 1) {
								$back_date     = mktime(0, 0, 0, date("m")  , date("d")+$term_credit, date("Y"));
								$str_back_date = date("d-m-Y", $back_date);
						
							}
							else {
								$str_back_date = "(PO Belum Tutup)";
							}
							echo $str_back_date;
							?>
							</td>
							<td align="center" nowrap>Penerima</td>
						</tr>
						<tr>
							<td colspan="4"><br/>&nbsp;</td>
						</tr>
						<tr>
							<td colspan="3">&nbsp;</td>
							<td align="center">(<strong><u>&nbsp;<?=$data_print->con_penerima?>&nbsp;</u></strong>)</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr class="noscreen">
				<td colspan="7">
					<table width="100%" cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td bgcolor="#999999">
								<img src="/images/black.gif" width="100%" height="1" border="0" alt="" />
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</td>
   </tr>
</table>
<?php 
endif;
?>
</div>
</div>
