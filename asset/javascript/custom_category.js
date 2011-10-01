$(document).ready(function(){

	var ttl_name = $("#ttl_name"),
	allFields = $([]).add(ttl_name),
	tips = $("#validateTips");

	function updateTips(t) {
		tips.text(t).effect("highlight",{},1500);
	}

	function checkLength(o,n,min,max) {

		if ( o.val().length > max || o.val().length < min ) {
			o.addClass('ui-state-error');
			updateTips("Length of " + n + " must be between "+min+" and "+max+".");
			return false;
		} else {
			return true;
		}

	}

	function checkRegexp(o,regexp,n) {

		if ( !( regexp.test( o.val() ) ) ) {
			o.addClass('ui-state-error');
			updateTips(n);
			return false;
		} else {
			return true;
		}
	}

	$("#category_dialog").dialog({
		bgiframe: true,
		autoOpen: false,
		height: 'auto',
		modal: true,
		buttons: {
			'Create Category': function() {
				var bValid = true;
				allFields.removeClass('ui-state-error');

				bValid = bValid && checkLength(ttl_name,"Title Name",5,30);

				if (bValid) {
					$.ajax({
						type: 'POST',
						url: 'index.php/daily_task/create_task/insert_category',
						data: $('#insert_category').serialize(),
						cache: false,
			            dataType: 'JSON',
						success: function() {
							$('#category_task').load('index.php/daily_task/create_task/cust_category_user');
							$("#category_dialog").dialog('close');
						}
					});
				}
			},
			Cancel: function() {
				$(this).dialog('close');
			}
		},
		close: function() {
			allFields.val('').removeClass('ui-state-error');
		}
	});
	
	$('#add_category').bind('click', function() {
		$('#category_dialog').dialog('open');	
	});
	
});