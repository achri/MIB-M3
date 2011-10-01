<script type="text/javascript">
$(document).ready(function () {
	masking('.number');
	masking_reload('.number');
	masking_select('.number_select','.number');
	masking_currency('.currency_select','.currency');
		$("#result").dialog({
			autoOpen: false,
			modal: true,
			bgiframe: false,
			width: 'auto',
			height: 'auto',
			resizable: false,
			draggable: false,
			show: 'drop',
			hide: 'drop',
			buttons: {
				"Keluar" : function() {
					$(this).dialog('close');
					batal();
				}
			}
		});
		

		$('#app_rfq_appr').submit(function() {
			$('#saving').attr('disabled',true);
			// KONFIRMASI
			$('.dialog_konfirmasi').dialog({
				title:'KONFIRMASI',
				autoOpen: false,
				bgiframe: true,
				width: 'auto',
				height: 'auto',
				resizable: false,
				//draggable: false,
				modal:true,
				position:['right','top'],
				buttons : { 
					'<?=$this->lang->line('back')?>' : function() {
						$('#saving').attr('disabled',false);
						$(this).dialog('close');
					},
					'<?=$this->lang->line('ok')?>' : function() {
						unmasking('.number');
						$.ajax({
							type: 'POST',
							url: 'index.php/<?php echo $link_controller;?>/rfq_add',
							data: $('#app_rfq_appr').serialize(),
							success: function(data) {
								$('#restext').html(data);
								$('#result').dialog('open');
							}
						});
						$(this).dialog('close');
					}
				}
			}).html('').html('<?=$this->lang->line('confirm')?>').dialog('open');
			return false;
		});
});

function batal(){
	window.location = 'index.php/<?php echo $link_controller;?>/index';
}
</script>

<?php 
	$cont = $get_rfq->row();
?>
<form id="app_rfq_appr">
<center>
<div class="ui-corner-all headers">
<table>
	<tr>
		<td class="labelcell" width="90"><?php echo $this->lang->line('rfq_label_no');?></td>
		<td class="labelcell2">: <?php echo $cont->rfq_no;?> <input type="hidden" name="rfq_id" value="<?php echo $cont->rfq_id; ?>"></td>
		<td width="20%"></td>
		<td class="labelcell" width="150"><?php echo $this->lang->line('rfq_label_cetak');?></td>
		<td class="labelcell2">: <?php echo $cont->rfq_date_print;?></td>
	</tr>
	<tr>
		<td class="labelcell"><?php echo $this->lang->line('rfq_label_tgl');?></td>
		<td class="labelcell2">: <?php echo $cont->rfq_date;?></td>
		<td></td>
		<td class="labelcell"><?php echo $this->lang->line('rfq_label_oleh');?></td>
		<td class="labelcell2">: <?php echo $cont->usr_name;?></td>
	</tr>
