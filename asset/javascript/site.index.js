
$(document).ready(function () {
	
	/*
	$('#selectable').selectable({
		filter: 'div',
		selected: function(ev,ui){
			//$(this).closest('ul').slideToggle("slow");
			ui.selectable.unselected();
		}
	});
	*/	
	$('#kategori').load('ajax/dailytask/set_category');
	
	$('#selectable:eq(0)> ul').hide();  
	$('#selectable:eq(0)> li').click(function() {
		$(this).next().slideToggle("slow");
	});
	
	$("#kategori li").droppable({
		accept: '#kdata li',
		activeClass: 'ui-state-hover',
		hoverClass: 'ui-state-active',
		tolerance: 'pointer',
		drop: function(ev, ui) { 
	 		var taskID = ui.draggable.attr("id");
	 		var	categoryID = $(this).attr("id");
		 		$.ajax({
			    type:"POST", 
			    url:"ajax/dailytask/update_task", 
			    data: {taskID:taskID, categoryID:categoryID}, 
			    //cache: false,
	            //dataType: "JSON",
			    success: function(del){
					//alert('sukses');
					ui.draggable.hide("slow");
					//$("#kdata").load('ajax/dailytask/list_task');
				},
				error: function() {
					alert('error');
	            }
		    });
			//alert(taskID+" "+categoryID);
	 		//alert('test');
		}
	});

});