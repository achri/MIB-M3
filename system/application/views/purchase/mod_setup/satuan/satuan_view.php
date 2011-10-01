<script type="text/javascript">
$(document).ready(function (){
/*
	$('.edit').editable('index.php/satuan/satuan_update',{
		indicator : 'Saving...',
		tooltip   : 'Click to edit...',
		width : '200px'
		}
	);
	*/

	$('.deletesatuan').click(function() {
		var id = $(this).attr('id');
		var sat = '#name'+id;
		var name = $(sat).val();

		del = confirm("Delete satuan "+name+" ..??");
		if (del == true){
			$.ajax({
				type: 'POST',
				url: 'index.php/<?php echo $link_controller;?>/delete_satuan',
				data: "id="+id,
				success: function(data) {
				$('#main_content').html(data);
	  			},
	  			dataType:"html"
			});
		}
	});

	$('.sort').click(function() {
		var id = $(this).attr('id');
		$.ajax({
			type: 'POST',
			url: 'index.php/<?php echo $link_controller;?>/list_satuan',
			data: "sort="+id,
			success: function(data) {
			$('#tabs-1').html(data);
  			},
  			dataType:"html"
		});
	})
});
</script>
<?php 
	if ($trig == '1'){
		$image = "<img src='./asset/img_source/asc.png' id='0' class='sort' border='0'>";
	}else{
		$image = "<img src='./asset/img_source/dsc.png' id='1' class='sort' border='0'>";
	}
?>
<table width="60%" class="table">
<tr class='ui-widget-header'>
<td align="center" width="5%"><?php echo($this->lang->line('kategori_col_0')); ?></td>
<td width="80%"><?php echo ($this->lang->line('satuan_col_1')).$image; ?></td>
<td align="center" width="10%"><?php echo($this->lang->line('kategori_col_3')); ?></td>
</tr>
<?php 
	$i = 1;
	if ($get_list->num_rows() > 0):
		foreach ($get_list->result() as $row): 
			echo "<tr class='x'>
						<td>".$i."<input type='hidden' id='name".$row->satuan_id."' value='".$row->satuan_name."'</td>
						<td class='edit' id='$row->satuan_id'>".$row->satuan_name."</td>
						<td align='center'><img src='./asset/img_source/button_empty.png' id='$row->satuan_id' class='deletesatuan'></td>
				  </tr>
				  ";
		$i++;
		endforeach;
	else:
		echo "Empty";
	endif;
?>
</table>
