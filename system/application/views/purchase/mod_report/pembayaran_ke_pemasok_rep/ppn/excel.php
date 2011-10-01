
<?php 
// ============== bwt bisa di download,, bwaan php =======================
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename='.$this->lang->line('lap_nama_file').'.xls');
header('Cache-Control: max-age=0');




	foreach($data_pemasok->result() as $rows):
              	if ($cari_pemasok == $rows->sup_id){
								$nama_pemasok=($rows->sup_name);
							}
						
              
    endforeach;

?>



<h3><?=$title_page?></h3>



<div class="clr"></div>

<? if ($cari_bulan != '0' || $cari_tahun != '0' ||  $cari_pemasok != '0' || $cari_no_bkbk != ''): ?>
	<table width="340" border="0">
  <tr>
    <td colspan="3"><?=$this->lang->line('lap_berdasarkan')?></td>
    </tr>
  
  
   <? if ($cari_tahun != '0'): ?>
  <tr>
    <td width="113"><?=$this->lang->line('tahun')?></td>
    <td width="191">:<font color="red" >
      <?=$cari_tahun?>
    </font></td>
    <td width="22">&nbsp;</td>
  </tr>
  <? endif;?>
  
   <? if ($cari_bulan != '0'): ?>
  <tr>
    <td width="113"><?=$this->lang->line('bulan')?></td>
    <td width="191">:<font color="red" >
      <?=$data_bulan[$cari_bulan]?>
    </font></td>
    <td width="22">&nbsp;</td>
  </tr>
  <? endif;?>

   <? if ($cari_pemasok!= '0'): ?>
  <tr>
    <td width="113"><?=$this->lang->line('supplier')?></td>
    <td width="191">:<font color="red" >
      <?=$nama_pemasok?>
    </font></td>
    <td width="22">&nbsp;</td>
  </tr>
  <? endif;?>

     <? if ($cari_no_bkbk != ''): ?>
  <tr>
    <td width="113"><?=$this->lang->line('lap_no_bkbk')?></td>
    <td width="191">:<font color="red" >
      <?=$cari_no_bkbk?>
    </font></td>
    <td width="22">&nbsp;</td>
  </tr>
  <? endif;?>
</table>
<? endif;?>



<br />

	<div align="right"> <?=$this->lang->line('lap_ada');?><font color="red" > <?=$jumlah_data?>  </font> <?=$this->lang->line('lap_data');?></div>

    <font size="-2">
    <table width="99%"  border="1" cellpadding="0" cellspacing="0" class="ui-widget-content ui-corner-all">
      <? if ($data_pembayaran->num_rows() > 0){ ?>
      <tr  class="ui-state-default" bgcolor="#CCCCCC">
        <td width="4%" align="center" rowspan="2"><?=$this->lang->line('no');?></td>
        <td width="7%" align="center" rowspan="2"><?=$this->lang->line('lap_tgl_bkbk');?>
        </td>
        <td width="7%" align="center" rowspan="2"><?=$this->lang->line('lap_no_bkbk');?>
        </td>
        <td width="24%" align="center" rowspan="2"><?=$this->lang->line('supplier');?>
        </td>
        <td colspan="2" align="center"><?=$this->lang->line('total');?></td>
        <td colspan="2" align="center"><?=$this->lang->line('ppn');?></td>
        <td align="center" colspan="2"><?=$this->lang->line('jumlah');?>
        </td>
      </tr>
      <tr  class="ui-state-default" bgcolor="#CCCCCC">
        <td width="11%" align="center"><?=$this->lang->line('rp')?></td>
        <td width="10%" align="center"><?=$this->lang->line('us$')?></td>
        <td width="9%" align="center"><?=$this->lang->line('rp')?></td>
        <td width="8%" align="center"><?=$this->lang->line('us$')?></td>
        <td width="11%" align="center"><?=$this->lang->line('rp')?></td>
        <td width="9%" align="center"><?=$this->lang->line('us$')?></td>
      </tr>
      <!-- ================== looping data  ========================= -->
      <? 
			if ( $data_pembayaran->result() > 0){
//			$no=1;// tanpa paging

			$no=$no_pos+1; // pake paging 
			$jumlah_ppn_rp		= 0;
			$jumlah_ppn_dol		= 0;
			$jumlah_total_rp	= 0;
			$jumlah_total_dol	= 0;
			
			foreach ( $data_pembayaran->result() as $row):
			$total_ppn_rp 	= 0;
			$total_ppn_dol 	= 0;			
			?>
      <tr bgcolor="lightgray" >
        <td  class="ui-state-active"align="center"><?=$no?></td>
        <td align="center"><?=$row->bkbk_date?></td>
        <td align="left"><?=$row->bkbk_no?></td>
        <td align="left"><?=$row->sup_name?>
          ,
          <?=$row->legal_name?></td>
        <td align="right"><?=number_format($row->pay_rp,$this->general->digit_rp())?></td>
        <td align="right"><?=number_format($row->pay_dol,$this->general->digit_dolar())?></td>
        <td align="right"><? 
					$total_ppn_rp	= $row->pay_rp * (10/100);
					$jumlah_ppn_rp 	= $jumlah_ppn_rp + $total_ppn_rp;
					echo number_format($total_ppn_rp,$this->general->digit_rp());
				?>
        </td>
        <td align="right"><? 
					$total_ppn_dol	= $row->pay_dol * (10/100);
					$jumlah_ppn_dol = $jumlah_ppn_dol + $total_ppn_dol;
					echo number_format($total_ppn_dol,$this->general->digit_dolar());
				?>
        </td>
        <td align="right"><?
					$jumlah_rp			= $row->pay_rp + $total_ppn_rp;
					$jumlah_total_rp 	= $jumlah_total_rp + $jumlah_rp;
					echo number_format($jumlah_rp,$this->general->digit_rp());
				?>
        </td>
        <td align="right"><?
					$jumlah_dol			= $row->pay_dol + $total_ppn_dol;
					$jumlah_total_dol 	= $jumlah_total_dol + $jumlah_dol;
					echo number_format($jumlah_dol,$this->general->digit_dolar());
				?>
        </td>
      </tr>
      <? 
			$no++;		
			endforeach; 
			
			} else {
			
			?>
      <tr bgcolor="lightgray" >
        <td colspan="10" align="center"><font color="#FF0000"><strong>
          <?=$this->lang->line('lap_tabel_tidak_ada_data');?>
        </strong></font> </td>
      </tr>
      <?
			} //end if
			?>
      <!-- ================== akhir looping data hutang ========================= -->
      <tr  class="ui-state-active">
        <td colspan="4" align="right"><strong>
          <?=$this->lang->line('total');?>
          : </strong> </td>
        <td align="right"><?=number_format($tot_rp,$this->general->digit_rp())?></td>
        <td align="right"><?=number_format($tot_dol,$this->general->digit_dolar())?></td>
        <td align="right"><?=number_format($jumlah_ppn_rp,$this->general->digit_rp())?></td>
        <td align="right"><?=number_format($jumlah_ppn_dol,$this->general->digit_dolar())?></td>
        <td align="right"><?=number_format($jumlah_total_rp,$this->general->digit_rp())?>
        </td>
        <td align="right"><?=number_format($jumlah_total_dol,$this->general->digit_dolar())?></td>
      </tr>
  
  <? } else { ?>
  <tr  >
    <td colspan="10" align="center"><font color="#FF0000"><strong>
      <?=$this->lang->line('lap_tabel_tidak_ada_data');?>
    </strong></font> </td>
  </tr>
  <? }  
			?>
    </table>
    </font>