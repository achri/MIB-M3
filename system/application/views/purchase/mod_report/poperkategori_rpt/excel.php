
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
    <td width="110"><?=$this->lang->line('tahun')?></td>
    <td width="215">:<font color="red" >
      <?=$cari_tahun?>
    </font></td>
    <td width="1">&nbsp;</td>
  </tr>
  <? endif;?>
  
   <? if ($cari_bulan != '0'): ?>
  <tr>
    <td width="110"><?=$this->lang->line('bulan')?></td>
    <td width="215">:<font color="red" >
      <?=$data_bulan[$cari_bulan]?>
    </font></td>
    <td width="1">&nbsp;</td>
  </tr>
  <? endif;?>
  
  
  <? if ($cari_kategori != 0):?>
  <tr>
    <td><?=$this->lang->line('kategori');?></td>
    <td>:<font color="red" >
      <?=$nama_kategori?>
    </font></td>
    <td>&nbsp;</td>
  </tr>
  <? endif;?>
</table>
<? endif;?>

<!--  ================== akhir bwt nampilin apa aja yng jadi filternya ====================== -->

<div align="right"> <?=$this->lang->line('lap_ada');?><font color="red" > <?=$jumlah_data_kategori?>  </font> <?=$this->lang->line('kategori');?></div>

<table width="100%" cellspacing="0" cellpadding="0" border="1">
	<?php 
	if ($jumlah_data_kategori >0 ): 
	for ($i=0 ; $i < sizeof($cat);$i++):?>
	<tr  class="ui-state-default">
	 <td>
<table width="920"  border="0">
  <tr>
          <td width="66" > 
            <?=$cat[$i]['cat_name']?> :: 
         <!-- di ilangin dulu 
           Rp. <?=number_format($total_rp,2);?>  
           $ <?=number_format($total_dol,2);?>
        -->		     </td>
          <td width="27" >&nbsp;</td>
          <td width="27" >&nbsp;</td>
          <td width="53" >&nbsp;</td>
          <td width="53" >&nbsp;</td>
          <td width="53" >&nbsp;</td>
          <td width="53" >&nbsp;</td>
          <td width="53" >&nbsp;</td>
          <td width="53" >&nbsp;</td>
          <td width="53" >&nbsp;</td>
          <td width="53" >&nbsp;</td>
          <td width="107" >&nbsp;</td>
          <td width="54" >&nbsp;</td>
          <td width="157"  align="right">	
	<? if($jumlah_data_kategori == 1): ?>	
	<?=$this->lang->line('lap_ada');?> <?=$jumlah_data_produk?>  <?=$this->lang->line('data');?> <?=$cat[$i]['cat_name']?>
	<? endif;?>	</td>
  </tr>
