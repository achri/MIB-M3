<script type="text/javascript">
function open_pr(prid){
	$.ajax({
		type: 'POST',
		url: 'index.php/<?php echo $link_controller;?>/open_pr/'+prid,
		data: '',
		success: function(data) {
			$('#prcontent').html(data);
		}
	});
	return false;
}
</script>
<?php
echo $js_grid;
?>
<table id="pr_list" style="display:none" class=""></table>