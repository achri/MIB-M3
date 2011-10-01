<script language="javascript">
$(document).ready(function () {
	// DIALOG
	$('.dialog_konfirmasi').dialog({
		autoOpen: false,
		bgiframe: true,
		width: 'auto',
		height: 'auto',
		resizable: false,
		draggable: false,
		modal:true,
		position:['right','top']
	});
	
	$('.dialog_informasi').dialog({
		autoOpen: false,
		bgiframe: true,
		width: 'auto',
		height: 'auto',
		resizable: false,
		draggable: false,
		modal:true,
		position:'center'
	});

	masking('.number');
	masking_reload('.number');
	//masking_select('.number_select','.number');
	masking_currency('.currency_select','.currency');
	
	$('#form_entry').submit(function(){
		$('.saving').attr('disabled',true);
		if (validasi('#form_entry')){
			$('.dialog_konfirmasi').dialog('option','buttons',{
				'<?=$this->lang->line('back')?>' : function() {
					$('#saving').attr('disabled',false);
					$(this).dialog('close');
				},
				'<?=$this->lang->line('ok')?>' : function() {
					unmasking('.number');
					$.ajax({
						type: 'POST',
						url: 'index.php/<?=$link_controller?>/so_charge',
						data: $('#form_entry').serialize(),
						success: function(data) {
							$('.dialog_informasi').html('').html(data)
							.dialog('option','buttons',{
								'OK': function() {
									$(this).dialog('close');
									location.href = 'index.php/<?=$link_controller?>/index';
								}
							}).dialog('open');
						}
					});
					$(this).dialog('close');
				}
			}).html('').html('<?=$this->lang->line('confirm')?>').dialog('open');
		}else{
			$('.saving').attr('disabled',false);
		}
		return false;
	});
	
});
</script>
<h3><?=$page_title?> No SO <strong><?=$so_list->row()->so_no?></strong></h3>
<form id="form_entry">
<div>
<table align="left" width="99%"  border="0" cellspacing="2" cellpadding="2" class="ui-widget-content ui-corner-all">
  <tr>
    <td width="10%" class="ui-widget-header">SO No</td>
	<td width="5%" class="head_title">:</td>
    <td width="30%" class="head_title_content"><?=$so_list->row()->so_no?>
	<input type="hidden" value="<?=$so_list->row()->so_id?>" name="so_id" />
	</td>
    <td width="15%">&nbsp;</td>
    <td width="11%" class="ui-widget-header">Pemasok</td>
	<td width="5%" class="head_title">:</td>
    <td width="30%" class="head_title_content"><?=$so_list->row()->sup_name?></td>
  </tr>
  <tr>
    <td class="ui-widget-header">Tgl SO</td>
	<td width="5%" class="head_title">:</td>
    <td class="head_title_content"><?=$so_list->row()->so_date?></td>
    <td>&nbsp;</td>
    <td></td>
    <td></td>
	<td></td>
  </tr>
</table>
<br />
<table align="left" width="70%"  border="0" cellpadding="1" cellspacing="1" class="ui-widget-content ui-corner-all">
        <tr class="ui-widget-header">
		  <td colspan="7">Daftar Barang yg digunakan :</td>
		</tr>
		<tr class="ui-widget-header">
		  <td width="5%" align="center">No</td>
		  <td width="45%" align="center">Barang/Kode</td>
		  <td width="8%" align="center">Satuan</td>
		  <td width="8%" align="center">Kuantitas</td>
	    </tr>
		<!--{section name=x loop=$po_detail}-->
		<?php 
		$sdet_no = 1;
		foreach ($so_det->result() as $row_sdet):?>
		<tr bgcolor="lightgray">
		  <td valign="top" align="right" class="ui-state-active"><?=$sdet_no?></td>
		  <td valign="top" align="left"><?=$row_sdet->pro_name?> (<?=$row_sdet->pro_code?>)</td>
		  <td valign="top" align="center"><?=$row_sdet->satuan_name?></td>
		  <td valign="top" align="right"><?=number_format($row_sdet->qty,$row_sdet->satuan_format)?></td>
		</tr>
		<?php 
		$sdet_no++;
		endforeach;?>
		<!--{/section}-->
</table>
<br />
<table align="left" width="99%"  border="0" cellspacing="2" cellpadding="2" class="ui-widget-content ui-corner-all">
  <tr>
    <td>Biaya Servis</td>
	<td>:</td>
	<td>
	<select input_id="so_cost" id="cur_id" class="currency_select">
		<?
			$q_curr = $this->db->query("select * from prc_master_currency");
			foreach ($q_curr->result() as $curr):
			?>
			<option value="<?=$curr->cur_id?>"><?=$curr->cur_symbol?></option>
			<?
			endforeach;
		?>
	</select>
	<input digit_decimal="2" type="text" id="so_cost" name="so_cost" class="required number currency" title="Biaya Servis"/>
	</td>
  </tr>
  <tr>
		<td colspan="3"><hr /></td>
	</tr>
  <tr>
  	<td><input type="submit" value="Simpan" class="saving"/></td>
  </tr>
</table>
</div>
</form>
