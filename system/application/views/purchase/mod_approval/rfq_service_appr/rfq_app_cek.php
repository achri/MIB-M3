<script type="text/javascript">
$(document).ready(function () {
	masking('.number');
		$("#result").dialog({
			autoOpen: false,
			modal: true,
			bgiframe: false,
			width: 'auto',
			height: 'auto',
			resizable: false,
			draggable: false,
			show: 'drop',
			hide: 'drop'
		});
		

		$('#app_rfq_appr').submit(function() {
			$('#saving').attr('disabled',false);
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
							url: 'index.php/<?=$link_controller?>/srfq_add',
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
	window.location = 'index.php/<?=$link_controller?>/index';
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
		<td class="labelcell2">: <?php echo $cont->srfq_no;?> <input type="hidden" name="rfq_id" value="<?php echo $cont->srfq_id; ?>"></td>
		<td width="20%"></td>
		<td class="labelcell" width="150"><?php echo $this->lang->line('rfq_label_cetak');?></td>
		<td class="labelcell2">: <?php echo $cont->srfq_date_print;?></td>
	</tr>
	<tr>
		<td class="labelcell"><?php echo $this->lang->line('rfq_label_tgl');?></td>
		<td class="labelcell2">: <?php echo $cont->srfq_date;?></td>
		<td></td>
		<td class="labelcell"><?php echo $this->lang->line('rfq_label_oleh');?></td>
		<td class="labelcell2">: <?php echo $cont->usr_name;?></td>
	</tr>
</table>
</div>
<br>
<table width="90%" id="dataview" class="table">
<?php 
	foreach ($get_rfq->result() as $dtlcont):
		//$get_price = $this->tbl_rfq_service->srfq_content_price($dtlcont->pro_id)->row();
		//$qlast_buy = $this->db->query("select price from prc_pr_detail where pro_id = '$dtlcont->pro_id' and po_id !=0 order by po_id desc");
		//if ($qlast_buy->num_rows() > 0) $last_buy = $qlast_buy->row()->price;
		//else $last_buy = 0;
		echo "<tr class='x'>
					<td valign='top'>
						<input type='hidden' name='pro_id[]' value='".$dtlcont->pro_id."'><b>"
						.$this->lang->line('rfq_label_brg')." :</b><br/>"
						.$dtlcont->pro_name."<br/><b>"
						.$this->lang->line('rfq_label_kode')." : </b>".$dtlcont->pro_code."<br/><b>"
						.$this->lang->line('rfq_label_sup')." :	</b>
						<input type='text' id='tsup".$dtlcont->pro_id."' name='tsup_".$dtlcont->pro_id."' value='".$dtlcont->legal_name.". ".$dtlcont->sup_name."' readonly='1'>
						<input type='hidden' id='id_sup_".$dtlcont->pro_id."' name='id_sup_".$dtlcont->pro_id."' value='".$dtlcont->sup_id."'>
						<input type='hidden' id='pro_".$dtlcont->pro_id."' name='pro_".$dtlcont->pro_id."' value='".$dtlcont->pro_name."'>
						<input type='hidden' id='procode_".$dtlcont->pro_id."' name='procode_".$dtlcont->pro_id."' value='".$dtlcont->pro_code."'>
						<input type='hidden' id='pr_".$dtlcont->pro_id."' name='pr_".$dtlcont->pro_id."' value='".$dtlcont->sr_id."'><br/>
						<b>"
						.$this->lang->line('rfq_label_status')." :</b><br/>
						<select name='status_".$dtlcont->pro_id."' id='status_".$dtlcont->pro_id."'>
							<option value='0'>-Pilih Status-</option>
							<option value='5'>-Disetujui</option>
							<option value='2'>-Ditunda</option>
							<option value='3'>-Ditolak</option>
						</select>
					</td>
					<td valign='top'><b>"
						.$this->lang->line('rfq_label_qty')." : </b><br/>
						<input class='number' type='text' id='qty' value='".$dtlcont->qty."' size='10' readonly='1'>
						<select name='satuan' readonly='1'>
							<option value=".$dtlcont->um_id.">".$dtlcont->satuan_name."</option>
						</select><br/><b>"
						.$this->lang->line('rfq_label_terima')." :</b><br/>
						<input type='text' name='deldate' value='".$dtlcont->rfq_deldate."' size='15' readonly='1'>
					</td>
					<td valign='top'><b>"
						.$this->lang->line('rfq_label_hasat')." :</b><br/>
						<select name='cur_".$dtlcont->pro_id."' readonly='1'>
							<option value='".$dtlcont->cur_id."'>".$dtlcont->cur_symbol."</option>
						</select>
						<input class='number' type='text' id='harga' value='".number_format($dtlcont->price,2)."' size='10' readonly='1'>
						<br/><b>"
						.$this->lang->line('rfq_label_disc')." :</b><br/>
						<input class='number' type='text' name='disc' value='' size='5' readonly='1'><br/><b>"
						.$this->lang->line('rfq_label_kurs1')." </b><br/>
						<input class='number' type='hidden' name='kurs' value=''size='5' readonly='1'>
					</td>
						<td valign='top'><b>"
						.$this->lang->line('rfq_label_pay')." :</b><br/>
						<select name='pay_".$dtlcont->pro_id."' readonly=1>
							<option value='".$dtlcont->term_id."'>".$dtlcont->term_id_name."</option>
						</select>
						<br/><b>"
						.$this->lang->line('rfq_label_jangka')." :</b><br/>
						<input class='number' type='text' value='".$dtlcont->term_days."' size='3' readonly='1'> <b>"
						.$this->lang->line('rfq_label_satkredit')."<b/>
					</td>
					
		</tr>";
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