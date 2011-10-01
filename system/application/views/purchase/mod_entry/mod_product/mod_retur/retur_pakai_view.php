<script type="text/javascript">
$(document).ready(function (){
	$('div#dlg_confirm').dialog({
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
			'Keluar': function() {
				$(this).dialog('close');
			}
		}
	});
	
	$("#showerror").dialog({
		autoOpen: false,
		modal: true,
		buttons: {
			'KELUAR': function() {
				$(this).dialog('close');
				$('.saving').attr('disabled',false);
			},
			'OK' : function() {
			$.ajax({
				type: 'POST',
				url: 'index.php/<?php echo $link_controller;?>/add_retur',
				data: $('#frm_pemakaian').serialize(),
				success: function(data) {
					//alert (data);
						var info = '<font color=red>Barang berhasil di kembalikan ke Gudang !!!</font>';
						$("#dlg_confirm").html('').html(info).bind('dialogclose', function(event, ui) {
							batal();
							$('.saving').attr('disabled',false);
						}).dialog('open');
					}
			});
			return false;
			//$(this).dialog('close');
			}
			
		}
	});
	

	$('#frm_pemakaian').submit(function() {
		$('#showerror').dialog('open');
		$('.saving').attr('disabled',true);
		return false;
	});
});

function batal(){
	window.location = 'index.php/<?php echo $link_controller;?>/index';
}
</script>

<?php 
	$content = $get_retur->row();
?>

<div id="dlg_confirm" title="INFORMASI"></div>

<form id="frm_pemakaian">
<center>
<div class="ui-corner-all headers">
<div id="hiddenfield" style="display:none;">
<input ype='text' name="mrid" value="<?php echo $content->mr_id;?>">
<input ype='text' name="grlid" value="<?php echo $content->grl_id;?>">
<input ype='text' name="proid" value="<?php echo $content->pro_id;?>">
<input type='text' name="realisasi" value="<?php echo $content->grl_realisasi;?>">
</div> 
<table>
	<tr>
		<td class="labelcell" width="100"><?php echo $this->lang->line('retur_label_mr');?></td>
		<td class="labelcell2">: <?php echo $content->mr_no;?></td>
		<td width="20%"></td>
		<td class="labelcell" width="100"><?php echo $this->lang->line('retur_label_pemohon');?></td>
		<td class="labelcell2">: <?php echo $content->usr_name;?></td>
	</tr>
	<tr>
		<td class="labelcell" valign="top"><?php echo $this->lang->line('retur_label_rf');?></td>
		<td class="labelcell2">: <?php echo $content->grl_no;?><input type="hidden" name="grl" value="<?php echo $content->grl_no;?>"></td>
		<td></td>
		<td class="labelcell" valign="top"><?php echo $this->lang->line('retur_label_tgl');?></td>
		<td class="labelcell2">: <?php echo $content->grl_releaseDate;?></td>
	</tr>
	<tr>
		<td class="labelcell" valign="top"><?php echo $this->lang->line('retur_label_pro');?></td>
		<td class="labelcell2">: <?php echo $content->pro_name."<br/> &nbsp; ".$content->pro_code;?></td>
		<td></td>
		<td class="labelcell" valign="top"><?php echo $this->lang->line('retur_label_jml');?></td>
		<td class="labelcell2" valign="top">: <?php echo number_format($content->grl_realisasi,$content->satuan_format)." &nbsp; ".$content->satuan_name;?></td>
	</tr>
	<tr>
		<td class="labelcell" valign="top"><?php echo $this->lang->line('retur_label_sup');?></td>
		<td class="labelcell2">: <?php echo $content->sup_name;?></td>
		<td></td>
		<td class="labelcell" valign="top"></td>
		<td class="labelcell2" valign="top"></td>
	</tr>
</table>
</div>
<br/>
</center>

<table width="100%" class="table">
	<tr class='ui-widget-header'>
		<td colspan="5"><?php echo $this->lang->line('retur_tabel_ttl');?></td>
	</tr>
	<tr class='ui-widget-header'>
		<td width="5%"><?php echo $this->lang->line('retur_tabel_no');?></td>
		<td width="10%"><?php echo $this->lang->line('retur_tabel_tgl');?></td>
		<td width="10%"><?php echo $this->lang->line('retur_tabel_sat');?></td>
		<td width="10%"><?php echo $this->lang->line('retur_tabel_jml');?></td>
		<td width="65%"><?php echo $this->lang->line('retur_tabel_ket');?></td>
	</tr>

<?php
$i = 0;
$history = $this->Tbl_mr->get_history_pemakaian($content->mr_id, $content->grl_id, $content->pro_id);
foreach ($history->result() as $hist):
	$i = $i + 1;	
	echo "<tr>
		<td>$i</td>
		<td>$hist->date</td>
		<td>$content->satuan_name</td>
		<td>".number_format($hist->qty_use,$content->satuan_format)."</td>
		<td>$hist->note</td>
	</tr>";
endforeach;
?>
</table>
<input type="hidden" name="remain" value="<?php echo $content->qty_remain;?>">
<input type="hidden" name="sup" value="<?php echo $content->sup_id;?>">
<input type="hidden" name="satuan" value="<?php echo $content->satuan;?>">
<input type="hidden" name="pro_satuan" value="<?php echo $content->pro_satuan;?>">

<br/><b>Sisa Tak Dipakai : <?php echo number_format($content->qty_remain,$content->satuan_format)." ".$content->satuan_name;?></b><br/><br/>

<input type="submit" value="<?php echo $this->lang->line('pr_button_submit');?>" class="saving">
<input type="button" value="<?php echo $this->lang->line('pr_button_batal');?>" onclick="batal()">
</form>

<div id="showerror" title="KONFIRMASI">
	<div id="result" style="text-align: left;">
		Terima retur sisa barang pemakaian <br/>
		Nama Barang : <?php echo $content->pro_name; ?> <br/>
		Jumlah : <?php echo number_format($content->qty_remain,$content->satuan_format)." ".$content->satuan_name; ?>
	</div>
</div>