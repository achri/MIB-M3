<script language="javascript">
var ret = false;
function cek_val() {
	var val_sup = $('#sup_id').val();
	var val_po = $('#po_id').val();
	if (val_sup != '' || val_po != '') {
		ret = true;
	}
	return ret;
}
$(document).ready(function() {
	$('.po_autocomplete').focus().autocomplete('index.php/<?=$link_controller?>/list_autocomplate',{
		minChars: 5,
		matchCase: true,
		max: 50
	}).result(function(event,item) {
		$('#po_id').val(item[1]);
		$('#spo_id').val(item[2]);
	}).css('text-transform','uppercase');
});
</script>
<h3><?=$page_title?></h3>
<form id="list_bon" action="index.php/<?=$link_controller?>/list_po" method="post" onsubmit="return cek_val();">
<table cellpadding="1" cellspacing="5" border="0" width="100%" class="ui-widget-content ui-corner-all" style="border:1px solid;width:auto" >
<tr>
	<td>Nama pemasok</td>
	<td>:</td>
	<td>
		<select id="sup_id" name="sup_id" validation="required">
			<option value="">- pilih pemasok -</option>
			<?php 
			if ($list_sup_bon->num_rows() > 0):
				foreach ($list_sup_bon->result() as $row_sup):
					$supplier = '<option value="'.$row_sup->sup_id.'">'.$row_sup->sup_name.', '.$row_sup->legal_name.'</option>';
					if ($row_sup->sup_status == 0)
						$supplier = '<option value="'.$row_sup->sup_id.'" style="color:red;">'.$row_sup->sup_name.', '.$row_sup->legal_name.' (non aktif)</option>';
					echo $supplier;
				endforeach;
			endif;
			?>
		</select>
	</td>
</tr>
<tr>
	<td>No PO</td>
	<td>:</td>
	<td>
		<input name="po_no" id="po_no" class="po_autocomplete cek">
		<input name="po_id" id="po_id" type="hidden">
		<input name="spo_id" id="spo_id" type="hidden">
	</td>
</tr>
<tr><td colspan="3"><hr></td></tr>
<tr>
	<td colspan="3" align="center">
		<input type="submit" value="Proses">&nbsp;<input type="reset" value="Reset">
	</td>
</tr>
</table>
</form>

