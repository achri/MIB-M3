<script type="text/javascript">
$(document).ready(function (){
	masking('.number');
	//masking_reload('.number');
	$("#showerror").dialog({
		autoOpen: false,
		modal: true,
		width: 'auto',
		height: 'auto',
		resizable: false,
		draggable: false,
		show: 'drop',
		hide: 'drop',
		buttons: {
			'OK' : function() {
			$(this).dialog('close');
			}
		}
	});

	$('#frm_realisasi').submit(function() {
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
							url: 'index.php/<?php echo $link_controller;?>/add_realisasi',
							data: $('#frm_realisasi').serialize(),
							success: function(data) {
								if (data == 'ok'){
									batal();
								}else{
									$('#result').html(data);
									$('#showerror').dialog('open');
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
	window.location = 'index.php/<?php echo $link_controller;?>/index';
}
</script>

<?php 
	$headcont = $realisasi['head']->row();
?>
<form id="frm_realisasi">
<center>
<div class="ui-corner-all headers">
<table>
	<tr>
		<td class="labelcell" width="150"><?php echo $this->lang->line('realisasi_label_nopcv');?></td>
		<td class="labelcell2">: <?php echo $headcont->pcv_no;?><input type="hidden" name="pcvid" value="<?php echo $headcont->pcv_id;?>"></td>
		<td width="10%"></td>
		<td class="labelcell" width="200"><?php echo $this->lang->line('realisasi_label_tgl');?></td>
		<td class="labelcell2">: <?php echo $headcont->pcv_receiveDate;?></td>
	</tr>
	<tr>
		<td class="labelcell" valign="top"><?php echo $this->lang->line('realisasi_label_diterima');?></td>
		<td class="labelcell2">: <?php echo $headcont->pcv_printDate;?></td>
		<td></td>
		<td class="labelcell" valign="top"><?php echo $this->lang->line('realisasi_label_penerima');?></td>
		<td class="labelcell2">: <?php echo $headcont->receive_name;?></td>
	</tr>
	<tr>
		<td class="labelcell" valign="top"><?php echo $this->lang->line('realisasi_label_ctk');?></td>
		<td class="labelcell2">: <?php echo $headcont->print_name;?></td>
		<td></td>
		<td class="labelcell" valign="top"></td>
		<td class="labelcell2" valign="top"></td>
	</tr>
</table>
</div>
<br/>
</center>

<table width="100%" class="table">
	<tr class='ui-widget-header' align="center">
		<td width="2%" rowspan="2"><?php echo $this->lang->line('realisasi_tabel_no');?></td>
		<td width="15%" rowspan="2"><?php echo $this->lang->line('realisasi_tabel_brg');?></td>
		<td colspan="3"><?php echo $this->lang->line('realisasi_tabel_rcn');?></td>
		<td colspan="2"><?php echo $this->lang->line('realisasi_tabel_real');?></td>
	</tr>
	<tr class='ui-widget-header' align="center">
		<td width="5%"><?php echo $this->lang->line('realisasi_tabel_sat');?></td>
		<td width="5%"><?php echo $this->lang->line('realisasi_tabel_jml');?></td>
		<td width="5%"><?php echo $this->lang->line('realisasi_tabel_hsat');?></td>
		<td width="5%"><?php echo $this->lang->line('realisasi_tabel_jml');?></td>
		<td width="5%"><?php echo $this->lang->line('realisasi_tabel_hsat');?></td>
	</tr>

<?php
$i = 0;
foreach ($realisasi['detail']->result() as $dtlcont):
	$i = $i + 1;	
	echo "<tr>
		<td>$i</td>
		<td>$dtlcont->pro_name ($dtlcont->pro_code)
		<input type='hidden' name='proid[]' value='$dtlcont->pro_id'>
		<input type='hidden' name='proname[]' value='$dtlcont->pro_name'>
		</td>
		<td align='center'>$dtlcont->satuan_name</td>
		<td align='right'>".number_format($dtlcont->qty,$dtlcont->satuan_format)."</td>
		<td><table width='100%'><tr><td style='border:0px'>Rp.</td><td align='right' style='border:0px'>".number_format($dtlcont->price_pre,2)."</td></tr></table></td>
		<td align='right'>".number_format($dtlcont->qty_receive,$dtlcont->satuan_format)."</td>
		<td>Rp. <input type='text' name='harga[]' class='number currency' id='num_".$i."'></td>
	</tr>";
endforeach;
?>
</table>
<br>
<div align="center">
<input type="submit" value="<?php echo $this->lang->line('pr_button_submit');?>" id="saving">
<input type="button" value="<?php echo $this->lang->line('pr_button_batal');?>" onclick="batal()">
</div>
</form>

<div id="showerror" title="Error">
	<div id="result" style="text-align: left;"></div>
</div>