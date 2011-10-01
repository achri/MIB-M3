<script type="text/javascript">
$(document).ready(function() {
	$("#dialog").dialog({
		autoOpen: false,
		modal: true});
});

	function open_pr(id) {
			$.ajax({
				type:'POST',
				url:'index.php/<?php echo $link_controller;?>/open_pr/'+id,
				success: function(data) {
					$('#prcontent').html(data);
				}
			});
			return false;
	}

	function alert_pr() {
		$('#dialog').dialog('open');
	}
</script>
<table id="flex1" style="display:none"></table>
	<?php
	if ($kosong == ''){
		echo $js_grid."<br>";
		echo "Catatan : <br>
	- Data PR yang berwarna merah adalah PR yang sudah jatuh tempo lebih dari 3 hari<br>
    - Status PR berwarna merah adalah PR yang Darurat ";
	}else{
		echo "<center><div class='ui-corner-all headers' align='center'><font color='red'>$kosong</font></div></center>";
	}
	?>
	
<div id="dialog" title="Error PR">
	<p><?php //echo ($this->lang->line('jabatan_form_error1'));?>
		silahkan proses PR yg berwarna merah
	</p>
</div>