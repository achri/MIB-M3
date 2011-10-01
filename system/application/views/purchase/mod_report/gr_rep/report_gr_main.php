
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
	$('#status_kb').val('');
	$('#cari_pemasok').val('');
	$('#cari_kategori').val('');	


}

</script>



<h3><?=$title_page?></h3>

<div class="noprint">
<!-- ==================== button bwt ekxport & cetak ======================== -->
	<? if ($cari_status != ''): ?>

<div align="right">
  <table border="0">
  <tr>
    <td>
		<form method="post" action="index.php/<?=$link_controller?>/excel" > 	  
	<!-- ====================== bwt seleksi,, klo kosong datanya eksport g aktid ========= -->
		<? if ($jumlah_data!= 0){ ?>
				<input type="submit" value="<?=$this->lang->line('lap_salin_ke_excel');?>">	
				<input type="button" id="print" value="<?=$this->lang->line('lap_print');?>" onclick="window.print();">
		<? }else { ?>
				<input type="submit" value="<?=$this->lang->line('lap_salin_ke_excel');?>" disabled="disabled">	
				<input type="button" id="print" value="<?=$this->lang->line('lap_print');?>" onclick="window.print();" disabled="disabled">
		
		<? } ?>
	<!-- =============================================================================== -->
		
				<!-- ekspor ke excel datanya -->	
				<input type=hidden name="cari_tahun" value="<?=$cari_tahun?>">				
				<input type=hidden name="cari_bulan" value="<?=$cari_bulan?>">
				<input type=hidden name="cari_pemasok" value="<?=$cari_pemasok?>">				
				<input type=hidden name="cari_kategori" value="<?=$cari_kategori?>">
				<input type=hidden name="status_kb" value="<?=$status_kb?>">
		</form>	
	</td>
    <td>
</td>
  </tr>
</table>
<!-- ==================== akhir button bwt ekxport & cetak ======================== -->


</div>
<? endif;?>

