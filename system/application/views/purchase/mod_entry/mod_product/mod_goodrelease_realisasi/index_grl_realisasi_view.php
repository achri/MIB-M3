<script type="text/javascript">
$(document).ready(function() {

	$("#dialog").dialog({
		autoOpen: false,
		modal: true});
	
	$('#frm_realisasi').submit(function() {
		var cek = $('#grl').val();
		if (cek == ''){
			$('#dialog').dialog('open');
			return false;
		}else{
			$.ajax({
				type: 'POST',
				url: 'index.php/<?php echo $link_controller;?>/open_realisasi',
				data: $(this).serialize(),
				success: function(data) {
						$('#realisasi_content').html(data);
					}
			});
			return false;
		}
	});
});
</script>


<h2><?php echo ($this->lang->line('gr_judul_halaman')); ?></h2>
<div id="realisasi_content">
<form id="frm_realisasi">
	<?php 
	echo $this->lang->line('gr_label_nogr')." 
		<select name='grl' id='grl'>
			<option value=''>- pilih good release -</option>";
				if ($list_grlno->num_rows() > 0):
					foreach ($list_grlno->result() as $gr): 
						echo "<option value=".$gr->grl_id.">".$gr->grl_no."</option>";
					endforeach;
				else:
					echo "Empty";
				endif;
			echo"</select>
		";
	?>
	<br/>
	<input type="submit" value="Proses">
	<input type="reset" value="<?php echo ($this->lang->line('gr_button_reset')); ?>">
</form>
</div>

<div id="dialog" title="Basic dialog">
	<p><?php echo ($this->lang->line('gr_error_novalue'));?></p>
</div>