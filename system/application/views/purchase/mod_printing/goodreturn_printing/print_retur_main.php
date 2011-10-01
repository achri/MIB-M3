<script type="text/javascript">
	function open_retur(id,print_stats) {
			$.ajax({
				type:'POST',
				url:'index.php/<?=$link_controller?>/print_retur_view/'+id+'/'+print_stats,
				success: function(data) {
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
<table id="print_retur_list" style="display:none" class=""></table>
</div>