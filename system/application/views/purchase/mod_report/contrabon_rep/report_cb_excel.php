<?
// ============ buat export ke excel
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename='.$this->lang->line('lap_nama_file').'.xls');
header('Cache-Control: max-age=0');


?>



<h3><?=$page_title?></h3>
<!--  ================== bwt nampilin apa aja yng jadi filternya ====================== -->
<div class="clr"></div>
<? if ($cari_tahun != '0' || $cari_bulan != 0 || $cari_pemasok != 0 || $cari_no_po != '' || $cari_no_bpb != '' || $cari_no_kb != '' || $cari_tanggal_awal != '' || $cari_tanggal_akhir != '' ): ?>
	<table width="340" border="0">
  <tr>
    <td colspan="3"><?=$this->lang->line('lap_berdasarkan')?></td>
    </tr>
  
     <? if ($cari_tahun != '0'): ?>
  <tr>
    <td width="113"><?=$this->lang->line('tahun')?></td>
    <td width="204">:<font color="red" >
      <?=$cari_tahun?>
    </font></td>
    <td width="9">&nbsp;</td>
  </tr>
  <? endif;?>
  
   <? if ($cari_bulan != '0'): ?>
  <tr>
    <td width="113"><?=$this->lang->line('bulan')?></td>
    <td width="204">:<font color="red" >
      <?=$data_bulan[$cari_bulan]?>
    </font></td>
    <td width="9">&nbsp;</td>
  </tr>
  <? endif;?>
  
   <? if ($cari_tanggal_awal != '' and $cari_tanggal_akhir !=''): ?>
  <tr>
    <td width="115">
  <?=$this->lang->line('range_tanggal');?>
  </td>
    <td width="14">: <font color="red" ><?=$cari_tanggal_awal?> <?=$this->lang->line('s.d.');?> <?=$cari_tanggal_akhir?></font></td>
    <td width="197">    </td>
  </tr>
  <? endif;?>  
  
   <? if ($cari_bulan != '0'): ?>
  <? endif;?>
  


   <? if ($cari_no_kb!= ''): ?>
  <tr>
    <td width="113">
      <?=$this->lang->line('lap_no_kb');?>    </td>
    <td width="204">:<font color="red" >
      <?=$cari_no_kb?>
    </font></td>
    <td width="9">&nbsp;</td>
  </tr>
  <? endif;?>

   <? if ($cari_no_bpb!= ''): ?>
  <tr>
    <td width="113"><?=$this->lang->line('lap_no_bpb')?></td>
    <td width="204">:<font color="red" >
      <?=$cari_no_bpb?>
    </font></td>
    <td width="9">&nbsp;</td>
  </tr>
  <? endif;?>
  
     <? if ($cari_no_po!= ''): ?>
  <tr>
    <td width="113"><?=$this->lang->line('lap_no_po');?></td>
    <td width="204">:<font color="red" >
      <?=$cari_no_po?>
    </font></td>
    <td width="9">&nbsp;</td>
  </tr>
  <? endif;?>
  
       <? if ($cari_pemasok!= ''): ?>
  <tr>
    <td width="113">
      <?=$this->lang->line('supplier');?>  </td>
    <td width="204">:<font color="red" >
      <?=$nama_pemasok?>
    </font></td>
    <td width="9">&nbsp;</td>
  </tr>
  <? endif;?>
</table>
<? endif;?>

