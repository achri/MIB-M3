<script type="text/javascript">
$(document).ready(function () {

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
			Cancel: function() {
				var id = $('#idnote').val();
				$('#status_'+id).val('0');
				$('#notes').val('');
				$(this).dialog('close');
			},
			'Save': function() {
				var caption = 'cat : ';
				var id = $('#idnote').val();
				var note = $('#notes').val();
				$('#viewnote_'+id).text(caption+note);
				$('#note_'+id).val(note);
				$('#notes').val('');
				$(this).dialog('close');
			}
		}
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
			hide: 'drop'
		});
		

		$('#pcv_appr').submit(function() {
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
						$(this).dialog('close');
						$.ajax({
							type: 'POST',
							url: 'index.php/<?php echo $link_controller;?>/app_pcv',
							data: $('#pcv_appr').serialize(),
							success: function(data) {
								if (data == 'ok'){
									//batal();
									var info = 'Persetujuan petty cash berhasil di proses !!!';
									$('.dialog_informasi').html('').html(info).dialog('option','buttons',{
										"OK" : function() {
											batal();
										}
									}).dialog('open').css('color','red');
								}else{
									$('#restext').html(data);
									$('#result').dialog('open');
								}	
							}
						});
					}
				}
			}).html('').html('<?=$this->lang->line('confirm')?>').dialog('open');
			return false;
		});
});

function open_alasan(status, id){
	if (status == 2){
		$('#idnote').val(id);
		$('#alasan').dialog('open');
	}else{
		$('#viewnote_'+id).text('');
		$('#note_'+id).val('');
		$('#notes').val('');
	}
}

function batal(){
	window.location = 'index.php/<?php echo $link_controller;?>/index';
}
</script>

<?php 
	$headcont = $get_pcv['head']->row();
?>
<form id="pcv_appr">
<center>
<div class="ui-corner-all headers">
<table>
	<tr>
		<td class="labelcell" width="100"><?php echo $this->lang->line('pcvapp_label_nopcv');?></td>
		<td class="labelcell2">: <?php echo $headcont->pcv_no;?>
		<input type="hidden" name="pcvid" value="<?php echo $headcont->pcv_id; ?>">
		</td>
	</tr>
	<tr>
		<td class="labelcell" width="150"><?php echo $this->lang->line('pcvapp_label_tgl');?></td>
		<td class="labelcell2">: <?php echo $headcont->pcv_date;?></td>
	</tr>
	<tr>
		<td class="labelcell" width="150"><?php echo $this->lang->line('pcvapp_label_nopr');?></td>
		<td class="labelcell2">: <?php echo $headcont->pr_no;?></td>
	</tr>
</table>
</div>
<br>
<table width="90%" id="dataview" class="table">
	<tr class='ui-widget-header' align="center">
		<td><?php echo $this->lang->line('pcvapp_tabel_stat'); ?></td>
		<td><?php echo $this->lang->line('pcvapp_tabel_brg'); ?></td>
		<td><?php echo $this->lang->line('pcvapp_tabel_qty'); ?></td>
		<td><?php echo $this->lang->line('pcvapp_tabel_sat'); ?></td>
		<td><?php echo $this->lang->line('pcvapp_tabel_harga'); ?></td>
	</tr>
<?php 
	$total = 0;
	foreach ($get_pcv['detail']->result() as $dtlcont):
		echo "<tr class='x'>
					<td valign='top'>
						<select name='status[]' id='status_".$dtlcont->pro_id."' onchange='open_alasan(this.value,".$dtlcont->pro_id.")'>
							<option value='0'>-Pilih Status-</option>
							<option value='1'>-Disetujui</option>
							<option value='2'>-Ditolak</option>
						</select>
						<div id='viewnote_".$dtlcont->pro_id."' style='width: 150px; color: red;'></div>
						<textarea name='note[]' id='note_".$dtlcont->pro_id."' style='display: none;'></textarea>
					</td>
					<td valign='top'><b>
						".$dtlcont->pro_name." (".$dtlcont->pro_code.")
						<input type='hidden' name='proid[]' value='".$dtlcont->pro_id."'>
						<input type='hidden' name='proname[]' value='".$dtlcont->pro_name."'>
					</b></td>
					<td valign='top'><b>
						".number_format($dtlcont->qty,$dtlcont->satuan_format)."
					</b></td>
					<td valign='top'><b>
						".$dtlcont->satuan_name."
					</b></td>
					<td valign='top'><b>
						".$dtlcont->cur_symbol."  ".number_format($dtlcont->price_pre,$dtlcont->cur_digit)."
					</b></td>
						
		</tr>";
		$total = $total + $dtlcont->price_pre;
	endforeach;
?>
	<!--tr class='ui-widget-header' align="center">
		<td colspan="3">Total<//?php //echo $this->lang->line('pcvapp_tabel_stat'); ?></td>
		<td colspan="2"><//?php echo "Rp. ".number_format($total,2);?></td>
	</tr-->
</table>
<br>
<input type="submit" value="<?php echo $this->lang->line('pr_button_submit');?>" id="saving">
<input type="button" value="<?php echo $this->lang->line('pr_button_batal');?>" onclick="batal()">
</form>
</center>

<div id="result" title="INFORMASI">
	<div id="restext" style='text-align: left;'></div>
</div>

<div id="alasan" title="ALASAN">
	<input type="hidden" id="idnote" />
	<p><textarea id="notes" cols="30" rows="5"></textarea></p>
</div>