<div class="noprint" >
    <form name="form_entry" method="post" action="index.php/<?=$link_controller?>/index">
    <div style="width:99%" class="ui-widget-content ui-corner-all">
	<b><?=$this->lang->line('lap_judul_cari');?></b>
	<table align="center" width="879" cellspacing="5" cellpadding="5">
		<tr>
			<td width="20"><?=$this->lang->line('tahun');?></td>
			<td width="182">: 
			  <select name="cari_tahun" id="cari_tahun" style="width:150px" onchange="setBulan(this)">
              <option value="0" >
              <?=$this->lang->line('combo_box_tahun');?>
              </option>
              <?php foreach($data_tahun->result() as $thn):?>
              <option value="<?=$thn->thn?>" <?=($cari_tahun == $thn->thn)?('SELECTED="selected"'):('')?>>
              <?=$thn->thn?>
              </option>
              <?php endforeach;?>
          </select></td>
			<td width="7">&nbsp;</td>
			<td width="24"><?=$this->lang->line('bulan');?></td>
		  <td width="170">: 
		
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
			<td width="5">&nbsp;</td>
		    <td width="142">
				<font size="-2">
					<?=$this->lang->line('status');?> <?=$this->lang->line('lap_kontra_bon');?>
				</font>			</td>
		    <td width="202">: 
		      <select name="status_kb"  id="status_kb" style="width:150px" >
              <option value="0" >
              <?=$this->lang->line('combo_box_status');?>
              </option>
             <option value="1" <?=($status_kb == 1)?('SELECTED="selected"'):('')?> >
              <?=$this->lang->line('status_sudah');?>
              </option>
			   <option value="2"  <?=($status_kb == 2)?('SELECTED="selected"'):('')?>>
              <?=$this->lang->line('status_belum');?>
              </option>
            </select></td>
	      </tr>
		<tr>
		  <td><?=$this->lang->line('kategori');?></td>
		  <td>:
            <select name="cari_kategori" id="cari_kategori" style="width:150px"  >
              <option value="0" >
              <?=$this->lang->line('combo_box_kategori');?>
              </option>
              <?php foreach($data_kategori->result() as $rows):?>
              <option value="<?=$rows->cat_code?>" <?=( $cari_kategori == $rows->cat_code )?('SELECTED="selected"'):('')?>>
              <?=$rows->cat_name?>
              <?
							if ($cari_kategori == $rows->cat_code){
								$nama_kategori=($rows->cat_name);
							}
						?>
              </option>
              <?php endforeach;?>
          </select></td>
		  <td>&nbsp;</td>
		  <td><?=$this->lang->line('supplier');?></td>
		  <td>:
            <select name="cari_pemasok" id="cari_pemasok" style="width:150px" >
              <option value="0" >
              <?=$this->lang->line('combo_box_supplier');?>
              </option>
              <?php foreach($data_pemasok->result() as $rows):?>
              <option value="<?=$rows->sup_id?>" <?=( $cari_pemasok == $rows->sup_id )?('SELECTED="selected"'):('')?>>
              <?=$rows->sup_name?>
              <?
							if ($cari_pemasok == $rows->sup_id){
								$nama_pemasok=($rows->sup_name);
							}
						?>
              </option>
              <?php endforeach;?>
          </select></td>
		  <td>&nbsp;</td>
	      <td>&nbsp;</td>
	      <td align="right">
		  <input type="button" name="bersihkan" value="<?=$this->lang->line('button_bersihkan');?>" onclick="bersihkanFilter()"/>
		  <input name="cari_status" type="submit" value="<?=$this->lang->line('cari');?>" /></td>
		  </tr>
	</table>
	<!--  blm jadi
	<input name="cari_lebih_rinci" type="checkbox" value="ceklist" <?=$checklist;?> onchange="document.form_entry.submit();" /> <?=$this->lang->line('lap_seleksi_canggih');?>
	-->
	<? if ($checklist != '') { ?>
	<table width="733" height="217" align="center" cellpadding="5" cellspacing="5">
		<tr>
			<td width="127" height="37"><?=$this->lang->line('lap_no_po');?></td>
			<td width="12">: </td>
			<td width="153"><input type="text" name="cari_po_no" onchange="document.form_entry.submit()" value="<?=$cari_po_no?>" width="0"/></td>
			<td width="68">&nbsp;</td>
			<td width="81"><?=$this->lang->line('lap_pemohon');?></td>
			<td width="9">:</td>
			<td width="140"><input type="text" name="cari_pemohon" onchange="document.form_entry.submit()" value="<?=$cari_pemohon?>" width="0"/></td>
			<td width="16">&nbsp;</td>
		</tr>
		<tr>
			<td width="127" height="37"><?=$this->lang->line('supplier');?></td>
			<td width="12">: </td>
			<td><input type="text" name="cari_pemasok" onchange="document.form_entry.submit()" value="<?=$cari_pemasok?>" /></td>
			<td>&nbsp;</td>
			<td><?=$this->lang->line('lap_kode_barang');?></td>
			<td>:</td>
			<td><input type="text" name="cari_kode_barang" onchange="document.form_entry.submit()" value="<?=$cari_kode_barang?>" /></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
          <td height="37"><?=$this->lang->line('kategori');?></td>
		  <td>: </td>
		  <td><input type="text" name="cari_kategori" onchange="document.form_entry.submit()" value="<?=$cari_kategori?>" width="0"/></td>
		  <td>&nbsp;</td>
		  <td><?=$this->lang->line('lap_jenis_barang');?></td>
		  <td>:</td>
		  <td><input type="text" name="cari_pemasok3" onchange="document.form_entry.submit()" value="<?=$cari_pemasok?>" /></td>
		  <td>&nbsp;</td>
	    </tr>
		<tr>
          <td height="37"><?=$this->lang->line('lap_nama_barang');?></td>
		  <td>: </td>
		  <td><input type="text" name="cari_nama_barang" onchange="document.form_entry.submit()" value="<?=$cari_nama_barang?>" width="0"/></td>
		  <td>&nbsp;</td>
		  <td><?=$this->lang->line('lap_qty_beli');?></td>
		  <td>:</td>
		  <td><input type="text" name="cari_qty_beli" onchange="document.form_entry.submit()" value="<?=$cari_qty_beli?>" /></td>
		  <td>&nbsp;</td>
	    </tr>
		<tr>
			<td width="127" height="37"><?=$this->lang->line('satuan');?></td>
			<td width="12">: </td>
			<td><input type="text" name="cari_satuan" onchange="document.form_entry.submit()" value="<?=$cari_satuan?>" width="0"/></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
	</table>
	<? } //endif ?>
    </div>
	</form>
</div>
</div>
<div class="clr"></div>
<br />