<!--  ================== akhir bwt nampilin apa aja yng jadi filternya ====================== -->


 <div align="right"> <?=$this->lang->line('lap_ada');?><font color="red" > <?=$jumlah_data?>  </font> <?=$this->lang->line('lap_data');?></div>

 <table width="100%" border="1" cellspacing="2" cellpadding="1" >
   <?php if ($report_list->num_rows() > 0):?>
   <tr bgcolor="#CCCCCC">
     <td width="5%" align="center" rowspan="2"><?=$this->lang->line('no')?>     </td>
     <td width="5%" align="center" rowspan="2"><?=$this->lang->line('tanggal')?>     </td>
     <td width="10%" align="center" rowspan="2"><?=$this->lang->line('lap_no_kb')?>     </td>
     <td width="15%" align="center" rowspan="2"><?=$this->lang->line('supplier')?>     </td>
     <td width="10%" align="center" rowspan="2"><?=$this->lang->line('lap_no_bpb')?></td>
     <td width="10%" align="center" rowspan="2"><?=$this->lang->line('lap_no_po')?></td>
     <td align="center" colspan="2"><?=$this->lang->line('total')?>     </td>
     <td width="6%" rowspan="2" align="center"><?=$this->lang->line('kurs_rp')?></td>
     <td width="7%" rowspan="2" align="center"><?=$this->lang->line('total_nilai')?></td>
     <td width="8%" align="center"><?=$this->lang->line('lap_jum_sudah_dibayar')?>     </td>
     <td width="9%" align="center"><?=$this->lang->line('lap_sisa_bayar')?>     </td>
   </tr>
   <tr bgcolor="#CCCCCC">
     <td width="7%" align="center"><?=$this->lang->line('rp')?></td>
     <td width="8%" align="center"><?=$this->lang->line('us$')?></td>
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
 class="ui-state-active"><?=$no?>     </td>
     <td valign="top" align="center"><!--{if $kb[x.index_prev].con_no ne $kb[x.index].con_no}-->
         <?=$rows->con_date?>
         <!--{/if}-->     </td>
     <td align="center" valign="top"><!--{if $kb[x.index_prev].con_no ne $kb[x.index].con_no}-->
         <?=$rows->con_no?>
         <!--{/if}-->     </td>
     <td valign="top"><!--{if $kb[x.index_prev].con_no ne $kb[x.index].con_no}-->
         <?=$rows->sup_name?>
         <!--{/if}-->     </td>
     <td valign="top"><?
		$con_id=$rows->con_id;
		
		$kumpulan_no_gr='';
		$gr_no=array();
		$sql_gr = "select gr_no from prc_gr as gr where gr.con_id = $con_id ";
		foreach ($this->db->query($sql_gr)->result () as $rows_gr){
			$gr_no[]=$rows_gr->gr_no;
			
		}	
		$kumpulan_no_gr=implode(', ',$gr_no); // buat gabungin string ny
	
	
		echo $kumpulan_no_gr; // buat gabungin string ny
	
		
	?>     </td>
     <td valign="top"><?=$rows->po_no?>     </td>
     <td valign="top" align="right"><!--{if $kb[x.index_prev].gr_no ne $kb[x.index].gr_no}-->
         <?=number_format($rows->tot_rp,$this->general->digit_rp())?>
         <!--{/if}-->     </td>
     <td align="right" valign="top" ><!--{if $kb[x.index_prev].gr_no ne $kb[x.index].gr_no}-->
         <?=number_format($rows->tot_dol,$this->general->digit_dolar())?>
         <!--{/if}-->     </td>
     <td align="right"><?
		$kurs=0;
		$sql_kurs = "select grd.kurs from prc_contrabon as c
					 inner join prc_gr as gr on c.con_id = gr.con_id	
				     inner join prc_gr_detail as grd on gr.gr_id = grd.gr_id
					 where c.con_id = '$con_id';
					 ";
		foreach ($this->db->query($sql_kurs)->result () as $rows_kurs){		
			$kurs = $rows_kurs->kurs;
		}
		echo number_format($kurs,2);
	
	?></td>
     <td align="right"><?
			// untuk total nilai kursnya
			$tot_kurs = 0;
			if ($rows->cur_symbol == 'Rp'){
				$tot_kurs=$rows->tot_rp;				
			}else if ($rows->cur_symbol == 'US$'){
				$tot_kurs=$kurs*$rows->tot_dol;
			}
		
			$jum_total_nilai = $jum_total_nilai + $tot_kurs;
			echo number_format($tot_kurs,$this->general->digit_rp());
			
			
		?></td>
     <td align="right"><?
	  	  $jum_bayar=0;
		  
		  
		  if ($rows->cur_symbol == 'Rp'){
			$jum_bayar=$rows->pay_rp ;
		  }else if ($rows->cur_symbol == 'US$'){
 			$jum_bayar=$rows->pay_dol * $kurs;
	  	  }
		  echo  number_format($jum_bayar,$this->general->digit_rp());
		  $jum_sudah_bayar=$jum_sudah_bayar + $jum_bayar;
	  ?></td>
     <td align="right"><? 
	  	$sisa_bayar=0;
	  	$sisa_bayar=$tot_kurs-$jum_bayar;
		
		echo number_format($sisa_bayar,$this->general->digit_rp());
		
		$jum_sisa_bayar	= $jum_sisa_bayar + $sisa_bayar;
		
		?></td>
   </tr>
   <?php 
							$i++;
							$no++;
							endforeach;
							?>
   <tr bgcolor="#CCCCCC">
     <td colspan="6" align="right" valign="top" 
 class="ui-state-active" ><?=$this->lang->line('total')?>
         <!--{/if}-->     </td>
     <td valign="top" align="right">
         <?=number_format($total_rp,$this->general->digit_rp())?>
         <!--{/if}-->     </td>
     <td align="right" valign="top" ><!--{if $kb[x.index_prev].gr_no ne $kb[x.index].gr_no}-->
         <?=number_format($total_dol,$this->general->digit_dolar())?>
         <!--{/if}-->     </td>
     <td align="right">&nbsp;</td>
     <td align="right"><?=number_format($jum_total_nilai,$this->general->digit_rp())?></td>
     <td align="right"><?=number_format($jum_sudah_bayar,$this->general->digit_rp())?></td>
     <td align="right"><?=number_format($jum_sisa_bayar,$this->general->digit_rp())?></td>
   </tr>
  
  <?php
							else:?>
  <tr  >
    <td colspan="13" align="center"><font color="#FF0000"><strong>
      <?=$this->lang->line('lap_tabel_tidak_ada_data');?>
    </strong></font></td>
  </tr>
  <?php endif;?>
  <?php else:?>
  <tr  >
    <td colspan="13" align="center"><font color="#FF0000"><strong>
      <?=$this->lang->line('lap_tabel_tidak_ada_data');?>
    </strong></font></td>
  </tr>
  <?php endif;

			?>
 </table>
