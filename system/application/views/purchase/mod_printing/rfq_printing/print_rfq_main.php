<script type="text/javascript">
	function open_rfq(id,status) {
			$.ajax({
				type:'POST',
				url:'index.php/<?=$link_controller?>/print_rfq_view/'+id+'/'+status,
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
<table id="print_rfq_list" style="display:none" class=""></table>
</div>