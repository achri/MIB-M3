<script type="text/javascript">
$(document).ready(function (){
	$('.edit1').editable('index.php/<?php echo $link_controller;?>/bank_update1',{
		indicator : 'Saving...',
		tooltip   : 'Click to edit...',
		width : '200px'
		}
	);

	$('.edit2').editable('index.php/<?php echo $link_controller;?>/bank_update2',{
		indicator : 'Saving...',
		tooltip   : 'Click to edit...',
		width : '200px'
		}
	);

	$('.deletebank').click(function() {
		var id = $(this).attr('id');
		var bank = '#name'+id;
		var name = $(bank).val();

		del = confirm("Delete bank "+name+" ..??");
		if (del == true){
			$.ajax({
				type: 'POST',
				url: 'index.php/<?php echo $link_controller;?>/delete_bank',
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
			url: 'index.php/<?php echo $link_controller;?>/list_bank',
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
<td width="30%"><?php echo ($this->lang->line('bank_col_1')).$image; ?></td>
<td width="50%"><?php echo ($this->lang->line('bank_col_2')).$image; ?></td>
<td align="center" width="10%"><?php echo($this->lang->line('kategori_col_3')); ?></td>
</tr>
<?php 
	$i = 1;
	if ($get_list->num_rows() > 0):
		foreach ($get_list->result() as $row): 
			echo "<tr class='x'>
						<td>".$i."<input type='hidden' id='name".$row->bank_id."' value='".$row->bank_name_singkat."'</td>
						<td class='edit1' id='$row->bank_id'>".$row->bank_name_singkat."</td>
						<td class='edit2' id='$row->bank_id.$row->bank_id'>".$row->bank_name_lengkap."</td>
						<td align='center'><img src='./asset/img_source/button_empty.png' id='$row->bank_id' class='deletebank'></td>
				  </tr>
				  ";
		$i++;
		endforeach;
	else:
		echo "Empty";
	endif;
?>
</table>