</table>	 </td>
	</tr>
	<tr bgcolor="#CCCCCC">
	 <td>
		<table width="100%" border="1" cellspacing="0" cellpadding="2">
        <tr class="ui-widget-header"> 
          <td width="5%" rowspan="2" align="center"> 
            <?=$this->lang->line('no');?>          </td>
          <td width="13%" rowspan="2" align="center"> 
            <?=$this->lang->line('lap_no_po');?>          </td>
          <td width="13%" rowspan="2" align="center"> 
            <?=$this->lang->line('lap_pemohon');?>          </td>
          <td width="13%" rowspan="2" align="center"> 
            <?=$this->lang->line('lap_kode_barang');?>          </td>
          <td width="13%" rowspan="2" align="center"> 
            <?=$this->lang->line('supplier');?>          </td>
          <td width="18%" rowspan="2" align="center"> 
            <?=$this->lang->line('kategori');?>          </td>
          <td width="18%" rowspan="2" align="center"> 
            <?=$this->lang->line('jenis_barang');?>          </td>
          <td width="18%" rowspan="2" align="center"> 
            <?=$this->lang->line('lap_nama_barang');?>          </td>
          <td width="9%" rowspan="2" align="center"> 
            <?=$this->lang->line('qty');?>          </td>
          <td width="9%" rowspan="2" align="center"> 
            <?=$this->lang->line('satuan');?>          </td>
          <td colspan="2" align="center"> 
            <?=$this->lang->line('harga');?>
            &nbsp; 
            <?=$this->lang->line('satuan');?>          </td>
          <td colspan="2" align="center"> 
            <?=$this->lang->line('total');?>          </td>
        </tr>
        <tr class="ui-widget-header"> 
          <td  align="center">Rp.</td>
          <td align="center">$ </td>
          <td  align="center">Rp.</td>
          <td  align="center">$</td>
        </tr>
        <?php 
			if (isset($pro[$i])):
			
			// ======= emergenci ========
				$total_semua_rp=0;
				$total_semua_dol=0;				
			
			// ======= (akhir) emergenci ========
			
			
			$no=1;
			for ($j = 0; $j < sizeof($pro[$i]);$j++):?>
        <tr bgcolor="#FFFFFF" valign="top"> 
          <td valign="top" align="center" class="ui-state-active"> 
            <?=$no?>          </td>
          <td valign="top"> 
            <?=$pro[$i][$j]['po_no']?>          </td>
          <td valign="top"> 
            <?=$pro[$i][$j]['usr_name']?>          </td>
          <td valign="top"> 
            <?=$pro[$i][$j]['pro_code']?>          </td>
          <td valign="top"> 
            <?=$pro[$i][$j]['sup_name']?>          </td>
          <td valign="top"> 
            <?=$cat[$i]['cat_name']?>          </td>
          <td valign="top"> 
            <?=$pro[$i][$j]['class_name']?>          </td>
          <td valign="top"> 
            <?=$pro[$i][$j]['pro_name']?>          </td>
          <td valign="top" align="center"> 
           <?=$this->general->digit_number($pro[$i][$j]['satuan_id'],$pro[$i][$j]['qty'])?>          </td>
          <td valign="top" align="center"> 
            <?=$pro[$i][$j]['satuan_name']?>          </td>
          <? if ($pro[$i][$j]['cur_symbol']=='Rp') {?>
          <td align="right"> 
          <?=number_format($pro[$i][$j]['price'],2);?></td>
		  
          <td align="right"> - </td>
          <td  align="right"> 
            <?=number_format($pro[$i][$j]['sub_total'],2);?>    
			  <? $total_semua_rp=$total_semua_rp+$pro[$i][$j]['sub_total']; ?>  
	      </td>
          <td  align="right">-</td>
          <? 
		  
		  
		  ?>
          <? }else{ ?>
          <td align="right"> - </td>
          <td align="right"> 
            <?=number_format($pro[$i][$j]['price'],2);?>
          </td>
          <td  align="right">-</td>
          <td  align="right"> 
            <?=number_format($pro[$i][$j]['sub_total'],2);?>
			
		  <? $total_semua_dol=$total_semua_dol+$pro[$i][$j]['sub_total']; ?>          </td>
          <? } ?>
        </tr>
        <?php 
			$no++;
		
			endfor;
			?>
        <tr bgcolor="#FFFFFF" class="ui-state-active"> 
          <td colspan="12" align="right" ><strong> 
            <?=$this->lang->line('total');?>
            &nbsp;&nbsp;</strong> </td>
          <td  align="right"><?=number_format($total_semua_rp,2);?>          </td>
          <td  align="right"> 
            <?=number_format($total_semua_dol,2);?>          </td>
        </tr>
        <?
		  
			else:
			?>
        <tr bgcolor="#FFFFFF"> 
          <td colspan="14" align="center"><font color="#FF0000"><strong><?=$this->lang->line('lap_tabel_tidak_ada_data');?></strong></font></td>
        </tr>
        <?php endif;?>
      </table>	 </td>
	</tr>
	
		<tr>
				<td>&nbsp;				</td>
	</tr>
	
	<?php endfor;
	else:
			?>
			<tr >
				<td colspan="6" align="center" bgcolor="#FFFFFF"><font color="#FF0000"><strong><?=$this->lang->line('lap_tabel_tidak_ada_data');?></strong></font></td>
			</tr>
			<?php endif;
			?>
	
</table>
