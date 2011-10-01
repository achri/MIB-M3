<script type="text/javascript">
$(document).ready(function() {
	$('#dlg_conlist').dialog({
		autoOpen: false,
		bgiFrame: false,
		width: 'auto',
		height: 'auto',
		draggable: false,
		resizable: false,
		buttons: {
			'KELUAR' : function() {
				$(this).dialog('close');
			}
		}
	});
	
});

	function open_retur(id,print_stats) {
			$.ajax({
				type:'POST',
				//url:'index.php/<?=$link_controller?>/select_contrabon/'+id+'/'+print_stats,
				url:'index.php/<?=$link_controller?>/print_payment_view/'+id+'/'+print_stats,
				success: function(data) {
					//$('#dlg_conlist').html('').html(data).dialog('open');
					$('#content_print').html(data);
				}
			});
			return false;
	}
</script>
<div align="left" id="content_print">
<h2><?=$page_title?></h2>
<?php
echo $js_grid;
?>
<table id="print_payment_list" style="display:none" class=""></table>
</div>
<div id="dlg_conlist" title="PILIH KONTRABON">

</div>