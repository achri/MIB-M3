<script language="javascript">
	function print_this(srfq_id,print_status,print_count) {
		window.print();
		$.ajax({
			url:'index.php/<?=$link_controller?>/after_print/'+srfq_id+'/'+print_status+'/'+print_count,
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
	//$print_count = $ret_list->row()->ret_printCount + 1;
?>
<table width="780" border="0" cellspacing="0" cellpadding="0">
<tr>
 <td colspan="2"></td>
</tr>
<tr> 
 <td width="473">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr><td><span style="font-size:18pt;font-weight:bold;text-decoration:underline;">Request for Quotation - Service</span></td></tr>
		<tr><td><span style="font-size:12pt;font-weight:bold;">Departemen Pembelian</span></td></tr>
	</table>
 </td>
 <td width="284" rowspan="2" align="center" valign="middle">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
		 <td width="33%">No RFQ</td>
		 <td width="3%">:</td>
		 <td width="64%"><span style="font-size:12pt;font-weight:bold;"><?=$srfq_list->row()->srfq_no?></span></td>
		</tr>
		<tr>
		 <td>Tgl Cetak</td>
		 <td>:</td>
		 <td><!--{$DATE_PRINT}--></td>
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
	<table width="100%" border="1" cellspacing="0" cellpadding="2" bgcolor="#C0C0C0">
	<tr align="center" valign="middle" bgcolor="#FFFFFF">
		<td align="center" nowrap="nowrap" width="3%"><STRONG>No</STRONG></td>
		<td align="center" width="5%"><STRONG>No SR</STRONG></td>
		<td align="center" nowrap="nowrap" width="5%"><STRONG>Tgl.SR</STRONG></td>
		<td align="left" width="15%"><STRONG>Nama Barang (Kode)</STRONG></td>
		<td align="center" nowrap="nowrap" width="5%"><STRONG>Unit</STRONG></td>
		<td align="center" nowrap="nowrap" width="6%"><STRONG>Qty</STRONG></td>
		<td nowrap="nowrap" width="15%"><strong>Supplier</strong></td>
		<td align="center" width="8%"><strong>Harga</strong></td>
		<td align="center" width="16%"><strong>Catatan</strong></td>
		<td align="center" width="3%"><strong>App</strong></td>
		<td align="center" width="5%"><strong>Tgl</strong></td>
	</tr>

	<?php 
	if($srfq_det_list->num_rows() > 0):
		$no=1;
		foreach($srfq_det_list->result() as $row_srfq):
	?>
	<tr valign="top" bgcolor="#FFFFFF">
		<td align="center" nowrap="nowrap"><?=$no?></td>
		<td align="center" nowrap="nowrap"><?=$row_srfq->sr_no?></td>
		<td align="center" nowrap="nowrap"><?=$row_srfq->sr_date?></td>
		<td align="center">
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr><td valign="top"><?=$row_srfq->pro_name?></td>
				</tr>
				<tr>
					<td>(<?=$row_srfq->pro_code?>)
					</td>
				</tr>
				<tr>
				    <td>Tipe : <strong><?=($row_srfq->service_cat=='in')?('Inside'):('Outside')?></strong>
					</td>
				</tr>
				<tr>
					<td>Kategori : <strong><?=($row_srfq->service_type=='repair')?('Perbaikan'):('Perawatan')?></strong>
					</td>
				</tr>
			</table>
		</td>
		<td align="center" nowrap="nowrap"><?=$row_srfq->satuan_name?></td>
		<td align="right" nowrap="nowrap"><?=number_format($row_srfq->qty,$row_srfq->satuan_format)?></td>
		<td align="right" nowrap="nowrap">
		   <table border="0" cellpadding="0" cellspacing="1" width="100%">
				<?php for ($i=1;$i<=$row_srfq->num_supplier;$i++):?>
				<tr>
				  <td valign="top"><?=$i?>.</td>
				</tr>
				<tr>
				  <td valign="top">&nbsp;</td>
				</tr>
				<?php endfor;?>
			</table>
		</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<?
		$no++;
		endforeach;
	endif;
	?>
	</table>
 </td>
</tr>
</table>
</div>
<?//=$row_po->sub_um_id.'|'.$row_po->pro_um_id?>
<br>
<div align="left" class="noprint">
<?php
	if ($print_status == 0):
		if ($srfq_list->row()->srfq_printStat != 1):	
?>
<input type="button" id="print" value="<?=$this->lang->line('print')?>" onclick="print_this('<?=$srfq_list->row()->srfq_id?>','<?=$print_status?>','0');">&nbsp;
<?php 
		endif;
	else:
?>
<input type="button" id="print" value="<?=$this->lang->line('print')?>" onclick="print_this('<?=$srfq_list->row()->srfq_id?>','<?=$print_status?>','<?=$print_count?>');">&nbsp;
<?php endif; ?>
<input type="button" id="cancel" value="<?=$this->lang->line('back')?>" onclick="back_this();">
</div>