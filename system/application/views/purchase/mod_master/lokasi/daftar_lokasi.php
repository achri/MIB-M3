<script type="text/javascript">
$(document).ready(function()  {
	$('.editneg').editable('index.php/lokasi/negara_update',{
		indicator : 'Saving...',
		tooltip   : 'Click to edit...',
		width : '150px'
	});
	//.css('text-transform','uppercase')
	
});

	function del_lokasi(id,table) {
		var dlg_lokasi = $('.dlg_lokasi');
		var info_konfirm = "Serius data akan dihapus ???";
		var info_error = "Data tidak berhasil dihapus !!!";
		var info_ok = "Data berhasil dihapus !!!";
		dlg_lokasi.html('').html(info_konfirm)
		.dialog('option','title','KONFIRMASI')
		.dialog('option','buttons', {
			"BATAL" : function() {
				$(this).dialog('close');
			},
			"OK" : function() {
				$.post('index.php/lokasi/del_lokasi/'+id+'/'+table,function(data){
					if (data == 'OK') {
						dlg_lokasi.dialog('close');
						dlg_lokasi.html('').html(info_ok)
						.dialog('option','title','INFORMASI')
						.dialog('option','buttons', {
							"OK" : function() {
								$(this).dialog('close');
								location.href = 'index.php/lokasi/index';
							}
						}).dialog('open');						
					} else {
						dlg_lokasi.dialog('close');
						dlg_lokasi.html('').html(info_error)
						.dialog('option','title','PERINGATAN')
						.dialog('option','buttons', {
							"OK" : function() {
								$(this).dialog('close');
							}
						}).dialog('open');
					}
				});
			}
		})
		.dialog('open');
		return false;
	}

	function open_prov(i){
		trig = $('#t_'+i).val();
		if (trig == 1){
			$('#r_'+i).text('');
			$('#t_'+i).val('0');
			$('#tr_'+i).hide();
			$('#but_'+i).html("<img border='0' src='./asset/img_source/add1.gif'>");
		}else{
		$.ajax({
			type:'POST',
			url:'index.php/lokasi/daftar_provinsi/'+i,
			success: function(data) {
				$('#r_'+i).html(data);
				$('#t_'+i).val('1');
				$('#tr_'+i).show();
				$('#but_'+i).html("<img border='0' src='./asset/img_source/rem1.gif'>");
			}
		});
		return false;
		}
	}

	function open_kota(i){
		trig = $('#t1_'+i).val();
		if (trig == 1){
			$('#r1_'+i).text('');
			$('#t1_'+i).val('0');
			$('#tr1_'+i).hide();
			$('#but1_'+i).html("<img border='0' src='./asset/img_source/add1.gif'>");
		}else{
		$.ajax({
			type:'POST',
			url:'index.php/lokasi/daftar_kota/'+i,
			success: function(data) {
				$('#r1_'+i).html(data);
				$('#t1_'+i).val('1');
				$('#tr1_'+i).show();
				$('#but1_'+i).html("<img border='0' src='./asset/img_source/rem1.gif'>");
			}
		});
		return false;
		}
	}
</script>

<table id='dataview' class='table'>
		<tr class='ui-widget-header'>
			<td align='center' width='230'>NEGARA</td>
			<td align='center' width='250'>PROVINSI</td>
			<td align='center' width='218'>KOTA</td>
		</tr>
</table>
<?php
	if ($list_neg->num_rows() > 0){
	echo "<table id='dataview' class='table'>";
		foreach ($list_neg->result() as $neg):
			echo "<tr class='ui-state-default'>
						<td width='15' id='a'>
						<a href='javascript:void(0)' onclick='open_prov(".$neg->negara_id.")'><div id='but_$neg->negara_id'><img border='0' src='./asset/img_source/add1.gif'></div></a></td>
						<td width='200'>
						<div style='float:left' class='editneg' id='$neg->negara_id'>$neg->negara_name</div>
						<div style='float:right'>
						";
			echo "<a href='javascript:void(0)' onclick=\"del_lokasi(".$neg->negara_id.",'negara');\"><img border='0' src='".base_url()."asset/img_source/button_empty.png'></a>";
			echo "		</div>
						</td>
				  </tr>
				  <tr style='display:none' id='tr_$neg->negara_id'>
						<td></td>
						<td></td>
						<td><div id='r_$neg->negara_id'></div><input type='hidden' id='t_$neg->negara_id' value='0'></td>
				  </tr>";
			
		endforeach;
	echo "</table>";
	}else{
		echo "Empty";
	}
?>
<br>
<i>
Untuk mengedit nama Negara, Provinsi atau Kota, Klik teks tersebut dan tekan enter.
</i>