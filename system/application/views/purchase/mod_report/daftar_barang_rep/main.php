
<!-- fungsi untuku klo filter tahun belum dipilih, maka filter bulan tidak bisa digunakan -->
<script type="text/javascript">

function setBulan(obj){
	if(obj.value == 0 ){
		document.forms["form_entry"].cari_bulan.disabled=true;		
	}else{		
		document.forms["form_entry"].cari_bulan.disabled=false;		
	}
}

function bersihkanFilter(){
	$('#cari_kode').val('');
	$('#cari_kelas').val('');
	$('#cari_grup').val('');
	$('#cari_produk').val('');
	$('#cari_kategori').val('');
	$('#cari_status_produk').val('');
	
	$('#cari_bulan').val('0');
	$('#cari_tahun').val('0');



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
				<input type=hidden name="cari_produk" value="<?=$cari_produk?>">
				<input type=hidden name="cari_kode" value="<?=$cari_kode?>">
				<input type=hidden name="cari_kategori" value="<?=$cari_kategori?>">
				<input type=hidden name="cari_status_produk" value="<?=$cari_status_produk?>">				
				<input type=hidden name="cari_kelas" value="<?=$cari_kelas?>">
				<input type=hidden name="cari_grup" value="<?=$cari_grup?>">
				
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
          <?=$this->lang->line('kode');?>        </td>
        <td width="70%">:
          <input type="text" style="width:150px" name="cari_kode" id="cari_kode" value="<?=$cari_kode?>" /></td>
      </tr>
      <tr>
        <td><?=$this->lang->line('grup');?></td>
        <td width="68%">: 		
		          <select name="cari_grup" id="cari_grup" style="width:150px">
                    <option value="0">
                    <?=$this->lang->line('combo_box_grup');?>
                    </option>
                    <? foreach ($data_grup->result() as $rows):?>
                    <option value="<?=$rows->cat_code?>" <?=($cari_grup == $rows->cat_code)?('SELECTED="selected"'):('')?>>
                    <?=$rows->cat_name.' ('.$rows->cat_code.')'?>
                    </option>
                    <?
						
							$sd=$rows->cat_code;
							if ($cari_grup == $rows->cat_code){
								$nama_grup=$rows->cat_name;
							}
						?>
                    <? endforeach;?>
                  </select>
				  </td>
      </tr>
    </table></td>
    <td width="34%"><table width="100%" cellspacing="5" cellpadding="5">
      
      <tr>
        <td width="32%"><?=$this->lang->line('kategori');?></td>
        <td width="68%">: 		
		          <select name="cari_kategori" id="cari_kategori" style="width:150px">
                    <option value="0">
                    <?=$this->lang->line('combo_box_kategori');?>
                    </option>
                    <? foreach ($data_kategori->result() as $rows):?>
                    <option value="<?=$rows->cat_code?>" <?=($cari_kategori == $rows->cat_code)?('SELECTED="selected"'):('')?>>
                    <?=$rows->cat_name?>
                    </option>
                    <?
						
							$sd=$rows->cat_code;
							if ($cari_kategori == $rows->cat_code){
								$nama_kategori=$rows->cat_name;
							}
						?>
                    <? endforeach;?>
                  </select>
				  </td>
      </tr>
      <tr>
         <td><?=$this->lang->line('produk');?></td>
        <td>: <input type="text" style="width:150px" name="cari_produk" id="cari_produk" value="<?=$cari_produk?>" /></td>
      </tr>
    </table></td>
    <td width="31%"><table width="123%" cellspacing="5" cellpadding="5">
	
      <tr>
     	<td><?=$this->lang->line('kelas');?></td>
         <td width="68%">: 		
		          <select name="cari_kelas" id="cari_kelas" style="width:150px">
                    <option value="0">
                    <?=$this->lang->line('combo_box_kelas');?>
                    </option>
                    <? foreach ($data_kelas->result() as $rows):?>
                    <option value="<?=$rows->cat_code?>" <?=($cari_kelas == $rows->cat_code)?('SELECTED="selected"'):('')?>>
                    <?=$rows->cat_name.' ('.$rows->cat_code.')'?>
                    </option>
                    <?
						
							$sd=$rows->cat_code;
							if ($cari_kelas == $rows->cat_code){
								$nama_kelas =$rows->cat_name;
							}
						?>
                    <? endforeach;?>
                  </select>
				  </td>
      </tr>
      <tr>
       <td><?=$this->lang->line('status');?></td>
        <td>: <select name="cari_status_produk" id="cari_status_produk" style="width:150px">
            <option value="" selected=selected >
            <?=$this->lang->line('combo_box_status');?>
            </option>
            <option value="active" <?=($cari_status_produk == 'active')?("selected=selected"):('')?>>
            <?=$this->lang->line('status_aktif');?>
            </option>
            <option value="non active" <?=($cari_status_produk == 'non active')?("selected=selected"):('')?>>
            <?=$this->lang->line('status_tidak_aktif');?>
            </option>
          </select></td>
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
<? if ($cari_tahun != '0' || $cari_bulan != 0 || $cari_status_produk != '' || $cari_kode != '' || $cari_kategori != '' || $cari_kelas != '' || $cari_grup != '' || $cari_produk != ''  ): ?>
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
  
   <? if ($cari_status_produk!= ''): ?>
  <tr>
    <td width="115"><?=$this->lang->line('status');?></td>
    <td width="14">:</td>
    <td width="197"><font color="red" >
					<?
					if($cari_status_produk == 'active'){
						echo $this->lang->line('status_aktif');
					}else {
						echo $this->lang->line('status_tidak_aktif');
					}
					?> 
	</font>    </td>
  </tr>
  <? endif;?>


   <? if ($cari_kode!= ''): ?>
  <tr>
    <td width="115">
      <?=$this->lang->line('kode');?>
  </td>
    <td width="14">:</td>
    <td width="197"><font color="red" ><?=$cari_kode?> </font>    </td>
  </tr>
  <? endif;?>

   <? if ($cari_kategori!= ''): ?>
  <tr>
    <td width="115">
      <?=$this->lang->line('kategori');?>
    </td>
    <td width="14">:</td>
    <td width="197"><font color="red" ><?=$nama_kategori?> </font></td>
  </tr>
  <? endif;?>
  
     <? if ($cari_kelas!= ''): ?>
  <tr>
    <td width="115">
      <?=$this->lang->line('kelas');?>
   </td>
    <td width="14">:</td>
    <td width="197"><font color="red" ><?=$nama_kelas?> (<?=$cari_kelas?>) </font>    </td>
  </tr>
  <? endif;?>
  
  
     <? if ($cari_grup!= ''): ?>
  <tr>
    <td width="115">
      <?=$this->lang->line('grup');?>
    </td>
    <td width="14">:</td>
    <td width="197"><font color="red" ><?=$nama_grup?> (<?=$cari_grup?>) </font>    </td>
  </tr>
  <? endif;?>
  
       <? if ($cari_produk!= ''): ?>
  <tr>
    <td width="115">
		  <?=$this->lang->line('produk');?>
 </td>
    <td width="14">:</td>
    <td width="197"><font color="red" ><?=$cari_produk?> </font>    </td>
  </tr>
  <? endif;?>
</table>
<? endif;?>

<!--  ================== akhir bwt nampilin apa aja yng jadi filternya ====================== -->


<? if ($cari_status !=''):?>	
	<div align="right"> <?=$this->lang->line('lap_ada');?><font color="red" > <?=$jumlah_data?>  </font> <?=$this->lang->line('lap_data');?></div>
<? endif; ?><!-- endif cari status -->

<br />
<table width="100%"  border="0" cellpadding="0" cellspacing="1" class="ui-widget-content ui-corner-all">
<? if ($cari_status !=''):?>	

	
		<tr class="ui-state-default">
		  <td width="4%" align="center" class="ui-state-default" ><?=$this->lang->line('no');?></td>
		  <td width="12%" align="center"><?=$this->lang->line('kode');?></td>
		  <td width="17%" align="center"><?=$this->lang->line('kategori');?></td>
		  <td width="17%" align="center"><?=$this->lang->line('kelas');?></td>
		  <td width="17%" align="center"><?=$this->lang->line('grup');?></td>
		  <td width="24%" align="center"><?=$this->lang->line('produk');?></td>		
		  <td width="9%" align="center"><?=$this->lang->line('status');?></td>
	    </tr>
		
	
		
		<?php 
		// $no = 1; //tanpa paging
		$i=1;
		$no = $no_pos+1; // pake paging 
		if ($jumlah_data > 0): // klo data query ada
		for ($i=0; $i < sizeof($array_produk);$i++):
		?>
		<tr bgcolor="lightgray">
		  <td valign="middle" align="center" class="ui-state-active"><?=$no?> </td>
		  <td valign="middle" align="center" ><?=$array_produk[$i]['kode_produk']?></td>
		  <td valign="middle" align="left"><?=$array_produk[$i]['kategori_produk']?></td>
		  <td valign="middle" align="left"><?=$array_produk[$i]['kelas_produk']?></td>
		  <td valign="middle" align="left"><?=$array_produk[$i]['grup_produk']?></td>
		  <td valign="middle" align="left"><?=$array_produk[$i]['nama_produk']?></td>		  
		  <td valign="middle" align="center">
		  	<?
				if ($array_produk[$i]['status_produk'] == 'active' )
				{
					echo $this->lang->line('status_aktif');
				}else {
					echo '<font color=red>'.$this->lang->line('status_tidak_aktif').'</font>';
				}

			?>
		  </td>
	    </tr>
		<?php 
		$no++;
		endfor;
		else:// klo data query ada?>
		<tr>
			  <td colspan="7" align="center"><font color="#FF0000"><strong>
			    <?=$this->lang->line('lap_tabel_tidak_ada_data');?></strong></font></td>				
		 </tr>
		<?php endif;
				else: //else cari status
		?>
			<tr >
			<td colspan="7" align="center" bgcolor="#FFFFFF">
			<font color="#FF0000">
				<strong>
					<?=$this->lang->line('lap_tabel_pilih_kriteria');?>
				</strong>
			</font></td>
			</tr>
<?php endif; // endif cari status?>
</table>
  