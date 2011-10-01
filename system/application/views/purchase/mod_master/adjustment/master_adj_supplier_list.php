<form id="form_suppliers">
<table class="table ui-widget-content" width="400">
<thead class="ui-widget-header">
<tr>
<th align="center"><?=$this->lang->line('select')?></th>
<th align="center"><?=$this->lang->line('supp_name')?></th>
<th align="center"><?=$this->lang->line('supp_address')?></th>
</tr>
</thead>
<tbody>
<?php 
	if ($sup_pro_list->num_rows() > 0):
		$i = 1;
		foreach ($sup_pro_list->result() as $rows):
?>
	<tr>
		<td>
		<input type="checkbox" name="sup_id[]" id="sel_sup_<?=$i?>"  value="<?=$rows->sup_id?>" class="sup_rows"/>
		</td>
		<td><?=$rows->sup_name?></td>
		<td><?=$rows->sup_address?></td>
	</tr>
<?php
		$i++;
		endforeach;
	endif;
?>
</tbody>
</table>
</form>