
<?
// bwt bisa didownload langsung
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename='.$this->lang->line('lap_nama_file').'.xls');
header('Cache-Control: max-age=0');

?>

<h3><?=$title_page?></h3>
<div class="clr"></div>

<!--  ================== bwt nampilin apa aja yng jadi filternya ====================== -->
<div class="clr"></div>
<? if ($cari_tahun != '0' || $cari_bulan != 0 || $cari_status_pcv != '0' || $cari_pcv_no != '' || $cari_dicetak_oleh != ''  ): ?>
	<table width="340" border="0">
  <tr>
    <td colspan="3"><?=$this->lang->line('lap_berdasarkan')?></td>
    </tr>
  
     <? if ($cari_tahun != '0'): ?>
  <tr>
    <td width="113"><?=$this->lang->line('tahun')?></td>
    <td width="81">:<font color="red" >
      <?=$cari_tahun?>
    </font></td>
    <td width="132">&nbsp;</td>
  </tr>
  <? endif;?>
  
   <? if ($cari_bulan != '0'): ?>
  <tr>
    <td width="113"><?=$this->lang->line('bulan')?></td>
    <td width="81">:<font color="red" >
      <?=$data_bulan[$cari_bulan]?>
    </font></td>
    <td width="132">&nbsp;</td>
  </tr>
  <? endif;?>
  
   <? if ($cari_bulan != '0'): ?>
  <? endif;?>
  
   <? if ($cari_status_pcv!= '0'): ?>
  <tr>
    <td width="113"><?=$this->lang->line('status');?></td>
    <td width="81">:<font color="red" >
      <?
					if($cari_status_pcv == 2){
						echo $this->lang->line('lap_brg_blm_terima');
					}else if($cari_status_pcv == 5){
						echo $this->lang->line('lap_belum_realisasi');
					}else if($cari_status_pcv == 6){
						echo $this->lang->line('lap_sudah_tutup');
					}
					?>
    </font></td>
    <td width="132">&nbsp;</td>
  </tr>
  <? endif;?>


   <? if ($cari_pcv_no!= ''): ?>
  <tr>
    <td width="113"><font size="-2">
      <?=$this->lang->line('lap_no_pcv');?>
    </font></td>
    <td width="81">:<font color="red" >
      <?=$cari_pcv_no?>
    </font></td>
    <td width="132">&nbsp;</td>
  </tr>
  <? endif;?>

   <? if ($cari_dicetak_oleh!= ''): ?>
  <tr>
    <td width="113"><font size="-2">
      <?=$this->lang->line('lap_dicetak_oleh');?>
    </font></td>
    <td width="81">:<font color="red" >
      <?=$cari_dicetak_oleh?>
    </font></td>
    <td width="132">&nbsp;</td>
  </tr>
  <? endif;?>
</table>
<? endif;?>

