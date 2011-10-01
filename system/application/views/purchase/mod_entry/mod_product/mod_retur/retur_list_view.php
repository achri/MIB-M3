<script type="text/javascript">
$(document).ready(function() {
	$("#dialog").dialog({
		autoOpen: false,
		modal: true});
});

	function alert_rfq() {
		$('#dialog').dialog('open');
	}

	function open_retur(id, proid) {
		$.ajax({
			type:'POST',
			url:'index.php/<?php echo $link_controller;?>/open_retur/'+id+'/'+proid,
			success: function(data) {
				$('#returcontent').html(data);
			}
		});
		return false;
		//alert (id + '\n' + proid);
	}
</script>
<table id="flex1" style="display:none"></table>
	<?php
	if ($kosong == '')
		echo $js_grid;
	else
		echo "<center><div class='ui-corner-all headers' align='center'><font color='red'>$kosong</font></div></center>";
	?>
	
<div id="dialog" title="Error PR">
	<p><?php //echo ($this->lang->line('jabatan_form_error1'));?>
		silahkan proses rfq yg berwarna merah terlebihdahulu
	</p>
</div>