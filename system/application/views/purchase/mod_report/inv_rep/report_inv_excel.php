
<?
// bwt ekxport ke excel nya
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename='.$this->lang->line('lap_nama_file').'.xls');
header('Cache-Control: max-age=0');


?>




  <?
							foreach($data_kategori->result() as $rows){
							if ($cari_kategori == $rows->cat_id)
								$nama_kategori=($rows->cat_name);
							}
						?>

<h3><?=$title_page?></h3>

<!--  ================== bwt nampilin apa aja yng jadi filternya ====================== -->
<div class="clr"></div>
<? if ($cari_tahun != '0' || $cari_bulan != 0 || $cari_kategori != 0): ?>
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
  
  
  <? if ($cari_kategori != 0):?>
  <tr>
    <td><?=$this->lang->line('kategori');?></td>
    <td>:</td>
    <td><font color="red" ><?=$nama_kategori?>    </font></td>
  </tr>
  <? endif;?>

</table>
<? endif;?>

<!--  ================== akhir bwt nampilin apa aja yng jadi filternya ====================== -->

<div align="right"> <?=$this->lang->line('lap_ada');?><font color="red" > <?=$jumlah_data_kategori?>  </font> <?=$this->lang->line('kategori');?></div>
<table width="100%" cellspacing="0" cellpadding="0" border="1">
	<?php 
	if ($jumlah_data_kategori >0 ): 
	for ($i=0; $i < sizeof($cat);$i++):
	?>
	<tr  class="ui-state-default">
	 <td colspan="2"><?=$cat[$i]['cat_name']?></td>
	</tr>
	<tr bgcolor="#CCCCCC">
	 <td width="10%" class="ui-widget-header">&nbsp;</td>
	 <td>
		<table width="100%" border="1" cellspacing="0" cellpadding="2">
			<tr class="ui-widget-header">
			  <td width="6%" align="center" rowspan="2"><?=$this->lang->line('no');?></td>
				<td width="15%" align="center" rowspan="2"><?=$this->lang->line('lap_group');?></td>
				<td width="11%" align="center" rowspan="2"><?=$this->lang->line('lap_kode_barang');?></td>
				<td width="26%" align="center" rowspan="2"><?=$this->lang->line('lap_nama_barang');?></td>
				<td align="center" colspan="3"><?=$this->lang->line('qty');?></td>
				<td width="9%" align="center" rowspan="2"><?=$this->lang->line('satuan');?></td>
			</tr>
			<tr  class="ui-state-default">
				<td width="10%"><?=$this->lang->line('lap_stok_akhir');?></td>
				<td width="10%"><?=$this->lang->line('lap_buffer');?></td>
				<td width="13%"><?=$this->lang->line('supplier');?></td>
			</tr>
			<?php 
			if (isset($pro[$i])):
			$no=1;
			for ($j=0; $j < sizeof($pro[$i]);$j++):
			?>
			<tr bgcolor="#FFFFFF">
			  <td valign="top" align="center"  class="ui-state-active" ><?=$no?></td>
				<td valign="top"><?=$pro[$i][$j]['group']?></td>
				<td valign="top"><?=$pro[$i][$j]['pro_code']?></td>
				<td valign="top"><?=$pro[$i][$j]['pro_name']?></td>
				<td colspan="3" align="left">
				    <table >
				    <?php 
				    	if (isset($stock[$i][$j])):
						for ($k=0; $k < sizeof($stock[$i][$j]);$k++):
						?>
						<tr>
							<td width="81"  valign="top" align="center">
								<?=number_format($stock[$i][$j][$k]['inv_end'],0)?>							</td>
							<td width="62"  valign="top" align="center">
								<?=$pro[$i][$j]['pro_min_reorder']?>							</td>
							<td width="57"  align="center">
								<?=($stock[$i][$j][$k]['sup_name']!='')?($stock[$i][$j][$k]['sup_name']):('-')?>							</td>
						</tr>
					   <?php 
					   endfor;
					   else:
					   ?>
					   	<tr bgcolor="#FFFFFF">
							<td colspan="3" align="center">---</td>
						</tr>
					   <?php 
					   endif;
					   ?>
					</table>				</td>
				
				<td valign="top" align="center"><?=$pro[$i][$j]['satuan_name']?></td>
			</tr>
			
			<?php 
			$no++;
			endfor;
			else:
			?>
			<tr >
				<td colspan="8" align="center" bgcolor="#FFFFFF"><font color="#FF0000"><strong><?=$this->lang->line('lap_tabel_tidak_ada_data');?></strong></font></td>
			</tr>
			<?php endif;?>
		</table>	 </td>
	</tr>
		<tr>
				<td colspan="2">&nbsp;				</td>
	</tr>
	<?php 
	endfor;
	else:
			?>
			<tr >
				<td colspan="8" align="center" bgcolor="#FFFFFF"><font color="#FF0000"><strong><?=$this->lang->line('lap_tabel_tidak_ada_data');?></strong></font></td>
			</tr>
			<?php endif;
					
			?>
			
			
</table>
