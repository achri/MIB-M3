<script type="text/javascript">
$(document).ready(function() {
	$('div.dialog_notice').dialog({
		autoOpen: false,
		bgiframe: true,
		width: 'auto',
		height: 'auto',
		resizable: false,
		draggable: false,
		modal:true,
		position:'center',
		//show: 'drop',
		//hide: 'drop',
		buttons: {
			'BATAL': function() {
				$(this).dialog('close');
			}
		}
	});
	
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
	
	var peringatan = $('div.dialog_notice');
	
	$('.cek_stok').keyup(function(){
		var row_id = $(this).attr('row_id');
		
		var qty_satuan = $(this).attr('qty_satuan');
		var qty = $(this).val();
		
		qty = parseFloat(qty.replace(/,/g,''));
		
		if (qty!=''||qty!='0'){
			if (qty > qty_satuan){
				$('#jml_'+row_id).val('');
				peringatan.html('').html('Kuantitas terima melebihi Permintaan !!!').dialog('open').css({'color':'red','font-weight':'bold'});
			}
		}
		else { $('#jml_'+row_id).val(''); }
		return false;
	});
	
	$('#receive_pcv').submit(function() {
		$('#saving').attr('disabled','disabled');
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
							url: 'index.php/receive_pcv/receive_add',
							data: $('#receive_pcv').serialize(),
							success: function(data) {
								if (data == 'ok'){
									batal();
								}else{
									$('#restext').html(data);
									$('#result').dialog('open');
								}	
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
		window.location = 'index.php/receive_pcv/index';
	}
</script>

<?php 
	$head = $get_receive['head']->row();
?>
<center>
<form id="receive_pcv">
<div class="ui-corner-all headers">
<table>
	<tr>
		<td class="labelcell" width="100"><?php echo $this->lang->line('receive_label_pcv');?></td>
		<td class="labelcell2">: <?php echo $head->pcv_no;?>
		<input type="hidden" name="pcvid" value="<?php echo $head->pcv_id; ?>">
		<input type="hidden" name="pcvno" value="<?php echo $head->pcv_no; ?>">
		</td>
	</tr>
	<tr>
		<td class="labelcell" width="100"> <?php echo $this->lang->line('receive_label_tgl');?></td>
		<td class="labelcell2">: <?php echo $head->pcv_date;?></td>
	</tr>
</table>
</div>
<br>
<table width="90%" id="dataview" class="table">
	<tr class='ui-widget-header' align="center">
		<td rowspan="2"><?php echo $this->lang->line('receive_tabel_no');?></td>
		<td rowspan="2"><?php echo $this->lang->line('receive_tabel_brg');?></td>
		<td colspan="2"><?php echo $this->lang->line('receive_tabel_rp');?></td>
		<td colspan='2'><?php echo $this->lang->line('receive_tabel_diterima');?></td>
	</tr>
	<tr class='ui-widget-header' align="center">
		<td><?php echo $this->lang->line('receive_tabel_sat');?></td>
		<td><?php echo $this->lang->line('receive_tabel_jml');?></td>
		<td><?php echo $this->lang->line('receive_tabel_sup');?></td>
		<td><?php echo $this->lang->line('receive_tabel_jml_gudang');?></td>
	</tr>
	<?php 
	$i = 0;
	foreach ($get_receive['detail']->result() as $detail):
		$i = $i +1;
		echo "<tr class='x'>
					<td valign='top'>".$i."</td>
					<td valign='top'>".$detail->pro_name."<br/>".$detail->pro_code."
					<input type='hidden' name='proid[]' value='".$detail->pro_id."'>
					<input type='hidden' name='proname[]' value='".$detail->pro_name."'>
					</td>
					<td valign='top'>".$detail->satuan_name." 
					<input type='hidden' name='satuan[]' value='".$detail->satuan_id."'>
					</td>
					<td valign='top'>".number_format($detail->qty,$detail->satuan_format)."</td>
					<td valign='top'>";
					if ($detail->is_stockJoin == '0'){
						//echo "<select name='supplier_".$detail->pro_id."'>";
						echo "<select name='sup_id[]'>";
						$datasup = $this->Tbl_inventory->get_supinv($detail->pro_id);			
						foreach ($datasup->result() as $sup):
							echo "<option value='".$sup->sup_id."'>".$sup->sup_name."</option>";
						endforeach;
						echo "</select>";
					}else {
						echo "<input type='hidden' name='sup_id[]' value='0'>";
					}
			echo	"</td>
					<td valign='top' align='center'>
					<input size='12' id='jml_".$i."' type='text' name='jml[]' class='required number cek_stok' autocomplete='off' qty_satuan='".$detail->qty."' digit_decimal='".$detail->satuan_format."' row_id='".$i."' qty_satuan='1' pro_satuan_name='".$detail->satuan_name."' title='Jumlah Terima Barang' />
					</td>
			</tr>";
	endforeach;
?>
</table>
<br>
<input type="submit" value="Proses" id="saving">
<input type="button" value="<?php echo $this->lang->line('gr_button_batal');?>" onclick="batal()">
</form>
</center>

<div id="result" title="INFORMASI">
	<div id="restext" style="text-align: left;"></div>
</div>
<div id="dialog_notice" title="PERINGATAN">
</div>