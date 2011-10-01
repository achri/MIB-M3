<script type="text/javascript">
function open_pcv_print(id) {
	$.ajax({
		type:'POST',
		url:'index.php/mod_pcv_printing/pcv_print/open_pcv_print/'+id,
		success: function(data) {
			$('#pcvcontent').html(data);
		}
	});
	return false;
}
</script>
<?php
echo $js_grid;
?>
<table id="pcv_list" style="display:none" class=""></table>