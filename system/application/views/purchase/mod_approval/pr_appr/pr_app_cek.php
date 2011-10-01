<script type="text/javascript">
masking('.number');
masking_reload('.number');
masking_select('.number_select','.number');
$(function() {
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
			buttons: {
				"Keluar" : function() {
					$(this).dialog('close');
					batal();
				}
			}
		});

		$("#history").dialog({
			autoOpen: false,
			modal: true,
			bgiframe: false,
			width: 'auto',
			height: 'auto',
			resizable: false,
			draggable: false,
			buttons: {
				"Keluar" : function() {
					$(this).dialog('close');
				}
			}
		});
		
		$("#alasan").dialog({
			modal: true,
			autoOpen: false,
			bgiframe: false,
			width: 'auto',
			height: 'auto',
			resizable: false,
			draggable: false,
			buttons: {
				"Batal": function() {
					var id = $('#idnote').val();
					$('#statuspr'+id).val('0');
					$('#sup'+id).attr('disabled','disabled');
					$('#buy'+id).hide();
					$('#notes').text('');
					$(this).dialog('close');
				},
				'Simpan': function() {
					var caption = 'cat : ';
					var id = $('#idnote').val();
					var note = $('#notes').val();
					var status = $('#statuspr'+id).val();
					if (status == 2){
						$('#deldate'+id).attr('disabled','');
						$('#satuan'+id).attr('disabled','');
						$('#qty'+id).attr('disabled','');
						$('#prctype'+id).attr('disabled','');
						$('#emergency'+id).attr('disabled','');
					}
					$('#buy'+id).show();
					$('#sup'+id).attr('disabled','');
					$('#viewnote'+id).text(caption+note);
					$('#pr_note'+id).val(note);
					$('#sup'+id).attr('disabled','');
					$('#buy').show();
					$(this).dialog('close');
				}
			}
		});

		$('#app_pr').submit(function() {
			// KONFIRMASI
			$('#saving').attr('disabled',true);
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
						$(this).dialog('close');
						unmasking('.number');
						$.ajax({
							type: 'POST',
							url: 'index.php/<?php echo $link_controller;?>/pr_add',
							data: $('#app_pr').serialize(),
							success: function(data) {
								$('#restext').html(data);
								$('#result').dialog('open');
							}
						});
					}
				}
			}).html('').html('<?=$this->lang->line('confirm')?>').dialog('open');
			return false;
		});
});

function openalasan(id){
	$('#idnote').val(id);
	$('#notes').val('');
	$('#viewnote'+id).text('');
	$('#pr_note'+id).val('');
	$('#buy'+id).hide();
	$('#deldate'+id).attr('disabled','disabled');
	$('#qty'+id).attr('disabled','disabled');
	$('#prctype'+id).attr('disabled','disabled');
	$('#emergency'+id).attr('disabled','disabled');
	$('#satuan'+id).attr('disabled','disabled');
	$('#sup'+id).attr('disabled','disabled');
	var status = $('#statuspr'+id).val();

	if (status == 0){
		$('#idnote').val(id);
		$('#notes').val('');
		$('#viewnote'+id).text('');
		$('#pr_note'+id).val('');
		$('#buy'+id).hide();
		$('#deldate'+id).attr('disabled','disabled');
		$('#qty'+id).attr('disabled','disabled');
		$('#prctype'+id).attr('disabled','disabled');
		$('#emergency'+id).attr('disabled','disabled');
		$('#satuan'+id).attr('disabled','disabled');
		$('#sup'+id).attr('disabled','disabled');
	}else if (status == 1){
		$('#deldate'+id).attr('disabled','disabled');
		$('#qty'+id).attr('disabled','disabled');
		$('#prctype'+id).attr('disabled','disabled');
		$('#emergency'+id).attr('disabled','disabled');
		$('#satuan'+id).attr('disabled','disabled');
		$('#sup'+id).attr('disabled','');
		$('#buy'+id).show();
	}else{
		$('#status').val(status);
		$('#alasan').dialog('open');
	}
}

