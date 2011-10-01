
<script language="javascript">
var	tot_tabs,tabs_idx = new Array();
var tabs_label;
function tabs_awal() {	
	tot_tabs = 0;
	tabs_idx['SR']=0;
	$('#tabs').tabs('select',0);
	
	$.getJSON('index.php/<?=$link_controller?>/cek_tabs',function(data) {
		$.each(data,function(ei,e) {
			if(e['SR']=='1'){
				tot_tabs = tot_tabs + 1;
				$('#tabs').tabs('add','index.php/<?=$link_controller?>/PRorMR/SR','<?=$this->lang->line('tab_sr')?>');// ( '+//e['PR_DATA']+' items)');
				tabs_idx['PR']=tot_tabs;
				$('#tabs').tabs('enable',tabs_idx['SR']);						
			}	
			return false;
		});
	});
	
	return false;
}

function addToPRMR(pro_id,PRorMR) {	
	$('#tabs').tabs('select',0);
	switch (PRorMR) {
		case 'SR' : 
			tabs_label = '<?=$this->lang->line('tab_sr')?>';
			break;
	}
	$.ajax({
		url:'index.php/<?=$link_controller?>/savePRorMR/'+PRorMR+'/'+pro_id,
		type:'POST',
		success:function(data) {				
			if (data) {
				if (tabs_idx[PRorMR] == 0 && tot_tabs <= 2){	
					tabs_idx[PRorMR] = tot_tabs + 1;					
					$('#tabs').tabs('add','index.php/<?=$link_controller?>/PRorMR/'+PRorMR,tabs_label,tabs_idx[PRorMR]);
					$('#tabs').tabs('select',tabs_idx[PRorMR]);
					tot_tabs = tot_tabs + 1;
					
				}else {
					
					$('#tabs').tabs('select',tabs_idx[PRorMR]);
				}
			}
			else {
				//tabs_awal();
				var info = "Barang sudah terdaftar di proses <font color='red' size='4pt'>"+PRorMR+"</font>";
				$('#dlg_confirm').html('').html(info).dialog('open');				
				//$('#tabs').tabs('select',tabs_idx[PRorMR]);
			}
			//alert(data);
			return false;
		}
	});
	return false;
}

function del_rows(PRorMR,row_id) { 
	$('#'+PRorMR+'_table > tr').each(function(){
		var a = $(this).attr('id');
		//alert(a);
	});
}

$(document).ready(function() {
	$('#tabs').tabs();
	tabs_awal();
	
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
});
</script>
<div id="dlg_confirm" title="INFORMASI"></div>
<h2><?=$page_title?></h2>
<div id="tabs">
	<ul>
		<li><a href="#tabs-1"><span><?=$this->lang->line('tab_pro_search')?></span></a></li>
	</ul>
	<div id="tabs-1">
		<?=$this->load->view($link_view.'/entry_inv_tree')?>
	</div>
</div>
