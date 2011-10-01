<?php 
// ============== bwt bisa di download,, bwaan php =======================
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename='.$this->lang->line('lap_nama_file').'.xls');
header('Cache-Control: max-age=0');

?>


<h3><?=$title_page?> </h3>

<!--  ================== bwt nampilin apa aja yng jadi filternya ====================== -->
<div class="clr"></div>
<? if ($cari_tahun != '0' || $cari_bulan != 0 || $cari_no_mr != ''): ?>
	
<table width="340" border="0">
  <tr> 
    <td colspan="2"> 
      <?=$this->lang->line('lap_berdasarkan')?>
    </td>
  </tr>
  <? if ($cari_tahun != '0'): ?>
  <tr> 
    <td width="113"> 
      <?=$this->lang->line('tahun')?>
    </td>
    <td>:<font color="red" > 
      <?=$cari_tahun?>
      </font><font color="red" >&nbsp; </font> </td>
  </tr>
  <? endif;?>
  <? if ($cari_bulan != '0'): ?>
  <tr> 
    <td width="113"> 
      <?=$this->lang->line('bulan')?>
    </td>
    <td>:<font color="red" > 
      <?=$data_bulan[$cari_bulan]?>
      </font><font color="red" >&nbsp; </font> </td>
  </tr>
  <? endif;?>
  <? if ($cari_no_mr!= ''):?>
  <tr> 
    <td> 
      <?=$this->lang->line('no').' '.$this->lang->line('lap_mr');?>
    </td>
    <td>:<font color="red" > 
      <?=$cari_no_mr?>
      </font><font color="red" >&nbsp; </font></td>
  </tr>
  <? endif;?>
</table>
  <? endif;?>  
<!--  ================== akhir bwt nampilin apa aja yng jadi filternya ====================== -->



<div align="right"> 
  <?=$this->lang->line('lap_ada');?>
  <font color="red" > 
  <?=$jumlah_data?>
  </font> 
  <?=$this->lang->line('lap_data');?>
</div>


