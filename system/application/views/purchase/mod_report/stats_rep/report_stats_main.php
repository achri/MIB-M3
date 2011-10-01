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
	$('#request_type').val('');
	$('#cari_pemohon').val('');	
	$('#cari_departemen').val('');
	$('#cari_no').val('');
}
</script>





<h3><?=$title_page?></h3>
<div class="noprint">


<? if ($cari_status !=''):?>	
<div align="right">
  <form method="post" action="index.php/<?=$link_controller?>/excel" >		
					<!-- ====================== bwt seleksi,, klo kosong datanya eksport g aktid ========= -->
				<?php if($jumlah_data > 0){?>	
					<input type="submit" value="<?=$this->lang->line('lap_salin_ke_excel');?>">	
					<input type="button" id="print" value="<?=$this->lang->line('lap_print');?>" onclick="window.print();">
				<?php }else {?>
					<input type="submit" disabled="disabled" value="<?=$this->lang->line('lap_salin_ke_excel');?>">
					<input type="button" id="print" disabled="disabled" value="<?=$this->lang->line('lap_print');?>" onclick="window.print();">
				<?php }?>
				
				<!-- =============================================================================== -->
			
				<!--  ================ bwt ngirim selesi ke excelnya =========== -->
				<input type=hidden name="request_type" value="<?=$request_type?>">	
				<input type=hidden name="cari_tahun" value="<?=$cari_tahun?>">	
				<input type=hidden name="cari_bulan" value="<?=$cari_bulan?>">					
				<input type=hidden name="cari_pemohon" value="<?=$cari_pemohon?>">	
				<input type=hidden name="cari_departemen" value="<?=$cari_departemen?>">					
				<input type=hidden name="cari_status" value="<?=$cari_status?>">	
				<input type=hidden name="cari_no" value="<?=$cari_no?>">					

				<!--  ================ (akhir) bwt ngirim selesi ke excelnya =========== -->
				

  </form>

</div><!-- akhir button export $cetak -->

 <? endif; ?><!-- endif cari status -->


<form name="form_entry" method="post" action="index.php/<?=$link_controller?>/index">
    <div style="width:99%" class="ui-widget-content ui-corner-all">
      <table width="101%">
  &nbsp; <b><?=$this->lang->line('lap_judul_cari');?></b>
  
  <tr>
    <td width="35%"><table width="100%" cellspacing="5" cellpadding="5">
      <tr>
        <td width="30%"><?=$this->lang->line('permintaan')?></td>
        <td width="70%">: <SELECT NAME="request_type" id="request_type" >
				<option value="PR" <?=($request_type=='PR')?("selected"):('')?>><?=$this->lang->line('lap_combo_box_PR')?></option>
				<option value="MR" <?=($request_type=='MR')?("selected"):('')?>><?=$this->lang->line('lap_combo_box_MR')?></option>
			</SELECT>	
</td>
      </tr>
      <tr>
        <td width="30%"><?=$this->lang->line('lap_tabel_pemohon')?></td>
        <td width="70%">:
          <input type="text" style="width:150px" name="cari_pemohon" id="cari_pemohon" value="<?=$cari_pemohon?>" /></td>
      </tr>
    </table></td>
    <td width="34%"><table width="100%" cellspacing="5" cellpadding="5">
      <tr>
        <td width="28%"><?=$this->lang->line('tahun');?></td>
        <td width="72%">:          <select name="cari_tahun" id="cari_tahun" style="width:150px" 
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
        <td width="28%"><?=$this->lang->line('lap_tabel_departemen')?></td>
        <td width="72%">: <input type="text" style="width:150px" name="cari_departemen" id="cari_departemen" value="<?=$cari_departemen?>" />          </td>
      </tr>
    </table></td>
    <td width="31%"><table width="100%" cellspacing="5" cellpadding="5">
      <tr>
        <td width="24%" height="34"><?=$this->lang->line('bulan');?></td>
        <td width="76%"> : <? if ($cari_tahun != 0 ){ ?>
			  	   <select name="cari_bulan" id="cari_bulan" style="width:150px"  >
			<? } else { ?>
			  	<select name="cari_bulan" id="cari_bulan" style="width:150px"  disabled="disabled">
			<? }?>
		   
		    <option value="0" >
            <?=$this->lang->line('combo_box_bulan');?>
            </option>
            <?php for ($i=1;$i<=12;$i++):?>
            <option value="<?=$i?>" <?=($cari_bulan == $i)?('SELECTED="selected"'):('')?>>
            <?=$data_bulan[$i]?>
            </option>
            <?php endfor;?>
          </select>		  </td>
      </tr>
      <tr>
     	<td width="28%"><?=$this->lang->line('no')?> <?=$request_type?></td>
        <td width="72%">
		: <input type="text" style="width:150px" name="cari_no" id="cari_no" value="<?=$cari_no?>" />         
		 </td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td width="35%">&nbsp;</td>
    <td width="34%">&nbsp;</td>
    <td width="31%" align="right">
		<input type="button" name="bersihkan" value="<?=$this->lang->line('button_bersihkan');?>" onclick="bersihkanFilter()"/>
		<input name="cari" type="submit" value="<?=$this->lang->line('cari');?>" />
	</td>
  </tr>
