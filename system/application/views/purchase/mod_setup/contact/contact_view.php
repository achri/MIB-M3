<script type="text/javascript">
$(document).ready(function (){
	$('.edit').editable('index.php/<?php echo $link_controller_term;?>/term_update',{
		indicator : 'Saving...',
		tooltip   : 'Click to edit...'
		}
	);


	$('.deleteterm').click(function() {
		var id = $(this).attr('id');
		var term = '#name'+id;
		var name = $(term).val();

		del = confirm("Delete Term "+name+" ..??");
		if (del == true){
			$.ajax({
				type: 'POST',
				url: 'index.php/<?php echo $link_controller_term;?>/delete_term',
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
			url: 'index.php/<?php echo $link_controller_term;?>/list_term',
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
<table width="80%" class="table">
<tr class='ui-widget-header'>
<td align="center" width="5%"><?php echo($this->lang->line('kategori_col_0')); ?></td>
<td width="20%"><?php echo ($this->lang->line('term_col_1')); ?></td>
<td width="35%"><?php echo ($this->lang->line('term_col_2')).$image; ?></td>
<td width="15%"><?php echo ($this->lang->line('term_col_3')); ?></td>
<td width="15%"><?php echo ($this->lang->line('term_col_4')); ?></td>
<td align="center" width="10%"><?php echo($this->lang->line('kategori_col_3')); ?></td>
</tr>
<?php 
	$i = 1;
	if ($get_list->num_rows() > 0):
		foreach ($get_list->result() as $row): 
			echo "<tr class='x'>
						<td>".$i."<input type='hidden' id='name".$row->term_id."' value='".$row->term_id."'</td>
						<td class='edit' id='$row->term_id'>".$row->term_id."</td>
						<td class='edit' id='$row->term_id.$row->term_id'>".$row->term_name."</td>
						<td class='edit' id='$row->term_id.$row->term_id.$row->term_id'>".$row->term_days."</td>
						<td class='edit' id='$row->term_id.$row->term_id.$row->term_id.$row->term_id'>".$row->term_discount."</td>
						<td align='center'><img src='./asset/img_source/button_empty.png' id='$row->term_id' class='deleteterm'></td>
				  </tr>
				  ";
		$i++;
		endforeach;
	else:
		echo "Empty";
	endif;
?>
</table>
