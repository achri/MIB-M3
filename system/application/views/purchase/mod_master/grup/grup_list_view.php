<link type="text/css" rel="stylesheet" href="<?=base_url()?>asset/javascript/jQuery/dynatree/skin-vista/ui.dynatree.css" /></link>
<script type="text/javascript" src="<?=base_url()?>asset/javascript/jQuery/dynatree/jquery.dynatree.js" ></script>

<script type='text/javascript'>
function editGroup() {
	var $ret = false;
	var $val = $('#cat_name').val(),
		$val_def = $('#cat_val').val();
		
	if ($val != $val_def) {
		$('#ubah_form').ajaxSubmit({
			url : 'index.php/<?php echo $link_controller_category;?>/cat_update/grup',
			data: $('#ubah_form').formSerialize(),
			type: 'POST',
			success: function(data) {
				if (data) {
					$('.dialog_informasi').html('').html('Grup berhasil diubah')
					.dialog('option','buttons',{
						'OK': function() {
							$('.dialog_informasi').dialog('close');
							$('#main_content').html(data);
							$ret = true;
						}
					})
					.dialog('open');
				}else{
					$('.dialog_informasi').html('').html('Grup gagal diubah !!!').dialog('open');
				}
			}
		});
	}
	return $ret;
}

function deleteGroup(id) {
	$("#dialog_hapus_confirm").dialog({
		autoOpen: false,
		modal: true,
		buttons: {
		'<?=$this->lang->line('jquery_button_close');?>': function() {
			$(this).dialog('close');
		},
		'OK' : function() {
			$.ajax({
				type: 'POST',
				url: 'index.php/<?php echo $link_controller;?>/grup_cek_delete',
				data: "id="+id,
				success: function(data) {
					if (data > 0){
						$('#dialog_hapus_error').dialog('open');
					}else{
						del = confirm("Delete Grup "+name+" ..?");
						if (del == true){
							$.ajax({
								type: 'POST',
								url: 'index.php/<?php echo $link_controller;?>/grup_delete',
								data: "code="+id,
								success: function(data) {
								$('#main_content').html(data);
								},
								dataType:"html"
							});
						}
					}
				}
			});
			$(this).dialog('close');
		}
	}
	}).dialog('open');
	return false;
}

$(document).ready(function() {
	$('#cat_name').css('text-transform','uppercase');
	
	$("#dialog_hapus_error").dialog({
		autoOpen: false,
		modal: true,
		buttons: {
			'<?=$this->lang->line('jquery_button_close');?>': function() {
				$(this).dialog('close');
			}
		}
	});
	
	$('#category_tree').dynatree({
		title: "KATEGORI",
	    rootVisible: true,
	    persist: false,
	    selectMode: 1,
	    keyboard: true,
	    autoFocus: false,
		activeVisible: true,
		//autoCollapse: true,
	    fx: { height: "toggle", duration: 200 },
	    onLazyRead: function(dtnode){
			dtnode.appendAjax({
				url: "index.php/<?php echo $link_controller;?>/dynatree_lazy/"+dtnode.data.key,
				data: {
					key: dtnode.data.key,
					mode: "branch"
				}
			});
	    },
		initAjax: {
			url: "index.php/<?php echo $link_controller;?>/dynatree_lazy",
			data: {
				key: "root",
				mode: "baseFolders"
			}
		},
		onActivate: function (dtnode) {
			$('#grup_detail').load('index.php/<?=$link_controller?>/grup_list/'+dtnode.data.key,function(data){
				$('#grup_detail').show();
			});
			return false;
		}
	});
	
});	

</script>

<div style="width:100%;display: table">
	<div style="width:50%;float: left;" class="ui-widget-content ui-corner-all">
		<div id="category_tree" style="overflow: auto;height: 343px;">
		</div>
	</div>
	<div id="grup_box" style="width:45%;height:343px;float: right;" class="ui-widget-content ui-corner-all">
		<div id="grup_detail" align="center">
			<?=br(8)?>
			<div class="ui-widget-content ui-corner-all" style="width:95%"><font color="red">PILIH GRUP</font></div>
		</div>
	</div>
</div>

<div id="dialog_hapus_confirm" title="KONFIRMASI" style="display:none">
	<p>Hapus Grup ???</p>
</div>

<div id="dialog_hapus_error" title="<?=($this->lang->line('jquery_dialog_peringatan'));?>">
	<p>Grup sudah digunakan tidak dapat dihapus</p>
</div>
