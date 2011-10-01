<script type="text/javascript">
$(document).ready(function() {
	masking('.number');
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
	
	$('.cek_stok').change(function() {
		var cek = false, info = '';
		var $item = $(this);
		var qty_stok = $(this).attr('qty_stok');
		var qty_mr = $(this).attr('qty_mr');
		var um_val = $(this).attr('um_val');
		var qty_show = $(this).attr('qty_show');
		var qty = $(this).val();
		qty = parseFloat(qty.replace(/,/g,''));
		
		if (um_val > 0) {
			var qty_conv = qty * um_val;
		}
		
		if (qty_conv > qty_stok) {
			info = 'Stok tidak mencukupi !!!';
			cek = true;
		}
		else if (qty > qty_mr) {
			info = 'Kuantitas melebihi permintaan barang !!!';
			cek = true;
		}
		
		if	(cek) {
			$('.dialog_informasi').html('').html(info)
			.dialog('option','buttons',{
				'OK': function() {
					$item.val('');
					$(this).dialog('close');
				}
			}).dialog('open').css({'color':'red','font-weight':'bold'});
		}
		
		return false;
	});
	
	$('#app_gr').submit(function() {
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
							url: 'index.php/<?php echo $link_controller;?>/gr_add',
							data: $('#app_gr').serialize(),
							success: function(data) {
								$('#restext').html(data);
								$('#result').dialog('open');
								$('#saving').attr('disabled',false);
								//alert (data);
							}
						});
					}
				}
			}).html('').html('<?=$this->lang->line('confirm')?>').dialog('open');
		return false;
	});
});

	function alasan_remover(id){
		var qty = $('#qty_'+id).val();
		var jml = $('#jml_'+id).val();
		var cek = qty - jml;
		
		if (cek == 0){
			$('#alasan_'+id).val('');
			$('#viewnote_'+id).text('');
		}else if (cek < 0){
			$('#alasan_'+id).val('');
			$('#viewnote_'+id).text('');
		}

		//alert (qty);

	}
	
	function set_alasan(id){
		var alasan = $('#reason_'+id).val();
		var cat = 'cat : ';	
		 $('#alasan_'+id).val(alasan);
		 $('#viewnote_'+id).text(cat+alasan);
		 $('#result').dialog('close');
	}

	function closedialog(){
		$('#result').dialog('close');
	}

	function batal(){
		window.location = 'index.php/<?php echo $link_controller;?>/index';
	}
</script>

<?php 
	$head = $grl_content->row();
?>
<form id="app_gr">
<center>
<div class="ui-corner-all headers">
<table>
	<tr>
		<td class="labelcell" width="100"><?php echo $this->lang->line('gr_label_nopo');?><input type="hidden" name="grl_id" value="<?php echo $grl_id; ?>"></td>
		<td class="labelcell2">: <?php echo $head->grl_no;?> <input type="hidden" name="mr_id" value="<?php echo $head->mr_id; ?>"></td>
		<td width="20%"><input type="hidden" name="grl_no" value="<?php echo $head->grl_no; ?>"></td>
		<td class="labelcell" width="100"><?php echo $this->lang->line('gr_label_pemohon');?></td>
		<td class="labelcell2">: <?php echo $head->usr_name;?></td>
	</tr>
</table>
</div>
<br>
<table width="90%" id="dataview" class="table">
	<tr class='ui-widget-header' align="center">
		<td><?php echo $this->lang->line('gr_tabel_no');?></td>
		<td><?php echo $this->lang->line('gr_tabel_brg');?></td>
		<td><?php echo $this->lang->line('gr_tabel_sup');?></td>
		<td><?php echo $this->lang->line('gr_tabel_qty');?></td>
		<td><?php echo $this->lang->line('gr_tabel_jml');?></td>
	</tr>
	<?php 
	$i = 0;
	foreach ($grl_content->result() as $detail):
		$i = $i +1;
		echo "<tr class='x'>
					<td valign='top'>".$i.". <input type='hidden' id='id' name='id[]' value='".$i."'></td>
					<td valign='top'>".$detail->pro_name."<br/>".$detail->pro_code." <input type='hidden' id='proid_".$i."' name='proid_".$i."' value='".$detail->pro_id."'></td>
					<td valign='top'>".$detail->legal_name.". ".$detail->sup_name." <input type='hidden' id='proname_".$i."' name='proname_".$i."' value='".$detail->pro_name."'></td>
					<td valign='top'>".number_format($detail->qty,$detail->satuan_format)." ".$detail->satuan_name."<input type='hidden' id='qty_".$i."' name='qty_".$i."' value='".$detail->qty."'></td>
					<td valign='top'>
					
					<input class='number cek_stok' digit_decimal='".$detail->satuan_format."' qty_stok='".$detail->inv_end."' qty_show='".number_format($detail->qty,$detail->satuan_format)."' um_val='".$detail->satuan_konversi."' qty_mr='".$detail->qty."' type='text' name='jml_".$i."' id='jml_".$i."' size='15' onchange='alasan_remover(".$i.")'> 
					&nbsp; ".$detail->satuan_name." <div id='note_".$i."'></div> 
									 <input type='hidden' id='sat_".$i."' name='sat_".$i."' value='".$detail->um_id."'>
									 <input type='hidden' id='sup_".$i."' name='sup_".$i."' value='".$detail->sup_id."'>
									 <textarea id='alasan_".$i."' name='alasan_".$i."'  style='display:none;'></textarea>
									 <div id='viewnote_".$i."' style='width: 150px; color: red;'></div></td>
			</tr>";
	endforeach;
?>
</table>
<br>
<input type="submit" value="Proses" id="saving">
<input type="button" value="<?php echo $this->lang->line('gr_button_batal');?>" onclick="batal()">
</form>
</center>

<div id="result" title="INFORMASI">
	<div id="restext" style="text-align: left;"></div>
</div>