<script type="text/javascript">
function open_po(poid){
	$.ajax({
		type: 'POST',
		url: 'index.php/mod_po_printing/po/open_po/'+poid,
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
<table id="po_list" style="display:none" class=""></table>