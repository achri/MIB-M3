<?php
function list_bulan($name,$size = '',$min = '',$max = '') {
	$bln = array('Semua Bulan','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
	$in_bulan = "<select name='$name' size='$size'>";
	$no_bulan = 1;
	foreach ($bln as $nm_bln):
		$in_bulan .= "<option value='$no_bulan'>$nm_bulan</option>";
	endforeach;
	$in_bulan .= "</select>";
	
	return $in_bulan;
}
?>