<!--  ================== akhir bwt nampilin apa aja yng jadi filternya ====================== -->


	<div align="right"> <?=$this->lang->line('lap_ada');?><font color="red" > <?=$jumlah_data?>  </font> <?=$this->lang->line('lap_data');?></div><font size="-3">
	<table width="100%"  border="1" cellpadding="0" cellspacing="1" class="ui-widget-content ui-corner-all">
  <?php if ($data_pcv->num_rows() > 0):?>
  <tr class="ui-state-default" bgcolor="#CCCCCC"> 
    <td width="22" rowspan="2" align="center" class="ui-state-default" ><?=$this->lang->line('no');?></td>
    <td width="45" rowspan="2" align="center"><?=$this->lang->line('lap_no_pcv');?></td>
    <td width="45" rowspan="2" align="center"><?=$this->lang->line('lap_tanggal_dicetak');?></td>
    <td width="45" rowspan="2" align="center"><?=$this->lang->line('lap_dicetak_oleh');?></td>
    <td width="32" rowspan="2" align="center"><?=$this->lang->line('lap_tanggal_terima_barang');?></td>
    <td width="32" rowspan="2" align="center"><?=$this->lang->line('satuan');?></td>
    <td width="32" rowspan="2" align="center"><?=$this->lang->line('mata_uang');?></td>
	<td colspan="3"  align="center"><?=$this->lang->line('realisasi_tabel_rcn')?></td>
    <td colspan="3" align="center"><?=$this->lang->line('realisasi')?></td>
    <td width="50" rowspan="2" align="center"><?=$this->lang->line('status');?></td>
  </tr>
  <tr class="ui-state-default" bgcolor="#CCCCCC"> 
    <td width="68" align="center"><?=$this->lang->line('jumlah')?></td>
    <td width="90" align="center">
		<?=$this->lang->line('harga').' '.$this->lang->line('satuan')?><br>
		<font size="-2" > 
		  (<?=$this->lang->line('perkiraan')?>)
		</font>
	</td>
    <td width="100" align="center"><?=$this->lang->line('total').' '.$this->lang->line('harga')?></td>
    <td width="74" align="center"><?=$this->lang->line('jumlah')?></td>
    <td width="87" align="center"><?=$this->lang->line('harga').' '.$this->lang->line('satuan')?></td>
    <td width="98" align="center"><?=$this->lang->line('total').' '.$this->lang->line('harga')?></td>
  </tr>
  <?php 
		// $no = 1; //tanpa paging
		$no = $no_pos+1; // pake paging 
		if ($data_pcv->num_rows() > 0):
		foreach ($data_pcv->result() as $rows):
  ?>
  <tr bgcolor="lightgray"> 
    <td align="center" class="ui-state-active" valign="middle"><?=$no?></td>
    <td align="center"><?=$rows->pcv_no?></td>
    <td align="center"><?=$rows->pcv_printDate?></td>
    <td align="left"><?=$rows->usr_name?></td>
    <td align="center"><?=$rows->pcv_receiveDate?></td>
	<td align="center"><?=$rows->satuan_name?></td>
	<td align="center"><?=$rows->cur_symbol?></td>
    <td align="center"><?=$this->general->digit_number($rows->satuan_id,$rows->permintaan_barang).' '.$rows->satuan_name?></td>
    <td align="right"><?=number_format($rows->harga_perkiraan,$rows->cur_digit)?></td>
    <td align="right"><?=number_format($rows->pcv_request,$rows->cur_digit)?></td>
    <td align="center"><?=$this->general->digit_number($rows->satuan_id,$rows->realisasi_barang).' '.$rows->satuan_name?></td>
    <td align="right"><?=number_format($rows->realisasi_harga,$rows->cur_digit)?></td>
    <td align="right"><?=number_format($rows->realisasi_tot_harga,$rows->cur_digit)?></td>
    <td align="center"><?=$this->general->status('pcv_status',$rows->pcv_status)?></td>
  </tr>
  <?php 
		$no++;
		endforeach;
		elseif ($data_pcv->num_rows() == 1):
		?>
  <tr> 
    <td colspan="14" align="center"><INPUT TYPE="submit" value="<?=$this->lang->line('lap_buat_rfq')?>"></td>
  </tr>
  <?php else:?>
  <tr  > 
    <td colspan="14" align="center">
		<font color="#FF0000">
			<strong> 
			  <?=$this->lang->line('lap_tabel_tidak_ada_data');?>
			</strong>
		</font>
	</td>
	
  </tr>
  <?php endif;?>
  <strong> 
  <tr class="ui-state-active"> 
    <td colspan="9" align="right" ><?=$this->lang->line('total').' :'?></td>
    <td class="ui-widget-active" align="right"><?=number_format($total_diminta,$rows->cur_digit)?></td>
    <td colspan="2" align="right" class="ui-widget-active"><?=$this->lang->line('total').' :'?></td>
    <td class="ui-widget-active" align="right"><?=number_format($total_realisasi,$rows->cur_digit)?></td>
    <td class="ui-widget-active">&nbsp;</td>
  </tr>
  <!--  bwt paging
			<tr><td colspan="8" class="ui-widget-header"> <?=$this->
  lang->line('halaman');?> : 
  <?=($this->pagination->create_links())?($this->pagination->create_links()):('-')?></td></tr>
  --> 
  </strong> 
  <?php else:?>
  <tr  > 
    <td colspan="13" align="center">
		<font color="#FF0000">
			<strong> 
		      	<?=$this->lang->line('lap_tabel_tidak_ada_data');?>
      		</strong>
		</font>
	</td>
  </tr>
  <?php endif;?>
</table></font>


