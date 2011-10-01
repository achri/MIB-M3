<h2>MENU CETAK RFQ</h2>
<div align="center">
<?php if(!isset($empty)):?>
<table width="90%"  border="0" cellpadding="0" cellspacing="2" class="ui-widget-content ui-corner-all">
	<tr bgcolor="#CCCCCC" class="ui-state-default">
	  <td width="5%" align="center">No</td>
	  <td width="15%" align="center">No RFQ</td>
	  <td width="10%" align="center">Tgl RFQ Dibuat</td>
	  <td width="5%" align="center">Overdue (hari)</td>
      <td width="5%" align="center">Jumlah Item</td>
	  <td width="5%" align="center">Status</td>
	  <td width="10%" align="center">Aksi</td>
	</tr>
	<?php 
	if ($print_list->num_rows() > 0):
	$no = 1;
	foreach ($print_list->result() as $print):
	?>
	<tr bgcolor="lightgray">
	  <td valign="top" align="right" class="ui-widget-header"><?=$no?></td>
	  <td valign="top" align="center"><?=$print->rfq_no?></td>
	  <td valign="top" align="center"><?=$print->rfq_date?></td>
	  <td valign="top" align="center"><?=$print->tgl_selisih?></td>
	  <td valign="top" align="center"><?=$print->jum_item?></td>
	  <td valign="top" align="center"><?=($print->emergency > 0)?("<font color='red'>Emg (".$print->emergency.")</font>"):("Normal")?></td>
	  <td valign="top" align="center"><a href="index.php/<?=$link_controller?>/print_rfq_view/<?=$print->rfq_id?>">
	  <img src="asset/javascript/jQuery/flexigrid/images/magnifier.png" border="0"></a></td>
	</tr>
	<?php 
	$no++;
	endforeach;
	else:
	?>
	<tr>
	  <td colspan="7" align="center">--Tidak ada data--</td>
	</tr>
	<?php 
	endif;
	?>
	<tr bgcolor="#000000">
	  <td colspan="7"><img src="images/spacer.gif" width="1" height="1"></td>
	</tr>
</table>
<?php else: echo $empty; endif;?>
</div>