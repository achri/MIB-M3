<script type="text/javascript">
function open_grl(grlid){
	$.ajax({
		type: 'POST',
		url: 'index.php/mod_goodrelease_printing/goodrelease/open_grl/'+grlid,
		data: '',
		success: function(data) {
			$('#grlcontent').html(data);
		}
	});
	return false;
}
</script>
<?php
echo $js_grid;
?>
<table id="grl_list" style="display:none" class=""></table>