<script type="text/javascript">
	function open_perkiraan(pcvid, prid) {
		$.ajax({
			type:'POST',
			url:'index.php/<?php echo $link_controller;?>/open_perkiraan/'+pcvid+'/'+prid,
			success: function(data) {
				$('#perkiraancontent').html(data);
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