
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

		foreach($data_cari_pemasok->result() as $rows){
			if ($cari_pemasok == $rows->sup_id)
			$nama_pemasok=($rows->sup_name);
		}


?>



<script type="text/javascript">

function bersihkanFilter(){
	$('#cari_pemasok').val('');
	$('#cari_kategori').val('');
}


</script>

<h3><?=$title_page?></h3>

<div class="clr"></div>

<!--  ================== bwt nampilin apa aja yng jadi filternya ====================== -->
<div class="clr"></div>
<? if ($cari_kategori != '' || $cari_pemasok!= '' ): ?>
	<table width="340" border="0">
  <tr>
    <td colspan="3"><?=$this->lang->line('lap_berdasarkan')?></td>
    </tr>  

  <? if ($cari_pemasok != ''): ?>
  <tr>
    <td width="113"><?=$this->lang->line('supplier')?></td>
    <td width="204">:<font color="red" >
      <?=$nama_pemasok?>
    </font></td>
    <td width="9">&nbsp;</td>
  </tr>
  <? endif;?>
  
   <? if ($cari_kategori != ''): ?>
  <tr>
    <td width="113"><?=$this->lang->line('kategori')?></td>
    <td width="204">:<font color="red" >
      <?=$nama_kategori?>
    </font></td>
    <td width="9">&nbsp;</td>
  </tr>
  <? endif;?>
</table>
<? endif;?>

<!--  ================== akhir bwt nampilin apa aja yng jadi filternya ====================== -->


<? 
	$width_tabel='100%'; // untuk ukuran tabel bila belum pilih kriteria
	$width_tabel=696; // untuk ukuran tabel bila sudah pilih kriteria
?>	
<div>
	<div align="right"> <?=$this->lang->line('lap_ada');?><font color="red" > <?=$jumlah_data?>  </font> <?=$this->lang->line('lap_data');?></div>

<table width="<?=$width_tabel?>"  border="1" cellpadding="0" cellspacing="1" class="ui-widget-content ui-corner-all">		
		  <tr align="center" bgcolor="#CCCCCC">
			<td width="55" ><?=$this->lang->line('no')?></td>
			<td width="329"><?=$this->lang->line('supplier')?></td>
			<td width="308"><?=$this->lang->line('kategori')?></td>
		  </tr>
		  
		  <? if ($data_pemasok->num_rows() > 0): // if (query data pemasok) 
		  	 $no=1;
 	 		 foreach ($data_pemasok->result() as $row): // looping data_pemasok
			// foreach ($data_pemasok->result() as $row): // looping data_pemasok
		  ?>
		  
		  <tr valign="middle" >
			<td align="center" class="ui-state-active" ><?=$no?></td>
			<td><?=$row->sup_name?></td>
			<td><?=$row->cat_name?></td>
		  </tr>
		<?php 
			$no++;
			endforeach; // (akhir) looping data_pemasok
			else: // else (query data pemasok)
		?>		  
		  <tr >
			 <td colspan="3" align="center">
			 	<font color="#FF0000">
					<strong>
			    		<?=$this->lang->line('lap_tabel_tidak_ada_data');?>
					</strong>				</font>			 </td>				
		  </tr>  
		<?php endif; // endif (query data pemasok)	?>
</table>
  
  
</div>