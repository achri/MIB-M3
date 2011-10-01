<script language="javascript">
$(document).ready(function(){
	$('#trans_product_tree').dynatree({
		title: "<?=$this->lang->line('tree_root_category')?>",
	    rootVisible: true, // Set to true, to make the root node visible.
	    minExpandLevel: 1, // 1: root node is not collapsible
	   	//imagePath: null, // Path to a folder containing icons. Defaults to 'skin/' subdirectory.
	    //children: null, // Init tree structure from this object array.
	    //initId: null, // Init tree structure from a <ul> element with this ID.
	    //initAjax: null, // Ajax options used to initialize the tree strucuture.
	    autoFocus: true, // Set focus to first child, when expanding or lazy-loading.
	    keyboard: true, // Support keyboard navigation.
	    persist: false, // Persist expand-status to a cookie
	    autoCollapse: true, // Automatically collapse all siblings, when a node is expanded.
	    clickFolderMode: 2, // 1:activate, 2:expand, 3:activate and expand
	    //activeVisible: false, // Make sure, active nodes are visible (expanded).
	    checkbox: false, // Show checkboxes.
	    selectMode: 1, // 1:single, 2:multi, 3:multi-hier
	    fx: { height: "toggle", duration: 200 },
	    strings: {
	        loading: "<?=$this->lang->line('tree_loading')?>",
	        loadError: "<?=$this->lang->line('tree_error')?>"
	    },
	    onLazyRead: function(dtnode){
			dtnode.appendAjax({
				url: "index.php/<?=$link_controller?>/treecat_node/"+dtnode.data.key,
				
				data: {
					key: dtnode.data.key,
					mode: "funnyMode"
				}
			});
	    },
		initAjax: {
			url: "index.php/<?=$link_controller?>/treecat_root",
			data: {
				key: "root",
				mode: "all"
			}	
		},
		onActivate: function(dtnode) {
			
			$('#inv_produk').load('index.php/<?=$link_controller?>/product_list/'+dtnode.data.key,function(data){
				$('#trans_product_box').show();
				//alert(data);
			});
			
			//alert(dtnode.data);
			return false;				
		},
		onDeactivate: function(dtnode) {
			$('#trans_product_box').hide();
			return false;	
		}
	});
/*
	$('#setup').click(function() {
		var pro_id = $('#pro_id').val();
		var joins = $('#is_join').val();
		if (joins >= 0 && joins <= 1 && joins!=''){
			tabs_setup(joins,pro_id);
		}else{
			$('#dlg_confirm').html('').html('Pilih produk yg akan di setup').dialog('open');
		}
	});
*/
	$('#trans_dialog').dialog({
		autoOpen:false,
		bgiframe:true,
		width:'auto',
		height:'auto',
		modal:true,
		resizable:false,
		draggable:true,
		position:['left','top'],
		//show:'drop',
		//hide:'drop',
		buttons: {
			"<?=$this->lang->line('close')?>" : function(){
				$(this).dialog('close');
			},
			"<?=$this->lang->line('add_pr')?>" : function() {
				var pro_id = $(this).find('input').val();
				addToPRMR(pro_id,'PR');
				$(this).dialog('close');
			},
			"<?=$this->lang->line('add_mr')?>" : function() {
				var pro_id = $(this).find('input').val();
				addToPRMR(pro_id,'MR');
				$(this).dialog('close');
			}
		},
		beforeclose: function(){
			var trans_history = $('#trans_history_dialog');
			if (trans_history.dialog('isOpen')){
				trans_history.dialog('close');
			}
		}
	});

	$('#trans_history_dialog').dialog({
		autoOpen:false,
		bgiframe:true,
		width:'auto',
		height:'auto',
		resizable:false,
		draggable:true,
		position:['right','top'],
		//show:'clip',
		//hide:'clip',
		dialogClass: 'alert',
		buttons: {
			"<?=$this->lang->line('close')?>" : function() {
				$(this).dialog('close');
			}
		}
	});
	
	$('#Search').click(function(){
		var val_id = $('#pro_id').val();
		
		if (val_id!='') {
			$('#trans_page').load('index.php/<?=$link_controller?>/kartu_stok/'+val_id,function(){
				$('#trans_dialog').dialog('open');
			});
		}else {
			$('#dlg_confirm').html('').html('Pilih produk dulu ...').dialog('open');
		}
		return false;
	});

	$('#Reset').click(function(){
		$('#pro_code').val('');
		$('#pro_name').val('');
		$('#pro_id').val('');
		$('#is_join').val('');
	});
	
	//$('.pro_mask').mask("99.99.99.9999",{placeholder:"_"});
	$('#pro_name').focus().autocomplete('index.php/<?=$link_controller?>/list_autocomplate/name',{
		minChars: 3,
		matchCase: true,
		max: 500
	}).result(function(event,item) {
		$('#pro_code').val(item[1]);
		$('#pro_id').val(item[2]);
	}).css('text-transform','uppercase');

	$('#pro_code').autocomplete('index.php/<?=$link_controller?>/list_autocomplate/code',{
		minChars: 3,
		matchCase: true,
		max: 500
	}).result(function(event,item) {
		$('#pro_name').val(item[1]);
		$('#pro_id').val(item[2]);
	}).css('text-transform','uppercase');

	$('#trans_product_box').show();
});

var ret = false;
function cek_val() {
	var val_name = $('#pro_name').val();
	var val_id = $('#pro_code').val();
	if (val_name != '' || val_id != '') {
		ret = true;
		
	}else {
		$('#dlg_confirm').html('').html('Pilih produk dulu ...').dialog('open');
	}
	return ret;
}

function pre_history(pro_id,sup_id,doc){
	//$("#history").click(function(){
		$('#trans_history').load('index.php/<?=$link_controller?>/product_history/'+pro_id+'/'+sup_id+'/'+doc,function(){
			$('#trans_history_dialog').dialog('open');
		});
	//});
	return false;
}
</script>
<div id="trans_dialog" title="<?=$this->lang->line('dialog_stock')?>">
	<div id="trans_page"></div>
</div>
<div id="trans_history_dialog" style="text-align: left" title="<?=$this->lang->line('dialog_history')?>">
	<div id="trans_history" style="text-align: left"></div>
</div>
<div style="width:100%;display: table">
	<div style="width:39%;float: left;">
		<div id="trans_product_tree" class="ui-widget-content ui-corner-all" style="overflow: auto;height: 232px;"></div>
		<br>
		<div class="ui-widget-content ui-corner-all">
		<table align="center">
		<tr>
			<td><?=$this->lang->line('inv_pro_name')?></td><td>:</td>
			<td>
			<input type="text" id="pro_name" class="pro_auto_name" name="pro_name">
			<input type="hidden" id="pro_id">
			</td>
		</tr>
		<tr>
			<td><?=$this->lang->line('inv_pro_code')?></td><td>:</td>
			<td>
			<input type="text" id="pro_code" class="pro_auto_code pro_mask" name="pro_code">
			<input type="hidden" id="is_join">
			</td>
		</tr>
		<tr>
			<td colspan="3" align="center"><input type="button" value="<?=$this->lang->line('search')?>" id="Search">&nbsp;<input type="button" id="Reset" value="<?=$this->lang->line('clear')?>"></td>
		</tr>
		</table>
		</div>
	</div>
	<div id="trans_product_box" style="width:59%;height:343px;float: right; display: none;" class="ui-widget-content ui-corner-all">
		<div id="inv_produk">
		<?=br(8)?>
		<div align="center"><font color="red">PILIH KATEGORI DAN PRODUK</font></div>
		</div>
	</div>
</div>