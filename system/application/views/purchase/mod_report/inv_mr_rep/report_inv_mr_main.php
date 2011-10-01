
<script type="text/javascript">

function setBulan(obj){
	if(obj.value == 0 ){
		document.forms["form_entry"].cari_bulan.disabled=true;		
	
	}else{
		
		document.forms["form_entry"].cari_bulan.disabled=false;		

	}
}



function bersihkanFilter(){
	$('#cari_tahun').val('');
	$('#cari_bulan').val('');		
	$('#cari_kategori').val('');
	$('#cari_tanggal_akhir').val('');	
	$('#cari_nama_barang').val('');
	$('#cari_kode_barang').val('');		
	$('#cari_kelompok').val('');
}

</script>

<h3><?=$title_page?></h3>

<div class="noprint">
<!-- ==================== button bwt ekxport & cetak ======================== -->
<? 
if ($cari_status !=''): // cek klo blm di pijit tombolnya?>
<div align="right">
  <table border="0">
  <tr>
    <td>
		<form method="post" action="index.php/<?=$link_controller?>/excel" > 	  
	<!-- ====================== bwt seleksi,, klo kosong datanya eksport g aktid ========= -->
		<? if ($jumlah_data_kategori != 0){ ?>
				<input type="submit" value="<?=$this->lang->line('lap_salin_ke_excel');?>">	
		<? }else { ?>
				<input type="submit" value="<?=$this->lang->line('lap_salin_ke_excel');?>" disabled="disabled">	
		
		<? } ?>
	<!-- =============================================================================== -->
		
				<!-- ekspor ke excel datanya -->	
				<input type=hidden name="cari_tahun" value="<?=$cari_tahun?>">				
				<input type=hidden name="cari_bulan" value="<?=$cari_bulan?>">
				<input type=hidden name="cari_kategori" value="<?=$cari_kategori?>">
				<input type=hidden name="cari_nama_barang" value="<?=$cari_nama_barang?>">
				<input type=hidden name="cari_kode_barang" value="<?=$cari_kode_barang?>">
				<input type=hidden name="cari_kelompok" value="<?=$cari_kelompok?>">
				<input type=hidden name="cari_status" value="<?=$cari_status?>">

		</form>	
	</td>
    <td><input type="button" id="print" value="<?=$this->lang->line('lap_print');?>" onclick="window.print();">
</td>
  </tr>
</table>
<? endif; // akhir if cari_status?>
<!-- ==================== akhir button bwt ekxport & cetak ======================== -->
</div>


<div class="noprint">
   <div  style="width:99%" class="ui-widget-content ui-corner-all">
 <form  name="form_entry" method="post" action="index.php/<?=$link_controller?>/index" >   
   <table width="901" >
   	<tr>
   	  <td width="268">	<b><?=$this->lang->line('lap_judul_cari');?></b></td>
   	  <td width="318">&nbsp;</td>
   	  <td width="299" align="center">	  </td>
 	  </tr>
   	<tr>
      <td><table width="104%" cellspacing="2" cellpadding="2">
        <tr>
          <td><?=$this->lang->line('tahun');?></td>
          <td>&nbsp;</td>
          <td><select name="cari_tahun" id="cari_tahun" style="width:150px" onchange="setBulan(this)">
              <option value="0" >
              <?=$this->lang->line('combo_box_tahun');?>
              </option>
              <?php foreach($data_tahun->result() as $thn):?>
              <option value="<?=$thn->thn?>" <?=($cari_tahun == $thn->thn)?('SELECTED="selected"'):('')?>>
              <?=$thn->thn?>
              </option>
              <?php endforeach;?>
          </select></td>
        </tr>
		<!-- =======tutup dulu ==========
          <tr>
            <td width="13%"><?=$this->lang->line('lap_group');?></td>
            <td width="5%">&nbsp;</td>
            <td width="82%"><input type="text" name="cari_kelompok" style="width:150px" value="<?=$cari_kelompok?>"/></td>
          </tr>
		 ======= akhir tutup dulu ==========-->
      </table></td>
   	  <!-- bwt langsung keplihnya pke onchange="document.form_entry.submit();" -->
      <td><table width="100%" cellspacing="2" cellpadding="2">
        <tr>
          <td><?=$this->lang->line('bulan');?></td>
          <td>&nbsp;</td>
          <td>
		  

			<? if ($cari_tahun != 0 ){ ?>
			  	<select name="cari_bulan" id="cari_bulan" style="width:150px"  >
			<? } else { ?>
			  	<select name="cari_bulan" id="cari_bulan"  style="width:150px"  disabled="disabled">
			<? }?>
			              <option value="0" >
              <?=$this->lang->line('combo_box_bulan');?>
              </option>
              <?php for ($i=1;$i<=12;$i++):?>
              <option value="<?=$i?>" <?=($cari_bulan == $i)?('SELECTED="selected"'):('')?>>
              <?=$data_bulan[$i]?>
              </option>
              <?php endfor;?>
          </select></td>
        </tr>
				<!-- =======tutup dulu ==========
          <tr>
            <td width="29%"><?=$this->lang->line('lap_kode_barang');?></td>
            <td width="5%">&nbsp;</td>
            <td width="66%"><input type="text" name="cari_kode_barang" style="width:150px" value="<?=$cari_kode_barang?>"/></td>
          </tr>
		 ======= akhir tutup dulu ==========-->
      </table></td>
   	  <td><table width="100%" cellspacing="2" cellpadding="2">
   	    <tr>
          <td><?=$this->lang->line('kategori');?></td>
   	      <td>&nbsp;</td>
   	      <td><select name="cari_kategori" id="cari_kategori" style="width:150px" >
              <option value="0" >
              <?=$this->lang->line('combo_box_kategori');?>
              </option>
              <?php foreach($data_kategori->result() as $rows):?>
              <option value="<?=$rows->cat_id?>" <?=( $cari_kategori == $rows->cat_id )?('SELECTED="selected"'):('')?>>
              <?=$rows->cat_name?>
              <?
							if ($cari_kategori == $rows->cat_id){
								$nama_kategori=($rows->cat_name);
							}
						?>
              </option>
              <?php endforeach;?>
          </select></td>
 	      </tr>
         		<!-- =======tutup dulu ==========
		  <tr>
            <td width="35%"><?=$this->lang->line('lap_nama_barang');?></td>
            <td width="3%">&nbsp;</td>
            <td width="62%"><input type="text" name="cari_nama_barang" style="width:150px" value="<?=$cari_nama_barang?>"/></td>
          </tr>
		akhir =======tutup dulu ==========-->
      </table></td>
 	  </tr>
   	
   	<tr>
		<td>&nbsp;</td>
		<!-- bwt langsung keplihnya pke onchange="document.form_entry.submit();" -->
		<td>&nbsp;</td>
		<td><table width="99%" cellspacing="2" cellpadding="2">

          <tr>
            <td width="13%">&nbsp;</td>
            <td width="5%">&nbsp;</td>
            <td width="82%" align="right">
				<input type="button" name="bersihkan" value="<?=$this->lang->line('button_bersihkan');?>" onclick="bersihkanFilter()"/>
				<input name="cari" type="submit" value="<?=$this->lang->line('cari');?>" />
			</td>
          </tr>
        </table></td>
	</tr>
   </table>
   
 </form>   
	
	
  </div>