<!--  ================== bwt nampilin apa aja yng jadi filternya ====================== -->
<div class="clr"></div>
<? if ($cari_tahun != '0' || $cari_bulan != 0 || $cari_kategori != 0 || $status_kb != 0 || $cari_pemasok != 0): ?>
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
  
  
  <? if ($cari_pemasok!= 0):?>
  <tr>
    <td><?=$this->lang->line('supplier');?></td>
    <td>:</td>
    <td><font color="red" ><?=$nama_pemasok?>    </font></td>
  </tr>
  <? endif;?>

  <? if ($status_kb!= 0):?>
  <tr>
    <td><?=$this->lang->line('status');?> <?=$this->lang->line('lap_kontra_bon');?></td>
    <td>:</td>
	<? if ($status_kb == 1){?>
	    <td><font color="red" ><?=$this->lang->line('status_sudah');?>    </font></td>
	<? } else if ($status_kb == 2){ ?>
	    <td><font color="red" ><?=$this->lang->line('status_belum');?>    </font></td>	
	<? }?>
  </tr>
  <? endif;?>

</table>
<? endif;?>

<!--  ================== akhir bwt nampilin apa aja yng jadi filternya ====================== -->




<? if ($cari_status !=''):?>	
<div align="right"> 
  <?=$this->lang->line('lap_ada');?>
  <font color="red" > 
  <?=$jumlah_data?>
  </font> 
  <?=$this->lang->line('lap_data');?>
</div>
<? endif; ?>
<div align="center">
	<font size="-3">  <!-- awal font seluruh tabel -->
