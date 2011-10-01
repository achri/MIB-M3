<h3>MENU TERIMA BARANG OLEH GUDANG : DAFTAR PO SUPPLIER <strong><?=$sup_name?></strong></h3>
<table width="60%"  border="0" cellpadding="1" cellspacing="1" class="ui-widget-content ui-corner-all">
		<tr class="ui-widget-header">
		  <td colspan="3">Daftar Pesanan (PO)</td>
		</tr>
		<tr class="ui-widget-header">
		  <td width="10%" align="center">No</td>
		  <td width="50%" align="center">No PO</td>
		  <td width="40%" align="center">Jumlah Item</td>
	    </tr>
		<?php 
		if ($po_list->num_rows() > 0):
		$no = 1;
		foreach ($po_list->result() as $row_po):
		?>
		<tr bgcolor="lightgray">
		  <td valign="top" align="right" class="ui-state-active"><?=$no?>. &nbsp;</td>
		  <td valign="top" align="center"><a href="index.php/<?=$link_controller?>/list_po_det/<?=$row_po->po_id?>/<?=$page_stats?>"><?=$row_po->po_no?></a></td>
		  <td valign="top" align="center"><?=$row_po->jum_item?></td>
		</tr>
		<?php 
		$no++;
		endforeach;
		endif;
		?>
</table>