
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

<table width="99%"  border="1" cellpadding="0" cellspacing="0" class="ui-widget-content ui-corner-all">
		<? if ($data_pembayaran->num_rows() > 0){ ?>

			<tr   bgcolor="#CCCCCC">
			  <td width="3%" align="center" rowspan="2"><?=$this->lang->line('no');?></td>
				<td width="6%" align="center" rowspan="2"><?=$this->lang->line('lap_tgl_bkbk');?>
			   </td>
				<td width="6%" align="center" rowspan="2"><?=$this->lang->line('lap_no_bkbk');?>
			    </td>
				<td width="15%" align="center" rowspan="2"><?=$this->lang->line('supplier');?>
			   </td>
				<td align="center" colspan="2"><?=$this->lang->line('total');?>
			   </td>
			</tr>
			<tr bgcolor="#CCCCCC">
				<td width="8%" align="center"><?=$this->lang->line('rp')?></td>
				<td width="7%" align="center"><?=$this->lang->line('us$')?></td>
			</tr>
			
			
			
			
		<!-- ================== looping data  ========================= -->
		<? 
			if ( $data_pembayaran->result() > 0){
//			$no=1;// tanpa paging

			$no=$no_pos+1; // pake paging 
			foreach ( $data_pembayaran->result() as $row):?>

			<tr bgcolor="lightgray" >
				<td  class="ui-state-active"align="center"><?=$no?></td>
				<td align="center"><?=$row->bkbk_date?></td>
				<td align="left"><?=$row->bkbk_no?></td>
				<td align="left"><?=$row->sup_name?>, <?=$row->legal_name?></td>
				<td align="right"><?=number_format($row->pay_rp,$this->general->digit_rp())?></td>
				<td align="right"><?=number_format($row->pay_dol,$this->general->digit_dolar())?></td>
 		 	</tr>

		<? 
			$no++;		
			endforeach; 
			
			} else {
			
			?>
			<tr bgcolor="lightgray" >
				<td colspan="6" align="center"><font color="#FF0000"><strong><?=$this->lang->line('lap_tabel_tidak_ada_data');?></strong></font>
			   </td>
			</tr>

			
			<?
			} //end if
			?>

		
		<!-- ================== akhir looping data hutang ========================= -->

			<tr  class="ui-state-active">
				<td colspan="4" align="right">
					<strong>
					<?=$this->lang->line('total');?> : 
					</strong>				</td>
				<td align="right"><?=number_format($tot_rp,$this->general->digit_rp())?>			</td>
				<td align="right"><?=number_format($tot_dol,$this->general->digit_dolar())?></td>
			</tr>
		<!-- untuk halaman
		<tr><td colspan="6" class="ui-widget-header"> <?=$this->lang->line('halaman');?> : <?=($this->pagination->create_links())?($this->pagination->create_links()):('-')?></td></tr>
			-->
					<? } else { ?>
					<tr  >
				<td colspan="6" align="center"><font color="#FF0000"><strong><?=$this->lang->line('lap_tabel_tidak_ada_data');?></strong></font>
			   </td>
			</tr>
			<? }  
			?>
</table>

