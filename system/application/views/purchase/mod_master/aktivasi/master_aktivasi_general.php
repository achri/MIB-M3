<script language="javascript">
<?=set_calendar('.kalender','null','dd-mm-yy',0);?>
$(document).ready(function() {
	masking('.number');
	//$('form#isjoin').validate({
		//submitHandler: function(form) {
	//$('#saving').attr('disabled',true);
	//$('#tabs').attr('disable',0);
	$('form#isjoin').submit(function(){
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
						$('form#isjoin').ajaxSubmit({
							type : 'POST'
							,url : 'index.php/<?=$link_controller?>/inventory_save/isjoin'
							,data: $('form#isjoin').formSerialize()
							,success : function(data) {
								//alert(data);
								var info;	
								if(data) {
									info = '<strong>Selamat... Produk berhasil di Aktivasi <br> Produk kode :<font color="red"> '+data+' </font></strong>';
									$('#dlg_confirm').text('').append(info).dialog('open');
									$('.saving').attr('disabled',false);
									$('#tabs').attr('enable',0);
									//alert(data);
									//$('.informasi').html(data);
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
		/*
		},
		focusInvalid: true,
		focusCleanup: true,
		highlight: function(element, errorClass) {
			$(element).addClass('ui-state-error');
		},
		unhighlight: function(element, errorClass) {
			$(element).removeClass('ui-state-error');
		}				
	});
	*/
});

</script>
<form id="isjoin" class="cmxform validasi_form">
<?php if ($pro_data->num_rows() > 0):?>
<input type="hidden" name="pro_id" value="<?=$pro_id?>">
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
<!--div class="ui-widget-content ui-corner-all"-->
<table border="0" width="100%" align="center" class="ui-widget-content ui-corner-all">
<tr><td width="44%" align="right"><?=$this->lang->line('date_min')?></td>
<td width="1%">:</td>
<td td width="44%">
<input id="tanggal" name="inv_transDate" class="required kalender" title="<?=$this->lang->line('date_min')?>" readonly="readonly" size="10">
</td>
</tr>
<tr><td align="right"><?=$this->lang->line('saldo')?></td>
<td>:</td>
<td><input digit_decimal="<?=$digit_satuan?>" id="saldo" name="saldo" class="required number kosong" title="<?=$this->lang->line('saldo')?>" size="10">&nbsp;<B><?//=$satuan_name?></B><!-- input type="text" value="</?=$satuan_name?>" disabled="disabled" size="10"--></td></tr>

</table>
<!--/div-->
<br>
<div align="center">
<input type="submit" value="<?=$this->lang->line('save')?>" class="saving">
<?php endif;?>
<input type="button" value="<?=$this->lang->line('cancel')?>" onclick="location.href='index.php/<?=$link_controller?>/index'">
</div>
</form>