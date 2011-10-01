<script type="text/javascript">
$(function() {
	masking('.number');
	masking_reload('.number');
	masking_select('.number_select','.number');
	masking_currency('.currency_select','.currency');
	
		$(".deldate").datepicker({
			dateFormat: 'dd-mm-yy',
			minDate: '0'//new Date(2009, 6, 6)
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
				"Keluar" : function() {
					$(this).dialog('close');
					batal();
				}
			}
		});

		$("#stockerror").dialog({
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
			show: 'drop',
			hide: 'drop',
			buttons: {
				'Batal': function() {
					var id = $('#idnote').val();
					$('#statusmr'+id).val('0');
					$('#notes').text('');
					$(this).dialog('close');
				},
				'Simpan': function() {
					var caption = 'cat : ';
					var id = $('#idnote').val();
					var note = $('#notes').val();
					var status = $('#statusmr'+id).val();
					if (status == 2){
						$('#deldate'+id).attr('disabled','');
						$('#satuan'+id).attr('disabled','');
						$('#qty'+id).attr('disabled','');
					}
					$('#viewnote'+id).text(caption+note);
					$('#pr_note'+id).val(note);
					$(this).dialog('close');
				}
			}
		});

		$('#app_mr').submit(function() {
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
							url: 'index.php/<?php echo $link_controller;?>/mr_add',
							data: $('#app_mr').serialize(),
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
	$('#deldate'+id).attr('disabled','disabled');
	$('#satuan'+id).attr('disabled','disabled');
	$('#qty'+id).attr('disabled','disabled');
	
	var status = $('#statusmr'+id).val();
	if (status == 0){
		$('#idnote').val(id);
		$('#notes').val('');
		$('#viewnote'+id).text('');
		$('#pr_note'+id).val('');
		$('#deldate'+id).attr('disabled','disabled');
		$('#satuan'+id).attr('disabled','disabled');
		$('#qty'+id).attr('disabled','disabled');
	}else if (status != 1){
		$('#status').val(status);
		$('#alasan').dialog('open');
	}
}

function closedialog(){
	$('#result').dialog('close');
}

function batal(){
	window.location = 'index.php/<?php echo $link_controller;?>/index';
}

function cekstok(pro_id){
	$.ajax({
		type: 'POST',
		url: 'index.php/<?php echo $link_controller;?>/cek_stok/'+pro_id,
		data: $(this).serialize(),
		success: function(data) {
			var defqty = $('#defvalqty'+pro_id).val();
			var qty = $('#qty'+pro_id).val();	
			if (qty > data) {
				$('#resstock').text(data);
				$('#qty'+pro_id).val(defqty);
				$('#qty'+pro_id).focus();
				$('#stockerror').dialog('open');
			}
		}
	});
	return false;
}
</script>

<?php 
	$hdrcont = $get_pr['head']->row();
?>
<form id="app_mr">
<center>
<div class="ui-corner-all headers">
<table>
	<tr>
		<td class="labelcell" width="100"><?php echo $this->lang->line('mr_label_nomr'); ?></td>
		<td class="labelcell2">: <?php echo $hdrcont->mr_no;?> <input type="hidden" name="mr_id" value="<?php echo $hdrcont->mr_id; ?>"></td>
		<td width="20%"></td>
		<td class="labelcell" width="100"><?php echo $this->lang->line('mr_label_dep'); ?></td>
		<td class="labelcell2">: <?php echo $hdrcont->dep_name;?></td>
	</tr>
	<tr>
		<td class="labelcell"><?php echo $this->lang->line('mr_label_tgl'); ?></td>
		<td class="labelcell2">: <?php echo $hdrcont->mr_date;?></td>
		<td></td>
		<td class="labelcell"><?php echo $this->lang->line('mr_label_pemohon'); ?></td>
		<td class="labelcell2">: <?php echo $hdrcont->usr_name;?></td>
	</tr>
</table>
</div>
<br>
<table width="90%" id="dataview" class="table">
	<tr class='ui-widget-header' align="center">
		<td><?php echo $this->lang->line('mr_tbl_col_1'); ?></td>
		<td><?php echo $this->lang->line('mr_tbl_col_2'); ?></td>
		<td><?php echo $this->lang->line('mr_tbl_col_3'); ?></td>
		<td><?php echo $this->lang->line('mr_tbl_col_4'); ?></td>
	</tr>
<?php 
	foreach ($get_pr['detail']->result() as $dtlcont):
		echo "<tr class='x'>
					<td valign='top'>
						<input type='hidden' name='pro_id[]' value='".$dtlcont->pro_id."'>
						<select name='statusmr_".$dtlcont->pro_id."' id='statusmr".$dtlcont->pro_id."' onchange='openalasan($dtlcont->pro_id)'>
							<option value='0'>-Pilih Status-</option>
							<option value='1'>-Disetujui</option>
							<option value='2'>-Diubah & disetujui</option>
							<option value='3'>-Disetujui Dgn Catatan</option>
							<option value='4'>-Ditunda</option>
							<option value='5'>-Ditolak</option>
						</select><br>
						<div id='viewnote".$dtlcont->pro_id."' style='width: 150px; color: red;'></div>
						<textarea name='pr_note_".$dtlcont->pro_id."' id='pr_note".$dtlcont->pro_id."' style='display:none;'></textarea>
					</td>
					<td valign='top'>
						<input class='number' type='text' digit_decimal='".$dtlcont->satuan_format."' id='qty".$dtlcont->pro_id."' name='qty_".$dtlcont->pro_id."' value='".number_format($dtlcont->qty,2)."' size='10' onchange='cekstok(".$dtlcont->pro_id.")' disabled>
						<select class='number_select' input_id='qty".$dtlcont->pro_id."' name='satuan_".$dtlcont->pro_id."' id='satuan".$dtlcont->pro_id."' disabled>
							<option value=".$dtlcont->satuan_id.">".$dtlcont->satuan_name."</option>";
							//if ($list_sat->num_rows() > 0):
							$satpro = $this->tbl_satuan_pro->get_satuan($dtlcont->pro_id);
							if ($satpro->num_rows() > 0):
								foreach ($satpro->result() as $sat): 
									echo "<option value=".$sat->satuan_id.">".$sat->satuan_name."</option>";
								endforeach;
							else:
								echo "Empty";
							endif;
						echo"</select>
						<input type='hidden' id='defvalqty".$dtlcont->pro_id."' name='defvalqty_".$dtlcont->pro_id."' value='".$dtlcont->qty."'><br>
						<input type='hidden' name='defvalsat_".$dtlcont->pro_id."' value='".$dtlcont->satuan_id."'>
					</td>
					<td valign='top'>
						<input type='text' name='deldate_".$dtlcont->pro_id."' id='deldate".$dtlcont->pro_id."' value='".$dtlcont->delivery_date."' class='deldate' size='15' disabled>
						<input type='hidden' name='defvaldeldate_".$dtlcont->pro_id."' value='".$dtlcont->delivery_date."'>
					</td>
					<td valign='top'><input type='hidden' name='pro_name_".$dtlcont->pro_id."' value='".$dtlcont->pro_name."'>
						<b>".$dtlcont->pro_name."</b>
						<br>".$this->lang->line('mr_label_kode')." : <b>".$dtlcont->pro_code."</b>
						<br>".$this->lang->line('mr_label_ket')." : ".$dtlcont->description."
						<input type='hidden' name='desc_".$dtlcont->pro_id."' value='".$dtlcont->description."'>
						<input type='hidden' name='procode_".$dtlcont->pro_id."' value='".$dtlcont->pro_code."'>
					</td>
		</tr>";
	endforeach;
?>
</table>
<br>
<input type="submit" value="<?php echo $this->lang->line('mr_button_submit'); ?>" id="saving">
<input type="button" value="<?php echo $this->lang->line('mr_button_batal'); ?>" onclick="batal()">
</form>
</center>

<div id="alasan" title="Isi Alasan">
	<input type="hidden" id="idnote" />
	<input type="hidden" id="status" />
	<p><textarea id="notes" cols="30" rows="5"></textarea></p>
</div>

<div id="result" title="INFORMASI">
	<div id="restext" style="text-align: left;"></div>
</div>

<div id="stockerror" title="PERINGATAN" style="text-align: left;">
	Stok Yang tersedia hanya <b><div id="resstock"></div></b>
	Anda tidak dapat mengisi Kuantitas melebihi stok yang ada!!
</div>