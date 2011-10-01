
<?php 
// ============== bwt bisa di download,, bwaan php =======================
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename='.$this->lang->line('lap_nama_file').'-'.$request_type.'.xls');
header('Cache-Control: max-age=0');

?>


<h3><?=$title_page?></h3>


 <br>
<? if ( $cari_tahun != '0' || $cari_bulan != '0' || $cari_pemohon != '' || $cari_departemen != '' || $cari_no!= ''): ?>
 <table width="340" border="0">
  <tr>
    <td colspan="3"><?=$this->lang->line('lap_berdasarkan')?></td>
    </tr>
  
     <? if ($cari_tahun != '0'): ?>
  <tr>
    <td width="115"><?=$this->lang->line('tahun')?></td>
    <td width="192">:<font color="red" >
      <?=$cari_tahun?>
    </font></td>
    <td width="19">&nbsp;</td>
  </tr>
  <? endif;?>
  
   <? if ($cari_bulan != '0'): ?>
  <tr>
    <td width="115"><?=$this->lang->line('bulan')?></td>
    <td width="192">:<font color="red" >
      <?=$data_bulan[$cari_bulan]?>
    </font></td>
    <td width="19">&nbsp;</td>
  </tr>
  <? endif;?>
  
   <? if ($cari_no != ''):?>
  <tr>
    <td><?=$this->lang->line('no')?> <?=$request_type?></td>
    <td>:</td>
    <td><font color="red" ><?=$cari_no?>    </font></td>
  </tr>
  <? endif;?>
  
  
  <? if ($cari_pemohon != ''):?>
  <tr>
    <td><?=$this->lang->line('lap_tabel_pemohon');?></td>
    <td>:<font color="red" >
      <?=$cari_pemohon?>
    </font></td>
    <td>&nbsp;</td>
  </tr>
  <? endif;?>
  
  
  <? if ($cari_departemen!= ''):?>
  <tr>
    <td><?=$this->lang->line('lap_tabel_departemen');?></td>
    <td>:<font color="red" >
      <?=$cari_departemen?>
    </font></td>
    <td>&nbsp;</td>
  </tr>
  <? endif;?>
</table>
<? endif;?>
 
 <div align="right"> <?=$this->lang->line('lap_ada');?><font color="red" > <?=$jumlah_data?>  </font> <?=$this->lang->line('lap_data');?></div>
 <!-- endif cari status -->


<table width="100%"  border="1" cellpadding="0" cellspacing="0" class="ui-widget-content ui-corner-all">
<?
$no = $no_pos+1;
if ($request_detail->num_rows() > 0):
?>
  <tr class="ui-state-default"> 
    <td colspan="11">&nbsp;
	  <strong>
		  <?=$this->lang->line('permintaan')?>
		  <?=$request_type?>
      </strong>
	 </td>
  </tr>
  <tr bgcolor="#CCCCCC" class="ui-state-default"> 
    <td rowspan="2" width="5%" align="center"><?=$this->lang->line('no')?></td>
    <td rowspan="2" width="15%" align="center"><?=$this->lang->line('no')?><?=$request_type?></td>
    <td rowspan="2" width="10%" align="center"><?=$request_type?>&nbsp;<?=$this->lang->line('tanggal')?></td>
    <td rowspan="2" width="10%" align="center"><?=$this->lang->line('lap_tabel_pemohon')?></td>
    <td rowspan="2" width="10%" align="center"><?=$this->lang->line('lap_tabel_departemen')?></td>
    <td rowspan="2" width="10%" align="center"><?=$this->lang->line('lap_tabel_jumlah_item')?></td>
    <td width="30%" colspan="5" align="center"><?=$this->lang->line('lap_tabel_status_item')?></td>
  <!-- keterangan  <td rowspan="2" width="10%" align="center"><?=$this->lang->line('lap_tabel_pesan_inbox')?></td> -->
  </tr>
  <tr bgcolor="#CCCCCC" class="ui-state-default"> 
    <td align="center" width="10%"><?=$this->lang->line('disetujui')?></td>
    <td align="center" width="10%"><?=$this->lang->line('diubah_disetujui')?></td>
    <td align="center" width="10%"><?=$this->lang->line('disetujui_dgn_catatan')?></td>
    <td align="center" width="10%"><?=$this->lang->line('ditunda')?></td>
    <td align="center" width="10%"><?=$this->lang->line('ditolak')?></td>
  </tr>
  <?php 
		
		foreach($request_detail->result() as $rows): ?>
  <tr bgcolor="lightgray"> 
    <td valign="top" align="center" class="ui-state-active"><?=$no?></td>
    <td valign="top" align="center"><?=$rows->req_no?></td>
    <td valign="top" align="center"><?=$rows->req_date?></td>
    <td valign="top" align="center"><?=$rows->usr_name?></td>
    <td valign="top" align="center"><?=$rows->dep_name?></td>
    <td valign="top" align="center"><?=$rows->req_jumitem?></td>
    <td valign="top" align="center"><?=$rows->req_disetujui?></td>
    <td valign="top" align="center"><?=$rows->req_diubah_disetujui?></td>
    <td valign="top" align="center"><?=$rows->req_disetujui_dgn_catatan?></td>
    <td valign="top" align="center"><?=$rows->req_ditunda?></td>
    <td valign="top" align="center"><?=$rows->req_ditolak?></td>
	<!-- keterangan    <td valign="top" align="center">&nbsp;</td> -->
  </tr>
  <?php 	
		$no++;
		endforeach;
		?>
  <!--
		<tr><td colspan="11" class="ui-widget-header"><?=$this->
  lang->line('halaman')?> : 
  <?=($this->pagination->create_links())?($this->pagination->create_links()):('-')?></td></tr>
  --> 
  <?php 
		else:
		?>
  <tr>
    <td colspan="11" align="center"><font color="#FF0000"><strong>
      <?=$this->lang->line('lap_tabel_tidak_ada_data')?>
      </strong></font></td>
  </tr>
  <?php endif; ?>
</table>
