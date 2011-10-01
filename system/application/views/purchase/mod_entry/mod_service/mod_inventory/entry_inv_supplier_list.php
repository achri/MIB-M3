<script type="text/javascript">
$('.addSuppToRows').click(function() {
	var row_id = '<?php echo $id_row.$row_id;?>';
	var sup_name = $(this).attr('title');
	var sup_id = $(this).attr('id');
	//var qty = $(this).attr('qty');
	$(row_id+' td input:eq(3)').val(sup_name);
	$(row_id+' td input:eq(2)').val(sup_id);
	$(row_id+' td input:eq(4)').attr('sup_id',sup_id);
	$('div#supplier_add_dialog').dialog('close');
	return false;
});
</script>
<table class="table ui-widget-content" width="400">
<thead class="ui-widget-header">
<tr>
<th align="center"><?=$this->lang->line('supp_name')?></th>
<th align="center"><?=$this->lang->line('supp_address')?></th>
<th align="center">STOK</th>
</tr>
</thead>
<tbody>
<?php 
	if ($sup_list->num_rows() > 0):
		foreach ($sup_list->result() as $rows):
			echo '<tr>
			<td><a class="addSuppToRows" href="#" id="'.$rows->sup_id.'" title="'.$rows->sup_name.'">'.$rows->sup_name.'</a></td>
			<td>'.$rows->sup_address.'</td>
			<td>'.$this->general->digit_number($rows->um_id,$rows->inv_end).' '.$rows->satuan_name.'</td></tr>';
		endforeach;
	endif;
?>
</tbody>
</table>
