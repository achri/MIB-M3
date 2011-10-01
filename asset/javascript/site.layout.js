var layoutSet;
$(document).ready(function () {
	
	layoutSet = $("body").layout({
				applyDefaultStyles:false
			,   west__applyDefaultStyles: true
			,	west__slideTrigger_open: "mouseover"
			,   west__size: "170"
			,	west__onopen: function() {
					layoutSet.open('west');
				}
	
	});
	
	// INI VARIABLE INDEX APPAND TAB
	var tabAppIdx = 1;

	$('#task_today').click(function() {
		$("#tabs").tabs('remove', tabAppIdx);
		if($("#tabs").tabs('length') <= tabAppIdx) {
	        $("#tabs").tabs('add', 'ajax/dailytask/create_task', 'Create New Task');
	        $("#tabs").tabs('select',tabAppIdx);
		}
	});

	$('#listtask').click(function() {
		$("#tabs").tabs('remove', tabAppIdx);
		if($("#tabs").tabs('length') <= tabAppIdx) {
	        $("#tabs").tabs('add', 'ajax/dailytask/list_task', 'List Daily Task');
	        $("#tabs").tabs('select',tabAppIdx);
		}
	});

	$('#newprog').click(function() {
		$("#tabs").tabs('remove', tabAppIdx);
		if($("#tabs").tabs('length') <= tabAppIdx) {
	        $("#tabs").tabs('add', 'ajax/create_purchasing.html', 'Create New Purchasing');
	        $("#tabs").tabs('select',tabAppIdx);
		}
	});

	$('#listprog').click(function() {
		$("#tabs").tabs('remove', tabAppIdx);
		if($("#tabs").tabs('length') <= tabAppIdx) {
	        $("#tabs").tabs('add', 'ajax/list_purchasing.html', 'List All Purchasing');
	        $("#tabs").tabs('select',tabAppIdx);
		}
	});
	
	$("#tabs").tabs(); 
});