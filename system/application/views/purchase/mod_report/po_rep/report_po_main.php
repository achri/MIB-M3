
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
	$('#cari_po_no').val('');
	$('#cari_pemasok').val('');	
	$('#cari_kategori').val('');
	$('#search_status').val('');	
}
</script>
	

<h3><?=$title_page?></h3>



<div class="noprint">
<? if ($cari_status !=''): // cek klo blm di pijit tombolnya?>
<div align="right">
<form method="post" action="index.php/<?=$link_controller?>/excel" >
	<h4><?=$this->lang->line('lap_daftar_asas');?> <!-- ====================== bwt seleksi,, klo kosong datanya eksport g aktid ========= -->
	<?php if($data_po->num_rows() > 0){?>	
		<input type="submit" value="<?=$this->lang->line('lap_salin_ke_excel');?>">	
		<input type="button" id="print" value="<?=$this->lang->line('lap_print');?>" onclick="window.print();">
	<?php }else {?>
		<input type="submit" disabled="disabled" value="<?=$this->lang->line('lap_salin_ke_excel');?>">
		<input type="button" disabled="disabled" id="print" value="<?=$this->lang->line('lap_print');?>" onclick="window.print();">
	<?php }?>
	
	<!-- =============================================================================== -->


</h4>
	
	<!--  ================ beda deui bwt ngirim selesi ke excelnya =========== -->
	<input type=hidden name="search_status" value="<?=$search_status?>">
	<input type=hidden name="search_month" value="<?=$search_month?>">
	<input type=hidden name="search_year"  value="<?=$search_year?>">
	<input type=hidden name="cari_po_no"  value="<?=$cari_po_no?>">
	<input type=hidden name="cari_pemasok"  value="<?=$cari_pemasok?>">
	<input type=hidden name="cari_kategori"  value="<?=$cari_kategori?>">

	<!-- ========================================================================== -->	


	
  </form>
</div>
<? endif; // akhir if cari_status?>



