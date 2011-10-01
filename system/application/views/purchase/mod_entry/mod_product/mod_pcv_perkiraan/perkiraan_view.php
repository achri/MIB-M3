<script type="text/javascript">
$(document).ready(function (){
	masking('.number');
	$("#showerror").dialog({
		autoOpen: false,
		modal: true,
		width: 500,
		buttons: {
			'OK' : function() {
			$(this).dialog('close');
			}
		}
	});
		
	$('#frm_perkiraan').submit(function() {
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
							url: 'index.php/<?php echo $link_controller;?>/add_perkiraan',
							data: $('#frm_perkiraan').serialize(),
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
	$headcontent = $get_pcv['head']->row();
?>
<form id="frm_perkiraan">
<center>
<div class="ui-corner-all headers">
<div id="hiddenfield" style="display:none;">
<input type='text' name="pcvid" value="<?php echo $headcontent->pcv_id;?>">
<input type='text' name="prid" value="<?php echo $headcontent->pr_id;?>">
</div> 
<table>
	<tr>
		<td class="labelcell" width="200"><?php echo $this->lang->line('perkiraan_label_nopcv');?></td>
		<td class="labelcell2">: <?php echo $headcontent->pcv_no;?></td>
		<td width="20%"></td>
		<td class="labelcell" width="100"><?php echo $this->lang->line('perkiraan_label_pemohon');?></td>
		<td class="labelcell2">: <?php echo $headcontent->usr_name;?></td>
	</tr>
	<tr>
		<td class="labelcell" valign="top"><?php echo $this->lang->line('perkiraan_label_nopr');?></td>
		<td class="labelcell2">: <?php echo $headcontent->pr_no;?></td>
		<td></td>
		<td class="labelcell" valign="top"></td>
		<td class="labelcell2"></td>
	</tr>
</table>
</div>
<br/>
</center>

<table width="100%" class="table">
	<tr class='ui-widget-header'>
		<td colspan="4"><?php echo $this->lang->line('perkiraan_tabel_ttl');?></td>
	</tr>
	<tr class='ui-widget-header' align="center">
		<td width="5%"><?php echo $this->lang->line('perkiraan_tabel_no');?></td>
		<td width="40%"><?php echo $this->lang->line('perkiraan_tabel_brg');?></td>
		<td width="25%"><?php echo $this->lang->line('perkiraan_tabel_qty');?></td>
		<td width="30%"><?php echo $this->lang->line('perkiraan_tabel_sat');?></td>
	</tr>

<?php
$i = 0;
foreach ($get_pcv['detail']->result() as $dtlcontent):
	$i = $i + 1;	
	echo "<tr>
		<td>$i.</td>
		<td>$dtlcontent->pro_name<br/>($dtlcontent->pro_code)
		<input type='hidden' name='proid[]' value='".$dtlcontent->pro_id."'>
		<input type='hidden' name='proname[]' value='".$dtlcontent->pro_name."'>
		</td>
		<td align='center'>".number_format($dtlcontent->qty,$dtlcontent->satuan_format)." &nbsp; $dtlcontent->satuan_name
		<input type='hidden' name='qty[]' value='".$dtlcontent->qty."'>
		</td>
		<td>Rp. <input type='text' id='hrg_$i' class='number' digit_decimal='2' name='harga[]'></td>
	</tr>";
endforeach;
?>
</table>
<br/>
<div align="center">
	<input type="submit" value="<?php echo $this->lang->line('pr_button_submit');?>" id="saving">
	<input type="button" value="<?php echo $this->lang->line('pr_button_batal');?>" onclick="batal()">
</div>
</form>

<div id="showerror" title="error">
	<div id="result" style="text-align: left;">
	</div>
</div>