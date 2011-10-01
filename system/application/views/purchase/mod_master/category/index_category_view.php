<script type="text/javascript">

$(document).ready(function (){
	$('#tabs').tabs();
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
		<h2><?php echo ($this->lang->line('kategori_judul_halaman'));?></h2>
		<div id="tabs">
			<ul>
				<li><a href="index.php/<?php echo $link_controller;?>/cat_list"><?php echo ($this->lang->line('kategori_tab_daftar'));?></a></li>
				<li><a href="index.php/<?php echo $link_controller;?>/cat_frm"><?php echo ($this->lang->line('kategori_tab_tambah'));?></a></li>		
			</ul>
		</div>