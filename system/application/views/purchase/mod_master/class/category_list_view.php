<script language="JavaScript">
$(document).ready(function(){
	$(".items1").draggable({opacity: 0.6, cursor: 'move', helper: 'clone'});
    $(".droparea2").droppable({
    	accept: ".items1",
        hoverClass: 'dropareahover',
        tolerance: 'pointer',
        drop: function(ev, ui) {
			var dropElemVal = ui.draggable.attr("id");
			var dropElemId = ui.draggable.attr("value");
            var dropElem = ui.draggable.html();
			$(this).html(dropElem);
			document.getElementById('cat_code').value = dropElemVal;
			document.getElementById('cat_id2').value = dropElemId;
			$('#frmcontent').show();
			$('#arrowright').show();
			//show("/mod_category_master/kelas/kelas_list/"+dropElemId,"#result2");
			$.ajax({
				url: 'index.php/<?php echo $link_controller;?>/kelas_list/'+dropElemId,
				success: function(response){			
		    		$('#result2').html(response);
		  		},
		  		dataType:"html"  		
		  	});
		  	return false;
            }
       });
});
</script>
<div id="contentLeft">	
	<ul style="padding: 0px; margin: 0px;">
	<?php
	if ($get_list->num_rows() > 0):
		foreach ($get_list->result() as $row):
		
		echo "<li class='items1' id='".$row->cat_code."' value='".$row->cat_id."'>".$row->cat_name."</li>";
		
		endforeach;
	else:
		echo "Belum ada data Kelas";
	endif;
	?>
	</ul>
</div>