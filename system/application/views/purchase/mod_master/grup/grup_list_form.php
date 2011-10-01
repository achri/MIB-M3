<?=br(8)?>
<div class="ui-widget-content ui-corner-all" style="width:95%">
<?php if ($cat_list->num_rows() > 0): 
	foreach ($cat_list->result() as $rows):
?>
	<form id="ubah_form" onsubmit="return editGroup();">
	<table align="center">
	<tr>
		<td>Nama grup</td><td>:</td>
		<td>
			<input type="text" id="cat_name" name="value" value="<?=$rows->cat_name?>">
			<input type="hidden" id="cat_id" name="id" value="<?=$rows->cat_id?>">
			<input type="hidden" id="cat_val" name="cat_val" value="<?=$rows->cat_name?>">
		</td>
	</tr>
	<tr>
		<td colspan="3" align="center">
			<input type="submit" value="Ubah">&nbsp;
			<input type="button" value="Hapus" onclick="deleteGroup('<?=$rows->cat_id?>')">
		</td>
	</tr>
	</table>
	</form>
<?php
	endforeach;
	else:
?>
<div align="center"><font color="red">PILIH GRUP</font></div>
<?php
	endif;
?>
</div>