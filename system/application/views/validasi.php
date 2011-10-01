<script language='javascript'>
$(document).ready(function() {
	// BUAT DIALOG INFORMASINYA
	$('.informasi').dialog({
		title: '<?=$this->lang->line('info')?>',
		autoOpen: false,
		bgiframe: true,
		width: 'auto',
		height: 'auto',
		resizable: false,
		//draggable: false,
		modal:true,
		//position:['right','top'],
		position: 'center',
		buttons: {
			'<?=$this->lang->line('back')?>': function() {
				$(this).dialog('close');	
			}
		}
	});
	
	// INPUT TEXT UNTUK ANGKA DI POSISI KANAN
	$('input.number').css('text-align','right');
});

// BUAT VALIDASI
// ==================

// VALIDASI GENERAL
// ilustrasi :
//			<form> <table> <tr> <td> <input type='text' class='validasi required number'> ...

function validasi_general(formData, jqForm, options) { 
	$('#saving').attr('disabled',true);
	var cek = false,name,val,
	dlginfo = $('.informasi');
	dlginfo.html('');
	$('.validasi_form input, .validasi_form select').each(function(i){
		if ($(this).hasClass('required')){
			val = $(this).val();
			name = $(this).attr('title');
			if (val == '') {
				dlginfo.append('- <b><font color="red">( '+name+' )</font> belum di isi !!!</b><br>');
				cek = true;
			}else if ($(this).hasClass('number')) {
				val = parseFloat(val.replace(',',''));
				if (isNaN(val) || val < 0) {
					dlginfo.append('* <b><font color="red">( '+name+' )</font> harus angka dan bernilai positif !!!</b><br>');
					cek = true;
				}
			}	
		}
	});
	
	if (cek) {
		dlginfo.dialog('open').css('text-align','left');
		$('#saving').attr('disabled',false);
		return false;
	}
}

// VALIDASI SPESIFIK
// Untuk menghitung jumlah row di tr masukin class validasi_tr
// ilustrasi :
//		<table>	<tr class='validasi_tr'> <td> <input type='text' class='required'> ....

function validasi_spesifik(formData, jqForm, options) {
	$('#saving').attr('disabled',true);
	var cek = false,name,val,
	jmlrows = $('.validasi_tr'),
	dlginfo = $('.informasi');
	dlginfo.html('');
		
	for (row = 1 ; row <= jmlrows.length ; row ++) {
		$('.validasi_tr:eq('+(row-1)+') input,.validasi_tr:eq('+(row-1)+') select').each(function(i){
			if ($(this).hasClass('required')){
				val = $(this).val();
				name = $(this).attr('title');
				if (val == '') {
					if ($(this).hasClass('select')) {
						dlginfo.append('- <b><font color="red">( '+name+' )</font> Baris ke-'+row+' belum dipilih !!!</b><br>');
					}else {
						dlginfo.append('- <b><font color="red">( '+name+' )</font> Baris ke-'+row+' belum di isi !!!</b><br>');
					}
					cek = true;
				}else if ($(this).hasClass('number')) {
					val = parseFloat(val.replace(',',''));
					if (isNaN(val) || val < 0) {
						dlginfo.append('* <b><font color="red">( '+name+' )</font> Baris ke-'+row+' harus angka dan bernilai positif !!!</b><br>');
						cek = true;
					}
				}			
			}
		});
	}
		
	if (cek) {
		dlginfo.dialog('open').css('text-align','left');
		$('#saving').attr('disabled',false);
		return false;
	}
	
	/*
	$('input.required,select.required').each(function(i) {
		var form = $(this).parents('form').attr('id');
		
		alert(a);
	});
	*/
	
}

// DOUBLE TABLE
function validasi_spesifik2(formData, jqForm, options) { 
	var cek = false,name,val,
	jmlrows1 = $('.validasi_tr_1'),
	jmlrows2 = $('.validasi_tr_2'),
	dlginfo = $('.informasi');
	dlginfo.html('');
	for (row1 = 1 ; row1 <= jmlrows1.length ; row1 ++) {
		for (row2 = 1 ; row2 <= jmlrows2.length ; row2 ++) {
			$('.validasi_tr2:eq('+(row2-1)+') input,.validasi_tr2:eq('+(row2-1)+') select').each(function(i){
				if ($(this).hasClass('required')){
					val = $(this).val();
					name = $(this).attr('title');
					if (val == '') {
						dlginfo.append('- <b><font color="red">( '+name+' )</font> Baris ke-'+row+' belum di isi !!!</b><br>');
						cek = true;
					}else if ($(this).hasClass('number')) {
						if (isNaN(val) || val <= 0) {
							dlginfo.append('* <b><font color="red">( '+name+' )</font> Baris ke-'+row1+' harus angka dan bernilai positif !!!</b><br>');
							cek = true;
						}
					}	
				}
			});
		}
	}
		
	if (cek) {
		dlginfo.dialog('open').css('text-align','left');
		//$('#saving').attr('disabled',false);
		return false;
	}
	
}
</script>