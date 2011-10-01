<tr>
 	<td width="150" align="center" class="fieldcell">
  	<select name="um_sub[]" id="um_sub" class="um_sub"><option value="0">--Pilih Satuan--</option>
	<?php 
		if ($unit_data->num_rows()>0):
		foreach ($unit_data->result() as $row_unit):
	?>
			<option value="<?=$row_unit->um_id?>"><?=$row_unit->um_name?></option>
	<?php 
		endforeach;
		endif;?>
	</select>
	</td>
	<td width="20" align="center">=</td>
	<td width="70" align="center" class="fieldcell"><input name="um_sub_val[]" class="um_sub_val" type="text" size="6" maxlength="6"></td>
	<td class="fieldcell">
	<select disabled="disabled" class="um_id2">
	<?php 
		if ($unit_data->num_rows()>0):
		foreach ($unit_data->result() as $row_unit):
	?>
		<option value="<?=$row_unit->um_id?>" <?=(($status=='EDIT')&&($pro_data->row()->um_id==$row_unit->um_id))?('SELECTED'):('')?>><?=$row_unit->um_name?></option>
	<?php 
		endforeach;
		endif;?>
	</select>
	</td>
	<td><INPUT TYPE="button" value="Hapus Satuan" id="<?php echo $i;?>" class="removeRowFromTable" title="TblSatuan"></td>
</tr>