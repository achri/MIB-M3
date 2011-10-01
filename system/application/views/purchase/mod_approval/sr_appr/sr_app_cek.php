<script type="text/javascript">
var status_id;
$(function() {
	$('.lock').attr('disabled',true);
		$("#alasan,#result").dialog({
			autoOpen: false,
			modal: true,
			bgiframe: false,
			width: 'auto',
			height: 'auto',
			resizable: false,
			draggable: false
		});

		$('#app_sr').submit(function() {
		if (validasi('#app_sr')) {
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
						$('#app_sr').ajaxSubmit({
							type: 'POST',
							url: 'index.php/<?=$link_controller?>/sr_add',
							data: $('#app_sr').formSerialize(),
							success: function(data) {
								$('#restext').html(data);
								$('#result').dialog('option','buttons',{
									'OK': function() {
										$(this).dialog('close');
										location.href='index.php/<?=$link_controller?>/index';
									}
								}).dialog('open');
							}
						});
					}
				}
			}).html('').html('<?=$this->lang->line('confirm')?>').dialog('open');
		}
			return false;
		});
});

// KEMBALIKAN NILAI KE ORIGINAL
function original(id) {
	for (eq=0;eq <= 4;eq++) {
		$('.lock_'+id+':eq('+eq+')').val($('.original_'+id+':eq('+eq+')').val());
	}
	return false;
}

// BERSIHKAN NOTE DAN KUNCI ITEM
function clean(id) {
	$('#view_note_'+id).html('');
	$('#sr_note_'+id).val('');
	$('.lock_'+id).attr('disabled',true);
	return false;
}

function openalasan(id){
	var status = $('#sr_status_'+id).val();
	
	if (status == 2 || status == 3 ||  status == 4) {
		$('#notes').val('');
		$('#alasan').dialog('option','buttons',{
			'Batal': function() {
				// BERSIHKAN
				$('#sr_status_'+id).val(0);
				clean(id);
				$(this).dialog('close');
			},
			'Simpan': function() {
				// JIKA DIUBAH BUKA ITEM
				if (status == 2) {
					$('.lock_'+id).attr('disabled',false);
				}else {
					original(id);
					$('.lock_'+id).attr('disabled',true);
				}
				// SIMPAN DAN TAMPILKAN NOTE
				var note = $('#notes').val();
				$('#view_note_'+id).html(note);
				$('#sr_note_'+id).val(note);
				$(this).dialog('close');
			}
		}).dialog('open');
	}
	else {
		original(id);
		clean(id);
	}	
	return false;
}

function closedialog(){
	$('#result').dialog('close');
}

function batal(){
	window.location = 'index.php/<?=$link_controller?>/index';
}
</script>

<?php 
	$hdrcont = $get_sr['head']->row();
	if ($hdrcont->plan_id == 1){
		$hdrcont->plan_id = 'Sesuai Hari Ketentuan';
	}else{
		$hdrcont->plan_id = 'Tidak Sesuai Hari Ketentuan';
	}
?>
<form id="app_sr">
<center>
<div class="ui-corner-all headers">
<table>
	<tr>
		<td class="labelcell" width="100"><?php echo $this->lang->line('sr_label_nosr');?></td>
		<td class="labelcell2">: <?php echo $hdrcont->sr_no;?> <input type="hidden" name="sr_id" value="<?php echo $hdrcont->sr_id; ?>"></td>
		<td width="20%"></td>
		<td class="labelcell" width="100"><?php echo $this->lang->line('sr_label_dep');?></td>
		<td class="labelcell2">: <?php echo $hdrcont->dep_name;?></td>
	</tr>
	<tr>
		<td class="labelcell"><?php echo $this->lang->line('sr_label_tgl');?></td>
		<td class="labelcell2">: <?php echo $hdrcont->srdate;?></td>
		<td></td>
		<td class="labelcell"><?php echo $this->lang->line('sr_label_pemohon');?></td>
		<td class="labelcell2">: <?php echo $hdrcont->usr_name;?></td>
	</tr>
	<tr>
		<td class="labelcell"><?php echo $this->lang->line('sr_label_status');?></td>
		<td class="labelcell2">: <?php echo $hdrcont->plan_id;?></td>
		<td></td>
		<td></td>
		<td></td>
	</tr>
