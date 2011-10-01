<script language="javascript">
var ret = false;
function cek_val() {
	var val = $('#sup_id').val();
	if (val != '') {
		ret = true;
	}
	return ret;
}

$(document).ready(function() {
	
});
</script>
<h3><?=$page_title?></h3>
<form id="list_bon" action="index.php/<?=$link_controller?>/list_gr" method="post" onsubmit="return cek_val();">
<table cellpadding="1" cellspacing="5" border="0" width="100%" class="ui-widget-content ui-corner-all"  style="border:1px solid;width:auto">
<tr>
	<td>Nama Pemasok</td>
	<td>:</td>
	<td>
		<select id="sup_id" name="sup_id" validate="required:true">
			<option value="">-- Pilih Pemasok --</option>
			<?php 
			if ($list_sup_pay->num_rows() > 0):
				foreach ($list_sup_pay->result() as $row_sup):
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
<tr><td colspan="3"><hr></td></tr>
<tr>
	<td colspan="3" align="center">
		<input type="submit" value="Proses">&nbsp;<input type="reset" value="Reset">
	</td>
</tr>
</table>
</form>

