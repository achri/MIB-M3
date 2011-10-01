
<?php 
//============== bwt bisa di download,, bwaan php =======================

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename='.$this->lang->line('lap_nama_file').'.xls');
header('Cache-Control: max-age=0');

?>



<h3><?=$title_page?></h3>

		<!-- sementauin bwt nampilin nama sup -->
					<? foreach ($data_pemasok->result() as $rows):?>

						<?
							if ($search_supplier == $rows->sup_id){
								$nama_sup=($rows->sup_name);
							}
						?>
									<? endforeach;?>
									
						<? foreach ($data_kategori->result() as $rows):?>

						<?
							if ($search_cat == $rows->cat_id){
								$nama_kategori=($rows->cat_name);
							}
						?>
									<? endforeach;?>
				


<div class="clr"></div>
<? if ($search_year != '0' || $search_month != '0' || $search_supplier != 0 || $search_cat != 0): ?>
	<table width="340" border="0">
  <tr>
    <td colspan="3"><?=$this->lang->line('lap_berdasarkan')?></td>
    </tr>
  
     <? if ($search_year != '0'): ?>
  <tr>
    <td width="110"><?=$this->lang->line('tahun')?></td>
    <td width="202">:<font color="red" >
      <?=$search_year?>
    </font></td>
    <td width="14">&nbsp;</td>
  </tr>
  <? endif;?>
   <? if ($search_month != '0'): ?>
  <tr>
    <td width="110"><?=$this->lang->line('bulan')?></td>
    <td width="202">:<font color="red" >
      <?=$data_cari_bulan?>
    </font></td>
    <td width="14">&nbsp;</td>
  </tr>
  <? endif;?>
  
  
  <? if ($search_supplier != 0):?>
  <tr>
    <td><?=$this->lang->line('supplier');?></td>
    <td>:<font color="red" >
      <?=$nama_sup?>
    </font></td>
    <td>&nbsp;</td>
  </tr>
  <? endif;?>
  
   <? if ($search_cat != 0):?>
  <tr>
    <td><?=$this->lang->line('kategori');?></td>
    <td>:<font color="red" >
      <?=$nama_kategori?>
    </font></td>
    <td>&nbsp;</td>
  </tr>
  <? endif;?>
</table>
<? endif;?>

<br />	
	
<div align="right"> <?=$this->lang->line('lap_ada');?><font color="red" > <?=$jumlah_data?>  </font> <?=$this->lang->line('lap_data');?></div>
<table width="100%"  border="1" cellpadding="0" cellspacing="2" >
<? if ($jumlah_data != 0){?>

			<tr bgcolor="#CCCCCC">
			  <td width="4%" align="center" rowspan="2"><?=$this->lang->line('no')?>
		      </td  bgcolor="lightgray">
				<td width="20%" align="center" rowspan="2"><?=$this->lang->line('supplier')?></td>
				<td align="center" colspan="2"><?=$this->lang->line('lap_awal')?></td>
				<td align="center" colspan="2"><?=$this->lang->line('lap_pembelian')?>
			    </td>
				<td align="center" colspan="2"><?=$this->lang->line('lap_pembayaran')?>
			   </td>
				<td align="center" colspan="2"><?=$this->lang->line('lap_akhir')?>
			   </td>
			</tr>
			<tr   bgcolor="#CCCCCC">
				<td width="10%" align="center"><?=$this->lang->line('rp')?></td>
				<td width="4%" align="center"><?=$this->lang->line('us$')?></td>
				<td width="10%" align="center"><?=$this->lang->line('rp')?></td>
				<td width="9%" align="center"><?=$this->lang->line('us$')?></td>
				<td width="11%" align="center"><?=$this->lang->line('rp')?></td>
				<td width="10%" align="center"><?=$this->lang->line('us$')?></td>
				<td width="10%" align="center"><?=$this->lang->line('rp')?></td>
				<td width="12%" align="center"><?=$this->lang->line('us$')?></td>
			</tr>
			
			
		<!-- ================== looping data hutang ========================= -->
		
	
		<? 
			$no=1;
		foreach ($data_hutang->result() as $rows):?>

			<tr >
			  <td align="center" ><?=$no?></td>			
			  <td align="left"><?=$rows->sup_name?></td>
			 <td align="right"><?=number_format($rows->awal_rp,$this->general->digit_rp())?></td>
			  <td align="right"><?=number_format($rows->awal_dol,$this->general->digit_dolar())?></td>
			  <td align="right"><?=number_format($rows->beli_rp,$this->general->digit_rp())?></td>
			  <td align="right"><?=number_format($rows->beli_dol,$this->general->digit_dolar())?></td>
			  <td align="right"><?=number_format($rows->bayar_rp,$this->general->digit_rp())?></td>
			  <td align="right"><?=number_format($rows->bayar_dol,$this->general->digit_dolar())?></td>
			  <td align="right"><? $akhir_rp= (($rows->awal_rp)+($rows->beli_rp))-($rows->bayar_rp); //untuk mengitung saldo akhirnya
									echo number_format($akhir_rp,$this->general->digit_rp());			  
			   					?>
			   </td>
			  <td align="right"><? $akhir_dol= (($rows->awal_dol)+($rows->beli_dol))-($rows->bayar_dol); //untuk mengitung saldo akhirnya
									echo number_format($akhir_dol,$this->general->digit_dolar());			  
			   					?>
			   </td>
 		 	</tr>
			
		<? 
			$no++;
		endforeach;?>
		
		
		
		<!-- ================== akhir looping data hutang ========================= -->
		<? $row_rp=$data_hutang_tot_rp->row();?>
		<? $row_dol=$data_hutang_tot_dol->row();?>

			<tr bgcolor="#CCCCCC">
			  <td colspan="2" align="center"><strong>
			    <?=$this->lang->line('total')?> :</strong></td>
				<td align="right"><?=number_format($row_rp->tot_awal_rp,$this->general->digit_rp())?></td>
				<td align="right"><?=number_format($row_dol->tot_awal_dol,$this->general->digit_dolar())?></td>

				<td align="right"><?=number_format($row_rp->tot_beli_rp,$this->general->digit_rp())?></td>
				<td align="right"><?=number_format($row_dol->tot_beli_dol,$this->general->digit_dolar())?></td>

				<td align="right"><?=number_format($row_rp->tot_bayar_rp,$this->general->digit_rp())?></td>
				<td align="right"><?=number_format($row_dol->tot_bayar_dol,$this->general->digit_dolar())?></td>

				<td align="right"><?=number_format($data_hutang_tot_akhir_rp,$this->general->digit_rp())?></td>
				<td align="right"><?=number_format($data_hutang_tot_akhir_dol,$this->general->digit_dolar())?></td>
			</tr>
			<? 	} // end if klo datanya ada
				else { // klo datanya ga ada?>

			<tr  >
			  <td colspan="2" align="center"><font color="#FF0000"><strong>
			    <?=$this->lang->line('lap_tabel_tidak_ada_data')?>
		     </strong></font></td>
			</tr>
			<? } //end else ?>
</table>

