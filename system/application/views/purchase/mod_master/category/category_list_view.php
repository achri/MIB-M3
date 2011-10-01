<link type="text/css" rel="stylesheet" href="<?=base_url()?>asset/javascript/jQuery/dynatree/skin-vista/ui.dynatree.css" /></link>
<script type="text/javascript" src="<?=base_url()?>asset/javascript/jQuery/dynatree/jquery.dynatree.js" ></script>

<script type='text/javascript'>
function editCat() {
	var $ubah,$ret = false;
	var $val = $('#cat_name').val(),
		$val_def = $('#cat_val').val();
		
	if ($val != $val_def) {
		$('#ubah_form').ajaxSubmit({
			url : 'index.php/<?php echo $link_controller;?>/cat_update/kategori',
			data: $('#ubah_form').formSerialize(),
			type: 'POST',
			success: function(data) {
				if (data) {
					$ubah = '<?php echo ($this->lang->line('kategori_update_ok'));?> <br><strong>'+$val+'</strong>';
					$('.dialog_informasi').html('').html($ubah)
					.dialog('option','buttons',{
						'OK': function() {
							$('.dialog_informasi').dialog('close');
							$('#main_content').html(data);
							$ret = true;
						}
					})
					.dialog('open');
				}else{
					$('.dialog_informasi').html('').html('<?php echo ($this->lang->line('kategori_update_error'));?>').dialog('open');
				}
			}
		});
	}
	return $ret;
}

function deleteCat(id) {
	$.ajax({
		type: 'POST',
		url: 'index.php/<?php echo $link_controller;?>/cat_cek_delete/'+id,
		success: function(data) {
			if (data){
				$('#dialog_hapus_error').dialog('open');
			}else{
				del = 'Hapus Kategori '+name+' ..?';
				$('.dialog_konfirmasi').html('').html(del).dialog('option','buttons',{
					'Batal': function() {
						$(this).dialog('close');
					},
					'OK': function() {
						$.ajax({
							type: 'POST',
							url: 'index.php/<?php echo $link_controller?>/cat_delete',
							data: "code="+id,
							success: function(data) {
								if (data) {
									$('#main_content').html(data);
									$('.dialog_konfirmasi').dialog('close');
								}
							},
							dataType:"html"
						});
					}
				}).dialog('open');
			}
		}
	});
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
	
	$('#cat_tree').dynatree({
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
				url: "index.php/<?=$link_controller?>/dynatree_lazy/"+dtnode.data.key,
				data: {
					key: dtnode.data.key,
					mode: "branch"
				}
			});
	    },
		initAjax: {
			url: "index.php/<?=$link_controller?>/dynatree_lazy",
			data: {
				key: "root",
				mode: "baseFolders"
			}
		},
		onActivate: function (dtnode) {
			$('#kat_detail').load('index.php/<?=$link_controller?>/class_list/'+dtnode.data.key,function(data){
				$('#kat_detail').show();
			});
			return false;
		}
	});
	
});	

</script>

<div style="width:100%;display: table">
	<div style="width:50%;float: left;" class="ui-widget-content ui-corner-all">
		<div id="cat_tree" style="overflow: auto;height: 343px;">
		</div>
	</div>
	<div id="kat_box" style="width:45%;height:343px;float: right;" class="ui-widget-content ui-corner-all">
		<div id="kat_detail" align="center">
			<?=br(8)?>
			<div class="ui-widget-content ui-corner-all" style="width:95%"><font color="red"><?php echo ($this->lang->line('kategori_pilih'));?></font></div>
		</div>
	</div>
</div>

<div id="dialog_hapus_confirm" title="KONFIRMASI" style="display:none">
	<p><?php echo ($this->lang->line('kategori_delete_konfirmasi'));?></p>
</div>

<div id="dialog_hapus_error" title="INFORMASI">
	<p><?php echo ($this->lang->line('kategori_delete_use'));?></p>
</div>