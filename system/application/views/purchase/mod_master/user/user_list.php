<script type="text/javascript">

	function edituser(id,status){
		$('#usrtabs').tabs( 'remove' ,[0]);
		$('#usrtabs').tabs( 'add' , 'index.php/<?php echo $link_controller;?>/user_frm_edit/'+id+'/'+status , 'Edit User', [0]);
		$('#usrtabs').tabs( 'select',0);
		$('#usrtabs').tabs( 'remove' ,[1]);
	}

	function deleteuser(id) {
		del = confirm("<?php echo $message; ?>");
		if (del == true){
			$.ajax({
				type: 'POST',
				url: 'index.php/<?php echo $link_controller;?>/delete_user/'+id,
				success: function(data) {
					//alert (data);
				$('#main_content').html(data);
	  			},
	  			dataType:"html"
			});
		}
	}
	
	<?
	if ($log_ids != ''):?>
		edituser('<?=$log_ids?>','Login');
	<?
	endif;
	?>
	
</script>
<table id="flex1" style="display:none"></table>
	<?php
		echo $js_grid;
	?>