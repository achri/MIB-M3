<script language="javascript">
$(document).ready(function() {
	<?=set_calendar('.kalender',0,'dd-mm-yy');?>
	masking('.number');
	masking_currency('.number_select','.number');
	//$('.required').attr('title','*');
	
	var form = $('#form_entry');
	form.submit(function(){
		//submitHandler : function(form) {
		if (validasi('#form_entry')) {
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
							url:'index.php/<?=$link_controller?>/buat_pembayaran_ppn',
							type:'POST',
							data:$(form).formSerialize(),
							success: function(data) {
								//$('.informasi').html(data);
								if (data) {
									info = '<font color="red"><strong>'+data+'</strong></font>';
									$('.dialog_informasi').html('').html(info).dialog('option','buttons',{
									'OK' : function() {
										$(this).dialog('close');
										$('#saving').attr('disabled',false);
									}
								}).dialog('open');
								}else {
									var bkbk = $('#bkbk_no').val();//'Maaf ... Proses pembayaran gagal dilakukan !!!';
									info = 'Selamat ... Proses pembayaran berhasil dilakukan<br><font color="red"><strong>NO BKBK : '+bkbk+'</strong></font>';
									$('.dialog_informasi').html('').html(info).dialog('option','buttons',{
									'OK' : function() {
										location.href = 'index.php/<?=$link_controller?>/index';
										$(this).dialog('close');
										$('#saving').attr('disabled',false);
									}
								}).dialog('open');
								}
								
								
							}
						});
						
						//alert();
						$(this).dialog('close');
					}
				}
			}).html('').html('<?=$this->lang->line('confirm')?>').dialog('open');
			
			//var a = $('.chk_val').val();
			//alert(Number(a));
		}
		return false;
		/*,
		focusInvalid: true,
		focusCleanup: true,
		highlight: function(element, errorClass) {
			$(element).addClass('ui-state-error');
		},
		unhighlight: function(element, errorClass) {
			$(element).removeClass('ui-state-error');
		}	
		*/
	});

	$('.chk_val').change(function() {
		var vals = $.fn.autoNumeric.Strip(this.id);
		var sisa = $(this).attr('sisa');
		//sisa = sisa.toFixed(2);
		var valsisa = $(this).attr('valsisa');
		var curr = $(this).attr('curr');
		//var vals = $(this).val();
		vals = parseFloat(vals.replace(/,/g,''));
		if (vals > sisa) {
			$('.dialog_validasi').html('').html('Harga melebihi sisa pembayaran : '+curr+'. '+valsisa).dialog('open');
			$(this).val('').focus();	
		}	
		//alert(sisa+'|'+vals);
		return false;	
	});
	
	$('#gr_dialog').dialog({
		autoOpen: false,
		width: 'auto',
		height: 'auto',
		resizable: false,
		draggable: false,
		buttons: {
			"KELUAR": function() {
				$(this).dialog('close');
			}
		}
	});
	
	$('.gr_list').click(function() {
		var con_id = $(this).attr('con_id');
		$.post('index.php/<?=$link_controller?>/list_bpb/'+con_id, function(data) {
			$('#gr_dialog').html('').html(data).dialog('open');
		});
		return false;
	});
		
});
</script>
<div id="gr_dialog" title="DAFTAR BPB"></div>
<H3><?=$page_title?></H3>
<div class="ui-widget-content ui-corner-all">
<br>
<form id="form_entry">
<table width="90%" align="center" border="0" cellpadding="1" cellspacing="1" class="ui-widget-content ui-corner-all">
		<tr class="ui-widget-header">
		  <td width="5%" align="center" rowspan="2">No</td>
		  <td width="15%" align="center" rowspan="2">No kontra bon</td>
		  <td width="20" align="center" rowspan="2">Nilai kontra bon </td>
		  <td width="15" align="center" rowspan="2">PPN 10% <br> ( Rp )</td>
		  <td width="45%" align="center" colspan="2">Total yang harus Dibayar</td>
	    </tr>
		<tr class="ui-widget-header">
		  <td align="center">Nilai kontra bon</td>
		  <td align="center">PPN 10% <br> ( Rp )</td>
		</tr>
		<?php 
		for ($i=0;$i<sizeOf($list_bon);$i++):?>
			<?php $no = $i+1;?>
		<tr bgcolor="lightgray" baris="<?=$no?>">
		  <td class="ui-state-active"><?=$no?>.</td>
		  <td align="center" class="ui-state-error"><a class='gr_list' href='#' con_id='<?=$list_bon[$i]['con_id']?>'><?=$list_bon[$i]['con_no']?></a></td>
		  
		  <td>
		     <table width="100%" cellpadding="0" cellspacing="0">
			   <tr>
				<td><?php echo $list_bon[$i]['cur_symbol'];?></td>
				<td width="90%" align="right">
				<?=number_format($list_bon[$i]['con_remain'],$list_bon[$i]['cur_digit'])?>
				<input type="hidden" id="sisa_<?=$no?>" name="sisa_<?=$no?>" value="<?=$list_bon[$i]['con_remain']?>">
				</td>
			 </table>
		  </td>
		  
		  <td>
		     <table width="100%" cellpadding="0" cellspacing="0">
			   <tr>
				<td width="90%" align="right">
				<?=number_format($list_bon[$i]['con_ppn_remain'],2)?>
				<input type="hidden" id="sisa_ppn_<?=$no?>" name="sisa_ppn_<?=$no?>" value="<?=$list_bon[$i]['con_ppn_remain']?>">
				</td>
			 </table>
		  </td>
		  
		  <td valign="top" align="left">
				<SELECT NAME="cur_id_s<?=$no?>" input_id="bayar_<?=$no?>" class="number_select" disabled>
				  <?php foreach($list_cur->result() as $row_cur):?>
				  <option value="<?=$row_cur->cur_id?>" <?=($row_cur->cur_id == $list_bon[$i]['cur_id'])?('selected'):('')?>><?=$row_cur->cur_symbol?></option>
				  <?php endforeach;?>
				</SELECT>
				<input type="hidden" name="cur_id_<?=$no?>" value="<?=$list_bon[$i]['cur_id']?>">
			    <INPUT id="bayar_<?=$no?>" TYPE="text" NAME="bayar_<?=$no?>" digit_decimal="<?=$list_bon[$i]['cur_digit']?>" class="required number currency chk_val kosong" sisa="<?=$list_bon[$i]['con_remain']?>" curr="<?=$list_bon[$i]['cur_symbol']?>" valsisa="<?=number_format($list_bon[$i]['con_remain'],$list_bon[$i]['cur_digit'])?>" autocomplete="off" title="Total Nilai yg akan dibayar">
				<INPUT TYPE="hidden" NAME="con_id_<?=$no?>" value="<?=$list_bon[$i]['con_id']?>">
				<INPUT TYPE="hidden" NAME="con_remain_<?=$no?>" value="<?=$list_bon[$i]['con_remain']?>">
		  </td>
		  
		  <td>
			<INPUT size="12" id="bayar_ppn_<?=$no?>" TYPE="text" NAME="bayar_ppn_<?=$no?>" digit_decimal="2" class="required number currency chk_val kosong" sisa="<?=$list_bon[$i]['con_ppn_remain']?>" curr="Rp" valsisa="<?=number_format($list_bon[$i]['con_ppn_remain'],2)?>" autocomplete="off" title="Total PPN yg akan dibayar">
			<INPUT TYPE="hidden" NAME="con_ppn_remain_<?=$no?>" value="<?=$list_bon[$i]['con_ppn_remain']?>">
		</td>
		  
		</tr>
		<?php endfor;?>
		<tr style="display:none">
			<td colspan="5">&nbsp;<INPUT TYPE="hidden" name="jum_row" value="<?=sizeOf($list_bon)?>">
			<INPUT TYPE="hidden" name="sup_id" value="<?=$sup_id?>">
			<INPUT TYPE="hidden" name="payment_methode" value="<?=$payment_method?>">
			</td>
		</tr>
