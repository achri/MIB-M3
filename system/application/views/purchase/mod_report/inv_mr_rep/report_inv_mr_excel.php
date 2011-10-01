
<?
// bwt ekxport ke excel nya
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename='.$this->lang->line('lap_nama_file').'.xls');
header('Cache-Control: max-age=0');


?>


<h3><?=$title_page?></h3>


  <?
							foreach($data_kategori->result() as $rows){
							if ($cari_kategori == $rows->cat_id)
								$nama_kategori=($rows->cat_name);
							}
						?>

<!--  ================== bwt nampilin apa aja yng jadi filternya ====================== -->
<div class="clr"></div>
<? if ($cari_tahun != '0' || $cari_bulan != 0 || $cari_kategori != 0): ?>
	<table width="340" border="0">
  <tr>
    <td colspan="3"><?=$this->lang->line('lap_berdasarkan')?></td>
    </tr>
  
     <? if ($cari_tahun != '0'): ?>
  <tr>
    <td width="115"><?=$this->lang->line('tahun')?></td>
    <td width="14">:</td>
    <td width="197"><font color="red" ><?=$cari_tahun?> </font>    </td>
  </tr>
  <? endif;?>
  
   <? if ($cari_bulan != '0'): ?>
  <tr>
    <td width="115"><?=$this->lang->line('bulan')?></td>
    <td width="14">:</td>
    <td width="197"><font color="red" ><?=$data_bulan[$cari_bulan]?> </font>    </td>
  </tr>
  <? endif;?>
  
  
  <? if ($cari_kategori != 0):?>
  <tr>
    <td><?=$this->lang->line('kategori');?></td>
    <td>:</td>
    <td><font color="red" ><?=$nama_kategori?>    </font></td>
  </tr>
  <? endif;?>

</table>
<? endif;?>

<!--  ================== akhir bwt nampilin apa aja yng jadi filternya ====================== -->

<div align="right"> <?=$this->lang->line('lap_ada');?><font color="red" > <?=$jumlah_data_kategori?>  </font> <?=$this->lang->line('kategori');?></div>

