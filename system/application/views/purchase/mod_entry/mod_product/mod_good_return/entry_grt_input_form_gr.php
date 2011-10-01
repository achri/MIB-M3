<script language="javascript">
$(document).ready(function(){
	masking('.number');
	<?=set_calendar('.kalender','-'.getDays($po_list->row()->po_date,date('Y-m-d H:i:s')),'dd-mm-yy',0);?>
	$('#form_entry').validate({
		submitHandler: function(form) {
		/*
		var d = '';
		$('.cek_min').each(function(i){
			var frow_id ='',fjml='',fsisa='';
			frow_id = $(this).attr('row_id');
			fjml = $(this).val();
			fsisa = $(this).attr('jml');
			//alert(fjml+' '+fsisa);
			if (fjml < fsisa) {
				//d = d + ' ' + row_id;
				alert('ID = '+frow_id+' JML = '+fjml+' SISA = '+fsisa);
			}
			//return false;
		});
		
		var as = false;
		if (as == true) {
			*/
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
						$(form).ajaxSubmit({
							url:'index.php/<?=$link_controller?>/save_bpb_gr/',
							type:'POST',
							success: function(data){
								//$('.informasi').html(data);
								var info;	
								if(data) {
									info = '<strong>Selamat... BPB berhasil di buat <br> BPB kode :<font color="red"> '+data+' </font></strong>';
									$('#dlg_confirm').text('').append(info).dialog('option','buttons', 
									{ "Keluar" : function() {
										location.href = 'index.php/<?=$link_controller?>/list_po_det/<?=$po_id?>/gr_input';
										$("#dlg_confirm").dialog('close');
									}}).dialog('open');
								} else {
									info = '<STRONG>Maaf... BPB Tidak Berhasil dibuat</STRONG>';
									$('#dlg_confirm').text('').append(info).dialog('option','buttons', 
									{ "Keluar" : function() {
										$('#dlg_confirm').dialog('close');
									}}).dialog('open');
								}
							}
						});
						$(this).dialog('close');
					}
				}
			}).html('').html('<?=$this->lang->line('confirm')?>').dialog('open');
		//}
		},
		focusInvalid: true,
		focusCleanup: true,
		highlight: function(element, errorClass) {
			$(element).addClass('ui-state-active');
		},
		unhighlight: function(element, errorClass) {
			$(element).removeClass('ui-state-active');
		},
		rules: {
			
		},
		messages: {
			
		}
	});

	$('.required').attr('title','*');
	$('.cek_jml').keyup(function(ev){
		var $item = $(this);
		var $crow_id = $item.attr('id');
		var $jml = $item.attr('jml');
		var $val = $item.val();
		var $propr = $item.attr('propr_id');
		var pro_pr_id = new Array();
		var row_id = $item.attr('row_id');
		pro_pr_id = $propr.split('_');
		$val = parseFloat($val.replace(',',''));
		if(isNaN($val)){
			$(this).val('');
		}
		else {
			if (Number($val) > Number($jml)) {
				$('#pro_id').val(pro_pr_id[0]);
				$('#pr_id').val(pro_pr_id[1]);
				$('#crow_id').val($crow_id);
				$('#dialog_auth').dialog('open');
				$(".ui-dialog-buttonpane button:first").css('float','right');
				$(".ui-dialog-buttonpane button:last").css('float','left');	
				return false;			
			}
			/*
			setTimeout(function(){
				if (Number($val) < Number($jml)) {
					$('#rows_id').val(row_id);
					$('#dlg_auth_c').dialog('open');
				}
				return false;
			},2000);	
			*/		
		}
		//alert(Number($val)+' '+Number($jml));
		return false;
	});

	var dlg_auth = $('#dialog_auth');
	dlg_auth.dialog({
		autoOpen:false,
		bgiFrame:true,
		modal:true,
		width:'auto',
		height:'auto',
		resizable:false,
		draggable:false,
		buttons : {
			'Batal' :function() {
				var $crow_id = '#'+$('#crow_id').val();
				$($crow_id).val('');
				dlg_auth.dialog('close');
				//return false;
			},
			'Konfirmasi' : function() {
				var $crow_id = $('#crow_id').val();
				//$('#dialog_auth').ajaxSubmit({
				$.ajax({
					url:'index.php/<?=$link_controller?>/cek_auth',
					type:'POST',
					data: $('#form_auth').serialize(),
					success:function(data){
						if (data) {
							var val = $('#'+$crow_id).val();
							if (Number(val) > Number(data)) {
								$('input#auth_no',dlg_auth).addClass('ui-state-error').val('Jumlah melebihi yg di tetapkan !!!').css('text-color','gray');
							}else{
								$('#'+$crow_id).val(data);
								dlg_auth.dialog('close');
							}
						}else {
							$('input#auth_no',dlg_auth).addClass('ui-state-error').val('Kode otorisasi salah !!!').css('text-color','gray');
						}
						return false;
					}
				});
				return false;
			}
		},
		close: function(event, ui) {
			$('input#auth_no',this).removeClass('ui-state-error').val('');
			return false;
		}
	}); 

	$('#dlg_confirm').dialog({
		autoOpen: false,
		bgiframe: true,
		width: 'auto',
		height: 'auto',
		resizable: false,
		draggable: false,
		modal: true,
		position: 'center',
		close : function() {
			$(this).dialog('close');
		}
	});

	$('#dlg_auth_c').dialog({
		autoOpen: false,
		bgiframe: true,
		width: 'auto',
		height: 'auto',
		resizable: false,
		draggable: false,
		modal: true,
		position: 'center',
		buttons : {
			"OK": function() {
				var get_row = $('#rows_id').val();
				var get_alasan = $('#info').val();
				$('#alasan_'+get_row).val(get_alasan);
				$('#otorisasi_view_'+get_row).text(get_alasan).css('color','red').show();
				$(this).dialog('close');
			},
			"Batal": function() {
				$('#info').val('');
				$('#rows_id').val('');
				$(this).dialog('close');
			}
		}
	});
	<?=remove_close_dialog(array('#dlg_confirm','#dialog_auth','#dlg_auth_c'))?>
});
</script>
<div id="dlg_auth_c" title="Konfirmasi">
	<input type="hidden" id="rows_id">
	<table border="0" celspan="0" colspan="0">
		<!-- tr><td valign="top">Jumlah</td><td valign="top">:</td><td valign="top"><input type="text" id="jmlh" name="jmlh"></td></tr-->
		<tr><td valign="top">Alasan</td><td valign="top">:</td><td valign="top"><textarea id="info" name="info" cols="5" rows="3"></textarea></td></tr>
	</table>
