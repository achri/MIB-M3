<script type="text/javascript">
$(document).ready(function() {
	$("#dialog").dialog({
		autoOpen: false,
		modal: true});
});

	function open_sr(id) {
			$.ajax({
				type:'POST',
				url:'index.php/<?=$link_controller?>/open_sr/'+id,
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
		echo $js_grid;
	}else{
		echo "<center><div class='ui-corner-all headers' align='center'><font color='red'>$kosong</font></div></center>";
	}
	?>
	
<div id="dialog" title="Error PR">
	<p><?php //echo ($this->lang->line('jabatan_form_error1'));?>
		silahkan proses SR yg berwarna merah
	</p>
</div>