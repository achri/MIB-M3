<script type="text/javascript">
$(function() {
	$("#result").dialog({
		modal: true,
		autoOpen: false,
		bgiframe: false,
		width: 'auto',
		height: 'auto',
		resizable: false,
		draggable: false,
		buttons: {
			'Batal': function() {
				$(this).dialog('close');
			},
			'Simpan': function() {
				id = $('#poid').val();
				reas = $('#alasan').val();
				if (reas == ""){
					$('#restext').text('Alasan Harus Diisi');
				}else{
					$.ajax({
						type: 'POST',
						url: 'index.php/<?php echo $link_controller;?>/close_po/'+id+'/'+reas,
						data: $(this).serialize(),
						success: function(data) {
							back();
							$(this).dialog('close');
						}
					});
					return false;
				}
			}
		}
	});
});
function tutup_po(id){
	$('#poid').val(id);
	$('#result').dialog('open');
}

function back(){
	window.location= "index.php/<?php echo $link_controller;?>/index";
}
</script>
<?php
	$hdrcont = $data['headpo']->row();
?>
<div class="ui-corner-all headers">
<table>
	<tr>
		<td class="labelcell" width="100">PO No<?php //echo $this->lang->line('pr_label_nopr');?></td>
		<td class="labelcell2">: <?php echo $hdrcont->po_no;?></td>
		<td width="20%"></td>
		<td class="labelcell" width="100"> Tgl PO<?php //echo $this->lang->line('pr_label_dep');?></td>
		<td class="labelcell2">: <?php echo $hdrcont->sup_name;?></td>
	</tr>
	<tr>
		<td class="labelcell">Supplier<?php //echo $this->lang->line('pr_label_tgl');?></td>
		<td class="labelcell2">: <?php echo $hdrcont->po_date;?></td>
		<td></td>
		<td></td>
	</tr>
</table>
</div>
<br/>
<br/>
<table width="100%" class="table">
	<tr class='ui-widget-header'>
		<td colspan="7">Daftar Pesanan</td>
	</tr>
	<tr class='ui-widget-header'>
		<td>No</td>
		<td>Barang / Kode</td>
		<td>satuan</td>
		<td>Pesan</td>
		<td>Terima</td
		<td>Retur</td>
		<td>+/-</td>
	</tr>

<?php
$i = 0;
foreach ($data['detailpo']->result() as $dtlcont):
	$i = $i + 1;	
	echo "<tr>
		<td>$i</td>
		<td>$dtlcont->pro_name ($dtlcont->pro_code)</td>
		<td>$dtlcont->satuan_name</td>
		<td>$dtlcont->qty</td>
		<td>$dtlcont->qty_terima</td>
		<td>$dtlcont->qty_retur</td>
		<td>$dtlcont->qty_status &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $dtlcont->qty_remain</td>
	</tr>";
endforeach;
?>

<br/>
<br/>
<table width="100%" class="table">
	<tr class='ui-widget-header'>
		<td colspan="8">Daftar Good Receive / Good Return</td>
	</tr>
	<tr class='ui-widget-header'>
		<td>No</td>
		<td>Barang / Kode</td>
		<td>Tgl Good Receive/<br/> Good Return</td>
		<td>Receive/Return</td>
		<td>Nomor</td
		<td>Surat Jalan</td>
		<td>jumlah Terima</td>
		<td>jumlah Return</td>
	</tr>

<?php
$i = 0;
foreach ($data['detailgr']->result() as $dtlgr):
	$i = $i + 1;
	if ($dtlgr->gr_type == 'rec'){
		$ket = "Receive";
		$qty1 = number_format($dtlgr->qty,2,',','.');
		$qty2 = "-";
	}else{
		$ket = "Return";
		$qty1 = "-";
		$qty2 = number_format($dtlgr->qty,2,',','.');
	}	
	echo "<tr>
		<td>$i</td>
		<td>$dtlgr->pro_name ($dtlcont->pro_code)</td>
		<td>$dtlgr->gr_date</td>
		<td>$ket</td>
		<td>$dtlgr->gr_no</td>
		<td>$dtlgr->gr_suratJalan</td>
		<td>$qty1</td>
		<td>$qty2</td>
	</tr>";
endforeach;
?>
</table>
<br/>
<input type="button" value="Tutup PO ini" onclick="tutup_po(<?php echo $hdrcont->po_id;?>)"></input>
<input type="button" value="Kembali Ke Daftar" onclick="back()"></input>

<div id="result" title="KONFIRMASI">
	<div id="restext"></div>
	<input type="hidden" id="poid"></input><br/>
	<textarea cols="40" rows="10" id="alasan"></textarea>
</div>