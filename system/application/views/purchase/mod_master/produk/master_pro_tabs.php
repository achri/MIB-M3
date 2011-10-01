<script language="javascript">
		
function validation_info(item,iname,info,tabs) {
	item.addClass('ui-state-error');
	//alert(iname+' '+info);
	$('.informasi').html('').html('<b><font color="red">'+iname+' '+info+'</font></b>').dialog('open');
	$('#tabs_form').tabs('select',tabs);
	return false;
}

function validation_length(item,iname,min,max,info,tabs) {
	if ( item.val().length > max || item.val().length < min ) {
		validation_info(item,iname,info,tabs);
		return false;
	} else {
		return true;
	}
}

function validation_value(item,iname,val,info,tabs,tipe) {
	if (tipe == 'number') {
		if ( isNaN(item.val()) || item.val() <= 0 ) {
			validation_info(item,iname,info,tabs);
			return false;
		} else {
			return true;
		}
	}
	else
	{
		if ( item.val() == val ) {
			validation_info(item,iname,info,tabs);
			return false;
		} else {
			return true;
		}
	}
}

function cek_produk() {
	var pro_code = $('#pro_code').val();
	var pro_name = $('#pro_name').val();
	$.ajax({
		url: 'index.php/<?=$link_controller?>/produk_search/'+pro_name+'/'+pro_code,
		success : function(data) {
			if (data) {
				validation_info('pro_name','pro_name','Nama Produk',0);
			}
			else return true;
		}
	});
}

function validation_input() {
	var validate = true, max,min;
	$('form :input').each(function() {
		var req = $(this).hasClass('required');
		if (req) {
			var item = $(this);
			var iname = $(this).attr('name');
			
			switch (iname) {
				case 'pro_ids'  : validate = validate && validation_length(item,'Group Produk',3,3,'harus dipilih',0); break;
				case 'pro_name' : validate = validate && validation_length(item,'Nama Produk',3,200,'harus diisi min 3 karakter',0); break;
				case 'pro_type' : validate = validate && validation_value(item,'Tipe Produk',0,'harus dipilih',1); break;
				case 'um_id'    : validate = validate && validation_value(item,'Jenis Unit',0,'harus dipilih',2); break;
				case 'is_stockJoin'    : validate = validate && validation_value(item,'Kartu Stok Supplier',2,'harus general atau spesifik',3); break;
			}		
		}
			
	});
	
	validate = validate && calc_um_sat(); // um sub validation
	validate = validate && calc_supp(); // supplier validation
	//validate = validate && cek_produk();
	
	if (validate){
		return true;
	}
	else {
		return false;
	}
}

function tabs_save(stats) {
	clear_all();
	if (stats == 'INSERT') {
		if (validation_input()) {
			
			var pro_idcode = $('#pro_idcode').text(),
				cat_code = $('#cat_code').val();

			$('#pro_code').val(cat_code+'.'+pro_idcode);
			$('#saving').attr('disabled','disabled');
			$('#tabs').tabs('disable', 0); 
			
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
						$('#tabs').tabs('enable', 0); 
						$(this).dialog('close');
					},
					'<?=$this->lang->line('ok')?>' : function() {
						unmasking('.number');
						$('#produk_form').ajaxSubmit({
							type: 'POST',
							url: 'index.php/<?=$link_controller?>/produk_insert', 
							data : $('#produk_form').formSerialize(),
							cache:false,
							success: function (data) {
								var info;
								if (data) {
									info = '<strong>Selamat... Produk berhasil dibuat <br> Kode Produk : <font color="red">'+data+' </font></strong>';			//$('.informasi').html('').html(data);
									$('#dlg_confirm').html('').html(info).dialog('open');
									$('#tabs').tabs('enable', 0);
								}
								else {
									info = '<STRONG>Maaf... Data Produk Tidak Berhasil Ditambahkan</STRONG>';
									$('#dlg_confirm').html('').html(info).dialog('open');
								}
								//$('.informasi').html(data);		
							}	
						});
						$(this).dialog('close');
					}
				}
			}).html('').html('<?=$this->lang->line('confirm')?>').dialog('open');
		}
	}
	else {
		if (validation_input()) {
			
			var pro_idcode = $('#pro_idcode').text(),
				cat_code = $('#cat_code').val(),
				pro_id = $('#pro_id').val();

			$('#pro_code').val(cat_code+'.'+pro_idcode);
			
			$('#saving').attr('disabled','disabled');
			$('#tabs').tabs('disable', 0); 
		 	// KONFIRMASI
			$('.dialog_konfirmasi').dialog({
				title:'KONFIRMASI',
				autoOpen: false,
				bgiframe: true,
				width: 'auto',
				height: 'auto',
				resizable: false,
				//draggable: false,
				//modal:true,
				position:['right','top'],
				buttons : { 
					'<?=$this->lang->line('back')?>' : function() {
						$('#saving').attr('disabled',false);
						$('#tabs').tabs('enable', 0); 
						$(this).dialog('close');
					},
					'<?=$this->lang->line('ok')?>' : function() {
						unmasking('.number');
						$('#produk_form').ajaxSubmit({
							type: 'POST',
							url: 'index.php/<?=$link_controller?>/produk_edit/'+pro_id, 
							cache:false,
							data : $('#produk_form').formSerialize(),
							success: function (data) {
								var info;
								if (data) {
									info = '<strong>Selamat... Produk berhasil diubah <br> Kode Produk : <font color="red">'+data+' </font></strong>';			//$('.informasi').html('').html(data);
									$('#dlg_confirm').html('').html(info).dialog('option','buttons',{
										"<?=$this->lang->line('ok')?>" : function() {
											$('#tabs').tabs('enable', 0);
											clear_all();
											tabs_add_cancel();
											$('#tabs').tabs('select',1);
											$('#product_list').flexReload();
											$('#dlg_confirm').dialog('close'); 
										}
									}).dialog('open');
									$('#tabs').tabs('enable', 0);
								}
								else {
									info = '<STRONG>Maaf... Data Produk Tidak Berhasil Ditambahkan</STRONG>';
									$('#dlg_confirm').html('').html(info).dialog('open');
								}

								//$('.informasi').html(data);	
							},
							error: function() {alert('wew');}
						});	
						$(this).dialog('close');
					}
				}
			}).html('').html('<?=$this->lang->line('confirm')?>').dialog('open');
		}
	}
	return false;
}

