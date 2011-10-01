<script type="text/javascript">
$(document).ready(function() {
	$("#dialog").dialog({
		autoOpen: false,
		modal: true});
});

	function open_mr(id) {
			$.ajax({
				type:'POST',
				url:'index.php/<?php echo $link_controller;?>/open_mr/'+id,
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
			echo $js_grid.'<br>';
			echo "Catatan : <br>
	- Data MR dalam 1 baris berwarna merah adalah MR yang sudah DUEDATE<br>";
			
		}else{
			echo "<center><div class='ui-corner-all headers' align='center'><font color='red'>$kosong</font></div></center>";
		}
	?>
	
<div id="dialog" title="Error MR">
	<p><?php //echo ($this->lang->line('jabatan_form_error1'));?>
		silahkan proses MR yg berwarna merah
	</p>
</div>