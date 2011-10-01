<script type="text/javascript">
$(document).ready(function (){
		// Tabs
		$('#tabs').tabs();
		shower();
});

function shower(){
	$.ajax({
		type : 'POST',
		url : 'index.php/<?php echo $link_controller;?>/supplier_flexigrid',
		success: function(response){			
		$('#flexi').html(response);
  		},
  		dataType:"html"  		
  	});
  	return false;
};
</script>
		<!-- Tabs -->
		<h2><?php echo ($this->lang->line('sup_judul_halaman')); ?></h2>
		<div id="tabs">
			<ul>
				<li><a href="#flexi" onclick='shower()'><?php echo ($this->lang->line('sup_tab_daftar')); ?></a></li>
				<li><a href="index.php/<?php echo $link_controller;?>/supplier_frm"><?php echo ($this->lang->line('sup_tab_tambah')); ?></a></li>
			</ul>
			<div id="flexi"></div>
		</div>
