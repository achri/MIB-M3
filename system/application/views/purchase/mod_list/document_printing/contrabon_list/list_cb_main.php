<div class="noprint">
<h2><?=$this->lang->line('doc_bon_title')?></h2>
</div>
<script type="text/javascript">
function open_con(con_id){
	$.ajax({
		type: 'POST',
		url: 'index.php/<?=$link_controller?>/print_bon_view/'+con_id,
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
<table id="con_list" style="display:none" class=""></table>
</div>