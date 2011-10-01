<script language="javascript">
var ret = false;
function cek_val() {
	$('.cek').each(function() {
		var val = $(this).val();
		if (val != '') {
			ret = true;
		}
	});	
	return ret;
}
$(document).ready(function() {
	$('.po_autocomplete_ret').focus().autocomplete('index.php/<?=$link_controller?>/list_autocomplate_ret',{
		minChars: 3,
		matchCase: true,
		extraParams: {sup_id:'<?=$sup_id?>'}
	}).result(function(event,item) {
		$('#po_id').val(item[1]);
	}).css('text-transform','uppercase');
});
</script>
<h3>MENU TERIMA BARANG OLEH GUDANG : PEMASOK <strong><?=$sup_name?></strong></h3>
<?php if ($page_stats=='good_return'):?>
<form action="index.php/<?=$link_controller?>/list_po_det/ret/<?=$page_stats?>" method="post" class="ui-widget-content ui-corner-all" style="border:1px solid;width:350px" onsubmit="return cek_val();">
<table cellpadding="1" cellspacing="5" border="0" width="100%">
<tr>
	<td>No PO</td>
	<td>:</td>
	<td>
		<input name="po_no" id="po_no" class="po_autocomplete_ret">
		<input type="hidden" name="po_id" id="po_id" class="cek">
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
<?php else:?>
<table width="60%"  border="0" cellpadding="1" cellspacing="1" class="ui-widget-content ui-corner-all">
		<tr class="ui-widget-header">
		  <td colspan="3">Daftar PO</td>
		</tr>
		<tr class="ui-widget-header">
		  <td width="10%" align="center">No</td>
		  <td width="50%" align="center">No PO</td>
		  <td width="40%" align="center">Jumlah item</td>
	    </tr>
		<?php 
		if ($po_list->num_rows() > 0):
		$no = 1;
		foreach ($po_list->result() as $row_po):
		?>
		<tr bgcolor="lightgray">
		  <td valign="top" align="right" class="ui-state-active"><?=$no?>. &nbsp;</td>
		  <td valign="top" align="center"><a href="index.php/<?=$link_controller?>/list_po_det/<?=$row_po->po_id?>/<?=$page_stats?>"><?=$row_po->po_no?></a></td>
		  <td valign="top" align="center"><?=$row_po->jum_item?></td>
		</tr>
		<?php 
		$no++;
		endforeach;
		endif;
		?>
</table>
<?php endif;?>