</div>
<div id="dlg_confirm" title="Konfirmasi"></div>
<div id="dialog_auth" title="Otorisasi">
	<form id="form_auth">
	<label for="auth_no">Kode Otorisasi</label>
	<input type="hidden" id="crow_id">
	<input type="hidden" name="pro_id" id="pro_id">
	<input type="hidden" name="pr_id" id="pr_id">
	<input type="text" name="auth_no" id="auth_no" onclick="">
	</form>
</div>
<h3>MENU TERIMA BARANG OLEH GUDANG : TERIMA UNTUK PO <?=$po_no?></h3>
<form id="form_entry"> <!-- action="index.php/entry_good_receive/save_bpb_gr" method="post" onsubmit="return performCheck('form_entry', rules, 'classic');"-->
<div class="ui-widget-content ui-corner-all">
<br>
<table align="center" width="99%"  border="0" cellspacing="2" cellpadding="2" >
  <tr>
    <td width="10%" class="ui-widget-header">No PO</td>
	<td width="5%" class="head_title">:</td>
    <td width="34%" class="head_title_content"><?=$po_no?></td>
    <td width="15%">&nbsp;</td>
    <td width="11%" class="ui-widget-header">Supplier</td>
	<td width="5%" class="head_title">:</td>
    <td width="30%" class="head_title_content">
    <?=$po_list->row()->sup_name?>
	<input type="hidden" name="sup_id" value="<?=$po_list->row()->sup_id?>">
	<td>
  </tr>
  <tr>
    <td class="ui-widget-header">Tgl PO</td>
	<td width="5%" class="head_title">:</td>
    <td class="head_title_content"><?=date_format(date_create($po_list->row()->po_date),'d-m-Y')?></td>
    <td>&nbsp;</td>
    <td></td>
    <td></td>
	<td></td>
  </tr>
  <tr>
    <td colspan="7">&nbsp;</td>
  </tr>
</table>
<table align="center" width="99%"  border="0" cellspacing="2" cellpadding="2">
  <tr>
   <td width="20%" valign="top" align="left" >Nomor Surat Jalan</td>
   <td width="5%" valign="top" align="left">:</td>
   <td width="23%" valign="top" align="left"><INPUT TYPE="text" NAME="no_sj" ID="no_sj" style="width:180px" autocomplete="off" class="required"></td>
   <td width="3%">&nbsp;</td>
   <td width="21%" valign="top" align="left">Nomor Kendaraan</td>
   <td width="5%" valign="top" align="left">:</td>
   <td width="30%" valign="top" align="left"><INPUT TYPE="text" NAME="no_kendaraan" ID="no_kendaraan" style="width:180px" autocomplete="off" class="required"></td>
  </tr>
  <tr>
   <td valign="top" align="left">Tgl Surat Jalan</td>
   <td valign="top" align="left">:</td>
   <td valign="top" align="left"><INPUT readonly="readonly" TYPE="text" NAME="tgl_sj" ID="tgl_sj" style="width:180px" class="kalender" class="required"></td>
   <td>&nbsp;</td>
   <td valign="top" align="left">Jenis Kendaraan</td>
   <td valign="top" align="left">:</td>
   <td valign="top" align="left">
     <SELECT NAME="jenis_kendaraan" ID="jenis_kendaraan" style="width:180px;" validate="required">
	   <option value="">--Pilih Jenis--</option>
	   <option value="motor">Motor</option>
	   <option value="mobil">Mobil</option>
	 </SELECT>
   </td>
  </tr>
  <tr>
   <td valign="top" align="left">Nama Supir</td>
   <td valign="top" align="left">:</td>
   <td valign="top" align="left"><INPUT TYPE="text" NAME="nama_supir" ID="nama_supir" style="width:180px" autocomplete="off" class="required"></td>
   <td>&nbsp;</td>
    <td valign="top" align="left">Kepemilikan Kendaraan</td>
   <td valign="top" align="left">:</td>
   <td valign="top" align="left">
     <SELECT NAME="milik_kendaraan" ID="milik_kendaraan" style="width:180px;" validate="required">
	   <option value="">--Pilih Kepemilikan--</option>
	   <option value="sewa">-sewa</option>
	   <option value="pribadi">-pribadi</option>
	 </SELECT>
   </td>
   </tr>
   <tr>
   <td valign="top" align="left">Nomor Identitas Supir</td>
   <td valign="top" align="left">:</td>
   <td valign="top" align="left"><INPUT TYPE="text" NAME="no_identitas" ID="no_identitas" style="width:180px" autocomplete="off" class="required"></td>
   <td>&nbsp;</td>
  
  </tr>
  <tr>
   <td colspan="7">&nbsp;</td>
  </tr>
