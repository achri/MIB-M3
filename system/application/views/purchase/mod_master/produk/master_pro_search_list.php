<script type="text/javascript">
var dTables;
$(document).ready(function (){
	/*
	$('#product_list tbody td').hover( function() {
		var iCol = $('td').index(this) % 5;
		var nTrs = dTables.fnGetNodes();
		$('td:nth-child('+(iCol+1)+')', nTrs).addClass( 'highlighted' );
	}, function() {
		var nTrs = dTables.fnGetNodes();
		$('td.highlighted', nTrs).removeClass('highlighted');
	} );
	
	dTables = $('#product_list').dataTable({

		"bPaginate": true,
		"bLengthChange": false,
		"bFilter": false,
		"bSort": true,
		"bInfo": true,
		"bAutoWidth": false,
		
		"sPaginationType": "full_numbers"
		
		//"bSortClasses": false
	});*/
});
</script>
<table id="product_list" width="100%" border="0" height="auto" class="labelcell ui-corner-all ui-widget-content">
<!-- >thead class='ui-widget-header'-->
<tr class="ui-state-default">
	<td align="center" width="15%"><?php echo($this->lang->line('pro_code')); ?></td>
	<td width="35%"><?php echo ($this->lang->line('pro_name')); ?></td>
	<td align="center" width="40%"><?php echo($this->lang->line('cat_id')); ?></td>
	<td align="center" width="10%"><?php echo($this->lang->line('pro_status')); ?></td>
</td>
<!-- ->/thead-->
<!-- ->tbody-->
<?php 
	if ($list_product->num_rows() > 0):
		foreach ($list_product->result() as $row): 
			$cat_name=implode('/',$this->pro_code->set_split_code($row->pro_code,'cat_name'));
			echo "<tr id='$row->pro_id' class='search_line' bgcolor='lightgray'>
						<td class='src' align='center'>".$row->pro_code."</td>
						<td class='srn change_name' id='$row->pro_id'>".$row->pro_name."</td>
						<td>".$cat_name."</td>
						<td align='center'>".$row->pro_status."</td>
				  </tr>
				  ";
		endforeach;
	else:
		echo "<tr><td colspan='5' align='center'>Empty</td></tr>";
	endif;
?>
<!-- ->/tbody-->
</table>