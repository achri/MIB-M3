<script language="javascript">
$(document).ready(function() {
	masking('.number');
	masking_currency('.curr_select','.number');
	var form = $('form#isjoin');
	$(form).submit(function(){
		if (validasi('form#isjoin')) {
		$('.saving').attr('disabled',true);
		$('#tabs').attr('disable',0);
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
					$('.saving').attr('disabled',false);
					$('#tabs').attr('enable',0);
					$(this).dialog('close');
				},
				'<?=$this->lang->line('ok')?>' : function() {
					unmasking('.number');
					$(form).ajaxSubmit({
						type : 'POST'
						,url : 'index.php/<?=$link_controller?>/inventory_save/isjoin'
						,data: $(form).formSerialize()
						,success : function(data) {
							var info;	
							if(data) {
								info = '<strong>Selamat... Produk berhasil di Aktivasi <br> Produk kode :<font color="red"> '+data+' </font></strong>';
								$('#dlg_confirm').text('').append(info).dialog('open');
								$('.saving').attr('disabled',false);
								$('#tabs').attr('enable',0);
							}else {
								info = '<STRONG>Maaf... Data Produk Tidak Berhasil di Aktivasi</STRONG>';
								$('#dlg_confirm').text('').append(info).dialog('open');
								$('.saving').attr('disabled',false);
								$('#tabs').attr('enable',0);
							}
							return false;
						}
					});
					$(this).dialog('close');
				}
			}
		}).html('').html('<?=$this->lang->line('confirm')?>').dialog('open');
		}
		return false;
				
	});

});

</script>
<?php $rows = $get_stok->row()?>
<form id="isjoin" class="cmxform validasi_form">
<?php if ($pro_data->num_rows() > 0):?>
<input type="hidden" name="pro_id" value="<?=$pro_id?>">
<input type="hidden" name="inv_id" value="<?=$rows->inv_id?>">
<table border="0" width="100%">
<tr><td width="15%"><?=$this->lang->line('tree_root_category')?></td><td width="1%">:</td><td><?=$cat_name?></td><td rowspan="4" width="40%" align="center">
<div id="photo_prev" class="ui-corner-all ui-widget-content" style="width:130px;height:80%;padding:5px;overflow:auto;">
<?=$this->pictures->thumbs($pro_data->row()->pro_id,126,126)?>
</div>
</td></tr>
<tr><td><?=$this->lang->line('pro_code')?></td><td>:</td><td><?=$pro_data->row()->pro_code?></td></tr>
<tr><td><?=$this->lang->line('pro_name')?></td><td>:</td><td><?=$pro_data->row()->pro_name?></td></tr>
<tr><td><?=$this->lang->line('satuan')?></td><td>:</td><td><?=$satuan_name?></td></tr>
</table>
<br>
<div class="ui-widget-content ui-corner-all">
<table border="0" width="70%" align="center">
<tr>
<td width="15%"><?=$this->lang->line('date_min')?></td><td width="1%">:</td>
<td>
<?=$rows->date_setup?>
<input type="hidden" id="tanggal" name="inv_transDate" class="validasi kalender required" title="<?=$this->lang->line('date_min')?>" readonly="readonly" size="10" value="<?=$rows->date_setup?>"></td>
</tr>
<tr>
<td><?=$this->lang->line('saldo')?></td><td>:</td>
<td>
<?=$this->general->digit_number($sat_id,$rows->inv_begin)?>
<input type="hidden" digit_decimal="<?=$digit_satuan?>" id="saldo" name="saldo" class="validasi required number kosong" title="<?=$this->lang->line('saldo')?>" size="10" value="<?=$rows->inv_begin?>" readonly>&nbsp;<B><?//=$satuan_name?></B><!-- input type="text" value="</?=$satuan_name?>" disabled="disabled" size="10"--></td>
</tr>
<!--tr>
<td><?//=$this->lang->line('opname')?></td><td>:</td><td>
<input id="saldo_opname" name="saldo_opname" class="validasi required number" title="<?//=$this->lang->line('opname')?>" size="10" value="<?//=$this->general->digit_number($sat_id,$rows->inv_end)?>">&nbsp;<B><?//=$satuan_name?></B><input type="text" value="</?=$satuan_name?>" disabled="disabled" size="10"></td>
</tr-->
<tr><td><?=$this->lang->line('price')?></td><td>:</td>
<td width="20%">
<select input_id="harga" id="cur_id" name="cur_id" class="validasi required curr_select" title="<?=$this->lang->line('cur')?>" style="width:112px" readonly>
<option value=""><?=$this->lang->line('selection')?></option>
<?php if ($curr_data->num_rows()>0):
	foreach ($curr_data->result() as $rows):
		echo '<option value="'.$rows->cur_id.'">'.$rows->cur_symbol.'.</option>';
	endforeach;
endif;?>
</select> 
<input id="harga" name="inv_price" class="validasi required number kosong currency" title="<?=$this->lang->line('price')?>" style="width:100px">
/ <?=$this->lang->line('satuan')?>
</td></tr>
</table>
</div>
<br>
<div align="center">
<input type="submit" value="<?=$this->lang->line('save')?>" class="saving">
<?php endif;?>
<input type="button" value="<?=$this->lang->line('cancel')?>" onclick="location.href='index.php/<?=$link_controller?>/index'">
</div>
</form>