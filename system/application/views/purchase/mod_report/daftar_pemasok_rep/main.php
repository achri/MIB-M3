

<script type="text/javascript">

function bersihkanFilter(){
	$('#cari_pemasok').val('');
	$('#cari_kategori').val('');
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

				<input type=hidden name="cari_pemasok" value="<?=$cari_pemasok?>">				
				<input type=hidden name="cari_kategori" value="<?=$cari_kategori?>">
				<input type=hidden name="cari_status" value="<?=$cari_status?>">


	<!-- ========================================================================== -->	

	</form>
</div>
<? endif; ?><!-- endif cari status -->

    <form name="form_entry" method="post" action="index.php/<?=$link_controller?>/index">
    <div style="width:99%" class="ui-widget-content ui-corner-all">
      <table width="101%">
  &nbsp; <b><?=$this->lang->line('lap_judul_cari');?></b>
  
  <tr>
    <td width="35%"><table width="100%" cellspacing="5" cellpadding="5">
      <tr>
        <td width="30%">
          <?=$this->lang->line('supplier');?>        </td>
        <td width="70%">:
		    <select name="cari_pemasok" id="cari_pemasok" style="width:150px" >
              <option value="0" >
              <?=$this->lang->line('combo_box_supplier');?>
              </option>
              <?php foreach($data_cari_pemasok->result() as $rows):?>
              <option value="<?=$rows->sup_id?>" <?=( $cari_pemasok == $rows->sup_id )?('SELECTED="selected"'):('')?>>
              <?=$rows->sup_name?>
              <?
							if ($cari_pemasok == $rows->sup_id){
								$nama_pemasok=($rows->sup_name);
							}
						?>
              </option>
              <?php endforeach;?>
          </select>		
		</td>
      </tr>
    </table></td>
    <td width="34%"><table width="100%" cellspacing="5" cellpadding="5">
      <tr>
        <td width="32%"><?=$this->lang->line('kategori');?></td>
        <td width="68%">: 
			<select name="cari_kategori" id="cari_kategori" style="width:150px"  >
              <option value="0" >
              <?=$this->lang->line('combo_box_kategori');?>
              </option>
              <?php foreach($data_kategori->result() as $rows):?>
              <option value="<?=$rows->cat_id?>" <?=( $cari_kategori == $rows->cat_id )?('SELECTED="selected"'):('')?>>
              <?=$rows->cat_name?>
              <?
							if ($cari_kategori == $rows->cat_id){
								$nama_kategori=($rows->cat_name);
							}
						?>
              </option>
              <?php endforeach;?>
          </select>
		
		
		</td>
      </tr>
    </table></td>
    <td width="31%"><table width="123%" cellspacing="5" cellpadding="5">
      <tr>
        <td width="24%">&nbsp;</td>
        <td width="76%">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td width="35%">&nbsp;</td>
    <td width="34%">&nbsp;</td>
    <td width="31%" align="right">
	  <input type="button" name="bersihkan" value="<?=$this->lang->line('button_bersihkan');?>" onclick="bersihkanFilter()"/> 
      <input name="cari" type="submit" value="<?=$this->lang->line('button_cari');?>" />	</td>
  </tr>
</table>

	
	</div>
	</form>
</div>
<div class="clr"></div>

<!--  ================== bwt nampilin apa aja yng jadi filternya ====================== -->
<div class="clr"></div>
<? if ($cari_kategori != '' || $cari_pemasok!= '' ): ?>
	<table width="340" border="0">
  <tr>
    <td colspan="3"><?=$this->lang->line('lap_berdasarkan')?></td>
    </tr>  

  <? if ($cari_pemasok != ''): ?>
  <tr>
    <td width="115"><?=$this->lang->line('supplier')?></td>
    <td width="14">:</td>
    <td width="197"><font color="red" ><?=$nama_pemasok?> </font>    </td>
  </tr>
  <? endif;?>
  
   <? if ($cari_kategori != ''): ?>
  <tr>
    <td width="115"><?=$this->lang->line('kategori')?></td>
    <td width="14">:</td>
    <td width="197"><font color="red" ><?=$nama_kategori?> </font>    </td>
  </tr>
  <? endif;?>
  
</table>
<? endif;?>

<!--  ================== akhir bwt nampilin apa aja yng jadi filternya ====================== -->


<? 
	$width_tabel='100%'; // untuk ukuran tabel bila belum pilih kriteria
	if ($cari_status !=''):  
	$width_tabel=696; // untuk ukuran tabel bila sudah pilih kriteria
?>	
	<div align="right"> <?=$this->lang->line('lap_ada');?><font color="red" > <?=$jumlah_data?>  </font> <?=$this->lang->line('lap_data');?></div>
<? endif; ?><!-- endif cari status -->

<br />
<table width="<?=$width_tabel?>"  border="0" cellpadding="0" cellspacing="1" class="ui-widget-content ui-corner-all">
	<? if ($cari_status !=''): // if (cari status)?>	
	
		  <tr align="center" class="ui-state-default">
			<td width="55" ><?=$this->lang->line('no')?></td>
			<td width="329"><?=$this->lang->line('supplier')?></td>
			<td width="308"><?=$this->lang->line('kategori')?></td>
		  </tr>
		  
		  <? if ($data_pemasok->num_rows() > 0): // if (query data pemasok) 
		  	 $no=1;
 	 		 foreach ($data_pemasok->result() as $row): // looping data_pemasok
			// foreach ($data_pemasok->result() as $row): // looping data_pemasok
		  ?>
		  
		  <tr valign="middle" bgcolor="#CCCCCC">
			<td align="center" class="ui-state-active" ><?=$no?></td>
			<td><?=$row->sup_name?></td>
			<td><?=$row->cat_name?></td>
		  </tr>
		<?php 
			$no++;
			endforeach; // (akhir) looping data_pemasok
			else: // else (query data pemasok)
		?>		  
		  <tr >
			 <td colspan="3" align="center">
			 	<font color="#FF0000">
					<strong>
			    		<?=$this->lang->line('lap_tabel_tidak_ada_data');?>
					</strong>
				</font>
			 </td>				
		  </tr>  
		<?php endif; // endif (query data pemasok)			
			  else: // else (cari status)
		?>		  
		  <tr >
			 <td colspan="3" align="center">
			 	<font color="#FF0000">
					<strong>
			    		<?=$this->lang->line('lap_tabel_pilih_kriteria');?>
					</strong>
				</font>
			 </td>				
		  </tr>  

		
	<?php endif; // endif cari status?>
</table>
  
  
