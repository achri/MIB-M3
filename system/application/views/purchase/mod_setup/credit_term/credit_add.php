<script type="text/javascript">
$(document).ready(function() {

	masking('.number');

	$("#dialog_tambah").dialog({
			autoOpen: false,
			modal: true,
			buttons: {
		'<?=$this->lang->line('jquery_button_close');?>': function() {
				$(this).dialog('close');
				location.href='index.php/<?php echo $link_controller;?>/index';
				}
			}
			});

			
	$("#dialog_kosong").dialog({
		autoOpen: false,
		modal: true,
		buttons: {
		'<?=$this->lang->line('jquery_button_close');?>': function() {
			$(this).dialog('close');
		}
	}
	});

	$("#dialog_tambah_gagal").dialog({
		autoOpen: false,
		modal: true,
		buttons: {
		'<?=$this->lang->line('jquery_button_close');?>': function() {
			$(this).dialog('close');
			}
		}
		});

	$('#frmterm').submit(function() {
	var name = document.getElementById('names').value;
	var stat = $('#stat').val();
			
	if (name == ''){
		if (stat == 1){
			$('#err_rest1').html('<?php echo ($this->lang->line('term_form_error'));?>');
			$('#dialog_tambah_gagal').dialog('open');
		}else{
			$('#err_rest2').html('<?php echo ($this->lang->line('term_form_error'));?>');
		}
	return false;
	}else{
	unmasking('.number');
	$.ajax({
		type: 'POST',
		url: 'index.php/<?php echo $link_controller;?>/add_term',
		data: $(this).serialize(),
		success: function(data) {
		
		if (data == 'ada'){
			if (stat == '1'){
				$('#err_rest1').html('<?php echo ($this->lang->line('term_form_error1'));?>');
				$('#dialog_tambah_gagal').dialog('open');
			}else{
				$('#err_rest2').html('<?php echo ($this->lang->line('term_form_error1'));?>');
			}
		}else{
			if (stat == '1'){
				$('#err_rest3').html('<?php echo ($this->lang->line('term_tmbh_judul_input'));?> <FONT COLOR="red"><b>'+ name +' </b></FONT><?php echo ($this->lang->line('jquery_dialog_tambah_berhasil'));?>');
				$('#dialog_tambah').dialog('open');
			}else{
				$('#add_shortcut').dialog('close');
				$('#termlist').append($("<option value="+data+" selected='selected'>"+name+"</option>"));
				
				
				$('#add_shortcut_berhasil').dialog('open');					
				$('#shortcut_behasil').html('<?=($this->lang->line('term_tmbh_judul_input'));?> <FONT COLOR=red><b>'+ name +' </b></FONT><?php echo ($this->lang->line('jquery_dialog_tambah_berhasil'));?>');		
				
				
			}
		}
		}
	});
	return false;
	}
	});
})
</script>

<form id="frmterm">
<table>
	<tr>
		<td class="labelcell"><?php echo ($this->lang->line('term_input_id')); ?></td>
		<td class="fieldcell">: <input type="text" name="name" id="names"  /> <input type="hidden" id="stat" value="1"  /></td>
	</tr>
	<tr>
		<td class="labelcell"><?php echo ($this->lang->line('term_input_name')); ?></td>
		<td class="fieldcell">: <input type="text" name="desc" id="desc"  /></td>
	</tr>
	<tr>
		<td class="labelcell"><?php echo ($this->lang->line('term_input_days')); ?></td>
		<td class="fieldcell">: <input type="text" name="days" id="days" maxlength="3" class="validation:numeric number"/></td>
	</tr>
	<tr>
		<td class="labelcell"><?php echo ($this->lang->line('term_input_disc')); ?></td>
		<td class="fieldcell">: <input type="text" name="disct" id="disct" maxlength="3" class="validation:numeric number"/></td>
	</tr>
</table>
<div id="err_rest2"></div>
<input type="submit" id="submit" value="<?php echo ($this->lang->line('term_button_submit')); ?>"/>
</form>

<!-- ==== bwt dialog konfirmasinya ===== -->
<div id="dialog_tambah_gagal" title="<?=($this->lang->line('jquery_dialog_peringatan'));?>">
	<div id="err_rest1"></div>
</div>

<div id="dialog_tambah" title="<?=($this->lang->line('jquery_dialog_konfirmasi'));?>">
	<div id="err_rest3"></div>
</div>

