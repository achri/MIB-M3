<script type="text/javascript">
function open_po_buka(poid){
	$.ajax({
		type: 'POST',
		url: 'index.php/<?=$link_controller?>/open_po/'+poid,
		data: '',
		success: function(data) {
			$('#pocontent').html(data);
		}
	});
	return false;
}
</script>
<?php
echo $js_grid;
?>
<table id="po_buka_list" style="display:none" class=""></table>