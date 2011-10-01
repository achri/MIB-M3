<script type="text/javascript">
$(document).ready(function (){
		// Tabs
		$('#usrtabs').tabs();
		shower();
});

function shower(){
	$.ajax({
		type : 'POST',
		url : '<?=($log_ids!='')?('index.php/'.$link_controller.'/user_flexigrid/'.$log_ids):('index.php/'.$link_controller.'/user_flexigrid')?>',
		success: function(response){			
		$('#flexi').html(response);
  		},
  		dataType:"html"  		
  	});
  	return false;
};

</script>
		<!-- Tabs -->
		<h2><?php echo ($this->lang->line('user_judul_halaman')); ?></h2>
		<div id="usrtabs">
			<ul>
				<li><a href="#flexi" onclick="shower()"><?php echo ($this->lang->line('user_tab_daftar')); ?></a></li>
				<?php if ($log_ids != ''): ?>
				<li><a href="index.php/<?php echo $link_controller;?>/user_frm"><?php echo ($this->lang->line('user_tab_tambah')); ?></a></li>
				<? endif;?>
			</ul>
			<div id="flexi"></div>
		</div>
