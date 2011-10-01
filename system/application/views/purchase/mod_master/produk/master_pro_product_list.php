<script type="text/javascript">
function test(com,grid)
{	
	if (com=="<?=$this->lang->line('produk_table_edit')?>")
	{
		var items = $('.trSelected',grid);
		var row_id = items[0].id.substr(3);
		if(($('.trSelected',grid).length!=0)&&(row_id!=0)){
			if($('.trSelected',grid).length==1){
		        var id ='';
		        for(i=0;i<items.length;i++){
					id = items[i].id.substr(3);
				}
		        tabs_edit(id);
			 }else{
				alert('Hanya 1 Produk yg diperbolehkan !!!');
			 }
		}else{
			alert('Pilih produk yg akan di edit !!!');
		}
	}else
	
    if (com=="<?=$this->lang->line('produk_selectall')?>")
    {
		$('.bDiv tbody tr',grid).addClass('trSelected');
    }else
    
    if (com=="<?=$this->lang->line('produk_deselectall')?>")
    {
		$('.bDiv tbody tr',grid).removeClass('trSelected');
    }else
    
    if (com=="<?=$this->lang->line('produk_table_hapus')?>")
        {
    		var items = $('.trSelected',grid);
    		var row_id = items[0].id.substr(3);
          	if(($('.trSelected',grid).length>0)&&(row_id!=0)){
			   if(confirm('Delete ' + $('.trSelected',grid).length + ' items?')){
		            var itemlist ='';
		        	for(i=0;i<items.length;i++){
						itemlist+= items[i].id.substr(3)+",";
					}
					
					$.ajax({
					   type: "POST",
					   url: "<?=site_url("/".$link_controller."/flexigrid_delete_ajax");?>",
					   data: "items="+itemlist,
					   success: function(data){
					   	$('#product_list').flexReload();
					  	alert(data);
					   }
					});
					return false;
				}
			} else {
				alert('Pilih produk yg akan di hapus !!!');
			} 
        }    
} 

function pro_del(id,name) {
	//if(confirm('Delete product id ' + id + ' ?')){
	
	var info = 'Data produk <font color="red">'+name+'</font> <br> akan di hapus ???';
	$('#dlg_confirm_del').html('').html(info).dialog('option','buttons',{
			"<?=$this->lang->line('cancel')?>": function() {
				$(this).dialog('close');
			},
			"<?=$this->lang->line('agree')?>": function() {
				$.ajax({
					type:'POST',
					url:'index.php/<?=$link_controller?>/produk_delete/'+id,
					success: function(data) {
						if (data) {
							var info = 'Data produk <font color="red">'+name+'</font> <br> berhasil di hapus ...';
							$('#dlg_confirm_del').html('').html(info).dialog('option','buttons',{
								"<?=$this->lang->line('ok')?>": function() {
									$(this).dialog('close');
									$('#product_list').flexReload();
								}
							}).dialog('open');
						} else {
							var info = 'Data produk <font color="red">'+name+'</font> <br> tidak dapat di hapus !!! <br> karena sudah di aktivasi';
							$('#dlg_confirm_del').html('').html(info).dialog('option','buttons',{
								"<?=$this->lang->line('ok')?>": function() {
									$(this).dialog('close');
								}
							}).dialog('open');
						}
					}
				});
				$(this).dialog('close');
			}
	}).dialog('open');
	
	//alert(id+''+name);
	//return false;
	//}
}

function flexEdit(celDiv,id) {
	$(celDiv).click(function(){
		$('> span',this).editable('index.php/<?=$link_controller?>/produk_name_change',{
			indicator : '<img src="asset/img_source/spinner.gif">',
			tooltip   : 'Click to edit...',
			width : '97%',
			height : 'auto'
		}); 
		
	});
	return false;
}

$(document).ready(function(){
	$('#dlg_confirm_del').dialog({
		autoOpen: false,
		bgiframe: true,
		width: 'auto',
		height: 'auto',
		resizable: false,
		draggable: false,
		modal:true,
		position:'center'
	});
});

</script>
<?php
echo $js_grid;
?>
<table id="product_list" style="display:none" class=""></table>