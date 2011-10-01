function masking(selector) {
	// USING CLASS
	$(selector).each(function(i) {
		var $item = $(this);
		var digit_decimal = $item.attr('digit_decimal');
		//var digit_length = $item.attr('digit_length');
		if (!digit_decimal) {
			if ($item.hasClass('currency')) {
				digit_decimal = 2;
			} else {
				digit_decimal = 0;
			}
		}
		/*
		if (!digit_length) {
			if ($item.hasClass('currency')) {
				digit_length = 0;
			} else {
				digit_length = 0;
			}
		}
		*/
		$item.attr('alt','p0c3p'+digit_decimal+'S').autoNumeric().blur(function() {
			var strip = $.fn.autoNumeric.Strip(this.id);
			if ($item.val() != '') {
				$item.val($.fn.autoNumeric.Format(this.id,strip));
			}
		});
	}).css('text-decoration','right');
	return false;
}

function masking_reload(selector) {
	$(selector).each(function(i) {
		var $item = $(this);
		var $id = $(this).attr('id');
		var digit_decimal = $item.attr('digit_decimal');
		
		if (!digit_decimal) {
			if ($item.hasClass('currency')) {
				digit_decimal = 2;
			} else {
				digit_decimal = 0;
			}
		}
		
		$item.attr('alt','p0c3p'+digit_decimal+'S').autoNumeric();
		var strip = $.fn.autoNumeric.Strip($id);
		if ($item.val() != '') {
			$item.val($.fn.autoNumeric.Format($id,strip));
		}
		
	}).css('text-decoration','right');
	return false;
}

// UBAH INPUT JIKA SELECT DI PILIH
// <input id = i1><select input_id = i1> ....
function masking_select(selector,selector_input) {
	$(selector).change(function() {
		var $item = $(this);
		var input_id = $item.attr('input_id');
		var um = $item.val();
		
		$.post('index.php/ajax_helper/ajax_satuan/'+um,function(decimal) {
			if (decimal) {
				$(selector_input+":input[id='"+input_id+"']").attr('digit_decimal',decimal);
				masking_reload(selector_input);
			}
			return false;
		});	
	});
	return false;
}

function masking_currency(selector,selector_input) {
	$(selector).change(function() {
		var $item = $(this);
		var input_id = $item.attr('input_id');
		var curr = $item.val();
		/*
		decimal = 2;
		if (curr == 2)
			decimal = 5;
		*/
		$.post('index.php/ajax_helper/ajax_currency/'+curr,function(currency) {
			if (currency) {
				$(selector_input+":input[id='"+input_id+"']").attr('digit_decimal',currency);
				masking_reload(selector_input);
			}
			//alert(currency);
			return false;
		});	
	});
	return false;
}

function unmasking(selector) {
	$(selector).each(function(i) {
		val = $(this).val();
		if (val != '') {
			val = parseFloat(val.replace(/,/g,''));
			$(this).val(val);
		}
	});
	
	return false;
}

$(document).ready(function() {
	$(':input').attr('maxlength','30');
	$('.number').attr('autocomplete','off');
	/*.addClass('ui-widget-content');*/
	//$('div.ui-dialog').dialog('isOpen',function() {
		//alert('waw');
		//$('a.ui-dialog-titlebar-close').hide();
		//alert('wa');
	//}
	//});
	
	//$('a.ui-dialog-titlebar-close',$(this).prev()).hide();
	
});

