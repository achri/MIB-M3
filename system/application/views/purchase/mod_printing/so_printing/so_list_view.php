<script type="text/javascript">
	function open_so(id) {
			$.ajax({
				type:'POST',
				url:'index.php/<?=$link_controller?>/open_so/'+id,
				success: function(data) {
					$('#pocontent').html(data);
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