<div class="noprint" >
    <form name="form_entry" method="post" action="index.php/<?=$link_controller?>/index">
    <div style="width:99%" class="ui-widget-content ui-corner-all">
	<b><?=$this->lang->line('lap_judul_cari');?></b>
	<table width="898" height="133"  cellpadding="5" cellspacing="5">
		<tr>
			<td width="18" height="37"><?=$this->lang->line('tahun');?></td>
			<td width="189">: 
			  <select name="search_year" id="search_year" style="width:150px" onchange="setBulan(this)">
                <option value="0" >
                <?=$this->lang->line('combo_box_tahun');?>
                </option>
                <?php foreach($data_year->result() as $thn):?>
                <option value="<?=$thn->year?>" <?=($search_year == $thn->year)?('SELECTED="selected"'):('')?>>
                <?=$thn->year?>
                </option>
                <?php endforeach;?>
              </select></td>
			<td width="39">&nbsp;</td>
			<td width="31"><?=$this->lang->line('bulan');?></td>
			<td width="176">:
              
			<? if ($search_year != 0 ){ ?>
			  	<select name="search_month" id="cari_bulan" style="width:150px"  >
			<? } else { ?>
			  	<select name="search_month"  id="cari_bulan" style="width:150px"  disabled="disabled">
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
			
            <td width="45">&nbsp;</td>
            <td width="67"><?=$this->lang->line('kategori');?></td>
            <input type=hidden name="search_cat" value="<?=$search_cat?>">
			
			<td width="198">:
              <select name="cari_kategori" id="cari_kategori" style="width:150px">
                <option value="0">
                <?=$this->lang->line('combo_box_kategori');?>
                </option>
                <? foreach ($data_kategori->result() as $rows):?>
                <option value="<?=$rows->cat_id?>" <?=($cari_kategori == $rows->cat_id)?('SELECTED="selected"'):('')?>>
                <?=$rows->cat_name?>
                </option>
                <?
							if ($cari_kategori == $rows->cat_id){
								$nama_kategori=($rows->cat_name);
							}
						?>
                <? endforeach;?>
              </select></td>
		</tr>
		<tr>
			<td width="18" height="37"><?=$this->lang->line('status');?></td>
			<td width="189">:
              <select name="search_status" id="search_status" style="width:150px">
                <option value="A" <?=($search_status == 'A')?("selected=selected"):('')?>>
                <?=$this->lang->line('combo_box_status');?>
                </option>
                <option value="0" <?=($search_status == '0')?("selected=selected"):('')?>>
                <?=$this->lang->line('combo_box_status_buka');?>
                </option>
                <option value="1" <?=($search_status == '1')?("selected=selected"):('')?>>
                <?=$this->lang->line('combo_box_status_tutup');?>
                </option>
              </select></td>
			<td>&nbsp;</td>
			<td><?=$this->lang->line('supplier');?></td>
			<td>:
              <select name="cari_pemasok" id="cari_pemasok" style="width:150px">
                <option value="0" >
                <?=$this->lang->line('combo_box_supplier');?>
                </option>
                <?php foreach($data_pemasok->result() as $rows):?>
                <option value="<?=$rows->sup_id?>" <?=( $cari_pemasok == $rows->sup_id )?('SELECTED="selected"'):('')?>>
                <?=$rows->sup_name?>
                <?
							if ($cari_pemasok == $rows->sup_id){
								$nama_pemasok=($rows->sup_name);
							}
						?>
                </option>
                <?php endforeach;?>
              </select></td>
			
          <td>&nbsp;</td>
			<td><?=$this->lang->line('lap_no_po');?></td>
			<td>:
            <input type="text" name="cari_po_no" id="cari_po_no" value="<?=$cari_po_no?>" width="0"/></td>
		</tr>
		<tr>
		  <td height="37">&nbsp;</td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  <td align="right">&nbsp;</td>
		  <td>&nbsp;</td>
		  <td align="right">
	   		    <input type="button" name="bersihkan" value="<?=$this->lang->line('button_bersihkan');?>" onclick="bersihkanFilter()"/>
		  		<input name="cari" type="submit" value="<?=$this->lang->line('cari');?>" />
			</td>
		  </tr>
	</table>
	</div>
	</form>
</div></div>
<div class="clr"></div>

<? if ($cari_status !=''): // cek klo blm di pijit tombolnya?>  
<? if ($cari_kategori != 0 || $search_status != 'A' || $cari_po_no != '' || $cari_pemasok !='' || $seldate != $this->lang->line('lap_semua_tanggal') || $search_month != 0 || $search_year != 0): ?>
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
<? endif; // akhir if cari_status?>






<br />
	
<? if ($cari_status !=''): // cek klo blm di pijit tombolnya?>
        <!-- bwt jumlah po yg dah diselsik -->
<div align="right"> <?=$this->lang->line('lap_ada');?><font color="red" > <?=$jumlah_po?>  </font> <?=$this->lang->line('data');?></div>
<? endif; // akhir if cari_status?>

<font size="-3">  <!-- awal font seluruh tabel -->

	<table width="99%"  border="0" cellpadding="0" cellspacing="1" class="ui-widget-content ui-corner-all">
	
<? if ($cari_status !=''): // cek klo blm di pijit tombolnya?>
	
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
		  	<a href="index.php/<?=$link_controller?>/get_detail/<?=$rows->po_id?>/<?=$rows->po_no?>/<?=$rows->po_status?>/view">
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
		<tr class="ui-state-active"align="right">
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
		<?php endif;
					else:
			?>
			<tr >
				<td height="19" colspan="16" align="center" bgcolor="#FFFFFF"><font color="#FF0000"><strong><?=$this->lang->line('lap_tabel_pilih_kriteria');?></strong></font></td>
			</tr>
			<?php endif;?>
    </table>
</font> <!-- akhir font seluruh tabel -->