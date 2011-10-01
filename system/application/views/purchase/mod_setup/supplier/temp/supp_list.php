<script type="text/javascript">
$(document).ready(function (){
	
	$('.editsupp').click(function(){
		var id = $(this).attr('id');
		$.ajax({
			type: 'POST',
			url: 'index.php/Supplier/Callfrm_supplier_edit',
			data: "id="+id,
			success: function(data) {
			$('#main_content').html(data);
  			},
  			dataType:"html"
		});
	});
});
</script>

<table id="dataview" width="80%" class='table'>
<tr class='ui-widget-header'>
<td align="center" width="30%"><?php echo($this->lang->line('kategori_col_0')); ?></td>
<td width="60%"><?php echo($this->lang->line('grup_col_0')); ?></td>
<td align="center" width="10%"><?php echo($this->lang->line('kategori_col_3')); ?></td>
</tr>
<?php
foreach ($get_sup->result() as $supp):
	echo "<tr class='x'>
		<td>".$supp->sup_name."</td><td>";
		$get_list = $this->Mpurchase->list_cat($supp->sup_id);
			foreach ($get_list->result() as $name) :
			echo '- '.$name->cat_name.'  ';
			endforeach;
	echo "</td><td align='center'><img src='./asset/img_source/button_edit.png' id='$name->sup_id' class='editsupp'></td></tr>";
endforeach;	
?>
</table>