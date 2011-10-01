
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
	$('#cari_tanggal_awal').val('');
	$('#cari_tanggal_akhir').val('');	
	$('#cari_no_kb').val('');
	$('#cari_no_po').val('');	
	$('#cari_pemasok').val('');

}

</script>



<h3><?=$page_title?></h3>
<div class="noprint">

<? if ($cari_status !=''):?>	
<div align="right">
<form  method="post" action="index.php/<?=$link_controller?>/excel" >
	
	<!-- ====================== bwt seleksi,, klo kosong datanya eksport g aktid ========= -->
	<?php if($jumlah_data > 0){?>	
		<input type="submit" value="<?=$this->lang->line('lap_salin_ke_excel');?>">	
		<input type="button" id="print" value="<?=$this->lang->line('lap_print');?>" onclick="window.print();">
	<?php }else {?>
		<input type="submit" disabled="disabled" value="<?=$this->lang->line('lap_salin_ke_excel');?>">
		<input type="button" id="print"  disabled="disabled"  value="<?=$this->lang->line('lap_print');?>" onclick="window.print();">
	<?php }?>
	<!-- =============================================================================== -->

	<!--  ================ bwt ngirim selesi ke excelnya =========== -->	

				<input type=hidden name="cari_tahun" value="<?=$cari_tahun?>">				
				<input type=hidden name="cari_bulan" value="<?=$cari_bulan?>">
				<input type=hidden name="cari_no_po" value="<?=$cari_no_po?>">				
				<input type=hidden name="cari_no_bpb" value="<?=$cari_no_bpb?>">
				<input type=hidden name="cari_no_kb" value="<?=$cari_no_kb?>">				
				<input type=hidden name="cari_pemasok" value="<?=$cari_pemasok?>">				

				<input type=hidden name="cari_tanggal_awal" value="<?=$cari_tanggal_awal?>">				
				<input type=hidden name="cari_tanggal_akhir" value="<?=$cari_tanggal_akhir?>">				

	<!-- ========================================================================== -->	

	</form>
