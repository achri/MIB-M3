<?php 
// ============== bwt bisa di download,, bwaan php =======================
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename='.$this->lang->line('lap_nama_file').'.xls');
header('Cache-Control: max-age=0');

?>

<? foreach ($data_kategori->result() as $rows):
	if ($cari_kategori== $rows->cat_id){
		$nama_kategori=($rows->cat_name);
	}
   endforeach;
   
  foreach ($data_pemasok->result() as $rows):
	if ($cari_pemasok== $rows->sup_id){
		$nama_pemasok=($rows->sup_name);
	}
  endforeach;

?>

<h3><?=$title_page?></h3>

    <form method="post">
	<h4><?=$this->lang->line('lap_daftar');?> 
	
	
<? if ($cari_kategori != 0 || $search_status != 'A' || $cari_po_no != '' || $cari_pemasok !=0 || $seldate != $this->lang->line('lap_semua_tanggal')): ?>
	<table width="412" border="0">
  <tr>
    <td colspan="3"><?=$this->lang->line('lap_berdasarkan')?></td>
    </tr>
  
  
   <? if ($search_status != 'A'): ?>
  <tr>
    <td><?=$this->lang->line('status')?></td>
    
    <td>: <font color="red" ><?=$search_status_nama?> </font>    </td>
  </tr>
  <? endif;?>
  
  
    <? if ($search_year != 0): ?>
  <tr>
    <td><?=$this->lang->line('tahun')?></td>
   
    <td><font color="red" >:  <?=$search_year?> </font>   </td>
  </tr>
  <? endif;?>
  
    <? if ($search_month != 0): ?>
  <tr>
    <td><?=$this->lang->line('bulan')?></td>
   
    <td><font color="red" >:  <?=$data_bulan[$search_month]?> </font>   </td>
  </tr>
  <? endif;?>

  
  <? if ($cari_po_no != ''):?>
  <tr>
    <td><?=$this->lang->line('lap_no_po');?></td>
   
    <td><font color="red" >: <?=$cari_po_no?>    </font></td>
  </tr>
  <? endif;?>
  
   <? if ($cari_pemasok != ''	): ?>
   <tr>
    <td width="126"><?=$this->lang->line('supplier');?></td>
  
    <td width="276">: <font color="red" ><?=$nama_pemasok?> </font></td>
  </tr>
  <? endif;?>
  
     <? if ($cari_kategori != 0	): ?>
   <tr>
    <td width="126"><?=$this->lang->line('kategori');?></td>
  
    <td width="276">: <font color="red" ><?=$nama_kategori?> </font></td>
  </tr>
  <? endif;?>

</table>
<? endif;?>
</h4>
	
	</form>
<!-- bwt jumlah po yg dah diselsik -->
<div align="right"> <?=$this->lang->line('lap_ada');?> <font color="red" ><?=$jumlah_po?> </font> <?=$this->lang->line('data');?></div>
	