</table>

<table align="center" width="99%"  border="0" cellpadding="1" cellspacing="1"  class="ui-widget-content ui-corner-all">
		<tr class="ui-widget-header">
		  <td width="5%" align="center">No</td>
		  <td width="45%" align="center">Nama Barang/Kode</td>
		  <td width="8%" align="center">Satuan</td>
		  <td width="8%" align="center">Pesan</td>
		  <td width="8%" align="center">Sisa</td>
		  <td width="18%" align="center">Terima</td>
	    </tr>
		<?php 
		$podet_no = 1;
		foreach ($po_det->result() as $row_podet):
		?>
		<tr bgcolor="lightgray">
		  <td valign="top" align="right" class="ui-state-active"><?=$podet_no?>.</td>
		  <td valign="top" align="left">
		    <?=$row_podet->pro_name?> (<?=$row_podet->pro_code?>)
		    <INPUT TYPE="hidden" name="pro_id_<?=$podet_no?>" value="<?=$row_podet->pro_id?>">
		  </td>
		  <td valign="top" align="center"><?=$row_podet->satuan_name?></td>
		  <td valign="top" align="right"><?=$row_podet->qty?></td>
		  <td valign="top" align="right"><?=$row_podet->qty_remain?></td>
		  <td valign="top">
		   <?php if ($row_podet->qty_remain > 0):?>
			<INPUT class="cek_jml required number" row_id=<?=$podet_no?> propr_id="<?=$row_podet->pro_id?>_<?=$row_podet->pr_id?>" jml="<?=$row_podet->qty_remain?>" TYPE="text" NAME="receive_<?=$podet_no?>" ID="receive_<?=$podet_no?>" style="width:180px">
		   <?php endif;?>
		   <div id="otorisasi_view_<?=$podet_no?>" style="display:none;overflow: auto;">
		   
		   </div>
		   <INPUT TYPE="hidden" name="pro_um_id_<?=$podet_no?>" value="<?=$row_podet->pro_um_id?>">
		   <INPUT TYPE="hidden" name="pr_um_id_<?=$podet_no?>" id="um_id_<?=$podet_no?>" value="<?=$row_podet->pr_um_id?>">
		   <INPUT TYPE="hidden" name="otorisasi_<?=$podet_no?>" id="otorisasi_<?=$podet_no?>" value="">
		   <INPUT TYPE="hidden" name="price_<?=$podet_no?>" id="price_<?=$podet_no?>" value="<?=$row_podet->price?>">
		   <INPUT TYPE="hidden" name="discount_<?=$podet_no?>" id="discount_<?=$podet_no?>" value="<?=$row_podet->discount?>">
		   <INPUT TYPE="hidden" name="cur_id_<?=$podet_no?>" id="cur_id_<?=$podet_no?>" value="<?=$row_podet->cur_id?>">
		   <INPUT TYPE="hidden" name="alasan_<?=$podet_no?>" id="alasan_<?=$podet_no?>">
		  </td>
		</tr>
		<?php 
		$podet_no++;
		endforeach;
		?>
		<tr>
		 <td colspan="7" align="center">&nbsp;</td>
		</tr>
		</table>
		<br>
		<div align="center">
		 <INPUT TYPE="hidden" name="po_id" value="<?=$po_list->row()->po_id?>">
		 <INPUT TYPE="hidden" name="jum_product" value="<?=$jum_produk?>">
		 <INPUT TYPE="submit" value="<?=$btn_save?>" id="saving">
		 <INPUT TYPE="Button" value="<?=$btn_back?>" onclick="location.href='index.php/<?=$link_controller?>/list_po_det/<?=$po_id?>/<?=$page_stats?>'">
		</div>
		<br>
</table>
</div>
</form>
