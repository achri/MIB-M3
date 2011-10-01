<script type="text/javascript">
$(document).ready(function() {
	$("#error1").dialog({
		autoOpen: false,
		modal: true
	});

	$("#error2").dialog({
		autoOpen: false,
		modal: true
	});

	$("#error3").dialog({
		autoOpen: false,
		modal: true
	});
		
	$('#add_class').submit(function() {
		$.ajax({
			type: 'POST',
			url: 'index.php/<?php echo $link_controller;?>/add_grup',
			data: $(this).serialize(),
			success: function(data) {
				if (data == 'null'){
					$('#error1').dialog('open');
					$('#detail').val('');
				}else if (data == 'no'){
					$('#error2').dialog('open');
					$('#detail').val('');
				}else if (data == 'ada'){
					$('#error3').dialog('open');
					$('#detail').val('');
				}else{
					$('#grup').val('');
					$('#detail').val('');
				    $('#result2').html(data);
				}
			}
		});
		document.getElementById('grup').value = '';
		return false;
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
			document.getElementById('cat_id1').value = dropElemId;
			$('#arrowdown').show();
			$('#arrowdown2').show();
			$('#droparea2').show();
			$('#droparea2').text('');
			$('#frmcontent').hide();
			$('#arrowright').hide();
			//== call result ==>
			$.ajax({
				url: 'index.php/<?php echo $link_controller_class;?>/kelas_list/'+dropElemId,
				success: function(response){			
		    		$('#result').html(response);
		  		},
		  		dataType:"html"  		
		  	});
		  	return false;
            }
       });
});
</script>
<!-- kolom pertama -->
<div class="cols-1">
	<div class="label"><?php echo ($this->lang->line('kelas_label_kategori')); ?></div>
		<div id="contentCat">
			<ul style ="margin: 0px; padding: 0px;">
			<?php
			if ($get_cat->num_rows() > 0):
				foreach ($get_cat->result() as $row):		
					echo "<li class='items' id='".$row->cat_code."' value='".$row->cat_id."'>".$row->cat_name."</li>";
				endforeach;
			else:
				echo "Empty";
			endif;
			?>
			</ul>
		</div>
</div>

<!-- kolom Kedua -->
<div class="cols-2">
<br>
	<div><img src='./asset/img_source/arrow_right.jpg'></div>
</div>

<!-- kolom Ketiga -->
<div class="cols-1">
	<div class="label"><?php echo ($this->lang->line('kelas_label_drop')); ?></div>
	<div class="droparea" id="droparea">&nbsp;</div>
	<br>
		<div style="display: none;" id="arrowdown">
		<img src='./asset/img_source/arrow_down.jpg' align="center">
		<div class="label"><?php echo ($this->lang->line('kelas_label_kelas')); ?></div>
		</div>
	<div id="result" class="result">&nbsp;</div>
	<br>
	<div style="display: none;" id="arrowdown2">
		<img src='./asset/img_source/arrow_down.jpg' align="center">
		<div class="label"><?php echo ($this->lang->line('kelas_label_dropkelas')); ?></div>
	</div>
	<div class="droparea2" id="droparea2" style="display: none;"></div>
</div>

<!-- kolom Keempat -->
<div class="cols-2">
<br>
<div style="display: none;" id="arrowright"><img src='./asset/img_source/arrow_right.jpg'></div>
</div>

<!-- kolom Kelima -->
<div class="cols-1">
	<div id="frmcontent" style="display: none;">
		<div class="label"><?php echo ($this->lang->line('grup_label_form')); ?></div>
		<form id="add_class">
			<input type="text" id="grup" name="grup" style="text-transform:uppercase"><br />
			<font size="2">produk dalam grup ini perlu detail pemakain?
			<select name="detail" id="detail">
				<option value="">[pilih]</option>
				<option value="1">Ya</option>
				<option value="0">Tidak</option>
			</select></font><br>
			<input type="submit" value="<?php echo ($this->lang->line('grup_button_submit')); ?>">
			<input type="hidden" id="cat_code" name="cat_code"><br />
			<input type="hidden" id="cat_id1" name="cat_id1">
			<input type="hidden" id="cat_id2" name="cat_id2">
		</form>
		<br>
		<div class="label"><?php echo ($this->lang->line('kelas_label_grup')); ?></div>
		<div id="result2" class="result2" height="400px">&nbsp;</div>
	</div>
</div>

<div>
	<font color="#fff">limit</font>
</div>

<div id="error1" title="konfirmasi">
	<p><?php echo($this->lang->line('grup_form_error1'));?></p>
</div>
<div id="error2" title="konfirmasi">
	<p><?php echo($this->lang->line('grup_form_error2'));?></p>
</div>
<div id="error3" title="konfirmasi">
	<p><?php echo($this->lang->line('grup_form_error3'));?></p>
</div>