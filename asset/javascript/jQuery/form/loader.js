$(document).ready(function(){
	var loader = $('<span id="loader"><img src="asset/javascript/jQuery/flexiGrid/images/other/spinner.gif"></span>')
		.css({position: "fixed", top: "50%", left: "50%", widht: "100px", height: "100px", "z-index": "100"})
		.appendTo("#pagewidth")
		.hide();
	$().ajaxStart(function() {
		loader.show();
	}).ajaxStop(function() {
		loader.hide();
	}).ajaxError(function(a, b, e) {
		throw e;
	});
});
