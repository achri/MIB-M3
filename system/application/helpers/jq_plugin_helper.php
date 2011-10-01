<?php
	function set_calendar($selector_array,$min_date='null',$format='yy/mm/dd',$max_date='null') {
		
		$cal_format = "
		var dateOption = {
		closeText: 'Pilih', 
		prevText: 'Kembali',
		nextText: 'Teruskan', 
		currentText: 'Bulan ini', 
		monthNames: ['Januari','Februari','Maret','April','Mei','Juni',
			'Juli','Agustus','September','Oktober','November','Desember'],
		monthNamesShort: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
		dayNames: ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'], 
		dayNamesShort: ['Mgu', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'], 
		dayNamesMin: ['Mg','Sn','Sl','Rb','Km','Jm','Sb'], 
		dateFormat: '$format', 
		firstDay: 0,
		isRTL: false,

		showAnim: 'show',
		showOptions: {}, 
		defaultDate: null, 
		
		appendText: '', 
		buttonText: 'kalender', 
		buttonImage: '', 
		buttonImageOnly: false, 
		hideIfNoPrevNext: false, 

		navigationAsDateFormat: false,
		gotoCurrent: true, 
		changeMonth: false,
		changeYear: false, 
		showMonthAfterYear: false,
		yearRange: '-10:+10', 

		showOtherMonths: false, 
		calculateWeek: this.iso8601Week,
		
		shortYearCutoff: '+10',
		
		minDate: $min_date, 
		maxDate: $max_date, 
		duration: 'fast', 
		beforeShowDay: null, 

		beforeShow: null, 

		onSelect: null, 
		onChangeMonthYear: null,
		onClose: null, 
		numberOfMonths: 1, 
		showCurrentAtPos: 0, 
		stepMonths: 1, 
		stepBigMonths: 12,
		altField: 'Klik untuk memilih tanggal', 
		altFormat: '', 
		constrainInput: true, 
		showButtonPanel: false 
		};";	
		
		$cal_tag = '';
		if (is_array($selector_array)):
			foreach ($selector_array as $component):
				$cal_tag .= "$('$component').datepicker(dateOption);";
			endforeach;
		else:
			$cal_tag .= "$('$selector_array').datepicker(dateOption);";
		endif;
		
		return $cal_format.$cal_tag;
		
	}
	
	// HIDE A CLOSE BUTTON DIALOG
	function remove_close_dialog($selector_array) {
		$tag = '';
		if (is_array($selector_array)):
			$selector_array = implode($selector_array,',');
		endif;
		$tag = "$('a.ui-dialog-titlebar-close', $('$selector_array').prev()).hide();";
		return $tag;
		
	}
	
	function dialog_info ($title,$ok,$back) {
		return "$('.dialog_konfirmasi').dialog({
			title:'".$title."',
			autoOpen: false,
			bgiframe: true,
			width: 'auto',
			height: 'auto',
			resizable: false,
			draggable: false,
			modal:true,
			position:'center',
			buttons : { 
				'".$back."' : function() {
					$(this).dialog('close');
				},
				'".$ok."' : function() {
					$(this).dialog('close');
				}
			}
		});";		
	}
	/*
	function masking ($selector_array,$mask="999,999,999.00",$char="(),.:/ -",$type="reverse") {
		$str = "$.mask.options = {
		attr: 'masking', // an attr to look for the mask name or the mask itself
		mask: null, // the mask to be used on the input
		type: 'fixed', // the mask of this mask
		maxLength: -1, // the maxLength of the mask
		defaultValue: '', // the default value for this input
		textAlign: true, // to use or not to use textAlign on the input
		selectCharsOnFocus: true, //selects characters on focus of the input
		setSize: false, // sets the input size based on the length of the mask (work with fixed and reverse masks only)
		autoTab: true, // auto focus the next form element
		fixedChars : '[$char]', // fixed chars to be used on the masks.
		onInvalid : function(){},
		onValid : function(){},
		onOverflow : function(){}
		};";
		
		$str .= "$.mask.masks = $.extend($.mask.masks,{";
		if (is_array($selector_array)):
			foreach ($selector_array as $component):
				$strs[] = "$component:{ mask: '$mask', type:'$type' }";
			endforeach;
			$str .= implode(',',$strs);
		else:
			$str .= "$selector_array:{ mask: '$mask', type:'$type' }";
		endif;
		
		$str .= "});
		$('input:text').setMask();";
		
		return $str;
	}
	*/
	
	function masking ($selector_array,$digit="2") {
		if (is_array($selector_array)):
			$selector = implode(',',$selector_array);
		else:
			$selector = $selector_array;
		endif;
		$str = "$('".$selector."').attr('alt','p6c3p".$digit."S').autoNumeric().blur(function() {
			var strip = $.fn.autoNumeric.Strip(this.id);
			if ($(this).val() != '') {
				$(this).val($.fn.autoNumeric.Format(this.id,strip));
			}
		});";		
		return $str;
	}
?>