<font size="-3">  <!-- awal font seluruh tabel -->

	<table width="99%"  border="1" cellpadding="0" cellspacing="0" class="ui-widget-content ui-corner-all">
		<tr bgcolor="#CCCCCC" class="ui-state-default">
		  <td width="3%" rowspan="2" align="center"><?=$this->lang->line('no');?></td>
		  <td width="9%" rowspan="2" align="center"><?=$this->lang->line('lap_no_po');?></td>
		  <td width="15%" rowspan="2" align="center"><?=$this->lang->line('supplier');?></td>
		  <td width="9%" rowspan="2" align="center"><?=$this->lang->line('lap_tanggal');?></td>
		  <td colspan="2" align="center"><?=$this->lang->line('lap_nilai_po');?></td>
		  <td colspan="2" align="center"><?=$this->lang->line('lap_nilai_sudah_diterima');?></td>
		  <td colspan="2" align="center"><?=$this->lang->line('selisih');?></td>
		  <td width="6%" rowspan="2" align="center"><?=$this->lang->line('status');?></td>
		  <td width="12%" rowspan="2" align="center"><?=$this->lang->line('keterangan');?>&nbsp;
	      <?=$this->lang->line('lap_po');?></td>
	  </tr>
		<tr bgcolor="#CCCCCC" class="ui-state-default">
		  <td width="6%" align="center"><?=$this->lang->line('rp')?></td>
		  <td width="6%" align="center"><?=$this->lang->line('us$')?></td>
		  <td width="6%" align="center"><?=$this->lang->line('rp')?></td>
		  <td width="6%" align="center"><?=$this->lang->line('us$')?></td>
		  <td width="6%" align="center"><?=$this->lang->line('rp')?></td>
		  <td width="6%" align="center"><?=$this->lang->line('us$')?></td>
        </tr>
		<?php 
		if ($data_po->num_rows() > 0):
		$no = $no_pos+1;
		foreach ($data_po->result() as $rows):?>
		<tr bgcolor="lightgray" valign="middle">
		  <td  align="center" class="ui-state-active"><?=$no?></td>
		  <td  align="center">
		  	<a href="index.php/<?=$link_controller?>/get_detail/<?=$rows->po_id?>/<?=$rows->po_no?>/<?=$rows->po_status?>">
			    <?=$rows->po_no?>
		  	</a>
		  </td> 		  
		  <td align="left"><?=$rows->sup_name.', '.$rows->legal_name?></td>
		  <td align="center"><?=$rows->po_date?></td>		  
		  <td align="right" ><?=number_format($rows->tot_rp,$this->general->digit_rp())?></td>
		  <td align="right" ><?=number_format($rows->tot_dol,$this->general->digit_dolar())?></td>
		  <td align="right" ><?=number_format($rows->rec_rp,$this->general->digit_rp())?></td>
		  <td align="right" ><?=number_format($rows->rec_dol,$this->general->digit_dolar())?></td>
		  <td align="right" ><?=number_format((($rows->tot_rp)-($rows->rec_rp)),$this->general->digit_rp())?></td>
		  <td align="right" ><?=number_format((($rows->tot_dol)-($rows->rec_dol)),$this->general->digit_dolar())?></td>
		  <td align="center"><?=$this->general->status('buka_tutup',$rows->po_status)?></td>
		  <td align="left"><?=$rows->po_note?></td>
		</tr>
		<?php 
		$no++;
		endforeach;
		?>
		<tr bgcolor="#CCCCCC" class="ui-state-active"align="right">
		  <td colspan="4" align="right"><strong><?=$this->lang->line('total');?> :</strong></td>
	      <td class="ui-widget-active"><?=number_format($total_po_rp,$this->general->digit_rp())?></td>
	      <td class="ui-widget-active"><?=number_format($total_po_dol,$this->general->digit_dolar())?></td>
	      <td class="ui-widget-active"><?=number_format($total_diterima_rp,$this->general->digit_rp())?></td>
	      <td class="ui-widget-active"><?=number_format($total_diterima_dol,$this->general->digit_dolar())?></td>
	      <td class="ui-widget-active"><?=number_format($total_selisih_rp,$this->general->digit_rp())?></td>
	      <td class="ui-widget-active"><?=number_format($total_selisih_dol,$this->general->digit_dolar())?></td>
	      <td colspan="2" class="ui-widget-active">&nbsp;</td>
      </tr>
	  
	  <!--  ========== buat paging nya ==============
		<tr><td colspan="11" class="ui-widget-header"> <?=$this->lang->line('halaman');?> : <?=($this->pagination->create_links())?($this->pagination->create_links()):('-')?></td></tr>
		
		========== buat paging nya ==============	-->
	
		<?php 
		else:
		?>
				<tr  >
			  <td colspan="16" align="center"><font color="#FF0000"><strong>
			    <?=$this->lang->line('lap_tabel_tidak_ada_data');?></strong></font></td>				
				</tr>
		<?php endif;?>
    </table>
</font>
