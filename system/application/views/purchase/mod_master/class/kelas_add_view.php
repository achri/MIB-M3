<script type="text/javascript">
$(document).ready(function() {

	$("#dialog").dialog({
	autoOpen: false,
	modal: true});

	$("#dialog1").dialog({
	autoOpen: false,
	modal: true});

	$("#dialog2").dialog({
	autoOpen: false,
	modal: true});
	
	$('#add_class').submit(function() {
	var cat = document.getElementById('kelas').value;
	var drop = $('#droparea').text();
		
		if (drop == ''){
			$('#dialog1').dialog('open');
			return false;
		}else if (cat == ''){
			$('#dialog').dialog('open');
			return false;
		}else{
			$.ajax({
			type: 'POST',
			url: 'index.php/<?php echo $link_controller;?>/kelas_add',
			data: $(this).serialize(),
			success: function(data) {
				if (data == 'ada'){
					$('#dialog2').dialog('open');
				}else{
					$('#kelas').val('');
					$('#result').html(data);
				}
			}
			});
			return false;
		}
	});

	$(".items").draggable({opacity: 0.6, cursor: 'move', helper: 'clone'});
    $(".droparea").droppable({
    	accept: ".items",
        hoverClass: 'dropareahover',
        tolerance: 'pointer',
        drop: function(ev, ui) {
			var dropElemVal = ui.draggable.attr("id");
			var dropElemId = ui.draggable.attr("value");
            var dropElem = ui.draggable.html();
			$(this).html(dropElem);
			document.getElementById('cat_code').value = dropElemVal;
			document.getElementById('cat_id').value = dropElemId;
			document.getElementById('kelas').value = '';
			//== call result ==>
			$.ajax({
				url: 'index.php/<?php echo $link_controller;?>/kelas_list/'+dropElemId,
				success: function(response){			
		    		$('#result').html(response);
		  		},
		  		dataType:"html"  		
		  	});
		  	return false;
            }
       });
})
</script>
<div class="catlabel"><?php echo ($this->lang->line('kelas_label_kategori')); ?></div>
<div class="space">&nbsp;</div>
<div class="droplabel"><?php echo ($this->lang->line('kelas_label_drop')); ?></div>
<div class="space">&nbsp;</div>
<div class="frmlbl"><?php echo ($this->lang->line('kelas_label_form')); ?></div>

<div id="contentCat">
	<ul style="padding: 0px; margin: 0px;"> 
	<?php
	if ($get_cat->num_rows() > 0):
		foreach ($get_cat->result() as $row):
		
		echo "<li class='items' id='".$row->cat_code."' value='".$row->cat_id."'>".$row->cat_name."</li>";
		
		endforeach;
	else:
		echo "Kosong";
	endif;
	?>
	</ul>
</div>
<div id="space" align='center'><img src='./asset/img_source/arrow_right.jpg'></div>
<div class="droparea" id="droparea"></div>
<div id="space" align='center'><img src='./asset/img_source/arrow_right.jpg'></div>
<div id="frmcontent">
<form id="add_class">
<input type="text" id="kelas" name="kelas" style="text-transform:uppercase"><br />
<input type="submit" value="<?php echo($this->lang->line('kelas_button_submit'));?>">
<input type="hidden" id="cat_code" name="cat_code"><br />
<input type="hidden" id="cat_id" name="cat_id">
</form>
</div><br />
<div id="space">&nbsp;</div>
<div id="keterangan"><?php echo($this->lang->line('kelas_label_nb'));?></div>
<div id="space">&nbsp;</div>
<div id="result" class="result">&nbsp;</div>
<div id="dialog" title="konfirmasi">
	<p><?php echo($this->lang->line('kelas_form_error2'));?></p>
</div>
<div id="dialog1" title="konfirmasi">
	<p><?php echo($this->lang->line('kelas_form_error1'));?></p>
</div>
<div id="dialog2" title="konfirmasi">
	<p><?php echo($this->lang->line('kelas_form_error3'));?></p>
</div>