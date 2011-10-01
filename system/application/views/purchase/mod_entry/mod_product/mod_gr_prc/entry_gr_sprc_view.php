<script language="javascript">
$(document).ready(function(){
	$('#adjust').click(function() {
		if ($(this).attr('status') == 'adjustment') {
			$('.default').hide();
			$('.edit').show();
			$('#adj_stat').val('2');
			$(this).attr('status','batal');
			$(this).val('Batal Perubahan');
		} else {
			
			$('.default').show();
			$('.edit').hide();
			$('#adj_stat').val('1');
			$(this).attr('status','adjustment');
			$(this).val('Perubahan');
		}
	});
	
	$('.hitung').keyup(function() {
		var baris = $(this).attr('baris');
		var receive = $('#receive_'+baris).val();
		var price = $('#price_'+baris).val();
		var sums = $('#calc_'+baris);
		var digit = $('#cur_digit_'+baris).val();
		/*var sums_ppn = $('#calc_ppn_'+baris);
		var sums_tot_ppn = $('#calc_tot_ppn_'+baris);*/
		receive = parseFloat(receive.replace(',',''));
		price = parseFloat(price.replace(',',''));
		calc = receive*price;
		/*calc_ppn = calc * 10 / 100;
		calc_tot_ppn = receive*price;*/
		if (!calc) { calc = 0; };
		sums.text(calc.toFixed(digit));
		/*sums_ppn.text(calc_ppn.toFixed(2));
		sums_tot_ppn.text(calc_tot_ppn.toFixed(2));*/
	});

	masking('.number');
	
	$('#dialog_info').dialog({
		title: 'INFORMASI',
		autoOpen: false,
		bgiframe: true,
		width: 'auto',
		height: 'auto',
		resizable: false,
		draggable: false,
		modal:true,
		position:'center',
		buttons: {
			'OK':function() {
				$(this).dialog('close');
				location.href = 'index.php/<?=$link_controller?>/index_cbpb/<?=$status?>';
			}
		}
	});
	
	var form = $('#form_entry');
	form.submit(function(){
		//submitHandler : function(form) {
		if (validasi('#form_entry')){
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
					
						unmasking('.number');
						
						$(form).ajaxSubmit({
							url : 'index.php/<?=$link_controller?>/create_faktur/<?=$status?>',
							type: 'POST',
							data: $(form).formSerialize(),
							success: function(data) {
								/*
								if (data) {
									//alert(data);
									$('#dialog_info').dialog('open');
								}
								*/
								var info;	
								//alert(data);
								
								if(data) {
									<?php if ($status != 'kurs'):?>
									info = '<strong>Selamat... Proses pengisian no faktur berhasil<br> No BPB :<font color="red"> <?=$gr_no?> </font> <br> No faktur :<font color="red"> '+data+' </font></strong>';
									<?php else: ?>
									info = '<strong>Selamat... Kurs berhasil di tambahkan <br> No BPB :<font color="red"> <?=$gr_no?> </font> <br> Nilai Kurs : Rp. <font color="red"> '+data+' </font></strong>';
									<?php endif; ?>
									$('#dialog_info').text('').append(info).dialog('option','buttons', 
									{ "KELUAR" : function() {
										location.href = 'index.php/<?=$link_controller?>/index_cbpb/<?=$status?>';
										$("#dialog_info").dialog('close');
									}}).dialog('open');
								} else {
									<?php if ($status == 'kurs'):?>
									info = '<STRONG>Maaf... Kurs tidak berhasil ditambahkan</STRONG>';
									<?php else:?>
									info = '<STRONG>Maaf... Pengisian faktur tidak berhasil</STRONG>';
									<?php endif; ?>
									$('#dialog_info').text('').append(info).dialog('option','buttons', 
									{ "KELUAR" : function() {
										$('#dialog_info').dialog('close');
									}}).dialog('open');
								}
								
							}
						});
						$(this).dialog('close');
					}
				}
			}).html('').html('<?=$this->lang->line('confirm')?>').dialog('open');
		}
		return false;
	});
	
	$('.calc')
});
</script>
<div id="dialog_info">
</div>
<H3><?=$page_title?></H3>
<form id="form_entry"><!--  action="index.php/entry_good_receive/create_faktur" method="post"-->
<table width="100%"  border="0" cellspacing="2" cellpadding="2">
	<tr>
	  <td valign="top" width="50%">
	    <table width="100%">
		  <tr>
		    <td width="30%" class="head_title">No BPB</td>
			<td width="5%" class="head_title">:</td>
			<td class="head_title_content"><b><?=$gr_no?></b></td>
		  </tr>
		  <tr>
		    <td class="head_title">No PO</td>
			<td class="head_title">:</td>
			<td class="head_title_content"><b><?=$list_gr->row()->po_no?></b></td>
		  </tr>
		  <tr>
		    <td class="head_title">No Surat jalan</td>
			<td class="head_title">:</td>
			<td class="head_title_content"><?=$list_gr->row()->gr_suratJalan?></td>
		  </tr>
	    </table>
	  </td>
	  <td valign="top">
	    <table width="100%">
		  <tr>
		    <td class="head_title">Tanggal terima</td>
			<td class="head_title">:</td>
			<td class="head_title_content"><b><?=$list_gr->row()->gr_date?></b></td>
		  </tr>
		  <tr>
		    <td class="head_title" width="40%">Pemasok</td>
			<td class="head_title" width="5%">:</td>
			<td class="head_title_content"><?=$list_gr->row()->legal_name?>. <?=$list_gr->row()->sup_name?></td>
		  </tr>
		  <?php if ($status != 'kurs'):?>
		  <tr>
		    <td class="head_title">No Faktur pemasok</td>
			<td class="head_title">:</td>
			<td class="head_title_content">
			<INPUT TYPE="text" NAME="faktur_sup" class="required" title="Nomor Faktur" validate="required: true">
			<INPUT TYPE="hidden" NAME="kurs" id="kurs" value="<?=$list_gr->row()->kurs?>">
			</td>
		  </tr>
		  <?php if ($list_gr->row()->kurs != 1): ?>
		  <tr>
		    <td class="head_title">Kurs</td>
			<td class="head_title">:</td>
			<td class="head_title_content">Rp. <?=number_format($list_gr->row()->kurs,2)?></td>
		  </tr>
		  <?php 
			endif;
		   else:?>
		   <tr>
		    <td class="head_title">Kurs</td>
			<td class="head_title">:</td>
			<td class="head_title_content">Rp. <INPUT digit_decimal="2" TYPE="text" NAME="kurs" id="kurs" class="required number currency" title="Kurs" validate="required: true"></td>
		  </tr>
		  <?php endif;?>
		</table>
	  </td>
	</tr>
	<tr>
	  <td colspan="2">&nbsp;</td>
	</tr>
