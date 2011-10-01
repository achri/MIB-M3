<script type="text/javascript">
$('.addSuppToRows').click(function() {
	var row_id = '<?php echo $id_row.$row_id;?>';
	var sup_name = $(this).attr('title');
	var sup_id = $(this).attr('id');
	$(row_id+' td input:eq(0)').val(sup_name);
	$(row_id+' td input:eq(1)').val(sup_id);
	$('div#supplier_add_dialog').dialog('close');
	return false;
});
var block_this = {
	message: null,
	overlayCSS:  {
		backgroundColor: '#fff',
		opacity:	  	 0,
		cursor:		  	 'inherit'
	}
}
$('.black_list').block(block_this).css('background-color','red');
</script>
<table class="table ui-widget-content" width="400">
<thead class="ui-widget-header">
<tr>
<th align="center"><?=$this->lang->line('supp_name')?></th>
<th align="center"><?=$this->lang->line('supp_address')?></th>
</tr>
</thead>
<tbody>
<?php 
	if ($sup_cat_list->num_rows() > 0):
		foreach ($sup_cat_list->result() as $rows):
			$block = '';
			$font = '';
			if($rows->sup_status == 0):
				$block = "black_list";
				
			endif;
			
			echo "<tr><td align='left'><div class='$block'><a class='addSuppToRows' href='#' id='$rows->sup_id' title='$rows->legal_name. $rows->sup_name'>$rows->sup_name, $rows->legal_name</a></div></td><td align='left'>$rows->sup_address</td></tr>";
		endforeach;
	endif;
?>
</tbody>
</table>