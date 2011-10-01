<?
// ============ buat export ke excel
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename='.$this->lang->line('lap_nama_file').'.xls');
header('Cache-Control: max-age=0');


?>

<?
		foreach($data_kategori->result() as $rows){
			if ($cari_kategori == $rows->cat_code)
			$nama_kategori=($rows->cat_name);
		}

		foreach($data_pemasok->result() as $rows){
			if ($cari_pemasok == $rows->sup_id)
			$nama_pemasok=($rows->sup_name);
		}


?>




<h3><?=$title_page?></h3>


<div class="clr"></div>
<br />

<!--  ================== bwt nampilin apa aja yng jadi filternya ====================== -->
<div class="clr"></div>
<? if ($cari_tahun != '0' || $cari_bulan != 0 || $cari_kategori != 0 || $status_kb != 0 || $cari_pemasok != 0): ?>
	<table width="340" border="0">
  <tr>
    <td colspan="3"><?=$this->lang->line('lap_berdasarkan')?></td>
    </tr>
  
     <? if ($cari_tahun != '0'): ?>
  <tr>
    <td width="105"><?=$this->lang->line('tahun')?></td>
    <td width="175">:<font color="red" >
      <?=$cari_tahun?>
    </font></td>
    <td width="25">&nbsp;</td>
  </tr>
  <? endif;?>
  
   <? if ($cari_bulan != '0'): ?>
  <tr>
    <td width="105"><?=$this->lang->line('bulan')?></td>
    <td width="175">:<font color="red" >
      <?=$data_bulan[$cari_bulan]?>
    </font></td>
    <td width="25">&nbsp;</td>
  </tr>
  <? endif;?>
  
  
  <? if ($cari_kategori != 0):?>
  <tr>
    <td><?=$this->lang->line('kategori');?></td>
    <td>:<font color="red" >
      <?=$nama_kategori?>
    </font></td>
    <td>&nbsp;</td>
  </tr>
  <? endif;?>
  
  
  <? if ($cari_pemasok!= 0):?>
  <tr>
    <td><?=$this->lang->line('supplier');?></td>
    <td>:<font color="red" >
      <?=$nama_pemasok?>
    </font></td>
    <td>&nbsp;</td>
  </tr>
  <? endif;?>

  <? if ($status_kb!= 0):?>
  <tr>
    <td><?=$this->lang->line('status');?> <?=$this->lang->line('lap_kontra_bon');?></td>
    <td>:<font color="red" >
      <?=$this->lang->line('status_sudah');?>
    </font></td>
	<? if ($status_kb == 1){?>
	    <td>&nbsp;</td>
	<? } else if ($status_kb == 2){ ?>
	    <td width="17"><font color="red" ><?=$this->lang->line('status_belum');?>    </font></td>	
	<? }?>
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





