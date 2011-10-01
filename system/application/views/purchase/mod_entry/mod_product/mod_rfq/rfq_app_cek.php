<script type="text/javascript">

$(document).ready(function () {
	masking('.number');
	masking_reload('.number');
	masking_select('.number_select','.number');
	masking_currency('.currency_select','.currency');
	
		$(".deldate").datepicker({
			dateFormat: 'dd-mm-yy',
			minDate: '0'
		});

		$("#result").dialog({
			autoOpen: false,
			modal: true,
			bgiframe: false,
			width: 'auto',
			height: 'auto',
			resizable: false,
			draggable: false,
			//show: 'drop',
			//hide: 'drop',
			buttons: {
				"Keluar" : function() {
					$(this).dialog('close');
					batal();
				}
			}
		});
		
		$("#supplier").dialog({
			modal: true,
			autoOpen: false,
			bgiframe: false,
			width: 'auto',
			minHeight: 'auto',
			maxHeight: 400,
			//height: 'auto',
			resizable: false,
			draggable: false,
			//show: 'drop',
			//hide: 'drop',
			buttons: {
				"Batal" : function() {
					$(this).dialog('close');
				}
			}
		});
		
		$('#app_rfq').submit(function() {
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
							data: $('#app_rfq').serialize(),
							success: function(data) {
								$('#saving').attr('disabled',false);
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
		
		$('.select_cur').change(function() {
			var row = $(this).attr('rows');
			var vals = $(this).val();
			$('#cur_org_'+row).val(vals);
		});
});

function setstatus(id){
	var status = $('#status_'+id).val();
	if (status == 1){
		$('#qty_'+id).attr('disabled','');
		$('#satuan_'+id).attr('disabled','');
		$('#deldate_'+id).attr('disabled','');
		//$('#cur_'+id).attr('disabled','');
		$('#harga_'+id).attr('disabled','');
		$('#disc_'+id).attr('disabled','');
		$('#kurs_'+id).attr('disabled','');
		$('#pay_'+id).attr('disabled','');
	}else{
		$('#qty_'+id).attr('disabled','disabled');
		$('#satuan_'+id).attr('disabled','disabled');
		$('#deldate_'+id).attr('disabled','disabled');
		//$('#cur_'+id).attr('disabled','disabled');
		$('#harga_'+id).attr('disabled','disabled');
		$('#disc_'+id).attr('disabled','disabled');
		$('#kurs_'+id).attr('disabled','disabled');
		$('#pay_'+id).attr('disabled','disabled');
		$('#tsup'+id).val('');
		$('#id_sup_'+id).val('');
	}
}

function open_sup(no, pro_id, code){
	var status = $('#status_'+no).val();
	if (status == 1){
		$.ajax({
			type: 'POST',
			url: 'index.php/<?php echo $link_controller;?>/get_sup/'+pro_id+'/'+code+'/'+no,
			data: $(this).serialize(),
			success: function(data) {
				$('#resultsup').html(data);
				$('#indpro').val(pro_id);
				$('#supplier').dialog('open');
			}
		});
		return false;
	}
}

function getterm(id, no){
	$.ajax({
		type: 'POST',
		url: 'index.php/<?php echo $link_controller;?>/get_term/'+id,
		data: $(this).serialize(),
		success: function(data) {
		$('#hari_'+no).val(data);
		}
	});
	return false;
}

function closedialog(){
	$('#result').dialog('close');
}

function batal(){
	window.location = 'index.php/<?php echo $link_controller;?>/index';
}
</script>

<?php 
	$cont = $get_rfq->row();
?>
<form id="app_rfq">
<center>
<div class="ui-corner-all headers">
<table>
	<tr>
		<td class="labelcell" width="100"><?php echo $this->lang->line('rfq_label_no');?></td>
		<td class="labelcell2">: <?php echo $cont->rfq_no;?> <input type="hidden"  name="rfq_id" value="<?php echo $cont->rfq_id; ?>"> </td>
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
		$code = explode('.', $dtlcont->pro_code);
		$code = $code[0];
		$catid = $this->Tbl_category->get_catid($code)->row();
		$catid->cat_id;
		
		$currs = 2;
		if ($dtlcont->cur_id == 2)
			$currs = 5;
		
		echo "<tr class='x'>
					<td valign='top'>
						<input type='hidden' name='pro_id[]' value='".$dtlcont->pro_id."'><b>"
						.$this->lang->line('rfq_label_brg')." :</b><br/>"
						.$dtlcont->pro_name."<br/>
						<b>"
							.$this->lang->line('rfq_label_kode')." : </b>"
							.$dtlcont->pro_code." <br>
						<b>"
							.$this->lang->line('rfq_label_status')." : </b>
							<select name='status_".$no."' id='status_".$no."' onchange='setstatus($no)'>
								<option value='0'>- pilih status -</option>
								<option value='1'>- disetujui -</option>
								<option value='2'>- ditunda -</option>
								<option value='3'>- ditolak -</option>
							</select> <br>
						<b>"
							.$this->lang->line('rfq_label_sup')." : </b>					
							<input type='text' id='tsup".$no."' name='tsup_".$no."' readonly='1'>
							<input type='hidden' id='id_sup_".$no."' name='id_sup_".$no."'>
							<input type='hidden' id='pro_".$no."' name='pro_".$no."' value='".$dtlcont->pro_name."'>
							<input type='hidden' id='pr_".$no."' name='pr_".$no."' value='".$dtlcont->pr_id."'>
							<a href='javascript:void(0)' onclick='open_sup(".$no.",".$dtlcont->pro_id.",".$catid->cat_id.")'><img src='".base_url()."asset/img_source/icon_lkp.gif' border='0'></a>
			
					</td>
					<td valign='top'><b>"
						.$this->lang->line('rfq_label_qty')." : </b><br/>
						<input class='number' digit_decimal='".$dtlcont->satuan_format."' type='text' id='qty_".$no."' name='qty_".$no."' value='".$dtlcont->qty."' size='10'  class='ui-corner-all number' disabled>
						<select class='number_select' input_id='qty_".$no."' name='satuan_".$no."' id='satuan_".$no."' disabled>
							<option value=".$dtlcont->um_id.">".$dtlcont->satuan_name."</option>";
							$satpro = $this->tbl_satuan_pro->get_satuan($dtlcont->pro_id);
							if ($satpro->num_rows() > 0):
								foreach ($satpro->result() as $sat): 
									echo "<option value=".$sat->satuan_unit_id.">".$sat->satuan_name."</option>";
								endforeach;
							else:
								echo "Empty";
							endif;
						echo"</select><br/><b>Tanggal diperlukan :</b><br/>
						<input type='text' value='".$dtlcont->delivery_date."' size='15' disabled><br/><b>"
						.$this->lang->line('rfq_label_terima')." :</b><br/>
						<input type='text' name='deldate_".$no."' id='deldate_".$no."' value='' class='deldate' size='15' disabled>
					</td>
					<td valign='top'><b>"
						.$this->lang->line('rfq_label_hasat')." :</b><br/>
						<select disabled class='currency_select select_cur' input_id='harga_".$no."' name='cur_".$no."' id='cur_".$no."' rows='".$no."' disabled>";
							if ($list_cur->num_rows() > 0):
								foreach ($list_cur->result() as $cur): 
									echo "<option value=".$cur->cur_id.">".$cur->cur_symbol."</option>";
								endforeach;
							else:
								echo "Empty";
							endif;
						echo"</select>
						<input id='cur_org_".$no."' type='hidden' name='cur_org_".$no."' value='1'>
						<input digit_decimal='".$currs."' class='number currency' type='text' id='harga_".$no."' name='harga_".$no."' size='10' disabled>
						<br/><b>"
						.$this->lang->line('rfq_label_disc')." :</b><br/>
						<input  class='number' digit_decimal='2' type='text' name='disc_".$no."' id='disc_".$no."' size='5' disabled><br/><b>"
						.$this->lang->line('rfq_label_kurss')." </b><br/>
						<input  class='number' type='hidden' name='kurs_".$no."' id='kurs_".$no."' size='5' disabled>
					</td>
						<td valign='top'><b>"
						.$this->lang->line('rfq_label_pay')." :</b><br/>
						<select name='pay_".$no."' id='pay_".$no."' onchange='getterm(this.value,".$no.")' disabled>";
							if ($list_term->num_rows() > 0):
									foreach ($list_term->result() as $term): 
										echo "<option value=".$term->term_id.">".$term->term_id_name."</option>";
									endforeach;
								else:
									echo "Empty";
								endif;
							echo"</select>
						<br/><b>"
						.$this->lang->line('rfq_label_jangka')." :</b><br/>
						<input class='number' type='text' name='hari_".$no."' id='hari_".$no."' size='5' readonly=1> <b>"
						.$this->lang->line('rfq_label_satkredit')."<b/>
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

<div id="supplier" title="DAFTAR PEMASOK">
	<div id="resultsup"> </div>
	<input type="hidden" id="indpro">
</div>

<div id="result" title="INFORMASI">
	<div id="restext" style='text-align: left;'></div>
</div>

<script language="javascript">
	$('.currency_select').change();
</script>