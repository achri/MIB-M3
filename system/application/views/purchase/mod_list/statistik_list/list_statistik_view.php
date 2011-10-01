<?
if ($qget_price->num_rows() > 0):
	$last_buy = $qlast_buy->row();
	$get_price = $qget_price->row();
?>
<table class='table' width='200px' align='center' valign='middle'>
<!--tr>
	<td width='40px'><b>-Currency</b></td>
	<td>
	<select name="cur_id">
	<//?
		$get_curr = $this->db->query("select cur_id,cur_symbol from prc_master_currency order by cur_symbol");
		foreach($get_curr->result() as $rcurr):
			echo "<option value='$rcurr->cur_id' ".($rcurr->cur_id == $last_buy->cur_id)?('SELECTED'):('')." >$rcurr->cur_symbol</option>";
		endforeach;
	?>
	</select>
	</td>
</tr-->
<tr>
	<td width='40px'><b>-<?=$this->lang->line('rfq_label_average')?></b></td>
	<td><?=$last_buy->cur_symbol.'. '.number_format($get_price->rata,$last_buy->cur_digit)?></td>
</tr>
<tr>
	<td><b>-<?=$this->lang->line('rfq_label_min')?></b></td>
	<td><?=$last_buy->cur_symbol.'. '.number_format($get_price->min,$last_buy->cur_digit)?></td>
</tr>
<tr>
	<td><b>-<?=$this->lang->line('rfq_label_max')?></b></td>
	<td><?=$last_buy->cur_symbol.'. '.number_format($get_price->max,$last_buy->cur_digit)?></td>
</tr>
<!--tr>
	<td><b>-<//?=$this->lang->line('rfq_label_curent')?></b></td>
	<td><//?=$get_price->cur_symbol.'. '.number_format($dtlcont->price,2,',','.')?></td>
</tr-->
<tr>
	<td><b>-<?=$this->lang->line('rfq_label_last')?></b></td>
	<td><?=$last_buy->cur_symbol.'. '.number_format($last_buy->inv_price,$last_buy->cur_digit)?></td>
</tr>
</table>
<?
endif;
?>