</table>

<table align="center" width="99%"  border="0" cellpadding="1" cellspacing="1" class="ui-widget-content ui-corner-all">
	<tr class="ui-widget-header">
	  <td width="5%" align="center">No</td>
	  <td width="25%" align="center">Barang/Kode</td>
	  <td width="8%" align="center">Terima</td>
	  <td width="10%" align="center">Harga Satuan <br> ( <?=$list_gr->row()->cur_symbol?> )</td>
	  <td width="10%" align="center">Jumlah <br> ( <?=$list_gr->row()->cur_symbol?> )</td>
	  <td width="10%" align="center">Diskon <br> ( <?=$list_gr->row()->cur_symbol?> )</td>
	  <td width="10%" align="center">Total <br> ( <?=$list_gr->row()->cur_symbol?> )</td>
	</tr>
	<?php 
	if ($list_gr_det->num_rows() > 0):
		$gr_num = 1;
		$total_all = 0;
		$total_hrg_disc = 0;
		$total_all_disc = 0;
		foreach ($list_gr_det->result() as $row_gr):
			$total_price = $row_gr->qty*$row_gr->price;
			$total_disc = $row_gr->qty*$row_gr->price-$row_gr->price_disc;
	?>
	<tr bgcolor="lightgray" baris="<?=$gr_num?>">
	  <td valign="top" align="right" class="ui-state-active"><?=$gr_num?>.</td>
	  <td valign="top" align="left"><?=$row_gr->pro_name?> <br>(<?=$row_gr->pro_code?>) 
	    <INPUT TYPE="hidden" id="cur_digit_<?=$gr_num?>" name="cur_digit_<?=$gr_num?>" value="<?=$row_gr->cur_digit?>">
		<INPUT TYPE="hidden" name="pro_id_<?=$gr_num?>" value="<?=$row_gr->pro_id?>">
		<INPUT TYPE="hidden" name="cur_id_<?=$gr_num?>" value="<?=$row_gr->cur_id?>">
		<INPUT TYPE="hidden" name="discount_<?=$gr_num?>" value="<?=$row_gr->discount?>">
	  </td>
	  <td valign="top" align="right">
		<input type="hidden" name="receive_awal_<?=$gr_num?>" id="receive_awal_<?=$gr_num?>" value="<?=number_format($row_gr->qty,$row_gr->satuan_format)?>">
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td><?=$row_gr->satuan_name?></td>
			<td>&nbsp;</td>
			<td align="right"><?=number_format($row_gr->qty,$row_gr->satuan_format)?></td>
		</tr>
		</table>
		<div class="" style="display:none">
			<input type="hidden" digit_decimal="<?=$row_gr->satuan_format?>" baris="<?=$gr_num?>" class="required hitung number" size="6" type="text" name="receive_<?=$gr_num?>" id="receive_<?=$gr_num?>" value="<?=number_format($row_gr->qty,$row_gr->satuan_format)?>">&nbsp;<?=$row_gr->satuan_name?>
		</div>
		<INPUT TYPE="hidden" name="cur_id_<?=$gr_num?>" value="<?=$row_gr->cur_id?>">
	  </td>
	  <td valign="top" align="right">
		<input type="hidden" name="price_awal_<?=$gr_num?>" id="price_awal_<?=$gr_num?>" value="<?=number_format($row_gr->price,$row_gr->cur_digit)?>">
		<div class="default">
		<?=number_format($row_gr->price,$row_gr->cur_digit)?>
		</div>
		<div class="edit" style="display:none">
		<input digit_decimal="<?=$row_gr->cur_digit?>" baris="<?=$gr_num?>" class="required hitung number currency" size="8" type="text" name="price_<?=$gr_num?>" id="price_<?=$gr_num?>" value="<?=number_format($row_gr->price,$row_gr->cur_digit)?>" title="Harga Satuan">
		</div>
	  </td>
		<td valign="top" align="right">
		<div style="float:right" class="calc" id="calc_<?=$gr_num?>"><?=number_format($total_price,$row_gr->cur_digit)?></div>
	  </td>
	  <td valign="top" align="right">
		<div style="float:right" class="calc" id="disc_<?=$gr_num?>"><?=number_format($row_gr->price_disc,$row_gr->cur_digit)?></div>
	  </td>
	   <td valign="top" align="right">
		<div style="float:right" class="calc" id="calc_tot_<?=$gr_num?>"><?=number_format($total_disc,$row_gr->cur_digit)?></div>
	  </td>
	</tr>
	<?php 
		$total_all = $total_all + $total_price;
		$total_hrg_disc = $total_hrg_disc + $row_gr->price_disc;
		$total_all_disc = $total_all_disc + $total_disc;
		$gr_num++;
		endforeach;
	?>
	<tr>
	<td colspan=3 class="ui-widget-header">&nbsp;</td>
	<td align="right" class="ui-state-active">Total Harga : </td>
	<td class="ui-state-active" align="right">
		<div style="float:right" id="total_all"><?=number_format($total_all,$row_gr->cur_digit)?></div>
	</td>
	<td class="ui-state-active" align="right">
		<div style="float:right" id="total_all"><?=number_format($total_hrg_disc,$row_gr->cur_digit)?></div>
	</td>
	<td class="ui-state-active" align="right">
		<div style="float:right" id="total_all"><?=number_format($total_all_disc,$row_gr->cur_digit)?></div>
	</td>
	</tr>
	<?php endif;?>
	</table>
	<br>
	<div align="center">
	  <INPUT TYPE="hidden" name="adj_stat" value="1" id="adj_stat">
	  <INPUT TYPE="hidden" name="gr_id" value="<?=$list_gr->row()->gr_id?>">
	  <INPUT TYPE="hidden" name="gr_no" value="<?=$gr_no?>">
	  <INPUT TYPE="hidden" name="sup_id" value="<?=$list_gr->row()->sup_id?>">
	  <INPUT TYPE="hidden" name="jum_product" value="<?=$jml_product?>">
	  <?php if ($status != 'kurs'): ?>
	  <INPUT TYPE="button" value="Perubahan" id="adjust" status="adjustment">
	  <?php endif;?>
	  <INPUT TYPE="submit" value="Simpan" id="saving"> 
	  <INPUT id="batal" TYPE="button" onclick="location.href='index.php/<?=$link_controller?>/index_cbpb/<?=$status?>'" value="Batal">
	 </div>
</form>
