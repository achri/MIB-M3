//
// dialog example
//
function konfirmasi() {
	
    $.blockUI({ message: 'Yakin data akan di prosess ???', css: { width: '275px' }});
}

$('.konfirm_btn_yes').click(function() {
    // update the block message
	$.unblockUI({ fadeOut: 200 });
    $.blockUI({ message: '<h1>Remote call in progress...</h1>' });

    $.ajax({
        url: 'wait2.php',
        cache: false,
        complete: function() {
        // unblock when remote call returns
        $.unblockUI();
        }
    });
});

$('.konfirm_btn_no').bind('click', $.unblockUI);
