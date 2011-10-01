<div class="noprint">
<h2><?=$this->lang->line('doc_bpb_title')?></h2>
</div>
<script type="text/javascript">
	function open_bpb(id) {
			$.ajax({
				type:'POST',
				url:'index.php/<?=$link_controller?>/print_bpb_view/'+id,
				success: function(data) {
					$('#content_print').html(data);
				}
			});
			return false;
	}
</script>
<div align="left" id="content_print">
<?php
echo $js_grid;
?>
<table id="print_bpb_list" style="display:none" class=""></table>
</div>