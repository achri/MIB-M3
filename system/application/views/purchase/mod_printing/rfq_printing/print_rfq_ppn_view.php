<script language="javascript">
	function print_this(rfq_id,status,count) {
		window.print();
		$.ajax({
			url:'index.php/<?=$link_controller?>/after_print/'+rfq_id+'/'+status+'/'+count,
			type:'POST',
			success: function(data) {
				if(data == 'ok') {
					$('#print').hide();
				}else{
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
<?php 
if ($print_rfq->num_rows() > 0):
	$data_print = $print_rfq->row();
	$print_count = $print_rfq->row()->rfq_printCount + 1;
?>
<div class="noprint">
<h2>MENU CETAK RFQ : TAMPILAN DILAYAR</h2>
</div>
<div align="left">
<table width="870" border="0" cellpadding="1" cellspacing="1" bgcolor="#CCCCCC">
<tr bgcolor="#FFFFFF"><td>
<table width="100%" cellpadding="0" cellspacing="0" border="0" >
<tr>
 <td colspan="2">&nbsp;</td>
</tr>
<tr> 
 <td width="473">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr><td><span style="font-size:18pt;font-weight:bold;text-decoration:underline;">Request for Quotation</span></td></tr>
		<tr><td><span style="font-size:12pt;font-weight:bold;">Departemen pembelian</span></td></tr>
	</table>
 </td>
 <td width="284" rowspan="2" align="center" valign="middle">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
		 <td width="33%">No RFQ</td>
		 <td width="3%">:</td>
		 <td width="64%"><span style="font-size:12pt;font-weight:bold;"><?=$data_print->rfq_no?> <? if ($print_status != 0) echo '(r'.$print_count.')';?></span></td>
		</tr>
		<tr>
		 <td>Tanggal cetak</td>
		 <td>:</td>
		 <td><?=date('d-m-Y')?></td>
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
	<table id="rfq_print" width="100%" border="1" cellspacing="0" cellpadding="2" >
	<tr align="center" valign="middle" bgcolor="#FFFFFF">
		<td align="center" nowrap width="3%"  ><STRONG>No</STRONG></td>
		<td align="center" width="5%"  ><STRONG>No PR</STRONG></td>
		<td align="center" nowrap width="5%"  ><STRONG>Tgl.PR</STRONG></td>
		<td align="left" width="15%"  ><STRONG>Nama barang (Kode)</STRONG></td>
		<td align="center" nowrap width="5%"  ><STRONG>Satuan</STRONG></td>
		<td align="center" nowrap width="6%"  ><STRONG>Kuantitas</STRONG></td>
		<td nowrap width="15%"  ><strong>Jml. pemasok</strong></td>
		<td align="center" width="8%"  ><strong>Harga</strong></td>
		<td align="center" width="16%"  ><strong>Catatan</strong></td>
		<td align="center" width="3%"  ><strong>Paraf</strong></td>
		<td align="center" width="5%"  ><strong>Tgl</strong></td>
	</tr>
	

	<?php 
	$no = 1;
	foreach ($print_rfq->result() as $data_detail):
	?>
	<tr valign="top" bgcolor="#FFFFFF">
		<td align="center" nowrap  ><?=$no?></td>
		<td align="center" nowrap  ><?=$data_detail->pr_no?></td>
		<td align="center" nowrap  ><?=$data_detail->pr_date?></td>
		<td align="center"  >
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr><td valign="top"><?=$data_detail->pro_name?></td>
				</tr>
				<tr>
					<td><?=$data_detail->pro_code?>
					<?=($data_detail->emergencyStat==1)?('<br /><strong><font color="red">(DARURAT)</font></strong>'):('')?>
					</td>
				</tr>
			</table>
		</td>
		<td align="center" nowrap  ><?=$data_detail->satuan_name?></td>
		<td align="right" nowrap  ><?=number_format($data_detail->qty,$data_detail->satuan_format)?></td>
		<?php $sup_row = $data_detail->num_supplier;?>
		<td align="right" nowrap  >
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
		<td align="right" nowrap  >
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
		<td align="right" nowrap  >
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
		<td align="right" nowrap  >
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
		<td align="right" nowrap  >
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
	<?php 
	echo $notes;
	?>
 </td>
</tr>
</table>

</div>
</td></tr></table>
<br>
<div align="left" class="noprint">
<?php 
	if ($print_status == 0):
		if ($data_print->rfq_printStat != 1):?>
		<input type="button" id="print" value="<?=$this->lang->line('print')?>" onclick="print_this('<?=$rfq_id?>','<?=$print_status?>','<?=$print_count?>');">&nbsp;
<?php 
		endif;
	else:
?>
<input type="button" id="print" value="<?=$this->lang->line('print')?>" onclick="print_this('<?=$rfq_id?>','<?=$print_status?>','<?=$print_count?>');">&nbsp;
<?php endif;?>
<?php 
endif;
?>
<input type="button" id="cancel" value="<?=$this->lang->line('back')?>" onclick="back_this();">
</div>
