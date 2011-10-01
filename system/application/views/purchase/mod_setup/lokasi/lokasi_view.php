<script type="text/javascript">
	$(function(){
		// Tabs
		$('#tabs').tabs();
	
		$('.dlg_lokasi').dialog({
			bgiFrame: true,
			autoOpen: false,
			resizable : false,
			draggable: false,
			width: 'auto',
			height: 'auto',
			buttons: {
				"KELUAR" : function() {
					$(this).dialog('close');
				}
			}
		});
	
	});
	
</script>
	<div class="dlg_lokasi"></div>
		<!-- Tabs -->
		<h2>MENU TAMBAH LOKASI<?php //echo ($this->lang->line('bank_judul_halaman')); ?></h2>
		<div id="tabs">
			<ul>
				<li><a href="index.php/<?php echo $link_controller;?>/frm_daftar">DAFTAR<?php //echo ($this->lang->line('bank_tab_daftar')); ?></a></li>
				<li><a href="index.php/<?php echo $link_controller;?>/frm_negara">NEGARA<?php //echo ($this->lang->line('bank_tab_tambah')); ?></a></li>
				<li><a href="index.php/<?php echo $link_controller;?>/frm_provinsi">PROVINSI<?php //echo ($this->lang->line('bank_tab_daftar')); ?></a></li>
				<li><a href="index.php/<?php echo $link_controller;?>/frm_kota">KOTA<?php //echo ($this->lang->line('bank_tab_tambah')); ?></a></li>
			</ul>
			<div id="view"></div>
		</div>