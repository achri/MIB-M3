<script type="text/javascript">
	function test(com,grid)
	{
	    if (com=='Select All')
	    {
			$('.bDiv tbody tr',grid).addClass('trSelected');
	    }
	    
	    if (com=='DeSelect All')
	    {
			$('.bDiv tbody tr',grid).removeClass('trSelected');
	    }       
	} 
	$("#dialog_hapus").dialog({
		autoOpen: false,
		modal: true,
		buttons: {		
		'<?=$this->lang->line('jquery_button_cancel');?>': function() {
			$(this).dialog('close');
		},
		'<?=$this->lang->line('jquery_button_hapus');?>': function() {	
			var tmp_id = $('#tmp').val();
			$(this).dialog('close');	
			$.ajax({
				type: 'POST',
				url: 'index.php/<?php echo $link_controller;?>/supplier_delete',
				data: "id="+tmp_id,				
	  			dataType:"html"
			});						
			location.href='index.php/<?php echo $link_controller;?>/index';
		}
	}
	});

	
	function editsup(id){
		$.ajax({
			type: 'POST',
			url: 'index.php/<?php echo $link_controller;?>/supplier_edit_frm',
			data: "id="+id,
			success: function(data) {
			$('#main_content').html(data);
  			},
  			dataType:"html"
		});
		return false;
	}

	function deletesup(id){
		$('#dialog_hapus').dialog('open');									
		$('#tmp').val(id);	
	}
</script>
<table id="flex1" style="display:none"></table>
	<?php
		echo $js_grid;
	?>

<!-- ==== bwt dialog konfirmasinya ===== -->
<input type="hidden" id="tmp"> <!-- bwt nyimpen id sementaun, idnnya bwt dimasukin ke dialog_hapus -->
<div id="dialog_hapus" title="<?=($this->lang->line('jquery_dialog_konfirmasi'));?>">
	<p><?=($this->lang->line('ajax_dialog_hapus'));?></p><p id="name"></p>
</div>

