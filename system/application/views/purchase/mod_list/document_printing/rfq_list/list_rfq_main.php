<div class="noprint">
<h2><?=$this->lang->line('doc_rfq_title')?></h2>
</div>
<script type="text/javascript">
function open_rfq(rfq_id){
	$.ajax({
		type: 'POST',
		url: 'index.php/<?=$link_controller?>/view_rfq_det/'+rfq_id,
		data: '',
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
<table id="rfq_list" style="display:none" class=""></table>
</div>