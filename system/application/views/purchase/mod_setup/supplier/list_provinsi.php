<script type="text/javascript">
$('#provinsi').change(function() {
	var a = $('#provinsi').val();
	//alert (a);
	$.ajax({
		type: 'POST',
		url: 'index.php/<?php echo $link_controller;?>/get_kota',
		data: "id="+a,
		success: function(data) {
			$('#rkota').html(data);
			//alert (data);
		}
	});
	return false;
});

$('#kota').change(function() {
	var a = $('#kota').val();
	//alert (a);
	$.ajax({
		type: 'POST',
		url: 'index.php/<?php echo $link_controller;?>/get_code',
		data: "id="+a,
		success: function(data) {
			$('#phone1').val(data);
			$('#phone2').val(data);
			$('#phone3').val(data);
			$('#fax').val(data);
			//alert (data);
		}
	});
	return false;
});
</script>

<?php 
if ($set == "prov"){
	echo ": <select name='provinsi' id='provinsi'>
	<option value=''>[pilih - Provinsi]</option>";
	if ($list_prov->num_rows() > 0):
		foreach ($list_prov->result() as $row): 
			echo "<option value='$row->provinsi_id'>$row->provinsi_name</option>";
		endforeach;
		else:
			echo "Empty";
		endif;
	echo "</select>";
} else if ($set == "kota") {
	echo ": <select name='kota' id='kota'>
	<option value=''>[pilih - Kota]</option>";
	if ($list_kota->num_rows() > 0):
		foreach ($list_kota->result() as $rowz): 
			echo "<option value='$rowz->kota_id'>$rowz->kota_name</option>";
		endforeach;
		else:
			echo "Empty";
		endif;
	echo "</select>";
}
?>