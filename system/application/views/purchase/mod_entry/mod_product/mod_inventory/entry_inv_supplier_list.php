<script type="text/javascript">
$('.addSuppToRows').click(function() {
	var row_id = '<?php echo $row_id;?>';
	var sup_name = $(this).attr('title');
	var sup_id = $(this).attr('sup_id');
	var qty = $(this).attr('qty');
	var qtydigit = $(this).attr('digit_satuan');
	
	$('#mr_sup_id_'+row_id).val(sup_id);
	$('#mr_pro_stok_'+row_id).val(qty+'_'+qtydigit);
	$('#mr_sup_name_'+row_id).val(sup_name);
	
	$('div#supplier_add_dialog').dialog('close');
	
	return false;
});
</script>
<table class="table ui-widget-content" width="400">
<thead class="ui-widget-header">
<tr>
<th align="center">Nama pemasok</th>
<th align="center">Alamat pemasok</th>
<th align="center">Stok akhir</th>
</tr>
</thead>
<tbody>
<?php 
	if ($sup_list->num_rows() > 0):
		$i = 1;
		foreach ($sup_list->result() as $rows):
			if ($rows->inv_end != 0)
			echo '<tr>
			<td align="left"><a class="addSuppToRows" href="#" id="list_sup_'.$i.'" sup_id="'.$rows->sup_id.'" digit_satuan="'.$rows->satuan_format.'" qty="'.$rows->inv_end.'" title="'.$rows->sup_name.', '.$rows->legal_name.'">'.$rows->sup_name.', '.$rows->legal_name.'</a></td>
			<td align="left">'.$rows->sup_address.'</td>
			<td><table width="100%" border=0 cellspacing=0 cellpadding=0><tr><td>'.$rows->satuan_name.'</td><td>'.$this->general->digit_number($rows->um_id,$rows->inv_end).'</td></tr></table></td></tr>';
			$i++;
		endforeach;
	endif;
?>
</tbody>
</table>