</div>


</div>


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

<? if ($cari_status !=''):?>
<div align="right"> <?=$this->lang->line('lap_ada');?><font color="red" > <?=$jumlah_data_kategori?>  </font> <?=$this->lang->line('kategori');?></div>
<? endif?>

<table width="99%" cellspacing="1" cellpadding="0" border="0" class="ui-widget-content ui-corner-all">
<?php 
  if ($cari_status != ''): // if yang cari status
	if ($jumlah_data_kategori >0 ): //if yang jumlah data kategori 
		for ($i=0 ; $i < sizeof($cat);$i++):?>
			<tr  class="ui-state-default">
				 <td >
					<table >
					  <tr class="ui-state-default">
						<td width="509" ><?=$cat[$i]['cat_name']?></td>
						<td width="401"  align="right">
							<? if($jumlah_data_kategori == 1): ?>
								<?=$this->lang->line('lap_ada');?> <?=$jumlah_data_produk?>  <?=$this->lang->line('data');?> <?=$cat[$i]['cat_name']?>
							<? endif;?>
						</td>
					  </tr>
					</table>
				 </td>
			</tr>
			
			<tr bgcolor="#CCCCCC">	 
				 <td>
					<!-- tabel isi -->
					<table width="100%" border="0" cellpadding="0" cellspacing="1" class="ui-widget-content ui-corner-all">
						<tr class="ui-widget-header">
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
						<tr bgcolor="#CCCCCC" valign="middle" >
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
								?>
							</td>
							<td align="center" >
							  <font size="-2">
								<!-- awal tabel dalam -->
								<table width="125%" cellspacing="1" border="0" >																
								<?php 
								  $total=0;
								  $total_harga=0;
								  if (isset($mr_det[$i][$j])): // if dua
								?>
									<tr class="ui-widget-header" border="1" >
									  <td rowspan="2"  align="center" >
									  <?=$this->lang->line('nama').'&nbsp;'.$this->lang->line('supplier');?></td>
									  <td colspan="2"  align="center"><?=$this->lang->line('pemakaian')?></td>
									  <td width="10%" rowspan="2"  align="center"><?=$this->lang->line('sisa_persedian_barang')?></td>									  
								      <td colspan="2"  align="center"><?=$this->lang->line('harga');?></td>
								  </tr>
									<tr class="ui-widget-header" border="1" >
									
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
											<?=($mr_det[$i][$j][$k]['sup_name']!='')?($mr_det[$i][$j][$k]['sup_name']):('-')?>
										</td>
										<td colspan="6" align="center">
											<font color="red">
												<?=$this->lang->line('lap_dalam_proses')?>
											</font>
										</td>
									</tr>
								
								<?			
									endif; // endif tiga ( seleksi pemesanan gudang sedang proses													
									 endfor;  // endfor dua
								?>
								<tr class="ui-state-active">
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
					<!-- akhir tabel isi -->				 				 
				 </td>
			</tr>		
			
			<tr >	 
				 <td>&nbsp;<!-- untuk pembatasnya --></td>
			</tr>		
		<? endfor; ?>
	<? else: //else yang jumlah data kategori ?>
			<tr>
				<td align="center" bgcolor="#FFFFFF">
					<font color="#FF0000">
						<strong>
							<?=$this->lang->line('lap_tabel_tidak_ada_data');?>
						</strong>
					</font>
				</td>
			</tr>
	<? endif; //endif yang jumlah data kategori ?>
<? else: // else yang cari status ?>
			<tr >
				<td  align="center" bgcolor="#FFFFFF">
					<font color="#FF0000">
						<strong>
							<?=$this->lang->line('lap_tabel_pilih_kriteria');?>
						</strong>
					</font>
				</td>
			</tr>
<? endif; // endif yang cari status?>
</table>