function open_history(proid){
	$.ajax({
		type: 'POST',
		url: 'index.php/<?php echo $link_controller;?>/open_history/'+proid,
		data: '',
		success: function(data) {
			$('#history').html(data);
			$('#history').dialog('open');
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
	$hdrcont = $get_pr['head']->row();
	if ($hdrcont->plan_id == 1){
		$hdrcont->plan_id = 'Sesuai Hari Ketentuan';
	}else{
		$hdrcont->plan_id = 'Tidak Sesuai Hari Ketentuan';
	}
?>
<form id="app_pr">
<center>
<div class="ui-corner-all headers">
<table>
	<tr>
		<td class="labelcell" width="100"><?php echo $this->lang->line('pr_label_nopr');?></td>
		<td class="labelcell2">: <?php echo $hdrcont->pr_no;?> <input type="hidden" name="pr_id" value="<?php echo $hdrcont->pr_id; ?>"></td>
		<td width="20%"></td>
		<td class="labelcell" width="100"><?php echo $this->lang->line('pr_label_dep');?></td>
		<td class="labelcell2">: <?php echo $hdrcont->dep_name;?></td>
	</tr>
	<tr>
		<td class="labelcell"><?php echo $this->lang->line('pr_label_tgl');?></td>
		<td class="labelcell2">: <?php echo $hdrcont->prdate;?></td>
		<td></td>
		<td class="labelcell"><?php echo $this->lang->line('pr_label_pemohon');?></td>
		<td class="labelcell2">: <?php echo $hdrcont->usr_name;?></td>
	</tr>
	<tr>
		<td class="labelcell"><?php echo $this->lang->line('pr_label_status');?></td>
		<td class="labelcell2">: <?php echo $hdrcont->plan_id;?></td>
		<td></td>
		<td></td>
		<td></td>
	</tr>
</table>
</div>
<br>
<table width="90%" id="dataview" class="table">
	<tr class='ui-widget-header' align="center">
		<td><?php echo $this->lang->line('pr_tbl_col_1');?></td>
		<td><?php echo $this->lang->line('pr_tbl_col_2');?></td>
		<td><?php echo $this->lang->line('pr_tbl_col_3');?></td>
		<td><?php echo $this->lang->line('pr_tbl_col_4');?></td>
		<td><?php echo $this->lang->line('pr_tbl_col_5');?></td>
	</tr>
<?php 
	foreach ($get_pr['detail']->result() as $dtlcont):
	if ($dtlcont->buy_via == 'po'){
		$buy = $this->lang->line('pr_cmb_buy_1');
	}else{
		$buy = $this->lang->line('pr_cmb_buy_2');
	}
		echo "<tr class='x'>
					<td valign='top'>
						<input type='hidden' name='pro_id[]' value='".$dtlcont->pro_id."'>
						<select name='statuspr_".$dtlcont->pro_id."' id='statuspr".$dtlcont->pro_id."' onchange='openalasan($dtlcont->pro_id)'>
							<option value='0'>- pilih status -</option>
							<option value='1'>- disetujui -</option>
							<option value='2'>- diubah & disetujui -</option>
							<option value='3'>- disetujui dgn catatan -</option>
							<option value='4'>- ditunda -</option>
							<option value='5'>- ditolak -</option>
						</select><br>
						<select name='buy_".$dtlcont->pro_id."' style='display:none;' id='buy".$dtlcont->pro_id."'>
							<option value='po' "; echo ($dtlcont->buy_via=='po')?('SELECTED'):(''); echo " >".$this->lang->line('pr_cmb_buy_1')."</option>
							<option value='pcv' "; echo ($dtlcont->buy_via=='pcv')?('SELECTED'):(''); echo " >".$this->lang->line('pr_cmb_buy_2')."</option>
						</select>
						<div id='viewnote".$dtlcont->pro_id."' style='width: 150px; color: red;'></div>
						<textarea name='pr_note_".$dtlcont->pro_id."' id='pr_note".$dtlcont->pro_id."' style='display:none;'></textarea>
					</td>
					<td valign='top'>
						<select name='prctype_".$dtlcont->pro_id."' id='prctype".$dtlcont->pro_id."' disabled>
						<option value=".$dtlcont->pty_id.">".$dtlcont->pty_name."</option>";
						if ($prc_type->num_rows() > 0):
							foreach ($prc_type->result() as $prc): 
							echo "<option value=".$prc->pty_id.">".$prc->pty_name."</option>";
							endforeach;
						else:
							echo "Empty";
						endif;
						
						$darurat = '';
						if ($dtlcont->emergencyStat == 1)
							$darurat = 'checked';
						echo"</select><br><input type='checkbox' name='emergency_".$dtlcont->pro_id."' id='emergency".$dtlcont->pro_id."' value='1' ".$darurat." disabled>".$this->lang->line('pr_label_emer')."
						<input type='hidden' name='defvalpty_".$dtlcont->pro_id."' value='".$dtlcont->pty_id."'>
					</td>
					<td valign='top'>
						<input class='number' digit_decimal='".$dtlcont->satuan_format."' type='text' id='qty".$dtlcont->pro_id."' name='qty_".$dtlcont->pro_id."' value='".$dtlcont->qty."' disabled>
						<select class='number_select' input_id='qty".$dtlcont->pro_id."' name='satuan_".$dtlcont->pro_id."' id='satuan".$dtlcont->pro_id."' disabled>
							<option value=".$dtlcont->satuan_id.">".$dtlcont->satuan_name."</option>";
							$satpro = $this->tbl_satuan_pro->get_satuan($dtlcont->pro_id);
							if ($satpro->num_rows() > 0):
								foreach ($satpro->result() as $sat): 
									echo "<option value=".$sat->satuan_id.">".$sat->satuan_name."</option>";
								endforeach;
							else:
								echo "Empty";
							endif;
						echo"</select>
						<br>".$this->lang->line('pr_label_sup')." : <input type;'text' name='sup_".$dtlcont->pro_id."' id='sup".$dtlcont->pro_id."' value='3' size='3' disabled>
						<input type='hidden' name='defvalqty_".$dtlcont->pro_id."' value='".$dtlcont->qty."'><br>
						<input type='hidden' name='defvalsat_".$dtlcont->pro_id."' value='".$dtlcont->satuan_id."'>
					</td>
					<td valign='top'>
						<input type='text' name='deldate_".$dtlcont->pro_id."' id='deldate".$dtlcont->pro_id."' value=".$dtlcont->deldate." class='deldate' size='15' disabled>
						<input type='hidden' name='defvaldeldate_".$dtlcont->pro_id."' value='".$dtlcont->deldate."'>
					</td>
					<td valign='top'><input type='hidden' name='pro_name_".$dtlcont->pro_id."' value='".$dtlcont->pro_name."'>
						<input type='hidden' name='pro_code_".$dtlcont->pro_id."' value='".$dtlcont->pro_code."'>
						".$dtlcont->pro_name."
						<br> Kode : ".$dtlcont->pro_code."
						<br> Keterangan : <br><i><font color='gray'>".$dtlcont->description."</font></i>
						<br><a href='javascript:void(0)' onclick='open_history(".$dtlcont->pro_id.")'>[lihat History]</a>
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

<div id="alasan" title="ISI ALASAN">
	<input type="hidden" id="idnote" />
	<input type="hidden" id="status" />
	<p><textarea id="notes" cols="30" rows="5"></textarea></p>
</div>

<div id="result" title="KONFIRMASI">
	<div id="restext" style="text-align : left"></div>
</div>

<div id="history" title="HISTORY">
</div>