<table width="99%" cellspacing="1" cellpadding="0" border="0" class="ui-widget-content ui-corner-all">
<?php 
	if ($jumlah_data_kategori >0 ): //if yang jumlah data kategori 
		for ($i=0 ; $i < sizeof($cat);$i++):?>
			<tr  class="ui-state-default">
				 <td width="38%" >
					<table >
					  <tr class="ui-state-default">
						<td width="377" ><?=$cat[$i]['cat_name']?></td>
						<td width="64"  align="right">						</td>
					  </tr>
					</table>					
							 </td>
				
				 
			     <td width="62%" align="right"> <? if($jumlah_data_kategori == 1): ?>
								<?=$this->lang->line('lap_ada');?> <?=$jumlah_data_produk?>  <?=$this->lang->line('data');?> <?=$cat[$i]['cat_name']?>
							<? endif;?>		</td>
			</tr>
			
			<tr >	 
				 <td colspan="2">
					<!-- tabel isi -->
					<table width="100%" border="1" cellpadding="0" cellspacing="1" class="ui-widget-content ui-corner-all">
						<tr bgcolor="#CCCCCC">
							<td width="6%" align="center"><?=$this->lang->line('no');?></td>
							<td width="13%" align="center"><?=$this->lang->line('lap_kode_barang');?></td>
							<td width="16%" align="center"><?=$this->lang->line('lap_nama_barang');?></td>
							<td width="6%" align="center"><?=$this->lang->line('satuan');?></td>
							<td width="6%" align="center"><?=$this->lang->line('kartu_stok').$this->lang->line('supplier');?></td>
							<td width="59%" align="center"><?=$this->lang->line('lap_jumlah_dipakai');?></td>
						</tr>
					<?php 
						if (isset($pro[$i])): // if satu
						$no=1;
						 for ($j = 0; $j < sizeof($pro[$i]);$j++): // for satu
					?>
						<tr valign="middle" >
							<td align="center" class="ui-state-active"><?=$no?></td>
							<td ><?=$pro[$i][$j]['pro_code']?></td>
							<td ><?=$pro[$i][$j]['pro_name']?></td>
							<td align="center"><?=$pro[$i][$j]['satuan_name']?></td>								
							<td align="center">
								<?php 
									if ($pro[$i][$j]['is_stockJoin'] == '0')
									{
										echo $this->lang->line('spesifik');
									}else if ($pro[$i][$j]['is_stockJoin'] == '1')
									{
									
										echo $this->lang->line('general');
									}									
								?>							</td>
							<td align="center" >
							  <font size="-2">
								<!-- awal tabel dalam -->
								<table width="125%" cellspacing="1" border="1" >																
								<?php 
								  $total=0;
								  $total_harga=0;
								  if (isset($mr_det[$i][$j])): // if dua
								?>
									<tr bgcolor="#CCCCCC" border="1" >
									  <td rowspan="2"  align="center" >
									  <?=$this->lang->line('nama').'&nbsp;'.$this->lang->line('supplier');?></td>
									  <td colspan="2"  align="center"><?=$this->lang->line('pemakaian')?></td>
									  <td width="10%" rowspan="2"  align="center"><?=$this->lang->line('sisa_persedian_barang')?></td>									  
								      <td colspan="2"  align="center"><?=$this->lang->line('harga');?></td>
								  </tr>
									<tr bgcolor="#CCCCCC" border="1" >
									
										<td width="9%"  align="center"><?=$this->lang->line('realisasi')?></td>										
										<td width="9%"  align="center"><?=$this->lang->line('digunakan')?></td>										
									    <td width="17%"  align="center"><?=$this->lang->line('satuan');?></td>
									    <td width="16%"  align="center"><?=$this->lang->line('jumlah');?></td>
								    </tr>									
								 <?php
									for ($k = 0; $k < sizeof($mr_det[$i][$j]);$k++): // for dua
										if ($mr_det[$i][$j][$k]['grl_realisasi'] != '0' ): // if tiga ( seleksi pemesanan gudang sedang proses			
										
									
										
										$simbol_mata_uang = $mr_det[$i][$j][$k]['cur_symbol'];
										//untuk seleksi mata uang
											if ($mr_det[$i][$j][$k]['cur_id'] == 1)
											{
												$digit_mata_uang = $this->general->digit_rp();
											} else if ($mr_det[$i][$j][$k]['cur_id'] == 2)
											{
												$digit_mata_uang = $this->general->digit_dolar();
											} else 
											{
												$digit_mata_uang = 2;
											}
									
								 ?>
									<tr border="1" bgcolor="#99CCCC" valign="middle">
										<td width="39%" >											
											<?=($mr_det[$i][$j][$k]['sup_name']!='')?($mr_det[$i][$j][$k]['sup_name']):('-')?>										</td>															
										<td align="center">											
											<?=$this->general->digit_number($mr_det[$i][$j][$k]['id_satuan'],$mr_det[$i][$j][$k]['qty_mr'])?></td>
										<td  align="center">											
											<?=$this->general->digit_number($mr_det[$i][$j][$k]['id_satuan'],$mr_det[$i][$j][$k]['qty_use'])?></td>
										<td align="center">
											<?php $bal_price = $this->general->digit_number($mr_det[$i][$j][$k]['id_satuan'],$mr_det[$i][$j][$k]['inv_end'])?>									
											<?= $total=$total+$bal_price;?></td>
								        <td align="right"><?=$mr_det[$i][$j][$k]['cur_symbol']?>.<?=number_format($mr_det[$i][$j][$k]['harga_satuan'],$digit_mata_uang)?></td>
								        <td align="right">
											<?php
												$jum=0;
												$jum=$mr_det[$i][$j][$k]['harga_satuan'] * $mr_det[$i][$j][$k]['qty_mr'];
												echo $mr_det[$i][$j][$k]['cur_symbol'].'.'.number_format($jum,$digit_mata_uang);
												$total_harga=$total_harga+$jum;
											?>										</td>								        
								  </tr>
								  
								<?php													
									 else: // else tiga ( seleksi pemesanan gudang sedang proses
								?>
									<tr bgcolor="#99CCCC">
										<td>
											<?=($mr_det[$i][$j][$k]['sup_name']!='')?($mr_det[$i][$j][$k]['sup_name']):('-')?>										</td>
										<td colspan="6" align="center">
											<font color="red">
												<?=$this->lang->line('lap_dalam_proses')?>
											</font>										</td>
									</tr>
								
								<?			
									endif; // endif tiga ( seleksi pemesanan gudang sedang proses													
									 endfor;  // endfor dua
								?>
								<tr  bgcolor="#CCCCCC">
									<td colspan="5" align="right" ><?=$this->lang->line('total')?></td>									<td align="right"><?=$simbol_mata_uang.'.'.number_format($total_harga,$digit_mata_uang);?> </td>
								</tr>
								<?
								   else: // else if dua
								?>
										<tr>
											<td colspan="7" align="center"><?=$this->lang->line('lap_barang_belum_dipakai')?></td>
										</tr>
								<?php endif; // endif if dua?>
							  </table>				
								<!-- akhir tabel dalam -->
							  </font>							</td>
						</tr>
					<?php 
						$no++;
						endfor; // endfor for satu
						else: // else if satu
					?>
						<tr >
							<td colspan="8" align="center" bgcolor="#FFFFFF">
								<font color="#FF0000">
									<strong>
										<?=$this->lang->line('lap_tabel_tidak_ada_data');?>
									</strong>								</font>							</td>
						</tr>
					<?php endif; // end if satu?>
				   </table>
					<!-- akhir tabel isi -->				 </td>
			</tr>		
			
			<tr >	 
				 <td colspan="2">&nbsp;<!-- untuk pembatasnya --></td>
			</tr>		
		<? endfor; ?>
	<? else: //else yang jumlah data kategori ?>
			<tr>
				<td colspan="2" align="center" bgcolor="#FFFFFF">
					<font color="#FF0000">
						<strong>
							<?=$this->lang->line('lap_tabel_tidak_ada_data');?>
						</strong>					</font>				</td>
			</tr>
	<? endif; //endif yang jumlah data kategori ?>
</table>
