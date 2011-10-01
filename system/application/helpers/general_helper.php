<?php  
if (!defined('BASEPATH')) exit('No direct script access allowed');
// MENGHITUNG BERAPA HARI DARI 2 TANGGAL
// $date1 = Tanggal Awal , $date2 = Tanggal Akhir  
function getDays($date1,$date2) {

	$st_a = strtotime($date1);
	
	$st_b = strtotime($date2);
	
	$gd_a = getdate( $st_a );
	
	$gd_b = getdate( $st_b );
	
	$a_new = mktime( 12, 0, 0, $gd_a['mon'], $gd_a['mday'], $gd_a['year'] );
	
	$b_new = mktime( 12, 0, 0, $gd_b['mon'], $gd_b['mday'], $gd_b['year'] );
	
	return round( abs( $a_new - $b_new ) / 86400 );

}

function numtoint($num) {
	$num = str_replace('.00','',$num);
	return str_replace(',','',$num);
}
 
?>