<table width="83%"  align="center" border="0" cellpadding="0" cellspacing="1" class="ui-widget-content ui-corner-all"
><? if ($cari_status !=''):?>

	<? if ($jumlah_data >0){ // untuk seleksi ada data ga ?>


		<tr bgcolor="#CCCCCC" 
class="ui-state-default">
		  <td width="3%" align="center" rowspan="2"><?=$this->lang->line('no');?></td>
		  <td width="3%" align="center" rowspan="2"><?=$this->lang->line('lap_no_bpb');?></td>
		  <td width="3%" align="center" rowspan="2"><?=$this->lang->line('lap_no_po');?></td>
		  <td width="3%" align="center" rowspan="2"><?=$this->lang->line('lap_tgl_surat_jalan');?></td>
		  <td align="center" colspan="2"><?=$this->lang->line('barang');?></td>
		  <td width="7%" align="center" rowspan="2"><?=$this->lang->line('supplier');?></td>
		  <td width="6%" align="center" rowspan="2"><?=$this->lang->line('lap_no_sj');?></td>
		  <td width="8%" align="center" rowspan="2"><?=$this->lang->line('qty');?></td>
		  <td width="8%" align="center" rowspan="2"><?=$this->lang->line('status');?>		    <?=$this->lang->line('lap_kontra_bon');?></td>
		  <td align="center" colspan="2"><?=$this->lang->line('lap_harga_satuan');?></td>
		  <td align="center" colspan="2"><?=$this->lang->line('total');?></td>
	    </tr>
		<tr bgcolor="#CCCCCC" 
class="ui-state-default">
		  <td align="center" width="7%"><?=$this->lang->line('kategori');?></td>
		  <td align="center" width="5%"><?=$this->lang->line('lap_nama_produk_kode');?></td>
		  <td width="5%" align="center"><?=$this->lang->line('rp')?></td>
		  <td width="5%" align="center"><?=$this->lang->line('us$')?></td>
		  <td width="6%" align="center"><?=$this->lang->line('rp')?></td>
		  <td width="23%" align="center"><?=$this->lang->line('us$')?></td>
		</tr>
	
		<?
			//$no=1;
			$no=1; //untunk paging
			//foreach($data_penerimaan->result() as $rows): // looping ntuk data penerimaan
			for ($i=0; $i < sizeof($data_penerimaan);$i++):// looping ntuk data penerimaan
				
			//	if ($data_penerimaan[$i]['cat_name'] == $nama_kategori ):
				//if ($data_penerimaan[$i]['cat_name'] !=''){// looping ntuk data penerimaan					
//				for ($j=0; $j < sizeof($data_kategori[$i]);$j++):
				
		?>
		
	
<tr bgcolor="#CCCCCC" >
		  
   		 	<td valign="top" align="center"  class="ui-state-active"><?=$no?> </td>
		  
   		  <td valign="top" align="center" ><?=$data_penerimaan[$i]['gr_no']?> </td>
		  <td valign="top" align="center"><?=$data_penerimaan[$i]['po_no']?></td>
		  <td valign="top" align="center"><?=$data_penerimaan[$i]['gr_dateSJ']?></td>
		  <td valign="top" align="center"> <?=$data_penerimaan[$i]['cat_name']?> </td>
		  <td valign="top" align="left"><?=$data_penerimaan[$i]['pro_name']?> <br> <?=$data_penerimaan[$i]['pro_code']?></td>
		  
    <td valign="top" align="left">
     <?=$data_penerimaan[$i]['sup_name']?>    </td>
		  <td valign="top" align="left"><?=$data_penerimaan[$i]['gr_suratJalan']?></td>
		  <td align="right" valign="top"> <?=$this->general->digit_number($data_penerimaan[$i]['satuan_id'],$data_penerimaan[$i]['qty'])?>
		   <br> <?=$data_penerimaan[$i]['satuan_name']?> 	  </td>

		  <td align="center" valign="top">
		  <? 
				if ($data_penerimaan[$i]['con_id'] <> 0 ){
				  echo $this->lang->line('status_sudah');
				}else if ($data_penerimaan[$i]['con_id'] == 0 ){
				  echo '<font color=red >'.$this->lang->line('status_belum').'</font>';
				 } else {
				 	echo "-";
				 }
			?>
	</td>
		
			  <td valign="top" align="right">
			    <?	if ($data_penerimaan[$i]['cur_symbol'] =='Rp') { ?>
			  		<?=number_format($data_penerimaan[$i]['price'],$this->general->digit_rp())?>
				<? } else { echo "-"; }?>								
			  </td>
			  <td valign="top" align="right">
  			    <?	if ($data_penerimaan[$i]['cur_symbol'] =='US$') { ?>
			  		<?=number_format($data_penerimaan[$i]['price'],$this->general->digit_dolar())?>
				<? } else { echo "-"; }?>
			  </td>
			  <td valign="top" align="right">
  			    <?	if ($data_penerimaan[$i]['cur_symbol'] =='Rp') { ?>
				  	<?=number_format($data_penerimaan[$i]['gd_totprice'],$this->general->digit_rp())?>
				<? } else { echo "-"; }?>
			   </td>
			  <td valign="top" align="center">
   			    <?	if ($data_penerimaan[$i]['cur_symbol'] =='US$') { ?>
			  		<?=number_format($data_penerimaan[$i]['gd_totprice'],$this->general->digit_dolar())?>
				<? } else { echo "-"; }?>
			  </td>
		
  </tr>
		
		<?
			$no++; // untuk nambah penomoran
		//	endforeach; // akhir looping data_kategori
		//	endif; // end if kategori
			endfor; // akhir looping data_penerimaan
		
		?>
		<tr bgcolor="#d0d0d0" 
 class="ui-state-active">
			<td colspan="12" align="right"><strong>
			  <?=$this->lang->line('total');?> 
		    :</strong></td>
			<td align="right">
			  <?=number_format($total_rp,$this->general->digit_rp())?>		    </td>
			<td align="right"><?=number_format($total_dol,$this->general->digit_dolar())?></td>
		</tr>
		
		<!-- ============ buat pagingnya ================== 
		<tr><td colspan="15" class="ui-widget-header"> <?=$this->lang->line('halaman');?> : <?=($this->pagination->create_links())?($this->pagination->create_links()):('-')?></td></tr>
		 ============ akhir buat pagingnya ================== -->
		<? } else { //seleksi data ada ato ga ?>
			
			<!-- klo data nya kosong -->
			<tr  >
				<td colspan="16" align="center"><font color="#FF0000"><strong><?=$this->lang->line('lap_tabel_tidak_ada_data');?></strong></font>			   </td>
			</tr>
		
		<? } // akhir selsksi ada ada ato g ?>
		<tr bgcolor="#000000">
		  <td colspan="16"><img src="images/spacer.gif" width="1" height="1"></td>
	    </tr>
		<?
				else:
			?>
			
			<tr >
				<td colspan="8" align="center" bgcolor="#FFFFFF"><font color="#FF0000"><strong><?=$this->lang->line('lap_tabel_pilih_kriteria');?></strong></font></td>
			</tr>
			<?php endif;?>
</table>
</font> <!-- akhir font seluruh tabel -->

</div>