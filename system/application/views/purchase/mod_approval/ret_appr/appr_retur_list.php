<script type="text/javascript">
$(document).ready(function() {
	$("#dialog").dialog({
		autoOpen: false,
		modal: true});
});

	function open_retur(id) {
			$.ajax({
				type:'POST',
				url:'index.php/<?=$link_controller?>/show_retur/'+id,
				success: function(data) {
					$('#retcontent').html(data);
				}
			});
			return false;
	}

	function alert_retur() {
		$('#dialog').dialog('open');
	}
</script>
<?php
	echo $js_grid;
?>
<table id="flex_adj" style="display:none" class=""></table>

