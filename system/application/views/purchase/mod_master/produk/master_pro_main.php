<script type="text/javascript">
var tab_stat;

function tabs_awal() {
	$('#tabs').tabs();
	$('#tabs').tabs('add' ,'index.php/<?=$link_controller?>/produk_add_search','<?=$this->lang->line('produk_tambah_tab')?>',1);
	$('#tabs').tabs('select',0);
	$('#product_list').flexReload();
	return false;
}

function tabs_edit(id) {
	$('#tabs').tabs('select',0);
	$('#tabs').tabs('remove',1);
	$('#tabs').tabs( 'add' ,'index.php/<?=$link_controller?>/produk_add_tabs/EDIT/'+id ,'<?=$this->lang->line('produk_edit_tab')?>',1);	
	$('#tabs').tabs('select',1);
	return false;
}

function tabs_add_cancel() {
	$('#tabs').tabs('select',0);
	$('#tabs').tabs('remove',1);
	$('#tabs').tabs('add' ,'index.php/<?=$link_controller?>/produk_add_search','<?=$this->lang->line('produk_tambah_tab')?>',1);
	$('#tabs').tabs('load',1);
	return false;
}

function tabs_search_close() {
	$('#tabs').tabs( 'select',0);
	$('#tabs').tabs( 'remove',2);
	return false;
}

function add_supp(sup_row_id) {
	var cat_parent = $('#lv1_code').text();
	if (cat_parent != '') {
		$('#supplier_add').load('index.php/<?=$link_controller?>/produk_supp_add/'+sup_row_id+'/'+cat_parent,function() {
			$('div#supplier_add_dialog').dialog('open');
		});
	}
	else {
		//alert('General Category belum dipilih');
		var infos = "Pilih kategori produk terlebih dahulu !!!";
		$('#dlg_peringatan').html('').html(infos).dialog('open');
	}
	return false;
}

$(document).ready(function (){
	// Tabs
	tabs_awal();

	$('div#supplier_add_dialog').dialog({
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
			'Keluar': function() {
				$(this).dialog('close');
			}
		}
	});
	
	$('#dlg_confirm').dialog({
		autoOpen: false,
		bgiframe: true,
		width: 'auto',
		height: 'auto',
		resizable: false,
		draggable: false,
		modal:true,
		position:'center',
		buttons : { 
			"KELUAR" : function() {
				location.href = 'index.php/<?=$link_controller?>/index';
		    	clear_all();
				tabs_add_cancel();
				$('#tabs').tabs('select',0);
				$('#product_list').flexReload();
				$('#dlg_confirm').dialog('close');
			}
		}
	});
	
	$('.informasi').dialog({
		title: 'INFORMASI',
		autoOpen: false,
		bgiframe: true,
		width: 'auto',
		height: 'auto',
		resizable: false,
		draggable: false,
		modal:true,
		position:'center',
		buttons : { 
			"KELUAR" : function() {
				$(this).dialog('close');
			}
		}
	});
	
	$('#dlg_peringatan').dialog({
		autoOpen: false,
		bgiframe: true,
		width: 'auto',
		height: 'auto',
		resizable: false,
		draggable: false,
		modal:true,
		position:'center',
		buttons : { 
			"KELUAR" : function() {
				$(this).dialog('close');
				$('#tabs_form').tabs('select',0);
			}
		}
	});
	
	//$('#tabs').bind('tabsselect', function(event, ui) {
		//var tab_select = $('#tabs').tabs('option', 'selected');
		/*var deselectable = $('#tabs').tabs('option', 'deselectable');
	
		if (deselectable == 1) {
			tabs_add_cancel();
		}*/
		//return false;
	//});
	

});

</script>

<style type="text/css">
.ui-selecting {color: red}
.ui-selected {color : blue}
</style>

<div id="supplier_add_dialog" title="TAMBAH PEMASOK">
	<div id="supplier_add"></div>
</div>

<div id="dlg_peringatan" title="PERINGATAN"></div>
<div id="dlg_confirm" title="INFORMASI"></div>
<div id="dlg_confirm_del" title="KONFIRMASI"></div>
	<!-- Tabs -->
	<h2><?=$page_title?></h2>
	<div id="tabs">
		<ul>
			<li><a href="#tabs1"><span><?=$this->lang->line('produk_general_tab')?></span></a></li>
		</ul>
		<div id="tabs1">
			<?=$this->load->view($link_view.'/master_pro_product_list')?>
		</div>
	</div>