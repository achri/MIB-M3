<script type="text/javascript">
$(document).ready(function() {
	$("#dialog").dialog({
		autoOpen: false,
		modal: true});
});

	function open_adj(id) {
			$.ajax({
				type:'POST',
				url:'index.php/<?=$link_controller?>/show_adjustment/'+id,
				success: function(data) {
					$('#prcontent').html(data);
				}
			});
			return false;
	}

	function alert_adj() {
		$('#dialog').dialog('open');
	}
</script>
<?php
	echo $js_grid;
?>
<table id="flex_adj" style="display:none" class=""></table>

