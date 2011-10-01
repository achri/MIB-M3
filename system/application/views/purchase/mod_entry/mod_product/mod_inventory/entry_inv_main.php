
<script language="javascript">

var	tot_tabs,tabs_idx = new Array();
var tabs_label,this_tab,this_status;
function tabs_awal() {	
	tot_tabs = 0;
	tabs_idx['PR']=0;
	tabs_idx['MR']=0;
	$('#tabs').tabs('select',0);
	
	$.getJSON('index.php/<?=$link_controller?>/cek_tabs',function(data) {
		$.each(data,function(ei,e) {
			if(e['PR']=='1'){
				tot_tabs = tot_tabs + 1;
				$('#tabs').tabs('add','index.php/<?=$link_controller?>/PRorMR/PR','<?=$this->lang->line('tab_pr')?>');// ( '+//e['PR_DATA']+' items)');
				tabs_idx['PR']=tot_tabs;
				$('#tabs').tabs('enable',tabs_idx['PR']);						
			}
			if(e['MR']=='1'){
				tot_tabs = tot_tabs + 1;
				$('#tabs').tabs('add','index.php/<?=$link_controller?>/PRorMR/MR','<?=$this->lang->line('tab_mr')?>');// ( '+//e['MR_DATA']+' items)');
				tabs_idx['MR']=tot_tabs;		
				$('#tabs').tabs('enable',tabs_idx['MR']);			
			}		
			return false;
		});
	});
	
	return false;
}

function addToPRMR(pro_id,PRorMR) {	
	$('#tabs').tabs('select',0);
	switch (PRorMR) {
		case 'PR' : 
			tabs_label = '<?=$this->lang->line('tab_pr')?>';
			break;
		case 'MR' : 
			tabs_label = '<?=$this->lang->line('tab_mr')?>';
			break;
	}
	$.ajax({
		url:'index.php/<?=$link_controller?>/savePRorMR/'+PRorMR+'/'+pro_id,
		type:'POST',
		success:function(data) {				
			if (data=='sukses') {
				if (tabs_idx[PRorMR] == 0 && tot_tabs <= 2){	
					tabs_idx[PRorMR] = tot_tabs + 1;					
					$('#tabs').tabs('add','index.php/<?=$link_controller?>/PRorMR/'+PRorMR,tabs_label,tabs_idx[PRorMR]);
					$('#tabs').tabs('select',tabs_idx[PRorMR]);
					tot_tabs = tot_tabs + 1;
					
				}else {
					
					$('#tabs').tabs('select',tabs_idx[PRorMR]);
				}
			}
			else if (data=='kosong') {
				//tabs_awal();
				//$('#tabs').tabs('select',tabs_idx[PRorMR]);
				var info = "Stok barang ini tidak tersedia untuk usul proses <font color='red' size='4pt'>MR</font>";
				$('#dlg_confirm').html('').html(info).dialog('open');				
				//$('#tabs').tabs('select',tabs_idx[PRorMR]);
			}
			else {
				//tabs_awal();
				$('#tabs').tabs('select',tabs_idx[PRorMR]);
				var info = "Barang sudah diusul untuk proses <font color='red' size='4pt'>"+PRorMR+"</font>";
				$('#dlg_confirm').html('').html(info).dialog('open');				
				//$('#tabs').tabs('select',tabs_idx[PRorMR]);
			}
			//alert(data);
			return false;
		}
	});
	return false;
}

function del_rows(PRorMR,row_no,prmr_id,pro_id) {

	$('.dialog_konfirmasi').dialog('option','buttons',{
		'<?=$dlg_btn_back?>' : function() {
			$(this).dialog('close');
		},
		'<?=$dlg_btn_ok?>' : function() {
			$.post('index.php/<?=$link_controller?>/del_prmr_row/'+PRorMR+'/'+prmr_id+'/'+pro_id, function(data) {

				if (data) {
					var num_row = $('.'+PRorMR+'_rows').length;
					if (num_row <= 1) {
						//tabs_awal();
						location.href = 'index.php/<?=$link_controller?>/index';
					}
					else {
						$('tr#'+PRorMR+'_row_'+row_no).remove();
					}
				}
			}); 		
			$(this).dialog('close');

			return false;
		}
	}).html('').html('<?=$dlg_info_delete?>').dialog('open');
	return false;
	
}

$(document).ready(function() {
	$('#tabs').tabs();
	tabs_awal();
	
	// SAVE HISTORY PR DAN MR
	$('#tabs').bind('tabsselect', function(event, ui) {
		var select = $(this).tabs('option', 'selected')
		if (select == this_tab) {
			//alert(select+'|'+this_tab+'|'+this_status);
			var form_usul = $('#'+this_status+'_form');
			unmasking('.number');
			form_usul.ajaxSubmit({
				type : 'POST'
				,url : 'index.php/<?=$link_controller?>/usul'+this_status
				,data: form_usul.formSerialize()
				,success : function(data) {
										
				}
			});
		}
	});
	
	$('.del_rows').css('cursor','pointer');
	
	$('.dialog_konfirmasi').dialog({
		title:'<?=$dlg_title_confirm?>',
		autoOpen: false,
		bgiframe: true,
		width: 'auto',
		height: 'auto',
		resizable: false,
		draggable: false,
		modal:true,
		position:['right','top']
	});
	
	$('div#dlg_confirm').dialog({
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
	
	$('div#supplier_add_dialog, div.dialog_notice').dialog({
		autoOpen: false,
		bgiframe: true,
		width: 'auto',
		minHeight: 'auto',
		maxHeight: 400,
		resizable: false,
		draggable: false,
		modal:true,
		position:'center',
		//show: 'drop',
		//hide: 'drop',
		buttons: {
			'<?=$dlg_btn_close?>': function() {
				$(this).dialog('close');
			}
		}
	});
});
</script>
<div id="dlg_confirm" title="INFORMASI"></div>
<div id="supplier_add_dialog" title="DAFTAR PEMASOK">
	<div id="supplier_add"></div>
</div>
<h2><?=$page_title?></h2>
<div id="tabs">
	<ul>
		<li><a href="#tabs-1"><span><?=$this->lang->line('tab_pro_search')?></span></a></li>
	</ul>
	<div id="tabs-1">
		<?=$this->load->view($link_view.'/entry_inv_tree')?>
	</div>
</div>
