
<script language="javascript">
function clear() {
	$('#pro_id').val('');
	$('#is_join').val('');
}
function tabs_awal() {
	$('#tabs').tabs();
	$('#tabs').tabs('enable',0);
	$('#tabs').tabs('select',0);
	$('#tabs').tabs('remove',1);
	$('#pro_name').val('');
	$('#pro_code').val('');
	$('#setup').attr('disabled',false);
	$('#product_list').flexReload();
	clear();

	$.ajax({
		url:'index.php/<?=$link_controller?>/cek_tabs/',
		type:'POST',
		success:function(data) {				
			if (data) {				
				$('#tabs').tabs('add','index.php/<?=$link_controller?>/listAdjustment/','Daftar Penyesuaian',1);
			}
			else {
				
			}
			return false;
		}
	});
	
	return false;
}

function save_adjustment(kartu_stok,pro_id) {
	switch (kartu_stok) {
	case "spesifik" :  
		$('#form_suppliers').ajaxSubmit({
			url:'index.php/<?=$link_controller?>/saveAdjustment/'+kartu_stok+'/'+pro_id,
			type:'POST',
			success:function(data) {						
				if (data) {		
					var tab_idx = $('#tabs').tabs('length');
					if (tab_idx <= 1){		
						$('#tabs').tabs('add','index.php/<?=$link_controller?>/listAdjustment/','Daftar Penyesuaian',1);
					}
					$('#tabs').tabs('select',1);		
					//$('#tabs').tabs('disable',0);	
				}
				else {
					info = "<strong><font color='red'>Barang sudah terdaftar di proses</font></strong>";
					$('#dlg_confirm').text('').append(info).dialog('open');
				}
				return false;
			}
		});
		break;
	case "general" :
		$.ajax({
			url:'index.php/<?=$link_controller?>/saveAdjustment/'+kartu_stok+'/'+pro_id,
			type:'POST',
			success:function(data) {				
				if (data) {		
					var tab_idx = $('#tabs').tabs('length');
					if (tab_idx <= 1){		
						$('#tabs').tabs('add','index.php/<?=$link_controller?>/listAdjustment/','Daftar Penyesuaian',1);
					}
					$('#tabs').tabs('select',1);		
					//$('#tabs').tabs('disable',0);	
				}
				else {
					info = "<strong><font color='red'>Barang sudah terdaftar di proses ...</font></strong>";
					$('#dlg_confirm').text('').append(info).dialog('open');
				}
				return false;
			}
		});
		break;
	}
	return false;
}

function tabs_adjustment(pro_id) {
	var count_sup;
	$.ajax({
		url:'index.php/<?=$link_controller?>/cek_stokjoin/'+pro_id,
		type:'POST',
		success:function(data) {
			if (data) {
				$('#dlg_suppliers').html('').html(data).dialog('option','buttons',{
					"<?=$this->lang->line('cancel')?>": function() {
						$(this).dialog('close');
						$('#setup').attr('disabled',false);
					},
					"<?=$this->lang->line('select')?>": function() {
						count_sup = $('.sup_rows:checked').length;
						if (count_sup > 0) {
							save_adjustment('spesifik',pro_id);
							$(this).dialog('close');
						}else {
							info = '<STRONG><font color="red">Pilih minimal satu Supplier!!!</font></STRONG>';
							$('#dlg_confirm').html('').html(info).dialog('option','buttons',{
								"<?=$this->lang->line('ok')?>": function() {
									$(this).dialog('close');
									$('#setup').attr('disabled',false);
								}
							}).dialog('open');
						}
					}
				}).dialog('open');
			} else {
				save_adjustment('general',pro_id);
			}
		}
	});
	return false;
}

$(document).ready(function() {
	tabs_awal();

	$('#dlg_confirm, #dlg_suppliers').dialog({
		autoOpen: false,
		bgiframe: true,
		width: 'auto',
		height: 'auto',
		resizable: false,
		draggable: false,
		modal:true,
		position:'center',
		buttons: {
			"<?=$this->lang->line('close')?>" : function() {
				$(this).dialog('close');
				$('#setup').attr('disabled',false);
				return false;
			}
		}
	});
});
</script>
<div id="dlg_confirm" title="Konfirmasi"></div>
<div id="dlg_suppliers" title="Pilih Supplier"></div>
<h2><?=$page_title?></h2>
<div id="tabs">
	<ul>
		<li><a href="#tabs1"><span><?=$this->lang->line('tabs1')?></span></a></li>
	</ul>
	
	<div id="tabs1"><?=$this->load->view($link_view.'/master_adj_tree')?></div>
</div>