</table>
<br>
<table width="90%" align="center" border="0" cellpadding="1" cellspacing="1">
		<tr>
		  <td width="15%">Nomor BKBK </td>
		  <td width="2%">:</td>
		  <td><INPUT TYPE="text" maxlength="15" id="bkbk_no" NAME="bkbk_no" style="width:200px;" class="required" title="Nomor BKBK"></td>
		</tr>
		<tr>
		  <td>Tanggal BKBK </td>
		  <td>:</td>
		  <td><INPUT readonly="readonly" TYPE="text" NAME="bkbk_date" ID="bkbk_date" style="width:200px;" class="inp_kalender kalender required" title="Tanggal BKBK"></td>
		</tr>
		<tr>
		  <td>Cara Bayar</td>
		  <td>:</td>
		  <td><?=$payment_method?></td>
		</tr>
	<?php if ($payment_method == 'TRANSFER'):?>
		<tr>
		  <td>Biaya Transfer</td>
		  <td>:</td>
		  <td><INPUT id="transfer_biaya" class="number currency" TYPE="text" NAME="transfer_biaya" style="width:200px;" class="required number currency" title="Biaya Transfer"></td>
		</tr>
		<tr>
		  <td>Nomor Transfer</td>
		  <td>:</td>
		  <td><INPUT TYPE="text" NAME="transfer_nomor" style="width:200px;" maxlength="20" class="required" title="Nomor Transfer"></td>
		</tr>
		<tr>
		  <td>Rekening Asal</td>
		  <td>:</td>
		  <td><INPUT TYPE="text" NAME="transfer_rekening" style="width:200px;"  maxlength="20" class="required" title="Rekening Asal"></td>
		</tr>
		<tr>
		  <td>Rekening Supplier</td>
		  <td>:</td>
		  <td><INPUT TYPE="text" NAME="transfer_supplier" style="width:200px;"  maxlength="20" class="required" value="Rekening Supplier"></td>
		</tr>
	<?php elseif ($payment_method == 'CEK/GIRO'):?>
		<tr>
		  <td>Jatuh Tempo</td>
		  <td>:</td>
		  <td><INPUT TYPE="text" NAME="cek_tempo" ID="cek_tempo" style="width:200px;" class="inp_kalender kalender required" readonly title="Tanggal Jatuh Tempo"></td>
		</tr>
		<tr>
		  <td>Nomor Cek/Giro</td>
		  <td>:</td>
		  <td><INPUT TYPE="text" NAME="cek_no" style="width:200px;"  maxlength="20" class="required" title="Nomor Cek/Giro"></td>
		</tr>
		<tr>
		  <td>Rekening Asal</td>
		  <td>:</td>
		  <td><INPUT TYPE="text" NAME="cek_rekening" style="width:200px;"  maxlength="20" class="required" title="Rekening Asal"></td>
		</tr>
	<?php endif;?>
		<tr>
		  <td valign="top">Memo</td>
		  <td valign="top">:</td>
		  <td><TEXTAREA NAME="memo" ROWS="" COLS="" style="width:200px;height:30px"></TEXTAREA></td>
		</tr>
		<tr>
		  <td valign="top">&nbsp;</td>
		  <td valign="top">&nbsp;</td>
		  <td><INPUT TYPE="submit" value="Simpan" id="saving"> <INPUT TYPE="button" value="Batal" onclick="location.href='index.php/<?=$link_controller?>/index'"></td>
		</tr>
</table>
<br>
</form>
</div>
