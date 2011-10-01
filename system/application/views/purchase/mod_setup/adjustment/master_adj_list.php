<script type="text/javascript">
function flexEdit(celDiv,id) {
	$(celDiv).click(function(){
		$.getJSON ('index.php/<?=$link_controller?>/flex_get_id/'+id, function(data) {
			$.each(data, function(entryIndex,entry){
				$('#pro_code').val(entry['pro_code']);
				$('#pro_name').val(entry['pro_name']);
				$('#pro_id').val(entry['pro_id']);
				$('#is_join').val(entry['is_join']);
				$('#setup').attr('disabled',false);
			});
		});
	});	
	return false;
}
</script>
<?php
echo $js_grid;
?>
<table id="product_list" style="display:none" class=""></table>