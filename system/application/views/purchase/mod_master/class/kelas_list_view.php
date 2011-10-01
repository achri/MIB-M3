<link type="text/css" rel="stylesheet" href="<?=base_url()?>asset/javascript/jQuery/dynatree/skin-vista/ui.dynatree.css" /></link>
<script type="text/javascript" src="<?=base_url()?>asset/javascript/jQuery/dynatree/jquery.dynatree.js" ></script>

<script type='text/javascript'>
function editClass() {
	var $ret = false;
	var $val = $('#cat_name').val(),
		$val_def = $('#cat_val').val();
		
	if ($val != $val_def) {
		$('#ubah_form').ajaxSubmit({
			url : 'index.php/<?php echo $link_controller_category;?>/cat_update/kelas',
			data: $('#ubah_form').formSerialize(),
			type: 'POST',
			success: function(data) {
				if (data) {
					$('.dialog_informasi').html('').html('<?php echo($this->lang->line('kelas_update_ok'));?>')
					.dialog('option','buttons',{
						'OK': function() {
							$('.dialog_informasi').dialog('close');
							$('#main_content').html(data);
							$ret = true;
						}
					})
					.dialog('open');
				}else{
					$('.dialog_informasi').html('').html('<?php echo($this->lang->line('kelas_update_error'));?>').dialog('open');
				}
			}
		});
	}else{
		//$('.dialog_informasi').html('').html('Nama Kelas masih sama !!!').dialog('open');
	}
	return $ret;
}

function deleteClass(id) {
	$.ajax({
		type: 'POST',
		url: 'index.php/<?php echo $link_controller_category;?>/cat_cek_delete/'+id,
		success: function(data) {
			if (data){
				$('#dialog_hapus_error').dialog('open');
			}else{
				del = 'Hapus Kelas '+name+' ..?';
				$('.dialog_konfirmasi').html('').html(del).dialog('option','buttons',{
					'Batal': function() {
						$(this).dialog('close');
					},
					'OK': function() {
						$.ajax({
							type: 'POST',
							url: 'index.php/<?=$link_controller?>/kelas_delete',
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
	
	$('#class_tree').dynatree({
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
			$('#kelas_detail').load('index.php/<?=$link_controller?>/class_list/'+dtnode.data.key,function(data){
				$('#kelas_detail').show();
			});
			return false;
		}
	});
	
});	

</script>

<div style="width:100%;display: table">
	<div style="width:50%;float: left;" class="ui-widget-content ui-corner-all">
		<div id="class_tree" style="overflow: auto;height: 343px;">
		</div>
	</div>
	<div id="kelas_box" style="width:45%;height:343px;float: right;" class="ui-widget-content ui-corner-all">
		<div id="kelas_detail" align="center">
			<?=br(8)?>
			<div class="ui-widget-content ui-corner-all" style="width:95%"><font color="red"><?php echo($this->lang->line('kelas_pilih'));?></font></div>
		</div>
	</div>
</div>

<div id="dialog_hapus_confirm" title="KONFIRMASI" style="display:none">
	<p><?php echo($this->lang->line('kelas_hapus_konfirmasi'));?> ???</p>
</div>

<div id="dialog_hapus_error" title="INFORMASI">
	<p><?php echo($this->lang->line('kelas_hapus_one'));?></p>
</div>