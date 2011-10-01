

<?php 
// ============== bwt bisa di download,, bwaan php =======================
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename='.$this->lang->line('lap_nama_file').'.xls');
header('Cache-Control: max-age=0');

?>


<? if ($cari_status !=''):?>	

 <? endif; ?><!-- endif cari status -->


<h3><?=$title_page?></h3>
<div class="clr"></div>

<? if ($cari_bulan != '0' || $cari_tahun!= 0 || $cari_no_po!= '' || $cari_pemasok!= ''  || $cari_pemesan != '' || $cari_alasan!= '' || $cari_no_sj!= ''): ?>
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
  <? endif;?>

  <? if ($cari_no_po != ''):?>
  <tr>
    <td><?=$this->lang->line('lap_no_po_pcv');?></td>
    <td>:<font color="red" >
      <?=$cari_no_po?>
    </font></td>
    <td>&nbsp;</td>
  </tr>
  <? endif;?>

  <? if ($cari_nama_barang!= ''):?>
  <tr>
    <td><?=$this->lang->line('nama');?>&nbsp;<?=$this->lang->line('barang');?></td>
    <td>:<font color="red" >
      <?=$cari_nama_barang?>
    </font></td>
    <td>&nbsp;</td>
  </tr>
  <? endif;?>

  <? if ($cari_kode_barang!= ''):?>
  <tr>
    <td><?=$this->lang->line('kode');?>&nbsp;<?=$this->lang->line('barang');?></td>
    <td>:<font color="red" >
      <?=$cari_kode_barang?>
    </font></td>
    <td>&nbsp;</td>
  </tr>
  <? endif;?>

  <? if ($cari_pemesan != ''):?>
  <tr>
    <td><?=$this->lang->line('lap_pemesan');?></td>
    <td>:<font color="red" >
      <?=$cari_pemesan?>
    </font></td>
    <td>&nbsp;</td>
  </tr>
  <? endif;?>


  <? if ($cari_alasan!= ''):?>
  <tr>
    <td><?=$this->lang->line('alasan');?></td>
    <td>:<font color="red" >
      <?=$cari_alasan?>
    </font></td>
    <td>&nbsp;</td>
  </tr>
  <? endif;?>



  <? if ($cari_no_sj != ''):?>
  <tr>
    <td><font size="-1"><?=$this->lang->line('lap_no_surat_jalan');?></font></td>
    <td>:<font color="red" >
      <?=$cari_no_sj?>
    </font></td>
    <td>&nbsp;</td>
  </tr>
  <? endif;?>
</table>
<? endif; // akhir dari pencarian berdasrkan ?>



<? if ($cari_status !=''):?>	
 <div align="right"> <?=$this->lang->line('lap_ada');?><font color="red" > <?=$jumlah_data?>  </font> <?=$this->lang->line('lap_data');?></div>
 <? endif; ?><!-- endif cari status -->

<table width="100%"  border="1" cellpadding="0" cellspacing="1" class="ui-widget-content ui-corner-all">
	<? if ($cari_status !=''):?>	
  <tr bgcolor="#CCCCCC">
    <td width="4%" align="center" rowspan="2">
      <?=$this->lang->line('no');?>
    </td>
    <td width="12%" align="center" rowspan="2"> 
      <?=$this->lang->line('tanggal');?>    </td>
    <td width="8%" align="center" rowspan="2"> 
      <?=$this->lang->line('lap_no_po_pcv');?>    </td>
    <td width="16%" align="center" rowspan="2"> 
      <?=$this->lang->line('lap_nama_barang_kode');?>    </td>
    <td width="8%" align="center" rowspan="2"> 
      <?=$this->lang->line('lap_pemesan');?>    </td>
    <td width="9%" align="center" rowspan="2"> 
      <?=$this->lang->line('jumlah');?>    </td>
    <td width="9%" align="center" rowspan="2"> 
      <?=$this->lang->line('alasan');?>    </td>
    <td align="center" colspan="3">
      <?=$this->lang->line('lap_barang_datang');?>
    </td>
  </tr>
  <tr bgcolor="#CCCCCC"> 
    <td align="center" width="12%">
      <?=$this->lang->line('tanggal');?>    </td>
    <td align="center" width="9%">
      <?=$this->lang->line('lap_no_sj');?>    </td>
    <td align="center" width="13%">
      <?=$this->lang->line('qty');?>    </td>
  </tr>
  <?php 
  		$no=$no_pos+1;
		if (sizeof($data_bpb) > 0):
		for ($i=0; $i < sizeof($data_bpb);$i++):
		?>
  <tr >
    <td valign="top" align="center" 
class="ui-state-active" ><?=$no?></td>
    <td valign="top" align="center"> 
      <?=date_format(date_create($data_bpb[$i]['pr_date']),'d-m');?>
      
      <?=date_format(date_create($data_bpb[$i]['pr_date']),'Y');?>
    </td>
    <td valign="top" align="center"> 
      <?php if ($data_bpb[$i]['pcv_id']==0) echo $data_bpb[$i]['po_no'];
			else if ($data_bpb[$i]['po_id']==0) echo $data_bpb[$i]['pcv_no']." (PCV)";
			?>
    </td>
    <td valign="top" align="left">
      <?=$data_bpb[$i]['pro_name']?>
      <br>
      (
      <?=$data_bpb[$i]['pro_code']?>
      )</td>
    <td valign="top" align="center">
      <?=$data_bpb[$i]['usr_name']?>
    </td>
    <td valign="top" align="right"> <table width="100%">
        <tr> 
          <td width="80%" align="right">
		
		 
            <?=$this->general->digit_number($data_bpb[$i]['um_id'],$data_bpb[$i]['qty'])?>
			
			
          </td>
          <td width="20%" align="left">
            <?=$data_bpb[$i]['satuan_name']?>
          </td>
        </tr>
      </table></td>
    <td valign="top" align="left">
      <?=$data_bpb[$i]['description']?>
    </td>
    <td valign="top" align="center">
      <?=$data_bpb[$i]['rec_date']?>
    </td>
    <td valign="top" align="center">
      <?=$data_bpb[$i]['rec_sj']?>
    </td>
    <td valign="top" align="right"> <table width="100%">
        <tr> 
          <td width="80%" align="right">
            <?=$this->general->digit_number($data_bpb[$i]['um_id'],$data_bpb[$i]['rec_qty'])?>
          </td>
          <td width="20%" align="left">
            <?=$data_bpb[$i]['satuan_name']?>
          </td>
        </tr>
      </table></td>
  </tr>
  <?php 
  		$no++;
		endfor;
		?>
	<!--  bwt paging	
  <tr>
    <td colspan="10" class="ui-widget-header">
      <?=$this->lang->line('halaman')?>
      : 
      <?=($this->pagination->create_links())?($this->pagination->create_links()):('-'); ?>
    </td>
  </tr>
  -->
  <?php
		else:
		?>
  <tr> 
    <td colspan="10" align="center">
     <font color="#FF0000"><strong>
			    <?=$this->lang->line('lap_tabel_tidak_ada_data');?></strong></font>
    </td>
  </tr>
  <?php endif;
  
  else: // else untuk cari status
			?>
			<tr >
				<td colspan="10" align="center" bgcolor="#FFFFFF"><font color="#FF0000"><strong><?=$this->lang->line('lap_tabel_pilih_kriteria');?></strong></font></td>
			</tr>
			<?php endif; // akhir cari status?>

</table>
