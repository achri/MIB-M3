<script language="javascript">
function print_this(rfq_id) {
	window.print();
	$.ajax({
		type:'POST',
		url:'index.php/<?=$link_controller?>/set_print/'+rfq_id,
		success: function(data) {
			$('#content_print').html(data);
		}
	});
	//location.href = 'index.php/<?=$link_controller?>/set_print/'+rfq_id;
	return false;
}
</script>
<?php 
if ($rfq_list->num_rows() > 0):
$data_print = $rfq_list->row();
$data_rfq = $rfq_get->row();
?>
<div class="noprint">
<div align="right">
<input type="button" id="print" value="Cetak" onclick="print_this('<?=$rfq_id?>');">&nbsp;
<input type="button" id="cancel" value="Cancel" onclick="document.location='index.php/<?=$link_controller?>/index'">
</div>
<hr>
</div>
<div align="center">
<table width="870" border="0" cellspacing="0" cellpadding="0">
<tr>
 <td colspan="2">&nbsp;</td>
</tr>
<tr> 
 <td width="473">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr><td><span style="font-size:18pt;font-weight:bold;text-decoration:underline;">Request for Quotation</span></td></tr>
		<tr><td><span style="font-size:12pt;font-weight:bold;">Departemen Pembelian</span></td></tr>
	</table>
 </td>
 <td width="284" rowspan="2" align="center" valign="middle">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
		 <td width="33%">No RFQ</td>
		 <td width="3%">:</td>
		 <td width="64%"><span style="font-size:12pt;font-weight:bold;"><?=$data_print->rfq_no?> <? if ($data_rfq->rfq_printCount > 0) echo '(r'.$data_rfq->rfq_printCount.')';?></span></td>
		</tr>
		<tr>
		 <td>Tgl Cetak</td>
		 <td>:</td>
		 <td><?=$data_rfq->rfq_printDate?></td>
		</tr>
	</table>
 </td>
</tr>
<tr>
 <td colspan="2"></td>
</tr>
<tr>
 <td colspan="2">&nbsp;</td>
</tr>

<tr>
  <td colspan="2">
	<table id="rfq_print" width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl_outline">
	<tr align="center" valign="middle" bgcolor="#FFFFFF">
		<td align="center" nowrap width="3%" class="tbl_outline"><STRONG>No</STRONG></td>
		<td align="center" width="5%" class="tbl_outline"><STRONG>No PR</STRONG></td>
		<td align="center" nowrap width="5%" class="tbl_outline"><STRONG>Tgl.PR</STRONG></td>
		<td align="left" width="15%" class="tbl_outline"><STRONG>Nama Barang (Kode)</STRONG></td>
		<td align="center" nowrap width="5%" class="tbl_outline"><STRONG>Satuan</STRONG></td>
		<td align="center" nowrap width="6%" class="tbl_outline"><STRONG>Kuantitas</STRONG></td>
		<td nowrap width="15%" class="tbl_outline"><strong>Supplier</strong></td>
		<td align="center" width="8%" class="tbl_outline"><strong>Harga</strong></td>
		<td align="center" width="16%" class="tbl_outline"><strong>Catatan</strong></td>
		<td align="center" width="3%" class="tbl_outline"><strong>Paraf</strong></td>
		<td align="center" width="5%" class="tbl_outline"><strong>Tgl</strong></td>
	</tr>
	

	<?php 
	$no = 1;
	foreach ($rfq_list->result() as $data_detail):
	?>
	<tr valign="top" bgcolor="#FFFFFF">
		<td align="center" nowrap class="tbl_outline"><?=$no?></td>
		<td align="center" nowrap class="tbl_outline"><?=$data_detail->pr_no?></td>
		<td align="center" nowrap class="tbl_outline"><?=$data_detail->pr_date?></td>
		<td align="center" class="tbl_outline">
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr><td valign="top"><?=$data_detail->pro_name?></td>
				</tr>
				<tr>
					<td><?=$data_detail->pro_code?>
					<?=($data_detail->emergencyStat==1)?('<br /><strong><font color="red">(EMERGENCY)</font></strong>'):('')?>
					</td>
				</tr>
			</table>
		</td>
		<td align="center" nowrap class="tbl_outline"><?=$data_detail->satuan_name?></td>
		<td align="right" nowrap class="tbl_outline"><?=$data_detail->qty?></td>
		<?php $sup_row = $data_detail->num_supplier;?>
		<td align="right" nowrap class="tbl_outline">
		   <table border="0" cellpadding="0" cellspacing="0" width="100%">
				<?php 
				for ($i=1;$i<=$sup_row;$i++):
				?>
				<tr>
				  <td valign="top" height="50px;" class="tbl_outline_supplier" align="left"><?=$i?>.</td>
				</tr>
				<?php 
				endfor;
				?>
			</table>
		</td>
		<td align="right" nowrap class="tbl_outline">
		   <table border="0" cellpadding="0" cellspacing="0" width="100%">
				<?php 
				for ($i=1;$i<=$sup_row;$i++):
				?>
				<tr>
				  <td valign="top" height="50px;" class="tbl_outline_supplier">&nbsp;</td>
				</tr>
				<?php 
				endfor;
				?>
			</table>
		</td>
		<td align="right" nowrap class="tbl_outline">
		   <table border="0" cellpadding="0" cellspacing="0" width="100%">
				<?php 
				for ($i=1;$i<=$sup_row;$i++):
				?>
				<tr>
				  <td valign="top" height="50px;" class="tbl_outline_supplier">&nbsp;</td>
				</tr>
				<?php 
				endfor;
				?>
			</table>
		</td>
		<td align="right" nowrap class="tbl_outline">
		   <table border="0" cellpadding="0" cellspacing="0" width="100%">
				<?php 
				for ($i=1;$i<=$sup_row;$i++):
				?>
				<tr>
				  <td valign="top" height="50px;" class="tbl_outline_supplier">&nbsp;</td>
				</tr>
				<?php 
				endfor;
				?>
			</table>
		</td>
		<td align="right" nowrap class="tbl_outline">
		   <table border="0" cellpadding="0" cellspacing="0" width="100%">
				<?php 
				for ($i=1;$i<=$sup_row;$i++):
				?>
				<tr>
				  <td valign="top" height="50px;" class="tbl_outline_supplier">&nbsp;</td>
				</tr>
				<?php 
				endfor;
				?>
			</table>
		</td>
	</tr>
	<?php 
	$no++;
	endforeach;
	?>
	</table>
 </td>
</tr>
</table>
<?php 
endif;
?>
</div>
<br>