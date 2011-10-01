<script language="javascript">
//$('#setup').attr('disabled',false);
$(document).ready(function(){
	$('#aktivasi_tree').dynatree({
		select: true,
		title: "<?=$this->lang->line('tree_root_category')?>",
	    rootVisible: true, // Set to true, to make the root node visible.
	    minExpandLevel: 1, // 1: root node is not collapsible
	   	//imagePath: null, // Path to a folder containing icons. Defaults to 'skin/' subdirectory.
	    //children: null, // Init tree structure from this object array.
	    //initId: null, // Init tree structure from a <ul> element with this ID.
	    //initAjax: null, // Ajax options used to initialize the tree strucuture.
	    autoFocus: true, // Set focus to first child, when expanding or lazy-loading.
	    keyboard: true, // Sup	port keyboard navigation.
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
			$('#aktivasi_produk').load('index.php/<?=$link_controller?>/inventory_list/'+dtnode.data.key,function(){
				$('#aktivasi_box').show();
			});
			return false;				
		},
		onDeactivate: function(dtnode) {
			$('#aktivasi_box').hide();
			return false;	
		},
		onExpand: function() {}
		
	});

	//$("#aktivasi_tree").dynatree("getTree").getNodeByKey("all").select();
	//$("#aktivasi_tree").dynatree("getTree").selectKey("all");

	$('#setup').click(function() {
		var pro_id = $('#pro_id').val();
		var joins = $('#is_join').val();
		//if (joins >= 0 && joins <= 1 && joins!=''){
		if (joins != '' && pro_id != ''){
			$('#setup').attr('disabled',true);
			tabs_setup(joins,pro_id);
		}else{
			alert('Pilih produk yg akan di setup');
		}
	});

	$('#pro_name').focus().autocomplete('index.php/<?=$link_controller?>/list_autocomplate/name',{
		minChars: 3,
		matchCase: true,
		max: 100
	}).result(function(event,item) {
		$('#pro_code').val(item[1]);
		$('#pro_id').val(item[2]);
		$('#is_join').val(item[3]);
	}).css('text-transform','uppercase');

	$('#pro_code').autocomplete('index.php/<?=$link_controller?>/list_autocomplate/code',{
		minChars: 3,
		matchCase: true,
		max: 100
	}).result(function(event,item) {
		$('#pro_name').val(item[1]);
		$('#pro_id').val(item[2]);
		$('#is_join').val(item[3]);
	}).css('text-transform','uppercase');

	$('#aktivasi_box').show();

	$('#Reset').click(function(){
		$('#pro_code').val('');
		$('#pro_name').val('');
		$('#pro_id').val('');
	});
});
</script>
<div style="width:100%;display: table">
	<div style="width:39%;float: left;">
		<div id="aktivasi_tree" class="ui-widget-content ui-corner-all" style="overflow: auto;height: 232px;"></div>
		<br>
		<div class="ui-widget-content ui-corner-all">
		<table align="center">
		<tr>
			<td><?=$this->lang->line('pro_name')?></td><td>:</td>
			<td>
			<input type="text" id="pro_name" class="pro_auto_name" name="pro_name">
			<input type="hidden" id="pro_id">
			</td>
		</tr>
		<tr>
			<td><?=$this->lang->line('pro_code')?></td><td>:</td>
			<td>
			<input type="text" id="pro_code" class="pro_auto_code pro_mask" name="pro_code">
			<input type="hidden" id="is_join">
			</td>
		</tr>
		<tr>
			<td colspan="3" align="center">
			<input type="button" value="<?=$this->lang->line('setup')?>" id="setup">&nbsp;<input type="reset" id="Reset" value="<?=$this->lang->line('clear')?>"></td>
		</tr>
		</table>
		</div>
	</div>
	<div id="aktivasi_box" style="width:59%;height:343px;float: right; display: none;" class="ui-widget-content ui-corner-all">
		<div id="aktivasi_produk">
		<?=br(8)?>
		<div align="center"><font color="red">PILIH KATEGORI DAN PRODUK YANG AKAN DI AKTIVASI</font></div>
		</div>
	</div>
</div>
