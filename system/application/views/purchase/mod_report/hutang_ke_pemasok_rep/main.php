
<script type="text/javascript">

function setBulan(obj){
	if(obj.value == 0 ){
		document.forms["form_entry"].cari_bulan.disabled=true;		
	
	}else{
		
		document.forms["form_entry"].cari_bulan.disabled=false;		

	}
}


function bersihkanFilter(){
	$('#search_year').val('');
	$('#cari_bulan').val('');	
	$('#search_supplier').val('');
	$('#search_cat').val('');	
	
}


</script>

<h3><?=$title_page?></h3>

<div class="noprint">

<? if ($cari_status !=''): // cek klo blm di pijit tombolnya?>
<div align="right">
	 <form  method="post" action="index.php/<?=$link_controller?>/excel" >
	
	<!-- ====================== bwt seleksi,, klo kosong datanya eksport g aktid ========= -->
		<? if ($jumlah_data != 0){ ?>
				<input type="submit" value="<?=$this->lang->line('lap_salin_ke_excel');?>">	
				<input type="button" id="print" value="<?=$this->lang->line('lap_print');?>" onclick="window.print();">
		<? }else { ?>
				<input type="submit" value="<?=$this->lang->line('lap_salin_ke_excel');?>" disabled="disabled">	
		<input disabled="disabled" type="button" id="print" value="<?=$this->lang->line('lap_print');?>" onclick="window.print();">
		
		<? } ?>
	<!-- =============================================================================== -->

				<!-- lier cara ngirimnya,, pkae yg ini dulu aj ahhhhh -->	
				<input type=hidden name="search_year" value="<?=$search_year?>">				
				<input type=hidden name="search_month" value="<?=$search_month?>">
				<input type=hidden name="search_supplier" value="<?=$search_supplier?>">
				<input type=hidden name="search_cat" value="<?=$search_cat?>">
	&nbsp;
	</form>
</div>

<? endif; // akhir cek cari_status ?>

   <div  style="width:99%" class="ui-widget-content ui-corner-all">
    <form  name="form_entry" method="post" action="index.php/<?=$link_controller?>/index" >
   <table width="901" >
   	<tr>
   	  <td width="316">	<b><?=$this->lang->line('lap_judul_cari');?></b></td>
   	  <td width="283">&nbsp;</td>
   	  <td width="286" align="center">	  </td>
 	  </tr>
   	<tr>
		<td>     
		  <table width="104%" cellspacing="2" cellpadding="2">
	<tr>
			<td width="14%"><?=$this->lang->line('tahun');?></td>
			<td width="86%">:
			  <select name="search_year" id="search_year" style="width:150px" onchange="setBulan(this)" >
                <option value="0" selected="selected" >
                <?=$this->lang->line('combo_box_tahun');?>
                </option>
                <? foreach($data_tahun-> result() as $rows): ?>
                <option value="<?=$rows->thn?>"  <?=($rows->thn==$search_year)?('SELECTED="selected"'):('')?>>
                <?=$rows->thn?>
                </option>
                <? endforeach; ?>
              </select></td>
			</tr>
		<tr>
			<td width="14%"><?=$this->lang->line('kategori');?></td>
			<td width="86%">: 
			  <select name="search_cat" id="search_cat" style="width:150px" >
              <option value="0">
              <?=$this->lang->line('combo_box_kategori');?>
              </option>
              <? foreach ($data_kategori->result() as $rows):?>
              <option value="<?=$rows->cat_id?>" <?=($search_cat == $rows->cat_id)?('SELECTED="selected"'):('')?>>
              <?=$rows->cat_name?>
              </option>
              <?
							if ($search_cat == $rows->cat_id){
								$nama_kategori=($rows->cat_name);
							}
						?>
              <? endforeach;?>
            </select></td>
			</tr>
	</table>
	</td>
	
		<td><table width="104%" cellspacing="2" cellpadding="2">
          <tr>
            <td width="13%"><?=$this->lang->line('bulan');?></td>
            <td width="69%">:
             
			<? if ($search_year != 0 ){ ?>
			  	<select name="search_month" id="cari_bulan" style="width:150px"  >
			<? } else { ?>
			  	<select name="search_month" id="cari_bulan"  style="width:150px"  disabled="disabled">
			<? }?>		 
			
                
				
				<option value="0" >
                <?=$this->lang->line('combo_box_bulan');?>
                </option>
                <?php for ($i=1;$i<=12;$i++):?>
                <option value="<?=$i?>" <?=($search_month == $i)?('SELECTED="selected"'):('')?>>
                <?=$data_bulan[$i]?>
                </option>
                <?php endfor;?>
              </select></td>
            </tr>
          <tr>
            <td width="13%">&nbsp;</td>
            <td width="69%">&nbsp;</td>
            </tr>


        </table></td>
		<td><table width="104%" cellspacing="2" cellpadding="2">
          <tr>
            <td width="13%"><?=$this->lang->line('supplier');?></td>
            <td width="69%">:
              <select name="search_supplier" id="search_supplier" style="width:150px" >
                <option value="0">
                <?=$this->lang->line('combo_box_supplier');?>
                </option>
                <? foreach ($data_pemasok->result() as $rows):?>
                <option value="<?=$rows->sup_id?>" <?=($search_supplier == $rows->sup_id)?('SELECTED="selected"'):('')?>>
                <?=$rows->sup_name?>
                </option>
                <?
							if ($search_supplier == $rows->sup_id){
								$nama_sup=($rows->sup_name);
							}
						?>
                <? endforeach;?>
              </select></td>
          </tr>
          <tr>
            <td width="13%">&nbsp;</td>
            <td width="69%" align="right"> 
				<input type="button" name="bersihkan" value="<?=$this->lang->line('button_bersihkan');?>" onclick="bersihkanFilter()"/>
				<input name="cari" type="submit" value="<?=$this->lang->line('cari');?>" />
			</td>
          </tr>
        </table></td>
	</tr>
   	<tr>
   	  <td>&nbsp;</td>
   	  <td>&nbsp;</td>
   	  <td align="right">&nbsp;</td>
   	</tr>
   </table>
   </form>
    
	
  </div>
