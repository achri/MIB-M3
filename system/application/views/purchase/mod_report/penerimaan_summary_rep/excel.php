

<?php 
// ============== bwt bisa di download,, bwaan php =======================
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename='.$this->lang->line('lap_nama_file').'.xls');
header('Cache-Control: max-age=0');

?>




 <?php foreach($data_pemasok->result() as $rows):
			if ($cari_pemasok == $rows->sup_id){
				$nama_pemasok=($rows->sup_name);
			}
		endforeach;
 ?>



<h3><?=$title_page?></h3>
<div class="clr"></div>
<? if ($cari_bulan != '0' || $cari_tahun!= 0 || $cari_no_po!= '' || $cari_no_bpb!= ''): ?>
	<table width="376" border="0">
  <tr>
    <td colspan="3"><?=$this->lang->line('lap_berdasarkan')?></td>
    </tr>
  
  
   <? if ($cari_bulan != '0'): ?>
  <tr>
    <td width="103"><?=$this->lang->line('bulan')?></td>
    <td width="216">:<font color="red" >
      <?=$data_bulan[$cari_bulan]?>
    </font></td>
    <td width="43">&nbsp;</td>
  </tr>
  <? endif;?>
  
     <? if ($cari_tahun != '0'): ?>
  <tr>
    <td width="103"><?=$this->lang->line('tahun')?></td>
    <td width="216">:<font color="red" >
      <?=$cari_tahun?>
    </font></td>
    <td width="43">&nbsp;</td>
  </tr>
  <? endif;?>
  

  <? if ($cari_pemasok != 0):?>
  <tr>
    <td><?=$this->lang->line('supplier');?></td>
    <td>:<font color="red" >
      <?=$nama_pemasok?>
    </font></td>
    <td>&nbsp;</td>
  </tr>
  <? endif;?>

  <? if ($cari_no_bpb != ''):?>
  <tr>
    <td><?=$this->lang->line('lap_no_bpb');?></td>
    <td>:<font color="red" >
      <?=$cari_no_bpb?>
    </font></td>
    <td>&nbsp;</td>
  </tr>
  <? endif;?>

  <? if ($cari_no_po != 0):?>
  <tr>
    <td><?=$this->lang->line('lap_no_po');?></td>
    <td>:<font color="red" >
      <?=$cari_no_po?>
    </font></td>
    <td>&nbsp;</td>
  </tr>
  <? endif;?>

  <? if ($cari_no_sj != 0):?>
  <tr>
    <td><font size="-1"><?=$this->lang->line('lap_no_surat_jalan');?></font></td>
    <td>:<font color="red" >
      <?=$cari_no_sj?>
    </font></td>
    <td>&nbsp;</td>
  </tr>
  <? endif;?>
</table>
<? endif;?>

<br />
<? if ($cari_status !=''):?>	
<div align="right"> <?=$this->lang->line('lap_ada');?><font color="red" > <?=$jumlah_data?>  </font> <?=$this->lang->line('lap_data');?></div>
<? endif; ?><!-- endif cari status -->


<table width="101%"  border="1" cellpadding="0" cellspacing="1" class="ui-widget-content ui-corner-all">
<? if ($cari_status !=''):?>	

		<? if ($data_penerimaan->num_rows() > 0){ ?>
			<tr  bgcolor="#CCCCCC">
			  <td width="5%" align="center" rowspan="2"><?=$this->lang->line('no');?>
		      </td>
			  <td width="15%" align="center" rowspan="2"><?=$this->lang->line('lap_no_bpb');?></td>
			  <td width="15%" align="center" rowspan="2"><?=$this->lang->line('lap_no_po');?></td>
			  <td width="20%" align="center" rowspan="2"><?=$this->lang->line('supplier');?></td>
			  <td width="15%" align="center" rowspan="2"><?=$this->lang->line('lap_no_surat_jalan');?>
		      </td>
			  <td align="center" colspan="2"><?=$this->lang->line('total');?></td>
			</tr>
			<tr  bgcolor="#CCCCCC">
			  <td width="18%" align="center"><?=$this->lang->line('rp')?></td>
			  <td width="12%" align="center"><?=$this->lang->line('us$')?></td>
			</tr>
			
			<? 
			//	$no=1; //tanpa paging 
				$no=$no_pos+1; //pake paging
				foreach($data_penerimaan->result() as $row){
			?>

			<tr   >
			  <td valign="top" align="center" class="ui-state-active"><?=$no?> </td>
			  <td valign="top" align="center"><?=$row->gr_no?></td>
			  <td valign="top" align="center"><?=$row->po_no?></td>
			  <td valign="top" align="left"><?=$row->sup_name?></td>
			  <td valign="top" align="left"><?=$row->gr_suratJalan?></td>
			  <td valign="top" align="right"><?=number_format($row->tot_rp,$this->general->digit_rp())?></td>
			  <td valign="top" align="right"><?=number_format($row->tot_dol,$this->general->digit_dolar())?></td>
 		 	</tr>

		<? 
			$no++;	
			}	
			?>
			
			
			
		<!-- ================== looping data hutang ========================= -->
		
		
		<!-- ================== akhir looping data hutang ========================= -->

			<tr  bgcolor="#CCCCCC">
				<td colspan="5" align="right"><strong>Total :</strong></td>
				<td align="right"><?=number_format($data_penerimaan_tot_rp,$this->general->digit_rp())?></td>
				<td align="right"><?=number_format($data_penerimaan_tot_dol,$this->general->digit_dolar())?></td>
			</tr>
			
<!--  bwt paging	
		<tr><td colspan="7" class="ui-widget-header"> <?=$this->lang->line('halaman');?> : <?=($this->pagination->create_links())?($this->pagination->create_links()):('-')?></td></tr>
-->
			
			<? } else { ?>
				<tr  >
			  <td colspan="7" align="center"><font color="#FF0000"><strong>
			    <?=$this->lang->line('lap_tabel_tidak_ada_data');?></strong></font></td>
			<? } 	else: // else untuk cari status
			?>
			<tr >
				<td colspan="8" align="center" bgcolor="#FFFFFF"><font color="#FF0000"><strong><?=$this->lang->line('lap_tabel_pilih_kriteria');?></strong></font></td>
			</tr>
			<?php endif; // akhir cari status?>
	
</table>