function tabs_next() {
	var this_tab = $("#tabs_form")
	var tab_pos = this_tab.tabs('option', 'selected');
	this_tab.tabs( 'select' , tab_pos + 1 );
}

function clear_all() {
	$('form *').each(function() {
		var item = $(this);
		item.removeClass('ui-state-error');
	});
	return false;
}

function level_clear() {
	$('#lv1_code').html('-');
	$('#lv1_name').html('-');
	$('#lv2_code').html('-');
	$('#lv2_name').html('-');
	$('#lv3_code').html('-');
	$('#lv3_name').html('-');
	$('#pro_idcode').html('-');
	$('#cat_id').val('');
	//$('#pro_ids').val('');
	return false;
}

function get_json(id) {
	$.getJSON('index.php/<?=$link_controller?>/produk_node_catid/'+id, function(data) {
		$.each(data, function(entryIndex, entry) {
			
			$('#lv1_code').text(entry['lv1_code']);
			$('#lv1_name').text(entry['lv1_name']);

			$('#lv2_code').text(entry['lv2_code']);
			$('#lv2_name').text(entry['lv2_name']);

			$('#lv3_code').text(entry['lv3_code']);
			$('#cat_code').val(entry['lv3_catcode']);
			$('#lv3_name').text(entry['lv3_name']);

			//$('#pro_idcode').text(entry['pro_idcode']);
			$('#pro_ids').val(entry['pro_idcode']);
			$('#cat_id').val(entry['cat_id']);
		});
	});
	return false;
}

$(document).ready(function() {
	$(document).ajaxStop($.unblockUI);
	
	$('.required').blur(function(){
		$(this).removeClass('ui-state-error');
	});

	masking('.number');
	var a = $('#tabs_form'),$tabs = $('#tabs_form').tabs();
	$tabs.bind('tabsselect', function(event, ui) {
		if (ui.index == 3) {
			$('#saving').show();
			$('#next').hide();
		}else {
			$('#saving').hide();
			$('#next').show();
		}
	});
	
	// BLOCK IT
	var block_opt = {
		message: null,
		overlayCSS:  {
			backgroundColor: '#fff',
			opacity:	  	 0,
			cursor:		  	 'inherit'
		}
	}	
	<?php 
	if ($status == 'EDIT'):
		if ($pro_data->row()->pro_status == 'active'):
	?>
		a.tabs('select',1);
		//a.data('disabled.tabs',[0,2,3]);
		$('#saving').show();
		$('#next').hide();
		
		$('div#treeb').block(block_opt);
		$('div#satuan').block(block_opt);
		$('div#suplier').block(block_opt);
	<?php 
		endif;
	endif;
	?>

});
</script>
<form id='produk_form'>
<div id="tabs_form">
	<ul>
		<li><a href="#general"><?=$this->lang->line('produk_general_tab')?></a></li>
		<li><a href="#pro_detail"><?=$this->lang->line('produk_detail_tab')?></a></li>
		<li><a href="#satuan"><?=$this->lang->line('produk_satuan_tab')?></a></li>
		<li><a href="#suplier"><?=$this->lang->line('produk_supplier_tab')?></a></li>
	</ul>
	
	<!-- GENERAL FORM -->
	<div id="general">
		<?=$this->load->view($link_view.'/master_pro_general')?>
	</div>
	
	<!-- DETAIL -->
	<div id="pro_detail">
		<?=$this->load->view($link_view.'/master_pro_detail')?>
	</div>
	
	<!-- SATUAN -->
	<div id="satuan">
		<?=$this->load->view($link_view.'/master_pro_satuan')?>
	</div>
	
	<!-- SUPLIER -->
	<div id="suplier">
		<?=$this->load->view($link_view.'/master_pro_supplier')?>
	</div>
</div>
<br>
<div align="center" class="ui-widget-content ui-corner-all">
<input type="button" onclick="tabs_save('<?=$status?>');" value="<?=$this->lang->line(strtolower($status))?>" id="saving" style="display:none">
<input type="button" onclick="tabs_next();" value="<?=$this->lang->line('next')?>" id="next">
<input type="button" onclick="tabs_add_cancel();" value="<?=$this->lang->line('cancel')?>">
</div>
</form>