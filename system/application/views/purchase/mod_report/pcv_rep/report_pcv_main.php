

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
	$('#cari_status_pcv').val('');
	$('#cari_dicetak_oleh').val('');	
	$('#cari_pcv_no').val('');
}




</script>

<h3><?=$title_page?></h3>
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
				<input type=hidden name="cari_status_pcv" value="<?=$cari_status_pcv?>">				
				<input type=hidden name="cari_dicetak_oleh" value="<?=$cari_dicetak_oleh?>">
				<input type=hidden name="cari_pcv_no" value="<?=$cari_pcv_no?>">				

	<!-- ========================================================================== -->	

	</form>
</div>
<? endif; ?><!-- endif cari status -->

    <form name="form_entry" method="post" action="index.php/<?=$link_controller?>/index">
    <div style="width:99%" class="ui-widget-content ui-corner-all">
      <table width="109%">
  &nbsp; <b><?=$this->lang->line('lap_judul_cari');?></b>
  
  <tr>
    <td width="33%"><table width="100%" cellspacing="5" cellpadding="5">
      <tr>
        <td width="30%"><?=$this->lang->line('tahun');?></td>
        <td width="70%">:
          <select name="cari_tahun" id="cari_tahun" style="width:150px" 
onchange="setBulan(this)"
 >
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
        <td width="30%"><font size="-2">
          <?=$this->lang->line('lap_dicetak_oleh');?>
        </font></td>
        <td width="70%">:
          <input type="text" style="width:150px" name="cari_dicetak_oleh" id="cari_dicetak_oleh" value="<?=$cari_dicetak_oleh?>" /></td>
      </tr>
    </table></td>
    <td width="31%"><table width="119%" cellspacing="5" cellpadding="5">
      <tr>
        <td width="32%"><?=$this->lang->line('bulan');?></td>
        <td width="68%">:
         
			<? if ($cari_tahun != 0 ){ ?>
			  	<select name="cari_bulan" id="cari_bulan" style="width:150px"  >
			<? } else { ?>
			  	<select name="cari_bulan"  id="cari_bulan" style="width:150px"  disabled="disabled">
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
        <td width="32%"><font size="-4"><?=$this->lang->line('lap_no_pcv');?></font></td>
        <td width="68%">: <input type="text" style="width:150px" name="cari_pcv_no" id="cari_pcv_no" value="<?=$cari_pcv_no?>" />          </td>
      </tr>
    </table></td>
    <td width="36%"><table width="100%" cellspacing="5" cellpadding="5">
      <tr>
        <td width="24%" height="34"><?=$this->lang->line('status');?></td>
        <td width="76%">:
          <select name="cari_status_pcv" id="cari_status_pcv" style="width:150px">
            <option value="0" <?=($cari_status_pcv == 0)?("selected=selected"):('')?>>
            <?=$this->lang->line('combo_box_status');?>
            </option>
            <option value="2" <?=($cari_status_pcv == '2')?("selected=selected"):('')?>>
            <?=$this->lang->line('lap_brg_blm_terima');?>
            </option>
            <option value="5" <?=($cari_status_pcv == '5')?("selected=selected"):('')?>>
            <?=$this->lang->line('lap_belum_realisasi');?>
            </option>
            <option value="6" <?=($cari_status_pcv == '6')?("selected=selected"):('')?>>
            <?=$this->lang->line('lap_sudah_tutup');?>
            </option>
          </select></td>
      </tr>
      <tr>
        <td width="24%">&nbsp;</td>
        <td width="76%" align="right"><input type="button" name="bersihkan" value="<?=$this->lang->line('button_bersihkan');?>" onclick="bersihkanFilter()"/>
          <input name="cari" type="submit" value="<?=$this->lang->line('cari');?>" /></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td width="33%">&nbsp;</td>
    <td width="31%">&nbsp;</td>
    <td width="36%" align="right">&nbsp;</td>
  </tr>
</table>

	
	</div>
	</form>
</div>
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
  
   <? if ($cari_bulan != '0'): ?>
  <? endif;?>
  
   <? if ($cari_status_pcv!= '0'): ?>
  <tr>
    <td width="115"><?=$this->lang->line('status');?></td>
    <td width="14">:</td>
    <td width="197"><font color="red" >
					<?
					if($cari_status_pcv == 2){
						echo $this->lang->line('lap_brg_blm_terima');
					}else if($cari_status_pcv == 5){
						echo $this->lang->line('lap_belum_realisasi');
					}else if($cari_status_pcv == 6){
						echo $this->lang->line('lap_sudah_tutup');
					}
					?> 
	</font>    </td>
  </tr>
  <? endif;?>


   <? if ($cari_pcv_no!= ''): ?>
  <tr>
    <td width="115"><font size="-2">
      <?=$this->lang->line('lap_no_pcv');?>
    </font></td>
    <td width="14">:</td>
    <td width="197"><font color="red" ><?=$cari_pcv_no?> </font>    </td>
  </tr>
  <? endif;?>

   <? if ($cari_dicetak_oleh!= ''): ?>
  <tr>
    <td width="115"><font size="-2">
      <?=$this->lang->line('lap_dicetak_oleh');?>
    </font></td>
    <td width="14">:</td>
    <td width="197"><font color="red" ><?=$cari_dicetak_oleh?> </font>    </td>
  </tr>
  <? endif;?>
</table>
<? endif;?>

<!--  ================== akhir bwt nampilin apa aja yng jadi filternya ====================== -->


<? if ($cari_status !=''):?>	
	<div align="right"> <?=$this->lang->line('lap_ada');?><font color="red" > <?=$jumlah_data?>  </font> <?=$this->lang->line('lap_data');?></div>
<? endif; ?><!-- endif cari status -->

<font size="-3">
<table width="100%"  border="0" cellpadding="0" cellspacing="1" class="ui-widget-content ui-corner-all">
  <? if ($cari_status !=''):?>
  <?php if ($data_pcv->num_rows() > 0):?>
  <tr class="ui-state-default"> 
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
  <tr class="ui-state-default"> 
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
    <td align="center"><?=$this->general->digit_number($rows->satuan_id,$rows->permintaan_barang)?></td>
    <td align="right"><?=number_format($rows->harga_perkiraan,$rows->cur_digit)?></td>
    <td align="right"><?=number_format($rows->pcv_request,$rows->cur_digit)?></td>
    <td align="center"><?=$this->general->digit_number($rows->satuan_id,$rows->realisasi_barang)?></td>
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
    <td colspan="2" align="right" class="ui-widget-active">&nbsp;</td>
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
  <?php endif;
				else: //else cari status
			?>
  <tr > 
    <td colspan="12" align="center" bgcolor="#FFFFFF">
		<font color="#FF0000">
			<strong> 
      			<?=$this->lang->line('lap_tabel_pilih_kriteria');?>
      		</strong>
		</font>
	</td>
  </tr>
  <?php endif; // endif cari status?>
</table>
</font>
  