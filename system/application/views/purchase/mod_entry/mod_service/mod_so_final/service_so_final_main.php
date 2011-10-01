<script language="javascript">
var ret = false;
function cek_val() {
	//var val_sup = $('#sup_id').val();
	var val_so = $('#so_no').val();
	if (val_so != '') {
		ret = true;
	}
	return ret;
}
$(document).ready(function() {
	//$('.po_mask').mask("99/99/99999",{placeholder:"_"});
	$('.so_autocomplete').focus().autocomplete('index.php/<?=$link_controller?>/list_autocomplate',{
		minChars: 3,
		matchCase: true,
		max: 50
	}).result(function(event,item) {
		$('#so_id').val(item[1]);
	}).css('text-transform','uppercase');
});
</script>
<h3><?=$page_title?></h3>
<form id="list_so" action="index.php/<?=$link_controller?>/list_so" method="post" class="ui-widget-content ui-corner-all" style="border:1px solid;width:350px" onsubmit="return cek_val();">
<table cellpadding="1" cellspacing="5" border="0" width="100%">
<tr>
	<td width="30%">SO NO</td>
	<td>:</td>
	<td>
		<input name="so_no" id="so_no" class="so_autocomplete cek">
		<input name="so_id" id="so_id" type="hidden">
	</td>
</tr>
<tr><td colspan="3"><hr></td></tr>
<tr>
	<td colspan="3" align="center">
		<input type="submit" value="<?=$btn_process?>">&nbsp;<input type="reset" value="<?=$btn_clear?>">
	</td>
</tr>
</table>
</form>
