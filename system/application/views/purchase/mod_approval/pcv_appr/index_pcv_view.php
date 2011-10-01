<h2><?php echo ($this->lang->line('pcvapp_judul_halaman')); ?></h2>
<div id="pcvcontent">
	<?php 
		$this->load->view($link_view.'/pcv_list_view');
	?>
</div>