</table>

	
	</div>
</form>

</div>
<!--  ================== bwt nampilin apa aja yng jadi filternya ====================== -->
<div class="clr"></div>
<? if ( $cari_tahun != '0' || $cari_bulan != '0' || $cari_pemohon != '' || $cari_departemen != '' || $cari_no!= ''): ?>
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
    <td>:</td>
    <td><font color="red" ><?=$cari_pemohon?>    </font></td>
  </tr>
  <? endif;?>
  
  
  <? if ($cari_departemen!= ''):?>
  <tr>
    <td><?=$this->lang->line('lap_tabel_departemen');?></td>
    <td>:</td>
    <td><font color="red" ><?=$cari_departemen?>    </font></td>
  </tr>
  <? endif;?>

</table>
<? endif;?>

<!--  ================== akhir bwt nampilin apa aja yng jadi filternya ====================== -->




<br>
<? if ($cari_status !=''):?>
<div align="right"> <?=$this->lang->line('lap_ada');?><font color="red" > <?=$jumlah_data?>  </font> <?=$this->lang->line('lap_data');?></div>
 <? endif; ?><!-- endif cari status -->


<table width="100%"  border="0" cellpadding="0" cellspacing="2" class="ui-widget-content ui-corner-all">
  <? if ($cari_status !=''):?>
  <?
$no = $no_pos+1;
if ($request_detail->num_rows() > 0):
?>
  <tr class="ui-state-default"> 
    <td colspan="12">&nbsp;
	  <strong>
		  <?=$this->lang->line('permintaan')?>
		  <?=$request_type?>
      </strong>
	 </td>
  </tr>
  <tr bgcolor="#CCCCCC" class="ui-state-default"> 
    <td rowspan="2" width="4%" align="center"><?=$this->lang->line('no')?></td>
    <td rowspan="2" width="13%" align="center"><?=$this->lang->line('no')?><?=$request_type?></td>
    <td rowspan="2" width="9%" align="center"><?=$request_type?>&nbsp;<?=$this->lang->line('tanggal')?></td>
    <td rowspan="2" width="9%" align="center"><?=$this->lang->line('lap_tabel_pemohon')?></td>
    <td rowspan="2" width="9%" align="center"><?=$this->lang->line('lap_tabel_departemen')?></td>
    <td rowspan="2" width="9%" align="center"><?=$this->lang->line('lap_tabel_jumlah_item')?></td>
    <td colspan="5" align="center"><?=$this->lang->line('lap_tabel_status_item')?></td>
  <!--  <td rowspan="2" width="10%" align="center"><?=$this->lang->line('lap_tabel_pesan_inbox')?><td width="0%"></td> -->
  </tr>
  <tr bgcolor="#CCCCCC" class="ui-state-default"> 
    <td align="center" width="9%"><?=$this->lang->line('disetujui')?></td>
    <td align="center" width="9%"><?=$this->lang->line('diubah_disetujui')?></td>
    <td align="center" width="9%"><?=$this->lang->line('disetujui_dgn_catatan')?></td>
    <td align="center" width="9%"><?=$this->lang->line('ditunda')?></td>
    <td align="center" width="11%"><?=$this->lang->line('ditolak')?></td>
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
 <!-- keterangan   <td valign="top" align="center">&nbsp;</td> -->
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
    <td colspan="12" align="center"><font color="#FF0000"><strong>
      <?=$this->lang->line('lap_tabel_tidak_ada_data')?>
      </strong></font></td>
  </tr>
  <?php 
		endif; 
		 else: // else untuk cari status
			?>
  <tr > 
    <td colspan="12" align="center" bgcolor="#FFFFFF"><font color="#FF0000"><strong>
      <?=$this->lang->line('lap_tabel_pilih_kriteria');?>
      </strong></font></td>
  </tr>
  <?php endif; // akhir cari status?>
</table>
