<script type="text/javascript">
	$(function(){
		// Tabs
		$('#tabs').tabs();
		shower();	
	});

	function shower(){
			$.ajax({
				type : 'POST',
				url : 'index.php/<?php echo $link_controller;?>/bank_flexigrid',
				success: function(response){			
	    		$('#tabs-1').html(response);
		  		},
		  		dataType:"html"  		
		  	});
		  	return false;
	};

	
</script>
		<!-- Tabs -->
		<h2><?php echo ($this->lang->line('bank_judul_halaman')); ?></h2>
		<div id="tabs">
			<ul>
				<li><a href="#tabs-1" onclick='shower()'><?php echo ($this->lang->line('bank_tab_daftar')); ?></a></li>
				<li><a href="index.php/<?php echo $link_controller;?>/bank_frm"><?php echo ($this->lang->line('bank_tab_tambah')); ?></a></li>
			</ul>
			<div id="tabs-1"></div>
		</div>