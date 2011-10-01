
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
//alert(pro_id);

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
		//alert(data);
			
			if (data == 'sukses') {
				if (tabs_idx[PRorMR] == 0 && tot_tabs <= 1){	
					tabs_idx[PRorMR] = tot_tabs + 1;					
					$('#tabs').tabs('add','index.php/<?=$link_controller?>/PRorMR/'+PRorMR,tabs_label,tabs_idx[PRorMR]);
					$('#tabs').tabs('select',tabs_idx[PRorMR]);
					tot_tabs = tot_tabs + 1;
					
				}else {
					
					$('#tabs').tabs('select',tabs_idx[PRorMR]);
				}
			}
			else if (data == 'one') {
				//tabs_awal();
				var info = "Barang yang diservis hanya diperbolehkan 1 item saja";
				$('#dlg_confirm').html('').html(info).dialog('open');				
				//$('#tabs').tabs('select',tabs_idx[PRorMR]);
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

function del_rows(PRorMR,row_no,prmr_id,pro_id) {

	$('.dialog_info').dialog('option','buttons',{
		'<?=$dlg_btn_back?>' : function() {
			$(this).dialog('close');
		},
		'<?=$dlg_btn_ok?>' : function() {
			$.post('index.php/<?=$link_controller?>/del_prmr_row/'+prmr_id+'/'+pro_id, function(data) {

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
	
	$('.dialog_info').dialog({
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
});
</script>
<div id="dlg_confirm" title="INFORMASI"></div>
<div id="dlg_info" title="INFORMASI"></div>
<h2><?=$page_title?></h2>
<div id="tabs">
	<ul>
		<li><a href="#tabs-1"><span><?=$this->lang->line('tab_pro_search')?></span></a></li>
	</ul>
	<div id="tabs-1">
		<?=$this->load->view($link_view.'/service_inv_tree')?>
	</div>
</div>
