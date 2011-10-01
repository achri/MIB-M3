<script type="text/javascript">
$(document).ready(function (){
	masking('.number');
	$("#showerror").dialog({
		autoOpen: false,
		modal: true,
		buttons: {
			'OK' : function() {
			$(this).dialog('close');
			}
		}
	});
	
	$(".tgl").datepicker({dateFormat: 'dd-mm-yy'});

	$('#frm_pemakaian').submit(function() {
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
							url: 'index.php/<?php echo $link_controller;?>/add_pemakaian',
							data: $('#frm_pemakaian').serialize(),
							success: function(data) {
								if (data == 'ok'){
									//batal();
									var info = 'Input pemakaian barang berhasil diproses !!!';
									$('.dialog_informasi').html('').html(info).dialog('option','buttons',{
										"OK" : function() {
											batal();
										}
									}).dialog('open').css('color','red');
								}else{
									$('#result').html(data);
									$('#showerror').dialog('open');
								}
								$('#saving').attr('disabled',false);
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
	$content = $get_pakai->row();
?>
<form id="frm_pemakaian">
<center>
<div class="ui-corner-all headers">
<div id="hiddenfield" style="display:none;">
<input type="text" name="supid" value="<?php echo $content->sup_id;?>">
<input type='text' name="mrid" value="<?php echo $content->mr_id;?>">
<input type='text' name="grlid" value="<?php echo $content->grl_id;?>">
<input type='text' name="proid" value="<?php echo $content->pro_id;?>">
<input type='text' name="realisasi" value="<?php echo $content->grl_realisasi;?>">
</div> 
<table>
	<tr>
		<td class="labelcell" width="100">No MR<?php //echo $this->lang->line('pr_label_nopr');?></td>
		<td class="labelcell2">: <?php echo $content->mr_no;?></td>
		<td width="20%"></td>
		<td class="labelcell" width="100">Pemohon<?php //echo $this->lang->line('pr_label_dep');?></td>
		<td class="labelcell2">: <?php echo $content->usr_name;?></td>
	</tr>
	<tr>
		<td class="labelcell" valign="top">No RF<?php //echo $this->lang->line('pr_label_tgl');?></td>
		<td class="labelcell2">: <?php echo $content->grl_no;?></td>
		<td></td>
		<td class="labelcell" valign="top">Tanggal RF<?php //echo $this->lang->line('pr_label_pemohon');?></td>
		<td class="labelcell2">: <?php echo $content->grl_releaseDate;?></td>
	</tr>
	<tr>
		<td class="labelcell" valign="top">Nama Produk<?php //echo $this->lang->line('pr_label_status');?></td>
		<td class="labelcell2">: <?php echo $content->pro_name."<br/> &nbsp; ".$content->pro_code;?></td>
		<td></td>
		<td class="labelcell" valign="top">Jumlah Diterima<?php //echo $this->lang->line('pr_label_status');?></td>
		<td class="labelcell2" valign="top">: <?php echo number_format($content->grl_realisasi,$content->satuan_format)." &nbsp; ".$content->satuan_name;?></td>
	</tr>
</table>
</div>
<br/>
</center>

<table width="100%" class="table">
	<tr class='ui-widget-header'>
		<td colspan="5">History Pemakaian</td>
	</tr>
	<tr class='ui-widget-header'>
		<td width="5%">no</td>
		<td width="10%">tanggal</td>
		<td width="10%">satuan</td>
		<td width="10%">jumlah <br/> dipakai</td>
		<td width="65%">keterangan</td>
	</tr>

<?php
$i = 0;
$ttlpakai = 0;
$history = $this->Tbl_mr->get_history_pemakaian($content->mr_id, $content->grl_id, $content->pro_id);
foreach ($history->result() as $hist):
	$i = $i + 1;	
	echo "<tr>
		<td>$i</td>
		<td>$hist->date</td>
		<td>$content->satuan_name</td>
		<td>".number_format($hist->qty_use,$content->satuan_name)."</td>
		<td>$hist->note</td>
	</tr>";
	$ttlpakai = $ttlpakai + $hist->qty_use;
endforeach;
?>
</table>
<input type="hidden" name="total" value="<?php echo $ttlpakai;?>">
<br/><br/>
<table width="100%" class="table">
	<tr class='ui-widget-header'>
		<td colspan="3">Input data Pemakaian</td>
	</tr>
	<tr class='ui-widget-header'>
		<td width="15%">Tanggal</td>
		<td width="15%">Jumlah Dipakai</td>
		<td>Keterangan</td>
	</tr>
	<tr>
		<td><input type="text" name="tgl" id="tgl" class="tgl"></td>
		<td><input class="number" digit_decimal="<?=$content->satuan_format?>" type="text" name="jml" id="jml" size="10"> <?php echo $content->satuan_name;?></td>
		<td><textarea cols="70" rows="2" name="ket"></textarea></td>
	</tr>
</table>

<input type="submit" value="<?php echo $this->lang->line('pr_button_submit');?>" id="saving">
<input type="button" value="<?php echo $this->lang->line('pr_button_batal');?>" onclick="batal()">
</form>

<div id="showerror" title="Error">
	<div id="result" style="text-align: left;"></div>
</div>