</table>
</div>
<br>
<table width="90%" id="dataview" class="table">
<?php 
	$no = 0;
	foreach ($get_rfq->result() as $dtlcont):
		//$last_buy = 0;
		$q_price = $this->tbl_rfq->rfq_content_price($dtlcont->pro_id,$dtlcont->sup_id,$dtlcont->cur_id,$dtlcont->is_stockJoin);
		$get_price = $q_price['price1']->row();
		
		$last_buy = 0;
		if ($q_price['price2']->num_rows() > 0):
			$last_buy = $q_price['price2']->row()->inv_price;
		endif;
		
		
		echo "<tr class='x'>
					<td valign='top'>
						<input type='hidden' name='pro_id[]' value='".$dtlcont->pro_id."'><b>"
						.$this->lang->line('rfq_label_brg')." :</b><br/>"
						.$dtlcont->pro_name."<br/><b>"
						.$this->lang->line('rfq_label_kode')." : </b>".$dtlcont->pro_code."<br/><b>"
						.$this->lang->line('rfq_label_status')." :</b>
						<select name='status_".$no."' id='status_".$no."'>
							<option value='0'>- pilih status -</option>
							<option value='5'>- disetujui -</option>
							<option value='2'>- ditunda -</option>
							<option value='3'>- ditolak -</option>
						</select><br><b>"
						.$this->lang->line('rfq_label_sup')." :	</b>
						<input type='text' id='tsup".$no."' name='tsup_".$no."' value='".$dtlcont->legal_name.". ".$dtlcont->sup_name."' readonly='1'>
						<input type='hidden' id='id_sup_".$no."' name='id_sup_".$no."' value='".$dtlcont->sup_id."'>
						<input type='hidden' id='pro_".$no."' name='pro_".$no."' value='".$dtlcont->pro_name."'>
						<input type='hidden' id='procode_".$no."' name='procode_".$no."' value='".$dtlcont->pro_code."'>
						<input type='hidden' id='pr_".$no."' name='pr_".$no."' value='".$dtlcont->pr_id."'>
					</td>
					<td valign='top'><b>"
						.$this->lang->line('rfq_label_qty')." : </b><br/>
						<input class='number' digit_decimal='".$dtlcont->satuan_format."' type='text' id='qty_".$no."' value='".$dtlcont->qty."' size='10' readonly='1'>
						<select class='number_select' input_id='qty_".$no."' id='sat_".$no."' name='satuan' readonly='1'>
							<option value=".$dtlcont->um_id.">".$dtlcont->satuan_name."</option>
						</select><br/><b>Tanggal diperlukan :</b><br/>
						<input type='text' value='".$dtlcont->delivery_date."' size='15' disabled><br/><b>"
						.$this->lang->line('rfq_label_terima')." :</b><br/>
						<input type='text' name='deldate_".$no."' value='".$dtlcont->rfq_deldate."' size='15' readonly='1'>
					</td>
					<td valign='top'><b>"
						.$this->lang->line('rfq_label_hasat')." :</b><br/>
						<select class='currency_select' input_id='harga_".$no."' id='currs_".$no."' name='cur_".$no."' readonly='1'>
							<option value='".$dtlcont->cur_id."'>".$dtlcont->cur_symbol."</option>
						</select>
						<input name='harga_".$no."' digit_decimal='".$dtlcont->cur_digit."' class='number currency' type='text' id='harga_".$no."' value='".$dtlcont->price."' size='10' readonly='1'>
						<br/><b>"
						.$this->lang->line('rfq_label_disc')." :</b><br/>
						<input class='number' digit_decimal='2' id='disc_".$no."' type='text' name='disc_".$no."' value='".$dtlcont->discount."' size='5' readonly='1'><br/><b>"
						.$this->lang->line('rfq_label_kurs1')." </b><br/>
						<input class='number' id='kurs_".$no."' type='hidden' name='kurs_".$no."' value='".$dtlcont->kurs."'size='5' readonly='1'>
					</td>
						<td valign='top'><b>"
						.$this->lang->line('rfq_label_pay')." :</b><br/>
						<select name='pay_".$no."' readonly=1>
							<option value='".$dtlcont->term_id."'>".$dtlcont->term_id_name."</option>
						</select>
						<br/><b>"
						.$this->lang->line('rfq_label_jangka')." :</b><br/>
						<input type='text' value='".$dtlcont->term_days."' size='3' readonly='1'> <b>"
						.$this->lang->line('rfq_label_satkredit')."<b/>
					</td>
					<td valign='top' align='left'><b>"
						.$this->lang->line('rfq_label_static')." ( ".$dtlcont->cur_symbol." ) :</b><br/>
					<table class='table' width='' align='left' valign='middle'>
						<tr>
							<td width='40px'><b>-".$this->lang->line('rfq_label_average')."</b></td>
							<td align='right'>".number_format($get_price->rata,$dtlcont->cur_digit)."</td>
						</tr>
						<tr>
							<td><b>-".$this->lang->line('rfq_label_min')."</b></td>
							<td align='right'>".number_format($get_price->min,$dtlcont->cur_digit)."</td>
						</tr>
						<tr>
							<td><b>-".$this->lang->line('rfq_label_max')."</b></td>
							<td align='right'>".number_format($get_price->max,$dtlcont->cur_digit)."</td>
						</tr>
						<tr>
							<td><b>-".$this->lang->line('rfq_label_curent')."</b></td>
							<td align='right'>".number_format($dtlcont->price,$dtlcont->cur_digit)."</td>
						</tr>
						<tr>
							<td><b>-".$this->lang->line('rfq_label_last')."</b></td>
							<td align='right'>".number_format($last_buy,$dtlcont->cur_digit)."</td>
						</tr>
					</table>
					</td>
		</tr>";
	$no++;
	endforeach;
?>
</table>
<br>
<input type="submit" value="<?php echo $this->lang->line('pr_button_submit');?>" id="saving">
<input type="button" value="<?php echo $this->lang->line('pr_button_batal');?>" onclick="batal()">
</form>
</center>

<div id="result" title="INFORMASI">
	<div id="restext" style='text-align: left;'></div>
</div>

<script language="javascript">
	$('.currency_select').change();
</script>