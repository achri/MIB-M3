function set_tabs(vals) {
	if (vals == "YES") {
		$("#new_task_tabs").tabs( 'add' ,'#share_task' , 'SHARE TO OTHER' );
		$("#new_task_tabs").tabs( 'select',1);
		
		$("#share_task").show();
	}
}

$(document).ready(function () {
	// TABS
	$("#home_tabs").tabs();
	$("#task_tabs").tabs();
	$("#new_task_tabs").tabs();

	// SORTABLE TABS
	$("#share_task").hide();
	$("#list_user_sort, #collaborate_sort, #viewer_sort").sortable().disableSelection();

	var $tabs = $("#share_tabs").tabs();

	var $tab_items = $("ul:first li",$tabs).droppable({
		accept: ".shareConnection li",
		revent: true,
		hoverClass: "ui-state-hover",
		drop: function(ev, ui) {
			var $item = $(this);
			var $list = $($item.find('a').attr('href')).find('.shareConnection');

			ui.draggable.hide('slow', function() {
				$(this).appendTo($list).show('slow');
			});
		}
	});
	$('#list_user_sort').load('index.php/daily_task/create_task/task_share_user');
	//$('#category_task').load('index.php/daily_task/create_task/cust_category_user');
	
	$.post("index.php/daily_task/create_task/cust_category_user", function(data){
		$('#category_task').html(data);
	});
	
	
	$.post("index.php/daily_task/create_task/list_task", function(data){
		$('#view_task_list').html(data);
	});
	
	
	var tips = $("#create_task_info");
	function updateInfo(t) {
		tips.text(t).effect("highlight",{},1500);
		tips.fadeIn();	
	}
	
	$("form").submit(function() {
		tips.fadeOut();	
		$.ajax({
		    type:"POST", 
		    url:"index.php/daily_task/create_task/insert_task", 
		    data: $("form").serialize(),
		    cache: false,
            dataType: "JSON",
		    success: function(del){
				updateInfo('SUKSES');
			},
			error: function() {
				updateInfo('GAGAL');
	        }
	    });
		return false;
	});
	
});