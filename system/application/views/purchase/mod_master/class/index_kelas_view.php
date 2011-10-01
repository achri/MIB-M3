<script type="text/javascript">
	$(function(){
		// Tabs
		$('#tabs').tabs({cache: false});
		var $dlg_info = $('.dialog_konfirmasi');
		$dlg_info.dialog({
			autoOpen: false,
			bgiframe: true,
			width: 'auto',
			height: 'auto',
			resizable: false,
			//draggable: false,
			modal:true,
			position:['right','top'],
			//position:'center',
			buttons : { 
				'OK': function() {
					$(this).dialog('close');
				}
			}
		});
	});
</script>
		<!-- Tabs -->
		<h2><?php echo ($this->lang->line('kelas_judul_halaman'));?></h2>
		<div id="tabs">
			<ul>
				<li><a href="<?php echo base_url(); ?>index.php/<?php echo $link_controller;?>/class_tree"><?php echo ($this->lang->line('kelas_tab_daftar'));?></a></li>
				<li><a href="<?php echo base_url(); ?>index.php/<?php echo $link_controller;?>/kelas_frm"><?php echo ($this->lang->line('kelas_tab_tambah'));?></a></li>
			</ul>
		</div>