<script type="text/javascript">
$(document).ready(function () {
	masking('.number');
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
			show: 'drop',
			hide: 'drop',
			buttons: {
				"OK" : function() {
					$(this).dialog('close');
				}
			}
		});
		
		$("#supplier").dialog({
			modal: true,
			autoOpen: false,
			bgiframe: false,
			width: 'auto',
			height: 'auto',
			resizable: false,
			draggable: false,
			show: 'drop',
			hide: 'drop',
			buttons: {
				"BATAL" : function() {
					$(this).dialog('close');
				}
			}
		});
		
		$('#app_rfq').submit(function() {
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
							data: $('#app_rfq').serialize(),
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

function setstatus(id){
	var status = $('#status_'+id).val();
	if (status == 1){
		$('#qty_'+id).attr('disabled','');
		$('#satuan_'+id).attr('disabled','');
		$('#deldate_'+id).attr('disabled','');
		$('#cur_'+id).attr('disabled','');
		$('#harga_'+id).attr('disabled','');
		$('#disc_'+id).attr('disabled','');
		$('#kurs_'+id).attr('disabled','');
		$('#pay_'+id).attr('disabled','');
	}else{
		$('#qty_'+id).attr('disabled','disabled');
		$('#satuan_'+id).attr('disabled','disabled');
		$('#deldate_'+id).attr('disabled','disabled');
		$('#cur_'+id).attr('disabled','disabled');
		$('#harga_'+id).attr('disabled','disabled');
		$('#disc_'+id).attr('disabled','disabled');
		$('#kurs_'+id).attr('disabled','disabled');
		$('#pay_'+id).attr('disabled','disabled');
		$('#tsup'+id).val('');
		$('#id_sup_'+id).val('');
	}
}

function open_sup(pro_id, code){
	var status = $('#status_'+pro_id).val();
	if (status == 1){
		$.ajax({
			type: 'POST',
			url: 'index.php/<?=$link_controller?>/get_sup/'+pro_id+'/'+code,
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

function getterm(id, pro){
	$.ajax({
		type: 'POST',
		url: 'index.php/<?=$link_controller?>/get_term/'+id,
		data: $(this).serialize(),
		success: function(data) {
		$('#hari_'+pro).val(data);
		}
	});
	return false;
}

function closedialog(){
	$('#result').dialog('close');
}

function batal(){
	window.location = 'index.php/<?=$link_controller?>/index';
}
</script>

<?php 
	$cont = $get_srfq->row();
?>
<form id="app_rfq">
<center>
<div class="ui-corner-all headers">
<table>
	<tr>
		<td class="labelcell" width="100"><?php echo $this->lang->line('rfq_label_no');?></td>
		<td class="labelcell2">: <?php echo $cont->srfq_no;?> <input type="hidden"  name="rfq_id" value="<?php echo $cont->srfq_id; ?>"> </td>
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
	foreach ($get_srfq->result() as $dtlcont):
		$code = explode('.', $dtlcont->pro_code);
		$code = $code[0];
		$catid = $this->Tbl_category->get_catid($code)->row();
		$catid->cat_id;
		echo "<tr class='x'>
					<td valign='top'>
						<input type='hidden' name='pro_id[]' value='".$dtlcont->pro_id."'><b>"
						.$this->lang->line('rfq_label_brg')." :</b><br/>"
						.$dtlcont->pro_name."<br/><b>"
						.$this->lang->line('rfq_label_kode')." : </b>".$dtlcont->pro_code."<br/><b>"
						.$this->lang->line('rfq_label_sup')." :	</b>
						<input type='text' id='tsup".$dtlcont->pro_id."' name='tsup_".$dtlcont->pro_id."' readonly='1'>
						<input type='hidden' id='id_sup_".$dtlcont->pro_id."' name='id_sup_".$dtlcont->pro_id."'>
						<input type='hidden' id='pro_".$dtlcont->pro_id."' name='pro_".$dtlcont->pro_id."' value='".$dtlcont->pro_name."'>
						<input type='hidden' id='pr_".$dtlcont->pro_id."' name='pr_".$dtlcont->pro_id."' value='".$dtlcont->sr_id."'>
						<a href='javascript:void(0)' onclick='open_sup(".$dtlcont->pro_id.",".$catid->cat_id.")'><img src='".base_url()."asset/img_source/icon_lkp.gif' border='0'></a><br/>
						<b>"
						.$this->lang->line('rfq_label_status')." :</b><br/>
						<select name='status_".$dtlcont->pro_id."' id='status_".$dtlcont->pro_id."' onchange='setstatus($dtlcont->pro_id)'>
							<option value='0'>-Pilih Status-</option>
							<option value='1'>-Disetujui</option>
							<option value='2'>-Ditunda</option>
							<option value='3'>-Ditolak</option>
						</select>
					</td>
					<td valign='top'><b>"
						.$this->lang->line('rfq_label_qty')." : </b><br/>
						<input type='text' id='qty_".$dtlcont->pro_id."' name='qty_".$dtlcont->pro_id."' value='".$dtlcont->qty."' size='10'  class='ui-corner-all number' disabled>
						<select name='satuan_".$dtlcont->pro_id."' id='satuan_".$dtlcont->pro_id."' disabled>
							<option value=".$dtlcont->um_id.">".$dtlcont->satuan_name."</option>";
							$satpro = $this->tbl_satuan_pro->get_satuan($dtlcont->pro_id);
							if ($satpro->num_rows() > 0):
								foreach ($satpro->result() as $sat): 
									echo "<option value=".$sat->satuan_id.">".$sat->satuan_name."</option>";
								endforeach;
							else:
								echo "Empty";
							endif;
						echo"</select><br/><b>"
						.$this->lang->line('rfq_label_terima')." :</b><br/>
						<input type='text' name='deldate_".$dtlcont->pro_id."' id='deldate_".$dtlcont->pro_id."' value='".$dtlcont->delivery_date."' class='deldate' size='15' disabled>
					</td>
					<td valign='top'><b>"
						.$this->lang->line('rfq_label_hasat')." :</b><br/>
						<select name='cur_".$dtlcont->pro_id."' id='cur_".$dtlcont->pro_id."' disabled>";
							if ($list_cur->num_rows() > 0):
								foreach ($list_cur->result() as $cur): 
									echo "<option value=".$cur->cur_id.">".$cur->cur_symbol."</option>";
								endforeach;
							else:
								echo "Empty";
							endif;
						echo"</select>
						<input class='number currency' type='text' id='harga_".$dtlcont->pro_id."' name='harga_".$dtlcont->pro_id."' size='10' disabled>
						<br/><b>"
						.$this->lang->line('rfq_label_disc')." :</b><br/>
						<input  class='number' type='text' name='disc_".$dtlcont->pro_id."' id='disc_".$dtlcont->pro_id."' size='5' disabled><br/><b>"
						.$this->lang->line('rfq_label_kurss')." </b><br/>
						<input  class='number' type='hidden' name='kurs_".$dtlcont->pro_id."' id='kurs_".$dtlcont->pro_id."' size='5' disabled>
					</td>
						<td valign='top'><b>"
						.$this->lang->line('rfq_label_pay')." :</b><br/>
						<select name='pay_".$dtlcont->pro_id."' id='pay_".$dtlcont->pro_id."' onchange='getterm(this.value,".$dtlcont->pro_id.")' disabled>";
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
						<input class='number' type='text' name='hari_".$dtlcont->pro_id."' id='hari_".$dtlcont->pro_id."' size='5' readonly=1> <b>"
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

<div id="supplier" title="DAFTAR PEMASOK">
	<div id="resultsup"> </div>
	<input type="hidden" id="indpro">
</div>

<div id="result" title="INFORMASI">
	<div id="restext" style='text-align: left;'></div>
</div>