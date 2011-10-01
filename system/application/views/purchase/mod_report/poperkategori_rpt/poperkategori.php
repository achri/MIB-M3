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
			  	<select name="cari_bulan" id="cari_bulan" style="width:150px" disabled="disabled">
			<? }?>              <option value="0" >
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
<font size="-3">  <!-- awal font seluruh tabel -->
<table width="100%" cellspacing="1" cellpadding="1" border="0" class="ui-widget-content ui-corner-all" >
	<?php 
		if ($cari_status != ''):
	if ($jumlah_data_kategori >0 ): 
	for ($i=0 ; $i < sizeof($cat);$i++):?>
	<tr  class="ui-state-default">
	 <td>
<table width="920"  border="0">
  <tr>
          <td width="509" > 
            <?=$cat[$i]['cat_name']?> :: 
         <!-- di ilangin dulu 
           Rp. <?=number_format($total_rp,2);?>  
           $ <?=number_format($total_dol,2);?>
        -->
		
		     </td>
    <td width="401"  align="right">	
	<? if($jumlah_data_kategori == 1): ?>	
	<?=$this->lang->line('lap_ada');?> <?=$jumlah_data_produk?>  <?=$this->lang->line('data');?> <?=$cat[$i]['cat_name']?>
	<? endif;?>	</td>
  </tr>
</table>	 </td>
	</tr>
	<tr >
	 <td>
		<table width="100%"  border="0" cellspacing="1" cellpadding="0" class="ui-widget-content ui-corner-all">
        <tr class="ui-widget-header"> 
          <td width="5%" rowspan="2" align="center"> 
            <?=$this->lang->line('no');?>          </td>
          <td width="13%" rowspan="2" align="center"> 
            <?=$this->lang->line('lap_no_po');?>          </td>
          <td width="13%" rowspan="2" align="center"> 
            <?=$this->lang->line('lap_pemohon');?>          </td>
          <td width="13%" rowspan="2" align="center"> 
            <?=$this->lang->line('lap_kode_barang');?>          </td>
          <td width="13%" rowspan="2" align="center"> 
            <?=$this->lang->line('supplier');?>          </td>
          <td width="18%" rowspan="2" align="center"> 
            <?=$this->lang->line('kategori');?>          </td>
          <td width="18%" rowspan="2" align="center"> 
            <?=$this->lang->line('jenis_barang');?>          </td>
          <td width="18%" rowspan="2" align="center"> 
            <?=$this->lang->line('lap_nama_barang');?>          </td>
          <td width="9%" rowspan="2" align="center"> 
            <?=$this->lang->line('qty');?>          </td>
          <td width="9%" rowspan="2" align="center"> 
            <?=$this->lang->line('satuan');?>          </td>
          <td colspan="2" align="center"> 
            <?=$this->lang->line('harga');?>
            &nbsp; 
            <?=$this->lang->line('satuan');?>          </td>
          <td colspan="2" align="center"> 
            <?=$this->lang->line('total');?>          </td>
        </tr>
        <tr class="ui-widget-header"> 
          <td  align="center"><?=$this->lang->line('rp')?>. </td>
          <td align="center"><?=$this->lang->line('us$')?></td>
          <td  align="center"><?=$this->lang->line('rp')?>. </td>
          <td  align="center"><?=$this->lang->line('us$')?></td>
        </tr>
        <?php 
			if (isset($pro[$i])):
			
			// ======= emergenci ========
				$total_semua_rp=0;
				$total_semua_dol=0;				
			
			// ======= (akhir) emergenci ========
			
			
			$no=1;
			for ($j = 0; $j < sizeof($pro[$i]);$j++):?>
        <tr bgcolor="#CCCCCC" valign="top"> 
          <td valign="top" align="center" class="ui-state-active"> 
            <?=$no?>          </td>
          <td valign="top"> 
            <?=$pro[$i][$j]['po_no']?>          </td>
          <td valign="top"> 
            <?=$pro[$i][$j]['usr_name']?>          </td>
          <td valign="top"><?=$pro[$i][$j]['pro_code']?></td>
          <td valign="top"><?=$pro[$i][$j]['sup_name']?></td>
          <td valign="top"> 
            <?=$cat[$i]['cat_name']?>          </td>
          <td valign="top"> 
            <?=$pro[$i][$j]['class_name']?>          </td>
          <td valign="top"> 
            <?=$pro[$i][$j]['pro_name']?>          </td>
          <td valign="top" align="center"> 
           <?=$this->general->digit_number($pro[$i][$j]['satuan_id'],$pro[$i][$j]['qty'])?>          </td>
          <td valign="top" align="center"> 
            <?=$pro[$i][$j]['satuan_name']?>          </td>
          <? if ($pro[$i][$j]['cur_symbol']=='Rp') {?>
          <td align="right"> 
          <?=number_format($pro[$i][$j]['price'],$this->general->digit_rp());?></td>
		  
          <td align="center"> - </td>
          <td  align="right"> 
            <?=number_format($pro[$i][$j]['sub_total'],$this->general->digit_rp());?>    
			  <? $total_semua_rp=$total_semua_rp+$pro[$i][$j]['sub_total']; ?>	      </td>
          <td  align="center">-</td>
          <? 
		  
		  
		  ?>
          <? }else{ ?>
          <td align="center"> - </td>
          <td align="right"> 
            <?=number_format($pro[$i][$j]['price'],$this->general->digit_dolar());?>          </td>
          <td  align="center">-</td>
          <td  align="right"> 
            <?=number_format($pro[$i][$j]['sub_total'],$this->general->digit_dolar());?>
			
		  <? $total_semua_dol=$total_semua_dol+$pro[$i][$j]['sub_total']; ?>          </td>
          <? } ?>
        </tr>
        <?php 
			$no++;
		
			endfor;
			?>
        <tr bgcolor="#FFFFFF" class="ui-state-active"> 
          <td colspan="12" align="right" ><strong> 
            <?=$this->lang->line('total');?>
            &nbsp;&nbsp;</strong> </td>
          <td  align="right"><?=number_format($total_semua_rp,$this->general->digit_rp());?>          </td>
          <td  align="right"> 
            <?=number_format($total_semua_dol,$this->general->digit_dolar());?>          </td>
        </tr>
        <?
		  
			else:
			?>
        <tr bgcolor="#FFFFFF"> 
          <td colspan="15" align="center"><font color="#FF0000"><strong><?=$this->lang->line('lap_tabel_tidak_ada_data');?></strong></font></td>
        </tr>
        <?php endif;?>
      </table>	 </td>
	</tr>
	
		<tr>
				<td>&nbsp;				</td>
	</tr>
	
	<?php endfor;
	else:
			?>
			<tr >
				<td colspan="6" align="center" bgcolor="#FFFFFF"><font color="#FF0000"><strong><?=$this->lang->line('lap_tabel_tidak_ada_data');?></strong></font></td>
			</tr>
			<?php endif;
					else:
			?>
			<tr >
				<td colspan="6" align="center" bgcolor="#FFFFFF"><font color="#FF0000"><strong><?=$this->lang->line('lap_tabel_pilih_kriteria');?></strong></font></td>
			</tr>
			<?php endif;?>
</table>
</font> <!-- akhir font seluruh tabel -->
