<script type="text/javascript">


function bersihkanFilter(){
	$('#cari_kategori').val('');
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
      </table>	  </td>


      <td>
	  </td>
   	  <td>&nbsp;</td>
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
<table width="100%" cellspacing="0" cellpadding="0" border="1">
	<?php 
	if ($cari_status != ''):
	if ($jumlah_data_kategori >0 ): 
	for ($i=0; $i < sizeof($cat);$i++):
	?>
	<tr  class="ui-state-default">
	 <td colspan="2">
	 <table width="920"  border="0">
  <tr>
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
		<table width="100%" border="1" cellspacing="0" cellpadding="2">
			<tr class="ui-widget-header">
			  <td width="6%" align="center" rowspan="2"><?=$this->lang->line('no');?></td>
				<td width="15%" align="center" rowspan="2"><?=$this->lang->line('lap_group');?></td>
				<td width="11%" align="center" rowspan="2"><?=$this->lang->line('lap_kode_barang');?></td>
				<td width="26%" align="center" rowspan="2"><?=$this->lang->line('lap_nama_barang');?></td>
				<td align="center" colspan="3"><?=$this->lang->line('qty');?></td>
				<td width="9%" align="center" rowspan="2"><?=$this->lang->line('satuan');?></td>
			</tr>
			<tr  class="ui-state-default">
				<td width="10%"><?=$this->lang->line('lap_stok_akhir');?></td>
				<td width="10%"><?=$this->lang->line('lap_buffer');?></td>
				<td width="13%"><?=$this->lang->line('supplier');?></td>
			</tr>
			<?php 
			if (isset($pro[$i])):
			$no=1;
			for ($j=0; $j < sizeof($pro[$i]);$j++):
			?>
			<tr bgcolor="#EEEEEE">
			  <td valign="top" align="center"  class="ui-state-active" ><?=$no?></td>
				<td valign="top"><?=$pro[$i][$j]['group']?></td>
				<td valign="top"><?=$pro[$i][$j]['pro_code']?></td>
				<td valign="top"><?=$pro[$i][$j]['pro_name']?></td>
				<td colspan="3" align="left">
				    <table >
				    <?php 
				    	if (isset($stock[$i][$j])):
						for ($k=0; $k < sizeof($stock[$i][$j]);$k++):
						?>
						<tr>
							<td width="81"  valign="top" align="center">
								<?=number_format($stock[$i][$j][$k]['inv_end'],0)?>							</td>
							<td width="62"  valign="top" align="center">
								<?=$pro[$i][$j]['pro_min_reorder']?>							</td>
							<td width="57"  align="center">
								<?=($stock[$i][$j][$k]['sup_name']!='')?($stock[$i][$j][$k]['sup_name']):('-')?>							</td>
						</tr>
					   <?php 
					   endfor;
					   else:
					   ?>
					   	<tr bgcolor="#FFFFFF">
							<td colspan="3" align="center">---</td>
						</tr>
					   <?php 
					   endif;
					   ?>
					</table>				</td>
				
				<td valign="top" align="center"><?=$pro[$i][$j]['satuan_name']?></td>
			</tr>
			
			<?php 
			$no++;
			endfor;
			else:
			?>
			<tr >
				<td colspan="8" align="center" bgcolor="#FFFFFF"><font color="#FF0000"><strong><?=$this->lang->line('lap_tabel_tidak_ada_data');?></strong></font></td>
			</tr>
			<?php endif;?>
		</table>	 </td>
	</tr>
		<tr>
				<td colspan="2">&nbsp;				</td>
	</tr>
	<?php 
	endfor;
	else:
			?>
			<tr >
				<td colspan="8" align="center" bgcolor="#FFFFFF"><font color="#FF0000"><strong><?=$this->lang->line('lap_tabel_tidak_ada_data');?></strong></font></td>
			</tr>
			<?php endif;
					else:
			?>
			<tr >
				<td colspan="8" align="center" bgcolor="#FFFFFF"><font color="#FF0000"><strong><?=$this->lang->line('lap_tabel_pilih_kriteria');?></strong></font></td>
			</tr>
			<?php endif;?>
</table>
