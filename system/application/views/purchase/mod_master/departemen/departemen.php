<script type="text/javascript">
	$(document).ready(function(){
		// Tabs
		$('#tabs').tabs();
		shower();
	});

	function shower(){
			$.ajax({
				type : 'POST',
				url : 'index.php/<?php echo $link_controller;?>/dep_flexigrid',
				success: function(response){			
	    		$('#depdata').html(response);
		  		},
		  		dataType:"html"  		
		  	});
		  	return false;
	};
			
</script>
		<!-- Tabs -->
		<h2><?php echo ($this->lang->line('dep_judul_halaman'));?></h2>
		<div id="tabs">
			<ul>
				<li><a href="#depdata" onclick='shower()'><?php echo ($this->lang->line('dep_tab_daftar'));?></a></li>
				<li><a href="index.php/<?php echo $link_controller;?>/dep_frm"><?php echo ($this->lang->line('dep_tab_tambah'));?></a></li>
			</ul>
			<div id="depdata"></div>
		</div>
