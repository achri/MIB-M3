<?php if ($pro_data->num_rows() > 0):?>
<form id="kartu_stok" class="cmxform">
<div style="text-align: left" class="ui-widget-content ui-corner-all">
<input type="hidden" name="pro_id" value="<?=$pro_id?>">
<table border="0" width="100%">
<tr><td width="25%"><?=$this->lang->line('tree_root_category')?></td>
<td width="2%">:</td><td><?=$cat_name?></td><td rowspan="4" align="center">
<div id="photo_prev" class="ui-corner-all ui-widget-content" style="width:130px;height:80%;padding:5px;overflow:auto;">
<?=$this->pictures->thumbs($pro_data->row()->pro_id,126,126)?>
</div>
</td></tr>
<tr><td><?=$this->lang->line('pro_code')?></td><td>:</td><td><?=$pro_data->row()->pro_code?></td></tr>
<tr><td><?=$this->lang->line('pro_name')?></td><td>:</td><td><?=$pro_data->row()->pro_name?></td></tr>
<tr><td><?=$this->lang->line('satuan')?></td><td>:</td><td><?=$pro_satuan->row()->satuan_name?></td></tr>
<!-- tr><td><?//=$this->lang->line('pro_grade')?></td><td>:</td><td>???</td></tr-->
</table>
</div>
<br>
<!-- ->div style="text-align: left" class="ui-widget-content ui-corner-all"-->

	<table id="stock_list" class="ui-widget-content ui-corner-all" width="700px">
	<tr class="ui-state-default"><td colspan="8" align="left">Tabel A : Detail Kartu Stok</td></tr>
	<tr class="ui-state-default">
		<td width="35px"><?=$this->lang->line('no')?></td>
		<td><?=$this->lang->line('supp_name')?></td>
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
		<td><?=$no?></td>
		<td><?=($get_his->sup_name != '')?($get_his->sup_name):('-')?></td>
		<td><?=date_format(date_create($get_his->inv_transDate),'d-m-Y H:i:s');?></td>
		<td><?=number_format($get_his->inv_begin,2)?></td>
		<td><?=number_format($get_his->inv_in,2)?></td>
		<td><?=number_format($get_his->inv_out,2)?></td>
		<td><?=number_format($get_his->inv_end,2)?></td>
		<?php
		if ($get_his->inv_out != 0)
			$doc = 1;
		else $doc = 0;
		?>
		<td><a onclick="pre_history('<?=$get_his->pro_id?>','<?=($get_his->sup_id!='')?($get_his->sup_id):('0')?>','<?=$doc?>');" style="cursor:pointer"><img src="asset/img_source/icon_lkp.gif"></a></td>
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
	
	<table id="po_list" class="ui-widget-content ui-corner-all" width="700px" border="0">
	<tr class="ui-state-default"><td colspan="7" align="left">Tabel B : PO Sedang diproses <?=($history_po_row > 2)?("( $history_po_row )"):('')?> </td></tr>
	<tr class="ui-state-default">
		<td width="35px"><?=$this->lang->line('no')?></td>
		<td><?=$this->lang->line('po_no')?></td>
		<td><?=$this->lang->line('supplier')?></td>
		<td><?=$this->lang->line('order')?></td>
		<td><?=$this->lang->line('terima')?></td>
		<td><?=$this->lang->line('kembali')?></td>
		<td><?=$this->lang->line('sisa')?></td>
	</tr>
	<?php 
	$no = 1;
	if ($history_po->num_rows() > 0):
	foreach($history_po->result() as $row_po):?>
	<tr bgcolor="lightgray">
		<td><?=$no?></td>
		<td><?=$row_po->po_no?></td>
		<td><?=$row_po->sup_name?></td>
		<td><?=number_format($row_po->qty,2)?></td>
		<td><?=number_format($row_po->terima,2)?></td>
		<td>0.00<?//=$row_po->?></td>
		<td><?=number_format($row_po->sisa,2)?></td>
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
	<tr class="ui-state-default"><td colspan="5" align="left">Tabel C : PR dan RFQ Sedang diproses <?=($history_rfq_row > 2)?("( $history_rfq_row )"):('')?></td></tr>
	<tr class="ui-state-default">
		<td width="35px"><?=$this->lang->line('no')?></td>
		<td><?=$this->lang->line('pr_no')?></td>
		<td><?=$this->lang->line('rfq_no')?></td>
		<td><?=$this->lang->line('pr_date')?></td>
		<td><?=$this->lang->line('usr_name')?></td>
	</tr>
	<?php 
	$no = 1;
	if ($history_rfq->num_rows() > 0):
	foreach($history_rfq->result() as $row_rfq):?>
	<tr bgcolor="lightgray">
		<td><?=$no?></td>
        <td><?=$row_rfq->pr_no?></td>
		<td><?=$row_rfq->rfq_no?></td>
        <td><?=$row_rfq->pr_date?></td>
        <td><?=$row_rfq->usr_name?></td>
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