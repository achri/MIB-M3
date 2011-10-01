<script type="text/javascript">
$(document).ready(function() {

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

$("#dialog").dialog({
	autoOpen: false,
	modal: true});

$("#dialog1").dialog({
	autoOpen: false,
	modal: true});
	
	$('#add_cat').submit(function() {
	var cat = document.getElementById('kategori').value;
		if (cat == ''){
		$('#dialog_kosong').dialog('open');
		return false;
		}else{
		$.ajax({
			type: 'POST',
			url: 'index.php/<?php echo $link_controller;?>/cat_add',
			data: $(this).serialize(),
			success: function(data) {
				if (data == 'ada'){				
					$('#dialog_tambah_gagal').dialog('open');
				}else{
					$('#dialog_tambah').dialog('open');				
					$('#dep_nama').text(data); 
				}
			}
		});
		return false;
		}
	});
})
</script>

<form id="add_cat">
<?php echo ($this->lang->line('kategori_input_kategori'));?> : <input type="text" name="kategori" id="kategori" maxlength="100" style="text-transform:uppercase"/> <br /><br />
<input type="submit" id="submit" value="<?php echo ($this->lang->line('kategori_button_submit'));?>"/>
</form>
<div id="dialog" title="Basic dialog">
	<p><?php echo ($this->lang->line('kategori_form_error'));?></p>
</div>
<div id="dialog1" title="Basic dialog">
	<p><?php echo ($this->lang->line('kategori_form_error1'));?></p>
</div>




<!-- ============= buat dialognya ============ -->
<div id="dialog_kosong" title="<?=($this->lang->line('jquery_dialog_peringatan'));?>">
	<p><?php echo ($this->lang->line('kategori_form_error'));?></p>
</div>
<div id="dialog_tambah_gagal" title="<?=($this->lang->line('jquery_dialog_peringatan'));?>">
	<p><?php echo ($this->lang->line('kategori_form_error1'));?></p>
</div>

<div id="dialog_tambah" title="<?=($this->lang->line('dlg_title_info'));?>">
	<b><?php echo ($this->lang->line('kategori_input_kategori'));?> <FONT COLOR="red"><b><p id="dep_nama"> </p> </b></FONT><?php echo ($this->lang->line('jquery_dialog_tambah_berhasil'));?></b>

</div>