<center>
<table width="99%"  border="1" cellpadding="0" cellspacing="0" class="ui-widget-content ui-corner-all">
    <? if ($cari_status !=''): // if satu (cari status)?>
    <? if ($jumlah_data > 0){ // if dua (jumlah data mr)			
		//	$no=1;
			for ($i=0 ; $i < sizeof($mr_no);$i++): // for satu ( looping mr)
		?>
    <tr> 
      <td colspan="7">&nbsp;</td>
    </tr>
    <tr   bgcolor="#CCCCCC"> 
      <td colspan="7" align="left"> <strong> 
        <?=$this->lang->line('no')?>
        <?=$this->lang->line('lap_mr')?>
        : 
        <?=$mr_no[$i]['mr_no']?>
        </strong> </td>
    </tr>
    <tr  class="ui-state-default"> 
      <td width="4%" align="center"> 
        <?=$this->lang->line('no');?>
      </td>
      <td width="18%" align="center"> 
        <?=$this->lang->line('produk');?>
      </td>
      <td width="8%" align="center"> 
        <?=$this->lang->line('lap_acc_mr');?>
      </td>
      <td width="12%" align="center"> 
        <?=$this->lang->line('lap_cetak_form_keluar_barang');?>
      </td>
      <td width="16%" align="center"> 
        <?=$this->lang->line('lap_keluarkan_barang');?>
      </td>
      <td width="23%" align="center"> 
        <?=$this->lang->line('isi').' '.$this->lang->line('daftar').' '.$this->lang->line('lap_pemakaian_barang');?>
      </td>
      <td width="19%" align="center"> 
        <?=$this->lang->line('lap_kemabali_barang_sisa');?>
      </td>
    </tr>
    <!-- ===================== untuk looping datanya =================== -->
    <?	
		$no=1;
		if($jumlah_data_produk > 0): //if tiga (jumlah data produk)
			for ($j=0; $j < sizeof($pro[$i]);$j++): // for dua (data produk)
	?>
    <tr  bgcolor="lightgray" valign="middle"> 
      <td align="center" class="ui-state-active"> 
        <?=$no?>
      </td>
      <td valign="top" align="left"> 
        <?=$pro[$i][$j]['pro_name']?>
      </td>
      <td align="center"> 
        <?	// cek status ACC MR
				if ($pro[$i][$j]['status_acc'] == 1)
				{
					echo $this->lang->line('selesai');
				}
				else if ($pro[$i][$j]['status_acc'] == 2)
				{
					echo $this->lang->line('selesai').'<br>';
					echo '<a href=# title="'.$this->lang->line('tooltip_laporan_alasan').'" ><font color=blue size=-3>'.$this->lang->line('diubah_disetujui').'</font></a>';
				}
				else if ($pro[$i][$j]['status_acc'] == 3)
				{
					echo $this->lang->line('selesai').'<br>';
					echo '<a href=# title="'.$this->lang->line('tooltip_laporan_alasan').'" ><font color=blue size=-3>'.$this->lang->line('disetujui_dgn_catatan').'</font></a>';
				}
				else if ($pro[$i][$j]['status_acc'] == 4)
				{
					echo $this->lang->line('selesai').'<br>';
					echo '<a href=# title="'.$this->lang->line('tooltip_laporan_alasan').'" ><font color=blue size=-3>'.$this->lang->line('ditunda').'</font></a>';
				}
				else if ($pro[$i][$j]['status_acc'] == 5)
				{ 
					echo '<img src="./asset/img_source/ditolak.gif" height="10"/><br>';
					echo '<a href=# title="'.$this->lang->line('tooltip_laporan_alasan').'" ><font color=blue size=-3>'.$this->lang->line('ditolak').'</font></a>';
				}
				else
				{
					echo '<font color="red">'.$this->lang->line('tunggu').'</font>';
				}
			
			?>
      </td>
      <td align="center"> 
        <? // cek status cetak form keluar barang
				if ($pro[$i][$j]['cetak_form'] == 1)
				{
					echo $this->lang->line('selesai');
				}
				else 
				{
					echo '<font color="red">'.$this->lang->line('tunggu').'</font>';
				}			
			?>
      </td>
      <td align="center"> 
        <?  	// cek status keluarkan barang				
				if ($pro[$i][$j]['status_keluar_barang'] == 1 )
				{
					echo $this->lang->line('selesai');
				}
				else if ($pro[$i][$j]['status_keluar_barang'] == 1 && $pro[$i][$j]['status_keluar_barang'] != '')
				{
					echo $this->lang->line('selesai').'<br>';
					echo '<a href=# title="'.$this->lang->line('tooltip_laporan_alasan').'" ><font color=blue size=-3>'.$this->lang->line('alasan').'</font></a>';					
				}
				else 
				{
					echo '<font color="red">'.$this->lang->line('tunggu').'</font>';
				}			
			?>
      </td>
      <td align="center"> 
        <? // input pemakaian barang
				if ( $pro[$i][$j]['qty_use'] > 0)
				{
					$sisa_pemakaian = $pro[$i][$j]['realisasi_barang'] - $pro[$i][$j]['qty_use'];
					echo $this->lang->line('selesai');
					echo '<br><a href=# ><font color=blue size=-3>'.$this->lang->line('pemakaian').' '.$this->general->digit_number($pro[$i][$j]['satuan_id'],$pro[$i][$j]['qty_use']).' '.$pro[$i][$j]['nama_satuan'].'</font></a>';
					echo '<br><a href=# ><font color=blue size=-3>'.$this->lang->line('sisa').' '.$this->general->digit_number($pro[$i][$j]['satuan_id'],$sisa_pemakaian).' '.$pro[$i][$j]['nama_satuan'].'</font></a>';	
				}
				else if ( $pro[$i][$j]['qty_use'] == 0 && $pro[$i][$j]['is_closed'] == 1 )
				{
					echo '<font color=blue size=-3>'.$this->lang->line('tidak_dipakai').'</font>';
				}
				else 			
				{
					echo '<font color="red">'.$this->lang->line('tunggu').'</font>';
				}			
			?>
      </td>
      <td align="center">
	  		<? // input pemakaian barang
				if ( $pro[$i][$j]['is_closed'] == 1)
				{
					$sisa_pemakaian = $pro[$i][$j]['realisasi_barang'] - $pro[$i][$j]['qty_use'];
					echo $this->lang->line('selesai');
					echo '<br><a href=# ><font color=blue size=-3>'.$this->lang->line('dikembalikan').' '.$this->general->digit_number($pro[$i][$j]['satuan_id'],$sisa_pemakaian).' '.$pro[$i][$j]['nama_satuan'].'</font></a>';	
				}
				else 
				{
					echo '<font color="red">'.$this->lang->line('tunggu').'</font>';
				}			
			?>
	  </td>
    </tr>
    <?
		$no++;
		endfor; // endfor (data produk)
		else: // elsetiga ( jumlah data produk )
		?>
    <tr > 
      <td colspan="7" align="center" bgcolor="#FFFFFF"> <font color="#FF0000"> 
        <strong> 
        <?=$this->lang->line('lap_tabel_tidak_ada_data');?>
        </strong> </font> </td>
    </tr>
    <?
		 endif; // endif tiga (jumlah data produk )	
		endfor; // endfor ( looping mr )
	?>
    <!-- ===================== akhir untuk looping datanya =================== -->
    <? } else { ?>
    <tr > 
      <td colspan="7" align="center" bgcolor="#FFFFFF"> <font color="#FF0000"> 
        <strong> 
        <?=$this->lang->line('lap_tabel_tidak_ada_data');?>
        </strong> </font> </td>
    </tr>
    <? }			
		else : //else cari_status ?>
    <tr > 
      <td colspan="7" align="center" bgcolor="#FFFFFF"> <font color="#FF0000"> 
        <strong> 
        <?=$this->lang->line('lap_tabel_pilih_kriteria');?>
        </strong> </font> </td>
    </tr>
    <?php endif; // endif cari_status?>
  </table>

</center>

