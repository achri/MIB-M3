<script language="javascript">
var ret = false;
function cek_val() {
	var val_sup = $('#sup_id').val();
	var val_po = $('#po_no').val();
	if (val_sup != '' || val_po != '') {
		ret = true;
	}
	return ret;
}
$(document).ready(function() {
	//$('.po_mask').mask("99/99/99999",{placeholder:"_"});
	$('.po_autocomplete').focus().autocomplete('index.php/<?=$link_controller?>/list_autocomplate',{
		minChars: 5,
		matchCase: true,
		max: 50
	}).css('text-transform','uppercase');
});
</script>
<h3><?=$page_title?></h3>
<form id="list_po" action="index.php/<?=$link_controller?>/list_po/<?=$page_stats?>" method="post" onsubmit="return cek_val();">
<table cellpadding="1" cellspacing="5" border="0" width="100%" class="ui-widget-content ui-corner-all" style="border:1px solid;width:auto">
<tr>
	<td width="100px">Nama pemasok</td>
	<td>:</td>
	<td>
		<select id="sup_id" name="sup_id">
			<option value="">- pilih pemasok -</option>
			<?php 
			if ($sup_list->num_rows() > 0):
			foreach ($sup_list->result() as $row_sup):
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
<? if ($page_stats != 'good_return'): ?>
<tr>
	<td>No PO</td>
	<td>:</td>
	<td>
		<input name="po_no" id="po_no" class="po_autocomplete cek">
	</td>
</tr>
<? endif; ?>
<tr><td colspan="3"><hr></td></tr>
<tr>
	<td colspan="3" align="center">
		<input type="submit" value="<?=$btn_process?>">&nbsp;<input type="reset" value="<?=$btn_clear?>">
	</td>
</tr>
</table>
</form>