<table width="100%"  border="1" cellpadding="0" cellspacing="1" 
>

	<? if ($jumlah_data >0){ // untuk seleksi ada data ga ?>
	
		<tr bgcolor="#CCCCCC" 
class="ui-state-default">
		  <td width="5%" align="center" rowspan="2"><?=$this->lang->line('no');?></td>
		  <td width="10%" align="center" rowspan="2"><?=$this->lang->line('lap_no_bpb');?></td>
		  <td width="10%" align="center" rowspan="2"><?=$this->lang->line('lap_no_po');?></td>
		  <td width="10%" align="center" rowspan="2"><?=$this->lang->line('lap_tgl_surat_jalan');?></td>
		  <td width="35%" align="center" colspan="2"><?=$this->lang->line('barang');?></td>
		  <td width="15%" align="center" rowspan="2"><?=$this->lang->line('supplier');?></td>
		  <td width="10%" align="center" rowspan="2"><?=$this->lang->line('lap_no_sj');?></td>
		  <td width="15%" align="center" rowspan="2"><?=$this->lang->line('qty');?></td>
		  <td width="15%" align="center" rowspan="2"><?=$this->lang->line('status');?>		    <?=$this->lang->line('lap_kontra_bon');?></td>
		  <td width="20%" align="center" colspan="2"><?=$this->lang->line('lap_harga_satuan');?></td>
		  <td width="20%" align="center" colspan="2"><?=$this->lang->line('total');?></td>
	    </tr>
		<tr bgcolor="#CCCCCC" 
class="ui-state-default">
		  <td align="center" width="15%"><?=$this->lang->line('kategori');?></td>
		  <td align="center" width="20%"><?=$this->lang->line('lap_nama_produk_kode');?></td>
		  <td align="center"><?=$this->lang->line('rp')?></td>
		  <td align="center"><?=$this->lang->line('us$')?></td>
		  <td align="center"><?=$this->lang->line('rp')?></td>
		  <td align="center"><?=$this->lang->line('us$')?></td>
		</tr>
	
		<?
			//$no=1;
			$no=1; //untunk paging
			//foreach($data_penerimaan->result() as $rows): // looping ntuk data penerimaan
			for ($i=0; $i < sizeof($data_penerimaan);$i++):// looping ntuk data penerimaan
				
			//	if ($data_penerimaan[$i]['cat_name'] == $nama_kategori ):
				//if ($data_penerimaan[$i]['cat_name'] !=''){// looping ntuk data penerimaan					
//				for ($j=0; $j < sizeof($data_kategori[$i]);$j++):
				
		?>
		
	
<tr  >
		  
   		 	<td valign="top" align="center" ><?=$no?> </td>
		  
   		  <td valign="top" align="center" ><?=$data_penerimaan[$i]['gr_no']?> </td>
		  <td valign="top" align="center"><?=$data_penerimaan[$i]['po_no']?></td>
		  <td valign="top" align="center"><?=$data_penerimaan[$i]['gr_dateSJ']?></td>
		  <td valign="top" align="center"> <?=$data_penerimaan[$i]['cat_name']?> </td>
		  <td valign="top" align="left"><?=$data_penerimaan[$i]['pro_name']?> <br> <?=$data_penerimaan[$i]['pro_code']?></td>
		  
    <td valign="top" align="left">
     <?=$data_penerimaan[$i]['sup_name']?>    </td>
		  <td valign="top" align="left"><?=$data_penerimaan[$i]['gr_suratJalan']?></td>
		  <td  align="right" valign="top"> <?=$this->general->digit_number($data_penerimaan[$i]['satuan_id'],$data_penerimaan[$i]['qty'])?>
		   <br> <?=$data_penerimaan[$i]['satuan_name']?> 	  </td>

		  <td  align="center" valign="top">
		  		  <? 
				if ($data_penerimaan[$i]['con_id'] <> 0 ){
				  echo $this->lang->line('status_sudah');
				}else if ($data_penerimaan[$i]['con_id'] == 0 ){
				  echo $this->lang->line('status_belum');
				 } else {
				 	echo "-";
				 }
			?>
		  
		  
		  </td>
	     <?	if ($data_penerimaan[$i]['cur_symbol'] =='Rp') { ?>
		
			  <td valign="top" align="right"><?=number_format($data_penerimaan[$i]['price'],$this->general->digit_rp())?></td>
			  <td valign="top" align="center">-</td>
			  <td valign="top" align="right"><?=number_format($data_penerimaan[$i]['gd_totprice'],$this->general->digit_rp())?></td>
			  <td valign="top" align="center">-</td>
		  <? } else { ?>
			  <td valign="top" align="center">-</td>
			  <td valign="top" align="right"><?=number_format($data_penerimaan[$i]['price'],$this->general->digit_dolar())?></td>
			  <td valign="top" align="center">-</td>
			  <td valign="top" align="right"><?=number_format($data_penerimaan[$i]['gd_totprice'],$this->general->digit_dolar())?></td>
		  <? }  ?>
  </tr>
		
		<?
			$no++; // untuk nambah penomoran
		//	endforeach; // akhir looping data_kategori
		//	endif; // end if kategori
			endfor; // akhir looping data_penerimaan
		
		?>
		<tr bgcolor="#d0d0d0" 
 class="ui-state-active">
			<td colspan="12" align="right"><strong>
			  <?=$this->lang->line('total');?> 
		    :</strong></td>
			<td align="right">
			  <?=number_format($total_rp,$this->general->digit_rp())?>		    </td>
			<td align="right"><?=number_format($total_dol,$this->general->digit_dolar())?></td>
		</tr>
		
		<!-- ============ buat pagingnya ================== 
		<tr><td colspan="15" class="ui-widget-header"> <?=$this->lang->line('halaman');?> : <?=($this->pagination->create_links())?($this->pagination->create_links()):('-')?></td></tr>
		 ============ akhir buat pagingnya ================== -->
		<? } else { //seleksi data ada ato ga ?>
			
			<!-- klo data nya kosong -->
			<tr  >
				<td colspan="16" align="center"><font color="#FF0000"><strong><?=$this->lang->line('lap_tabel_tidak_ada_data');?></strong></font>			   </td>
			</tr>
		
		<? } // akhir selsksi ada ada ato g ?>
</table>


