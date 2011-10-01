<?php if ($pro_data->num_rows() > 0):?>
<form id="kartu_stok" class="cmxform">
<div style="text-align: left" class="ui-widget-content ui-corner-all">
<input type="hidden" name="pro_id" value="<?=$pro_id?>">
<table border="0" width="100%">
<tr><td width="25%"><?=$this->lang->line('inv_category')?></td>
<td width="2%">:</td><td><?=$cat_name?></td><td rowspan="4" align="center">
<div id="photo_prev" class="ui-corner-all ui-widget-content" style="width:130px;height:80%;padding:5px;overflow:auto;">
<?=$this->pictures->thumbs($pro_data->row()->pro_id,126,126)?>
</div>
</td></tr>
<tr><td><?=$this->lang->line('inv_pro_code')?></td><td>:</td><td><?=$pro_data->row()->pro_code?></td></tr>
<tr><td><?=$this->lang->line('inv_pro_name')?></td><td>:</td><td><?=$pro_data->row()->pro_name?></td></tr>
<tr><td><?=$this->lang->line('inv_satuan')?></td><td>:</td><td><?=$pro_satuan->row()->satuan_name?></td></tr>
<!-- tr><td><?//=$this->lang->line('pro_grade')?></td><td>:</td><td>???</td></tr-->
</table>
</div>
<br>
<!-- ->div style="text-align: left" class="ui-widget-content ui-corner-all"-->

	<table id="stock_list" class="ui-widget-content ui-corner-all" width="700px" border="0" cellpadding="2" cellspacing="2">
	<tr class="ui-state-default"><td colspan="8" align="left"><?=$this->lang->line('inv_table_a')?></td></tr>
	<tr class="ui-state-default">
		<td width="35px"><?=$this->lang->line('no')?></td>
		<?php
			if ($pro_data->row()->is_stockJoin == 0):?>
		<td><?=$this->lang->line('inv_sup_name')?></td>
		<?php 
			endif;?>
		<td><?=$this->lang->line('inv_transDate')?></td>
		<td><?=$this->lang->line('inv_begin')?></td>
		<td><?=$this->lang->line('inv_in')?></td>
		<td><?=$this->lang->line('inv_out')?></td>
		<td><?=$this->lang->line('inv_end')?></td>
		<td width="55px"><?=$this->lang->line('pre_history')?></td>
	</tr>
	<?php 
	$no = 1;
	if ($history_inv->num_rows() > 0):
	foreach($history_inv->result() as $get_his):
		/*if ($row_his->sup_id == 0)
			$get_his = $this->tbl_inventory->get_inv_his_new($row_his->pro_id);
		else*/
			//$get_his = $this->tbl_inventory->get_inv_his_new($row_his->pro_id,$row_his->sup_id);
		//foreach ($get_his->result() as $get_his):
	?>
	<tr bgcolor="lightgray">
		<td valign="top" align="right"><?=$no?>.</td>
		<?php
			if ($pro_data->row()->is_stockJoin == 0):?>
		<td valign="top" align="left"><?php echo $get_his->sup_name;?>, <?php echo $get_his->legal_name;?></td>
		<?php
			endif;
		?>
		<td valign="top"><?=date_format(date_create($get_his->inv_transDate),'d-m-Y');?></td>
		<td valign="top" align="right"><?=number_format($get_his->inv_begin,$get_his->satuan_format)?></td>
		<td valign="top" align="right"><?=number_format($get_his->inv_in,$get_his->satuan_format)?></td>
		<td valign="top" align="right"><?=number_format($get_his->inv_out,$get_his->satuan_format)?></td>
		<td valign="top" align="right"><?=number_format($get_his->inv_end,$get_his->satuan_format)?></td>
		<?php
		if ($get_his->inv_out != 0)
			$doc = 1;
		else $doc = 0;
		?>
		<td valign="top"><a onclick="pre_history('<?=$get_his->pro_id?>','<?=($get_his->sup_id!='')?($get_his->sup_id):('0')?>','<?=$doc?>');" style="cursor:pointer"><img src="asset/img_source/icon_lkp.gif"></a></td>
	</tr>
	<?php 
	//endforeach;
	$no++;
	endforeach;
	else:
	?>
	<tr class="lightgray">
		<td colspan="6"><small><font color="red">-= TIDAK ADA HISTORY =-</font></small></td>
	</tr>
	<?php endif;?>
	</table>
	
	<br>
	
	<table id="po_list" class="ui-widget-content ui-corner-all" width="700px" border="0" cellpadding="2" cellspacing="2">
	<tr class="ui-state-default"><td colspan="7" align="left"><?=$this->lang->line('inv_table_b')?> <?=($history_po_row > 2)?("( $history_po_row )"):('')?> </td></tr>
	<tr class="ui-state-default">
		<td width="35px"><?=$this->lang->line('no')?></td>
		<td><?=$this->lang->line('inv_no_po')?></td>
		<td><?=$this->lang->line('inv_sup_name')?></td>
		<td><?=$this->lang->line('inv_order')?></td>
		<td><?=$this->lang->line('inv_terima')?></td>
		<td><?=$this->lang->line('inv_retur')?></td>
		<td><?=$this->lang->line('inv_sisa')?></td>
	</tr>
	<?php 
	$no = 1;
	if ($history_po->num_rows() > 0):
	foreach($history_po->result() as $row_po):?>
	<tr bgcolor="lightgray">
		<td valign="top" align="right"><?=$no?>.</td>
		<td valign="top" align="left"><?=$row_po->po_no?></td>
		<td valign="top" align="left"><?=$row_po->sup_name?> ,<?=$row_po->legal_name?></td>
		<td valign="top" align="right"><?=number_format($row_po->qty,$row_po->satuan_format)?></td>
		<td valign="top" align="right"><?=number_format($row_po->terima,$row_po->satuan_format)?></td>
		<td valign="top" align="right"><?=number_format(0,$row_po->satuan_format)?></td>
		<td valign="top" align="right"><?=number_format($row_po->sisa,$row_po->satuan_format)?></td>
	</tr>
	<?php 
	$no++;
	endforeach;
	else:
	?>
	<tr class="lightgray">
		<td colspan="7"><small><font color="red">-= TIDAK ADA PO AKTIF =-</font></small></td>
	</tr>
	<?php endif;?>
	</table>
	
	<br>
	
	<table id="rfq_list" class="ui-widget-content ui-corner-all" width="700px">
	<tr class="ui-state-default"><td colspan="5" align="left"><?=$this->lang->line('inv_table_c')?> <?=($history_rfq_row > 2)?("( $history_rfq_row )"):('')?></td></tr>
	<tr class="ui-state-default">
		<td width="35px"><?=$this->lang->line('no')?></td>
		<td><?=$this->lang->line('inv_no_pr')?></td>
		<td><?=$this->lang->line('inv_no_rfq')?></td>
		<td><?=$this->lang->line('inv_tgl_pr')?></td>
		<td><?=$this->lang->line('inv_request')?></td>
	</tr>
	<?php 
	$no = 1;
	if ($history_rfq->num_rows() > 0):
	foreach($history_rfq->result() as $row_rfq):?>
	<tr bgcolor="lightgray">
		<td valign="top" align="right"><?=$no?>.</td>
        <td valign="top" align="center"><?=$row_rfq->pr_no?></td>
		<td valign="top" align="center"><?=$row_rfq->rfq_no?></td>
        <td valign="top" align="center"><?=$row_rfq->pr_date?></td>
        <td valign="top" align="left"><?=$row_rfq->usr_name?></td>
	</tr>
	<?php 
	$no++;
	endforeach;
	else:
	?>
	<tr class="lightgray">
		<td colspan="6"><small><font color="red">-= TIDAK ADA PR DAN RFQ AKTIF =-</font></small></td>
	</tr>
	<?php endif;?>
	</table>

</form>
<?php endif;?>