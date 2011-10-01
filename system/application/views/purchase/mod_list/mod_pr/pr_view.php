<script type="text/javascript">
function back(){
	window.location = "index.php/<?php echo $link_controller;?>/index";
}
</script>


<?php
	$head = $pr['header']->row();
?>
<table width="100%">
  <tr>
    <td class='ui-widget-header' width='120'>No PR</td>
    <td><?php echo $head->pr_no; ?></td>
    <td width='300'></td>
    <td class='ui-widget-header' width='120'>Departemen</td>
    <td><?php echo $head->dep_name; ?></td>
  </tr>
  <tr>
    <td class='ui-widget-header' width='120'>Tanggal PR</td>
    <td><?php echo $head->pr_date; ?></td>
    <td width='300'></td>
    <td class='ui-widget-header' width='120'>Pemohon</td>
    <td><?php echo $head->usr_name; ?></td>
  </tr>
  <tr>
    <td class='ui-widget-header' width='120'>Tipe rencana</td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
</table>

<br/>
<table id="dataview" class="table" width="100%">
	<tr class='ui-widget-header'>
		<td colspan="10">DETIL PR</td>
	</tr>
	<tr class='ui-widget-header' align="center">
		<td>No</td>
		<td>Kode</td>
		<td>Nama produk</td>
		<td>Tanggal kirim</td>
		<td>Satuan</td>
		<td>Kuantitas</td>
		<td>Status pembelian</td>
		<td width="150">Keterangan</td>
		<td>Status persetujuan</td>
		<td width="150">Keterangan persetujuan</td>
	</tr>
<?php 
$i = 0;
	foreach ($pr['detail']->result() as $dtl):
	$i = $i + 1;
	
	if ($dtl->emergencyStat != ''){
		if ($dtl->emergencyStat == 0)
			$stat = "Normal";
		else
			$stat = "<font color=red>Darurat</font>";
	}
		
	if ($dtl->requestStat == 1)
		$restat = "Disetujui";
	else if ($dtl->requestStat == 2)
		$restat = "Diubah & Disetujui";
	else if ($dtl->requestStat == 3)
		$restat = "Disetujuidgn Cat";
	else if ($dtl->requestStat == 4)
		$restat = "Ditunda";
	else if ($dtl->requestStat == 5)
		$restat = "Ditolak";
	else
		$restat = "Belum ditentukan";
		
	echo "<tr class='x' align='center'> 
			<td valign='top'>".$i."</td>
			<td valign='top'>".$dtl->pro_code."</td>
			<td valign='top' align='left'>".$dtl->pro_name."</td>
			<td valign='top'>".$dtl->delivery_date."</td>
			<td valign='top'>".$dtl->satuan_name."</td>
			<td valign='top' align='right'>".number_format($dtl->qty,$dtl->satuan_format)."</td>
			<td valign='top'>".$stat."</td>
			<td valign='top' align='left'>".$dtl->description."</td>
			<td valign='top'>".$restat."</td>
			<td valign='top' align='left'>".$dtl->pr_appr_note."</td>
		</tr>";
	endforeach;
?>
</table>
<br>
<div align="center">
<input type="button" value="Kembali" onclick="back()"></input>
</div>