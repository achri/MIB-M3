<?php
$svn = 'revisi.txt';
$versi = 'versi.txt';

$revisi = 300;
$type = 'Release';
$title= 'PT. INTERNUSA ASRIDAYA SAKTI';

// CEK REVISI DI SVN
if (file_exists($svn)) {
	// GET REVISI DARI SVN
	$rev = file($svn);
	$revisi = $rev[0];
}

// TULIS
$fp = fopen($versi, 'r+');
fwrite($fp, $revisi);
fclose($fp);

// CEK ATAU BUAT FILE UNTUK VERSI
if (file_exists($versi)) {
	$rev = file($versi);
	$revisi = $rev[0];
}else{

}


$config['m3_ppn'] = 'PPN'; // PPN, NON_PPN


$config['m3_year'] = date('Y');
$config['m3_title'] = $title;
$config['m3_jenis'] = $type;
$config['m3_versi'] = 'v.2.0.'.$revisi;
?>