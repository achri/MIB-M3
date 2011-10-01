<script type="text/javascript">
$('.addSOToRows').click(function() {
	var row_id = '<?php echo $row_id;?>';
	var prmr = '<?php echo $type;?>';
	var so_id = $(this).attr('so_id');
	var so_no = $(this).attr('so_no');
	
	
	$('#'+prmr+'_so_id_'+row_id).val(so_id);
	$('#'+prmr+'_so_no_'+row_id).val(so_no);
	
	$('div#supplier_add_dialog').dialog('close');
	
	return false;
});
</script>
<table class="table ui-widget-content" width="400">
<thead class="ui-widget-header">
<tr>
<th align="center">No</th>
<th align="center">No SO</th>
<th align="center">Tgl SO</th>
</tr>
</thead>
<tbody>
<?php 
	if ($so_list->num_rows() > 0):
		$i = 1;
		foreach ($so_list->result() as $rows):
?>
		<tr>
			<td><?=$i?>.</td>
			<td><a class="addSOToRows" href="#" id="list_so_<?=$i?>" so_id="<?=$rows->so_id?>" so_no="<?=$rows->so_no?>"><?=$rows->so_no?></a></td>
			<td><?=$rows->so_date?></td>
		</tr>			
<?
			$i++;
		endforeach;
	endif;
?>
</tbody>
</table>
