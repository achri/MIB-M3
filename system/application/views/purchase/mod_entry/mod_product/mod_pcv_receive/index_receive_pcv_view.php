<script type="text/javascript">
$(document).ready(function() {

	$("#dialog").dialog({
		autoOpen: false,
		modal: true}).css('color','red');
	
	$('#frm_receive_pcv').submit(function() {
		var cek = $('#pcv').val();
		if (cek == ''){
			$('#dialog').dialog('open');
			return false;
		}else{
			$.ajax({
				type: 'POST',
				url: 'index.php/<?php echo $link_controller;?>/open_receive',
				data: $(this).serialize(),
				success: function(data) {
						$('#pcv_content').html(data);
					}
			});
			return false;
		}
	});
});
</script>


<h2><?php echo ($this->lang->line('receive_judul_halaman')); ?></h2>
<div id="pcv_content">
<form id="frm_receive_pcv">
	<?php 
	echo $this->lang->line('receive_label_nopcv')." 
		: <select name='pcv' id='pcv'>
			<option value=''>-Pilih Petty Cash-</option>";
				if ($list_pcv->num_rows() > 0):
					foreach ($list_pcv->result() as $pcv): 
						echo "<option value=".$pcv->pcv_id.">".$pcv->pcv_no."</option>";
					endforeach;
				else:
					echo "Empty";
				endif;
			echo"</select>
		";
	?>
	<br/>
	<input type="submit" value="Proses">
	<input type="reset" value="Bersihkan">
</form>
</div>

<div id="dialog" title="PERINGATAN">
	<p><?php echo ($this->lang->line('receive_error_val'));?></p>
</div>