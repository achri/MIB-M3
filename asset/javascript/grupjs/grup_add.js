$(document).ready(function() {
	$('#add_class').submit(function() {
		$.ajax({
			type: 'POST',
			url: $(this).attr('action'),
			data: $(this).serialize(),
			success: function(data) {
				$('#result').html(data);
			}
		})
		document.getElementById('kelas').value = '';
		return false;
	});
})

$(document).ready(function(){
	$(".items").draggable({opacity: 0.6, cursor: 'move', helper: 'clone'});
    $(".droparea").droppable({
    	accept: ".items",
        hoverClass: 'dropareahover',
        tolerance: 'pointer',
        drop: function(ev, ui) {
			var dropElemVal = ui.draggable.attr("id");
			var dropElemId = ui.draggable.attr("value");
            var dropElem = ui.draggable.html();
			$(this).append(dropElem);
			document.getElementById('cat_code').value = dropElemVal;
			document.getElementById('cat_id1').value = dropElemId;
			show("Purchase/list_kelas/"+dropElemId,"#result")
            }
       });
});