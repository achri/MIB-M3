<script type="text/javascript">
$(document).ready(function (){
	$('.edit').editable('index.php/<?php echo $link_controller;?>/dep_update',{
		indicator : 'Saving...',
		tooltip   : 'Click to edit...',
		width : '200px'
		}
	);

	$('.deletedep').click(function() {
		var id = $(this).attr('id');
		var dep = '#name'+id;
		var name = $(dep).val();

		del = confirm("Delete Departemen "+name+" ..?");
		if (del == true){
			$.ajax({
				type: 'POST',
				url: 'index.php/<?php echo $link_controller;?>/delete_dep',
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
			url: 'index.php/<?php echo $link_controller;?>/list_dep',
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
<td width="80%"><?php echo ($this->lang->line('dep_col_1')).$image; ?></td>
<td align="center" width="10%"><?php echo($this->lang->line('kategori_col_3')); ?></td>
</tr>
<?php 
	$i = 1;
	if ($get_list->num_rows() > 0):
		foreach ($get_list->result() as $row): 
			echo "<tr class='x'>
						<td>".$i."<input type='hidden' id='name".$row->dep_id."' value='".$row->dep_name."'></td>
						<td class='edit' id='$row->dep_id'>".$row->dep_name."</td>
						<td align='center'><img src='./asset/img_source/button_empty.png' id='$row->dep_id' class='deletedep'></td>
				  </tr>
				  ";
		$i++;
		endforeach;
	else:
		echo "Empty";
	endif;
?>
</table>