</div>
<div class="clr"></div>
<? if ($search_year != '0' || $search_month != '0' || $search_supplier != 0 || $search_cat != 0): ?>
	<table width="340" border="0">
  <tr>
    <td colspan="3"><?=$this->lang->line('lap_berdasarkan')?></td>
    </tr>
  
     <? if ($search_year != '0'): ?>
  <tr>
    <td width="115"><?=$this->lang->line('tahun')?></td>
    <td width="14">:</td>
    <td width="197"><font color="red" ><?=$search_year?> </font>    </td>
  </tr>
  <? endif;?>
   <? if ($search_month != '0'): ?>
  <tr>
    <td width="115"><?=$this->lang->line('bulan')?></td>
    <td width="14">:</td>
    <td width="197"><font color="red" ><?=$data_cari_bulan?> </font>    </td>
  </tr>
  <? endif;?>
  
  
  <? if ($search_supplier != 0):?>
  <tr>
    <td><?=$this->lang->line('supplier');?></td>
    <td>:</td>
    <td><font color="red" ><?=$nama_sup?>    </font></td>
  </tr>
  <? endif;?>
  
   <? if ($search_cat != 0):?>
  <tr>
    <td><?=$this->lang->line('kategori');?></td>
    <td>:</td>
    <td><font color="red" ><?=$nama_kategori?>    </font></td>
  </tr>
  <? endif;?>

</table>
<? endif;?>

<br />	
	
<? if ($cari_status !=''): // cek klo blm di pijit tombolnya?>
	
<div align="right"> <?=$this->lang->line('lap_ada');?><font color="red" > <?=$jumlah_data?>  </font> <?=$this->lang->line('lap_data');?></div>
<? endif; // akhir cek cari_status ?>
<font size="-3">  <!-- awal font seluruh tabel -->
<table width="100%"  border="1" cellpadding="0" cellspacing="1" class="ui-widget-content ui-corner-all">


<? if ($cari_status !=''): // cek klo blm di pijit tombolnya?>
<? if ($jumlah_data != 0){?>

			<tr  class="ui-state-default">
			  <td width="4%" align="center" rowspan="2"><?=$this->lang->line('no')?>
		      </td>
				<td width="20%" align="center" rowspan="2"><?=$this->lang->line('supplier')?></td>
				<td align="center" colspan="2"><?=$this->lang->line('lap_awal')?></td>
				<td align="center" colspan="2"><?=$this->lang->line('lap_pembelian')?>
			    </td>
				<td align="center" colspan="2"><?=$this->lang->line('lap_pembayaran')?>
			   </td>
				<td align="center" colspan="2"><?=$this->lang->line('lap_akhir')?>
			   </td>
			</tr>
			<tr  class="ui-state-default">
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

			<tr bgcolor="lightgray" >
			  <td align="center" class="ui-state-active"><?=$no?></td>			
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

			<tr  class="ui-state-active">
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
			  <td colspan="10" align="center"><font color="#FF0000"><strong>
			    <?=$this->lang->line('lap_tabel_tidak_ada_data')?>
		     </strong></font></td>
			</tr>
			<? } //end else 		
			else:
			?>
			<tr >
				<td height="10" colspan="16" align="center" bgcolor="#FFFFFF"><font color="#FF0000"><strong><?=$this->lang->line('lap_tabel_pilih_kriteria');?></strong></font></td>
			</tr>
			<?php endif;?>

</table>

</font> <!-- akhir font seluruh tabel -->