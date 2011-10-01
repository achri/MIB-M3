<script type="text/javascript">
	function open_pcv_print(id,status) {
			$.ajax({
				type:'POST',
				url:'index.php/<?php echo $link_controller;?>/open_pcv_print/'+id+'/'+status,
				success: function(data) {
					$('#pcvcontent').html(data);
				}
			});
			return false;
	}

</script>
<table id="flex1" style="display:none"></table>
	<?php
	if ($kosong == '')
		echo $js_grid;
	else
		echo "<center><div class='ui-corner-all headers' align='center'><font color='red'>$kosong</font></div></center>";
	?>