</div>
<? endif; ?><!-- endif cari status -->


    <form name="form_entry" method="post" action="index.php/<?=$link_controller?>/index">
    <div style="width:99%" class="ui-widget-content ui-corner-all">
      <table width="101%">
	   &nbsp;<b><?=$this->lang->line('lap_judul_cari');?></b>
  <tr>
    <td width="28%"><table width="97%" cellspacing="5" cellpadding="5">
      <tr>
        <td width="22%"><?=$this->lang->line('tahun');?></td>
        <td width="78%">:
          <select name="cari_tahun" id="cari_tahun" style="width:150px" onchange="setBulan(this)">
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
      <tr>
        <td width="22%"><?=$this->lang->line('lap_no_kb')?></td>
        <td width="78%">:          
          <input type="text" style="width:150px" name="cari_no_kb" id="cari_no_kb" value="<?=$cari_no_kb?>" /></td>
      </tr>
    </table>
      </td>
    <td width="37%"><table width="96%" cellspacing="5" cellpadding="5">
      <tr>
        <td width="36%"><?=$this->lang->line('bulan');?></td>
        <td width="64%">:          
         <? if ($cari_tahun != 0 ){ ?>
			  	<select name="cari_bulan" id="cari_bulan" id="cari_bulan" style="width:150px"  >
			<? } else { ?>
			  	<select name="cari_bulan" id="cari_bulan" style="width:150px"  id="cari_bulan"  disabled="disabled">
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
      <tr>
        <td width="36%"><?=$this->lang->line('lap_no_po')?></td>
        <td width="64%">: <input type="text" style="width:150px" name="cari_no_po" id="cari_no_po"  value="<?=$cari_no_po?>" />          </td>
      </tr>
    </table></td>
    <td width="35%"><table width="123%" cellspacing="5" cellpadding="5">
      <tr>
        <td height="34"><font size="-2"><?=$this->lang->line('range_tanggal');?></font></td>
        <td> : 
          <input type="text" style="width:30px" name="cari_tanggal_awal" id="cari_tanggal_awal"  value="<?=$cari_tanggal_awal?>" />
          <?=$this->lang->line('s.d.');?>
          <input type="text" style="width:30px" name="cari_tanggal_akhir" id="cari_tanggal_akhir"  value="<?=$cari_tanggal_akhir?>" /></td>
        </tr>
      <tr>
        <td width="28%" height="34"><?=$this->lang->line('supplier');?></td>
        <td width="72%">:
          <select name="cari_pemasok" id="cari_pemasok" style="width:150px" >
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
      </tr>
    </table></td>
  </tr>
  <tr>
    <td width="28%">&nbsp;</td>
    <td width="37%">&nbsp;</td>
    <td width="35%" align="right"><input type="button" name="bersihkan" value="<?=$this->lang->line('button_bersihkan');?>" onclick="bersihkanFilter()"/>
    <input name="cari" type="submit" value="<?=$this->lang->line('cari');?>" /></td>
  </tr>
</table>

	
	</div>
	</form>
</div>
<!--  ================== bwt nampilin apa aja yng jadi filternya ====================== -->
<div class="clr"></div>
<? if ($cari_tahun != '0' || $cari_bulan != 0 || $cari_pemasok != 0 || $cari_no_po != '' || $cari_no_bpb != '' || $cari_no_kb != ''  || $cari_tanggal_awal != '' || $cari_tanggal_akhir != ''): ?>
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
  
 <? if ($cari_tanggal_awal != '' and $cari_tanggal_akhir !=''): ?>
  <tr>
    <td width="115">
  <?=$this->lang->line('range_tanggal');?>
  </td>
    <td width="14">:</td>
    <td width="197"><font color="red" ><?=$cari_tanggal_awal?> <?=$this->lang->line('s.d.');?> <?=$cari_tanggal_akhir?></font>    </td>
  </tr>
  <? endif;?>  


   <? if ($cari_no_kb!= ''): ?>
  <tr>
    <td width="115">
      <?=$this->lang->line('lap_no_kb');?>
    </td>
    <td width="14">:</td>
    <td width="197"><font color="red" ><?=$cari_no_kb?> </font>    </td>
  </tr>
  <? endif;?>

   <? if ($cari_no_bpb!= ''): ?>
  <tr>
    <td width="115"><?=$this->lang->line('lap_no_bpb')?></td>
    <td width="14">:</td>
    <td width="197"><font color="red" ><?=$cari_no_bpb?> </font>    </td>
  </tr>
  <? endif;?>
  
     <? if ($cari_no_po!= ''): ?>
  <tr>
    <td width="115"><?=$this->lang->line('lap_no_po');?></td>
    <td width="14">:</td>
    <td width="197"><font color="red" ><?=$cari_no_po?> </font>    </td>
  </tr>
  <? endif;?>
  
   <? if ($cari_pemasok!= ''): ?>
  <tr>
    <td width="115">
  <?=$this->lang->line('supplier');?>
  </td>
    <td width="14">:</td>
    <td width="197"><font color="red" ><?=$nama_pemasok?> </font>    </td>
  </tr>
  <? endif;?>
</table>
<? endif;?>

<!--  ================== akhir bwt nampilin apa aja yng jadi filternya ====================== -->


<? if ($cari_status !=''):?>	
 <div align="right"> <?=$this->lang->line('lap_ada');?><font color="red" > <?=$jumlah_data?>  </font> <?=$this->lang->line('lap_data');?></div>
<? endif; ?><!-- endif cari status -->

<!-- <div align="left"> bisa keambil sih semuanya,, cm jadi di tengah jelek -->
<font size="-3">  <!-- awal font seluruh tabel -->

<table width="100%" border="0" cellspacing="2" cellpadding="2" class="ui-widget-content ui-corner-all">

<? if ($cari_status !=''):?>	
<?php if ($report_list->num_rows() > 0):?>
  <tr class="ui-state-default">
    <td width="5%" align="center" rowspan="2">
      <?=$this->lang->line('no')?>    </td>
    <td width="5%" align="center" rowspan="2">
      <?=$this->lang->line('tanggal')?>    </td>
    <td width="10%" align="center" rowspan="2">
      <?=$this->lang->line('lap_no_kb')?>    </td>
    <td width="15%" align="center" rowspan="2">
      <?=$this->lang->line('supplier')?>    </td>
    <td width="10%" align="center" rowspan="2"><?=$this->lang->line('lap_no_bpb')?></td>
    <td width="10%" align="center" rowspan="2"><?=$this->lang->line('lap_no_po')?></td>
    <td align="center" colspan="2">
      <?=$this->lang->line('total')?>    </td>
    <td width="5%" rowspan="2" align="center"><?=$this->lang->line('kurs_rp')?></td>
    <td width="6%" rowspan="2" align="center"><?=$this->lang->line('total_nilai')?></td>
    <td width="11%" align="center"><?=$this->lang->line('lap_jum_sudah_dibayar')?></td>
    <td width="8%" align="center">
      <?=$this->lang->line('lap_sisa_bayar')?>    </td>
  </tr>
  <tr class="ui-state-default"> 
    <td width="8%" align="center"><?=$this->lang->line('rp')?></td>
    <td width="7%" align="center"><?=$this->lang->line('us$')?></td>
    <td align="center"><?=$this->lang->line('rp')?></td>
    <td align="center"><?=$this->lang->line('rp')?></td>
  </tr>
  <?php 
			if ($report_list->num_rows() > 0):
			$i = $no_pos;
			$no=$no_pos+1;
			$jum_total_nilai=0;
			$jum_sudah_bayar=0;
			$jum_sisa_bayar=0;
			foreach ($report_list->result() as $rows): ?>
  <tr bgcolor="lightgray" valign="top">
    <td valign="top" align="center" 
 class="ui-state-active">
      <?=$no?>    </td>
    <td valign="top" align="center">
      <!--{if $kb[x.index_prev].con_no ne $kb[x.index].con_no}-->
      <?=$rows->con_date?>
      <!--{/if}-->    </td>
    <td align="center" valign="top">
      <!--{if $kb[x.index_prev].con_no ne $kb[x.index].con_no}-->
      <?=$rows->con_no?>
      <!--{/if}-->    </td>
    <td valign="top">
      <!--{if $kb[x.index_prev].con_no ne $kb[x.index].con_no}-->
      <?=$rows->sup_name?>
      <!--{/if}-->    </td>
    <td valign="top">
		
	<?
		$con_id=$rows->con_id;
		
		$kumpulan_no_gr='';
		$gr_no=array();
		$sql_gr = "select gr_no from prc_gr as gr where gr.con_id = $con_id ";
		foreach ($this->db->query($sql_gr)->result () as $rows_gr){
			$gr_no[]=$rows_gr->gr_no;
			
		}	
		$kumpulan_no_gr=implode(', ',$gr_no); // buat gabungin string ny
	
	
		echo $kumpulan_no_gr; // buat gabungin string ny
	
		
	?>	</td>
    <td valign="top">   <?=$rows->po_no?> </td>
    <td valign="top" align="right">
      <!--{if $kb[x.index_prev].gr_no ne $kb[x.index].gr_no}-->
      <?=number_format($rows->tot_rp,$this->general->digit_rp())?>
      <!--{/if}-->    </td>
    <td align="right" valign="top" >
      <!--{if $kb[x.index_prev].gr_no ne $kb[x.index].gr_no}-->
      <?=number_format($rows->tot_dol,$this->general->digit_dolar())?>
      <!--{/if}-->    </td>
    <td align="right">
	
	<?
		$kurs=0;
		$sql_kurs = "select grd.kurs from prc_contrabon as c
					 inner join prc_gr as gr on c.con_id = gr.con_id	
				     inner join prc_gr_detail as grd on gr.gr_id = grd.gr_id
					 where c.con_id = '$con_id';
					 ";
		foreach ($this->db->query($sql_kurs)->result () as $rows_kurs){		
			$kurs = $rows_kurs->kurs;
		}
		echo number_format($kurs,$this->general->digit_rp());
	
	?>
	
	
	</td>
    <td align="right">
		<?
			// untuk total nilai kursnya
			$tot_kurs = 0;
			if ($rows->cur_symbol == 'Rp'){
				$tot_kurs=$rows->tot_rp;				
			}else if ($rows->cur_symbol == 'US$'){
				$tot_kurs=$kurs*$rows->tot_dol;
			}
		
			$jum_total_nilai = $jum_total_nilai + $tot_kurs;
			echo number_format($tot_kurs,$this->general->digit_rp());
			
			
		?>
	
	</td>
    <td align="right"> 
   
	  <?
	  	  $jum_bayar=0;
		  
		  
		  if ($rows->cur_symbol == 'Rp'){
			$jum_bayar=$rows->pay_rp ;
		  }else if ($rows->cur_symbol == 'US$'){
 			$jum_bayar=$rows->pay_dol * $kurs;
	  	  }
		  echo  number_format($jum_bayar,$this->general->digit_rp());
		  $jum_sudah_bayar=$jum_sudah_bayar + $jum_bayar;
	  ?>    
	  
	  </td>
    <td align="right"> 
      <? 
	  	$sisa_bayar=0;
	  	$sisa_bayar=$tot_kurs-$jum_bayar;
		
		echo number_format($sisa_bayar,$this->general->digit_rp());
		
		$jum_sisa_bayar	= $jum_sisa_bayar + $sisa_bayar;
		
		?>
    </td>
  </tr> 
  
  <?php 
							$i++;
							$no++;
							endforeach;
							?>
<tr bgcolor="lightgray"  class="ui-state-active">
    <td colspan="6" align="right" valign="top" 
 class="ui-state-active" >
    <?=$this->lang->line('total')?><!--{/if}-->    </td>
    <td valign="top" align="right">
      <!--{if $kb[x.index_prev].gr_no ne $kb[x.index].gr_no}-->
      <?=number_format($total_rp,$this->general->digit_rp())?>
      <!--{/if}-->    </td>
    <td align="right" valign="top" >
      <!--{if $kb[x.index_prev].gr_no ne $kb[x.index].gr_no}-->
      <?=number_format($total_dol,$this->general->digit_dolar())?>
      <!--{/if}-->    </td>
    <td align="right">&nbsp;</td>
    <td align="right"><?=number_format($jum_total_nilai,$this->general->digit_rp())?></td>
    <td align="right"> 
      <?=number_format($jum_sudah_bayar,$this->general->digit_rp())?>    </td>
    <td align="right"> 
      <?=number_format($jum_sisa_bayar,$this->general->digit_rp())?>    </td>
  </tr>							
							
							
	<!-- bwt paging 
  <tr>
    <td colspan="12" class="ui-widget-header">
      <?=$this->lang->line('halaman')?>
      : 
      <?=($this->pagination->create_links())?($this->pagination->create_links()):('-')?>
    </td>
  </tr>
  -->
  <?php
							else:?>
			<tr  >
			  <td colspan="13" align="center"><font color="#FF0000"><strong>
			    <?=$this->lang->line('lap_tabel_tidak_ada_data');?></strong></font></td>				
				</tr>
  <?php endif;?>
<?php else:?>
			<tr  >
			  <td colspan="13" align="center"><font color="#FF0000"><strong>
			    <?=$this->lang->line('lap_tabel_tidak_ada_data');?></strong></font></td>				
				</tr>

<?php endif;

			else: //else cari status
			?>
			<tr >
				<td colspan="13" align="center" bgcolor="#FFFFFF"><font color="#FF0000"><strong><?=$this->lang->line('lap_tabel_pilih_kriteria');?></strong></font></td>
			</tr>
			<?php endif; // endif cari status?>
</table>

</font> <!-- akhir font seluruh tabel -->
<!-- </div> div yg ngoba bwt di print tea -->