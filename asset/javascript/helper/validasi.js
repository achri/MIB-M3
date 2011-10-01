/* 
JQUERY HELPER VALIDASI v.3.0.1
=======================
HOW TO USE IT ????

- JAVASCRIPT
$(document).ready(function() {
	var form = $('selector'); 				// GUNAKAN NAMA SELECTOR OBJECT EX: var form = $('form#form1'); atau var form = $('form.form1');
	form.sumbit(function() {
		if(validasi('selector')) { 			// GUNAKAN NAMA SELECTOR TEXT EX: if(validasi('form#form1')){ .. atau if(validasi('form.form1)){ ...
			....
		}
		return false;
	});
});

- CLASS
	required = class item yg akan di cek
	number = class item yg akan di cek numeric
	kosong = class item yg numeriknya boleh nol

- HTML TAG
-- Validasi Normal
<form id=form1>
	<input id='input1' name='input1' class='required number' title='input1'>
	...
	<select id='select1' class='required'>
		<option value=''>pilih</option>
		<option value='op1'>option1</option>
		...
	</select>
	...
</form>

-- Validasi Table / Ul

<form id=form1>
	<table>
	<tr baris='1'>
	<td>
		<input id='input1' name='input1' class='required number' title='input1'>
		...
		<select id='select1' class='required'>
			<option value=''>pilih</option>
			<option value='op1'>option1</option>
			...
		</select>
		...
	</td>
	...
	</table>
</form>

<form id=form2>
	<ul>
	<li baris='1'>
		<input id='input1' name='input1' class='required number' title='input1'>
		...
		<select id='select1' class='required'>
			<option value=''>pilih</option>
			<option value='op1'>option1</option>
			...
		</select>
		...
	</li>
	...
	</ul>
</form>
	
	
---- MIB 2010
*/ 
var selector_dialog_validasi = '.dialog_validasi';

$(document).ready(function() {
	$(selector_dialog_validasi).dialog({
		autoOpen: false,
		bgiframe: true,
		width: 'auto',
		height: 'auto',
		resizable: false,
		//draggable: false,
		modal:true,
		position: 'center',
		buttons : {
			'KELUAR' : function() {
				$(this).dialog('close');
			}
		}
	}).css({'color':'red','font-wight':'bold'});
	
	// MATIKAN AUTOCOMPLETE BROWSER UNTUK KLAS VALIDASI
	$('.required').attr('autocomplete','off');
	
});

function validasi(form) {
	var val,baris,komponen,dlg_validasi = $(selector_dialog_validasi),cek=true;
	dlg_validasi.html('');
	$(form+' input.required,'+form+' select.required').each(function(i) {
		val = $(this).val();
		baris = $(this).parents('tr').attr('baris');
		if (!baris) {baris='';} // JIKA TIDAK ADA TR ATTRIBUTE BARIS
		komponen = $(this).attr('title');
		if (val == '') {
			if (baris == '') {
				dlg_validasi.append('- <b><font color="red">( '+komponen+' )</font> belum di isi !!!</b><br>');
			}else {
				if ($(this).get(0).tagName == 'SELECT') {
					dlg_validasi.append('- <b><font color="red">( '+komponen+' )</font> Baris ke-'+baris+' belum dipilih !!!</b><br>');
				} else {
					dlg_validasi.append('- <b><font color="red">( '+komponen+' )</font> Baris ke-'+baris+' belum di isi !!!</b><br>');
				}
			}
			cek = false;		
		}else if ($(this).hasClass('number')) {
			val = parseFloat(val.replace(/,/g,''));
			if (isNaN(val)) {
				if (baris == '') {
					dlg_validasi.append('* <b><font color="red">( '+komponen+' )</font> harus angka !!!</b><br>');
				} else {
					dlg_validasi.append('* <b><font color="red">( '+komponen+' )</font> Baris ke-'+baris+' harus angka !!!</b><br>');
				}
				cek = false;
			}else if (!$(this).hasClass('kosong') && val <= 0) {
				if (baris == '') {
					dlg_validasi.append('* <b><font color="red">( '+komponen+' )</font> harus angka positif dan tidak nol !!!</b><br>');
				} else {
					dlg_validasi.append('* <b><font color="red">( '+komponen+' )</font> Baris ke-'+baris+' harus angka positif dan tidak nol !!!</b><br>');
				}
				cek = false;
			}else if (val < 0){  
				if (baris == '') {
					dlg_validasi.append('* <b><font color="red">( '+komponen+' )</font> harus angka dan bernilai positif !!!</b><br>');
				} else {
					dlg_validasi.append('* <b><font color="red">( '+komponen+' )</font> Baris ke-'+baris+' harus angka dan bernilai positif !!!</b><br>');
				}
				cek = false;
			}
		}			
	});
	
	if (!cek) {
		dlg_validasi.dialog('open').css('text-align','left');
	}
	
	return cek;
}