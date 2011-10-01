
<?

// bwt bisa didownload langsung
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename='.$this->lang->line('lap_nama_file').'.xls');
header('Cache-Control: max-age=0');

?>

<?
		foreach($data_kategori->result() as $rows){
			if ($cari_kategori == $rows->cat_code)
			$nama_kategori=($rows->cat_name);
		}

		foreach($data_kelas->result() as $rows){
			if ($cari_kelas == $rows->cat_code)
			$nama_kelas=($rows->cat_name);
		}
		
		foreach($data_grup->result() as $rows){
			if ($cari_grup == $rows->cat_code)
			$nama_grup=($rows->cat_name);
		}
		
		

?>



<h3><?=$title_page?></h3>
<div class="clr"></div>

<!--  ================== bwt nampilin apa aja yng jadi filternya ====================== -->
<div class="clr"></div>
<? if ($cari_tahun != '0' || $cari_bulan != 0 || $cari_status_produk != '' || $cari_kode != '' || $cari_kategori != '' || $cari_kelas != '' || $cari_grup != '' || $cari_produk != ''  ): ?>
	<table width="340" border="0">
  <tr>
    <td colspan="2"><?=$this->lang->line('lap_berdasarkan')?></td>
    </tr>
  
     <? if ($cari_tahun != '0'): ?>
  <tr>
    <td width="115"><?=$this->lang->line('tahun')?></td>
    <td>:<font color="red" > <?=$cari_tahun?> 
    </font>    </td>
    </tr>
  <? endif;?>
  
   <? if ($cari_bulan != '0'): ?>
  <tr>
    <td width="115"><?=$this->lang->line('bulan')?></td>
    <td>:<font color="red" > <?=$data_bulan[$cari_bulan]?> 
    </font>    </td>
    </tr>
  <? endif;?>
  
   <? if ($cari_bulan != '0'): ?>
  <? endif;?>
  
   <? if ($cari_status_produk!= ''): ?>
  <tr>
    <td width="115"><?=$this->lang->line('status');?></td>
    <td>:<font color="red" >
					<?
					if($cari_status_produk == 'active'){
						echo $this->lang->line('status_aktif');
					}else {
						echo $this->lang->line('status_tidak_aktif');
					}
					?> 
	</font>    </td>
    </tr>
  <? endif;?>


   <? if ($cari_kode!= ''): ?>
  <tr>
    <td width="115">
      <?=$this->lang->line('kode');?>  </td>
    <td>:<font color="red" > <?=$cari_kode?> 
    </font>    </td>
    </tr>
  <? endif;?>

   <? if ($cari_kategori!= ''): ?>
  <tr>
    <td width="115">
      <?=$this->lang->line('kategori');?>    </td>
    <td> : <font color="red" ><?=$nama_kategori?> 
    </font></td>
    </tr>
  <? endif;?>
  
     <? if ($cari_kelas!= ''): ?>
  <tr>
    <td width="115">
      <?=$this->lang->line('kelas');?>   </td>
    <td>: <font color="red" ><?=$nama_kelas?> (<?=$cari_kelas?>) </font>    </td>
    </tr>
  <? endif;?>
  
  
     <? if ($cari_grup!= ''): ?>
  <tr>
    <td width="115">
      <?=$this->lang->line('grup');?>    </td>
    <td>: <font color="red" ><?=$nama_grup?> (<?=$cari_grup?>) </font>    </td>
    </tr>
  <? endif;?>
  
       <? if ($cari_produk!= ''): ?>
  <tr>
    <td width="115">
		  <?=$this->lang->line('produk');?> </td>
    <td>: <font color="red" ><?=$cari_produk?> 
    </font>    </td>
    </tr>
  <? endif;?>
</table>
<? endif;?>

<!--  ================== akhir bwt nampilin apa aja yng jadi filternya ====================== -->


	<div align="right"> <?=$this->lang->line('lap_ada');?><font color="red" > <?=$jumlah_data?>  </font> <?=$this->lang->line('lap_data');?></div>

<br />
<table width="100%"  border="1" cellpadding="0" cellspacing="1" class="ui-widget-content ui-corner-all">

	
		<tr bgcolor="#CCCCCC" class="ui-state-default">
		  <td width="4%" align="center" class="ui-state-default" ><?=$this->lang->line('no');?></td>
		  <td width="12%" align="center"><?=$this->lang->line('kode');?></td>
		  <td width="17%" align="center"><?=$this->lang->line('kategori');?></td>
		  <td width="17%" align="center"><?=$this->lang->line('kelas');?></td>
		  <td width="17%" align="center"><?=$this->lang->line('grup');?></td>
		  <td width="24%" align="center"><?=$this->lang->line('produk');?></td>		
		  <td width="9%" align="center"><?=$this->lang->line('status');?></td>
	    </tr>
		
	
		
		<?php 
		// $no = 1; //tanpa paging
		$i=1;
		$no = $no_pos+1; // pake paging 
		if ($jumlah_data > 0): // klo data query ada
		for ($i=0; $i < sizeof($array_produk);$i++):
		?>
		<tr >
		  <td valign="middle" align="center" class="ui-state-active"><?=$no?> </td>
		  <td valign="middle" align="center" ><?=$array_produk[$i]['kode_produk']?></td>
		  <td valign="middle" align="left"><?=$array_produk[$i]['kategori_produk']?></td>
		  <td valign="middle" align="left"><?=$array_produk[$i]['kelas_produk']?></td>
		  <td valign="middle" align="left"><?=$array_produk[$i]['grup_produk']?></td>
		  <td valign="middle" align="left"><?=$array_produk[$i]['nama_produk']?></td>		  
		  <td valign="middle" align="center">
		  	<?
				if ($array_produk[$i]['status_produk'] == 'active' )
				{
					echo $this->lang->line('status_aktif');
				}else {
					echo '<font color=red>'.$this->lang->line('status_tidak_aktif').'</font>';
				}

			?>
		  </td>
	    </tr>
		<?php 
		$no++;
		endfor;
		else:// klo data query ada?>
		<tr>
			  <td colspan="7" align="center"><font color="#FF0000"><strong>
			    <?=$this->lang->line('lap_tabel_tidak_ada_data');?></strong></font></td>				
  </tr>
		<?php endif;?>
</table>
  