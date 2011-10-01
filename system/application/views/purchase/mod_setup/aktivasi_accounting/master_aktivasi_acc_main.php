<script language="javascript">
function clear() {
	$('#pro_id').val('');
	$('#is_join').val('');
}
function tabs_awal() {
	//$('#tabs').tabs('destroy');
	$('#tabs').tabs();
	//$('#tabs').tabs('add' ,'index.php/setup/aktivasi_inventory/aktivasi_tree','General',0);
	$('#tabs').tabs('enable',0);
	$('#tabs').tabs('select',0);
	$('#tabs').tabs('remove',1);
	$('#pro_name').val('');
	$('#pro_code').val('');
	$('#setup').attr('disabled',false);
	$('#product_list').flexReload();
	clear();
	return false;
}

function tabs_setup(is_join,pro_id) {
	if (is_join=='1'){
		$('#tabs').tabs('add' ,'index.php/<?=$link_controller?>/join/'+pro_id,'<?=$this->lang->line('tabs3')?>',1);
		$('#tabs').tabs('select',1);
	}else {
		$('#tabs').tabs('add' ,'index.php/<?=$link_controller?>/notjoin/'+pro_id,'<?=$this->lang->line('tabs2')?>',1);
		$('#tabs').tabs('select',1);
	}
	$('#tabs').tabs('disable',0);
}

$(document).ready(function() {
	//$('#tabs').tabs();
	tabs_awal();

	$('#dlg_confirm').dialog({
		autoOpen: false,
		bgiframe: true,
		width: 'auto',
		height: 'auto',
		resizable: false,
		draggable: false,
		modal:true,
		position:'center',
		//show: 'drop',
		//hide: 'drop',
		buttons: {
			"Keluar" : function() {
				//tabs_awal();
				location.href = 'index.php/<?=$link_controller?>/index';
				$('#dlg_confirm').dialog('close');
				return false;
			}
		}
	/*,
		close: function(ev) {
				$(this).dialog('close');
				tabs_awal();
				return false;
		}*/	
	});
});
</script>
<div id="dlg_confirm" title="INFORMASI"></div>
<h2><?=$page_title?></h2>
<div id="tabs">
	<ul>
		<li><a href="#tabs1"><span><?=$this->lang->line('tabs1')?></span></a></li>
	</ul>
	
	<div id="tabs1"><?=$this->load->view($link_view.'/master_aktivasi_acc_tree')?></div>
</div>