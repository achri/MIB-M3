<script type="text/javascript">
	$(function(){
		// Tabs
		$('#tabs').tabs();
		shower();
	});

	function shower(){
		$.ajax({
			type : 'POST',
			url : 'index.php/<?php echo $link_controller;?>/contact_flexigrid',
			success: function(response){			
    		$('#datacontact').html(response);
	  		},
	  		dataType:"html"  		
	  	});
	  	return false;
	};

</script>
		<!-- Tabs -->
		<h2><?php echo ($this->lang->line('contact_judul_halaman')); ?></h2>
		<div id="tabs">
			<ul>
				<li><a href="#datacontact" onclick='shower()'><?php echo ($this->lang->line('contact_tab_daftar')); ?></a></li>
				<li><a href="index.php/<?php echo $link_controller;?>/contact_frm"><?php echo ($this->lang->line('contact_tab_tambah')); ?></a></li>
			</ul>
			<div id="datacontact"></div>
		</div>