</table>
</div>
<br>
	<table width="91%"  border="0" cellpadding="2" cellspacing="1" id="dataview" class="table">
		<tr bgcolor="#CCCCCC" class='ui-widget-header' align="center">
		  <td width="20%" align="center">Status</td>
		  <td width="20%" align="center">Tipe</td>
		  <td width="25%" align="center">Kuantitas</td>
		  <td width="35%" align="center">Nama Produk (Kode)</td>
	    </tr>
		<?
		$i = 1;
		foreach ($get_sr['detail']->result() as $dtlcont):
		?>
		<tr class='x' baris="<?=$i?>">
		  <td valign="top" align="left">
			  <SELECT class="required" name='sr_status[<?=$i?>]' id='sr_status_<?=$dtlcont->pro_id?>' onchange="openalasan('<?=$dtlcont->pro_id?>')" title="Status">
					<option value=''>-[Pilih Status]-</option>
					<option value='1'>-Disetujui</option>
					<option value='2'>-Diubah & disetujui</option>
					<option value='3'>-Disetujui Dgn Catatan</option>
					<option value='4'>-Ditunda</option>
					<option value='5'>-Ditolak</option>
			  </SELECT> <br />
			  <div id='view_note_<?=$dtlcont->pro_id?>' style='width: 150px; color: red;'></div>
			  <INPUT TYPE="hidden" name="sr_note[<?=$i?>]" id="sr_note_<?=$dtlcont->pro_id?>" value="">
		  </td>
		  <td valign="top">
		    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="">
			 <tr>
			  <td>Kategori</td>
			  <td>:</td>
			  <td>
				<select class="lock lock_<?=$dtlcont->pro_id?>" name="sr_type[<?=$i?>]" id="sr_type_<?=$dtlcont->pro_id?>" style="width:100px;">
				<option value="maintain" <?=($dtlcont->service_type=='maintain')?('selected'):('')?>>-Perawatan</option>
				<option value="repair" <?=($dtlcont->service_type=='repair')?('selected'):('')?>>-Perbaikan</option>
				</select>
			  </td>
			 </tr>
			 <tr>
			  <td>Tipe</td>
			  <td>:</td>
			  <td><SELECT class="lock lock_<?=$dtlcont->pro_id?>" name="sr_cat[<?=$i?>]" ID="sr_cat_<?=$dtlcont->pro_id?>" style="width:100px;">
				<option value="0">--Pilih Kategori Service--</option>
				<option value="in" <?=($dtlcont->service_cat=='in')?('selected'):('')?>>-Inhouse</option>
				<option value="out" <?=($dtlcont->service_cat=='out')?('selected'):('')?>>-Outside</option>
			 </SELECT></td>
			 </tr>
		   </table>    			   
		  </td>
		  <td valign="top">
			<INPUT class="required number lock lock_<?=$dtlcont->pro_id?>" TYPE="text" name="sr_qty[<?=$i?>]" ID="sr_qty_<?=$dtlcont->pro_id?>" value="<?=number_format($dtlcont->qty,$dtlcont->satuan_format)?>" style="width:80px;" title="Kuantitas"> 
			  <select class="lock lock_<?=$dtlcont->pro_id?>" name='sr_um[<?=$i?>]' id='sr_um_<?=$dtlcont->pro_id?>'>
				<option value="<?=$dtlcont->satuan_id?>"><?=$dtlcont->satuan_name?></option>
				<?
				$satpro = $this->tbl_satuan_pro->get_satuan($dtlcont->pro_id);
							if ($satpro->num_rows() > 0):
								foreach ($satpro->result() as $sat): 
									echo "<option value=".$sat->satuan_id.">".$sat->satuan_name."</option>";
								endforeach;
							else:
								echo "Empty";
							endif;
				?>
			</select> <br />
			  Jumlah Pemasok : <INPUT class="required number lock lock_<?=$dtlcont->pro_id?>" TYPE="text" name="sr_sup[<?=$i?>]" ID="sr_sup_<?=$dtlcont->pro_id?>" value="3" style="width:50px;" title="Jumlah Pemasok">
		  </td>
		  <td valign="top" align="left">
		   <span class="text_barang"><?=$dtlcont->pro_name?></span><br />
		   <strong>Kode :&nbsp;</strong><span class="text_kode"><?=$dtlcont->pro_code?></span> <br />
		   <? if ($dtlcont->description != ''):?>
		   <strong>Keterangan : </strong><?=$dtlcont->description?> <br />
		   <? endif;?>
		   
		   <INPUT TYPE="hidden" id="sr_id" name="sr_id" value="<?=$dtlcont->sr_id?>">
		   <INPUT TYPE="hidden" id="sr_pro_id" name="pro_id[<?=$i?>]" value="<?=$dtlcont->pro_id?>">
		   <INPUT TYPE="hidden" id="sr_pro_name" name="pro_name[<?=$i?>]" value="<?=$dtlcont->pro_name?>">
		   <INPUT class="original_<?=$dtlcont->pro_id?>" TYPE="hidden" id="sr_type_org_<?=$dtlcont->pro_id?>" name="sr_type_org[<?=$i?>]" value="<?=$dtlcont->service_type?>">
		   <INPUT class="original_<?=$dtlcont->pro_id?>" TYPE="hidden" id="sr_cat_org_<?=$dtlcont->pro_id?>" name="sr_cat_org[<?=$i?>]" value="<?=$dtlcont->service_cat?>">
		   <INPUT class="original_<?=$dtlcont->pro_id?>" TYPE="hidden" id="sr_qty_org_<?=$dtlcont->pro_id?>" name="sr_qty_org[<?=$i?>]" value="<?=number_format($dtlcont->qty,$dtlcont->satuan_format)?>">
		   <INPUT class="original_<?=$dtlcont->pro_id?>" TYPE="hidden" id="sr_um_org_<?=$dtlcont->pro_id?>" name="sr_um_org[<?=$i?>]" value="<?=$dtlcont->satuan_id?>">
		   <INPUT class="original_<?=$dtlcont->pro_id?>" TYPE="hidden" ID="sr_sup_org_<?=$dtlcont->pro_id?>" NAME="sr_sup_org[<?=$i?>]" value="3">
		   <INPUT class="original_<?=$dtlcont->pro_id?>" TYPE="hidden" id="sr_desc_org_<?=$dtlcont->pro_id?>" name="sr_desc[<?=$i?>]" value="<?=$dtlcont->description?>">
		  </td>
	    </tr>
		<? 
		$i++;
		endforeach;
		?>
    </table>

<br>
<input type="submit" value="<?php echo $this->lang->line('sr_button_submit');?>" id="saving">
<input type="button" value="<?php echo $this->lang->line('sr_button_batal');?>" onclick="batal()">
</form>
</center>

<div id="alasan" title="Isi Alasan">
	<p><textarea id="notes" cols="30" rows="5"></textarea></p>
</div>

<div id="result" title="konfirmasi">
	<div id="restext" style="text-align : left"></div>
</div>

<div id="history" title="History">
</div>