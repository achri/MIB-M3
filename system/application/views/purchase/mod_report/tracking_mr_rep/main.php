

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
	$('#cari_no_mr').val('');

}
</script>

<h3><?=$title_page?> </h3>
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
				<input type=hidden name="cari_no_mr" value="<?=$cari_no_mr?>">
				<input type=hidden name="cari_status" value="<?=$cari_status?>">				
		</form>	
	</td>
    <td>
</td>
  </tr>
</table>
<!-- ==================== akhir button bwt ekxport & cetak ======================== -->
</div>
<? endif;?>
</div>
	
<div class="noprint" >	
	<div  style="width:99%" class="ui-widget-content ui-corner-all">	
	<form name="form_entry" method="post" action="index.php/<?=$link_controller?>/index">
	<table align="center" width="846" cellspacing="5" cellpadding="5">
			&nbsp; <b><?=$this->lang->line('lap_judul_cari');?></b>
			<tr>
				<td width="22"><?=$this->lang->line('tahun');?></td>
				<td width="186">: 
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
				<td width="22">&nbsp;</td>
				<td width="26"><?=$this->lang->line('bulan');?></td>
			  <td width="173">: 
			<? if ($cari_tahun != 0 ){ ?>
			  	<select name="cari_bulan" id="cari_bulan" style="width:150px"  >
			<? } else { ?>
			  	<select name="cari_bulan" id="cari_bulan" style="width:150px" disabled="disabled">
			<? }?>                  <option value="0" >
                  <?=$this->lang->line('combo_box_bulan');?>
                  </option>
                  <?php for ($i=1;$i<=12;$i++):?>
                  <option value="<?=$i?>" <?=($cari_bulan == $i)?('SELECTED="selected"'):('')?>>
                  <?=$data_bulan[$i]?>
                  </option>
                  <?php endfor;?>
                </select></td>
				<td width="7">&nbsp;</td>
				<td width="99"><?=$this->lang->line('no').' '.$this->lang->line('lap_mr');?></td>
				<td width="168">: <input type="text" name="cari_no_mr" id="cari_no_mr" value="<?=$cari_no_mr?>" /></td>
			</tr>
			<tr>
			  <td>&nbsp;</td>
			  <td>&nbsp;</td>
			  <td>&nbsp;</td>
			  <td>&nbsp;</td>
			  <td>&nbsp;</td>
			  <td>&nbsp;</td>
			  <td>&nbsp;</td>
			  <td align="right"> 
			  		<input type="button" name="bersihkan" value="<?=$this->lang->line('button_bersihkan');?>" onclick="bersihkanFilter()"/>
			  		<input name="cari_status" type="submit" value="<?=$this->lang->line('cari');?>" />
			   </td>
		    </tr>
		</table>
	  </form>
	</div> <!-- akhir div garis pinggir -->
</div> <!-- akhir div no prin -->
<br />


<!--  ================== bwt nampilin apa aja yng jadi filternya ====================== -->
<div class="clr"></div>
<? if ($cari_tahun != '0' || $cari_bulan != 0 || $cari_no_mr != ''): ?>
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
  
  
  <? if ($cari_no_mr!= ''):?>
  <tr>
    <td><?=$this->lang->line('no').' '.$this->lang->line('lap_mr');?></td>
    <td>:</td>
    <td><font color="red" ><?=$cari_no_mr?>    </font></td>
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

<center>
  <table width="99%"  border="0" cellpadding="0" cellspacing="1" class="ui-widget-content ui-corner-all">
    <? if ($cari_status !=''): // if satu (cari status)?>
    <? if ($jumlah_data > 0){ // if dua (jumlah data mr)			
		//	$no=1;
			for ($i=0 ; $i < sizeof($mr_no);$i++): // for satu ( looping mr)
		?>
    <tr> 
      <td colspan="8">&nbsp;</td>
    </tr>
    <tr  class="ui-state-default"> 
      <td colspan="8" align="left"> <strong> 
        <?=$this->lang->line('no')?>
        <?=$this->lang->line('lap_mr')?>
        : 
        <?=$mr_no[$i]['mr_no']?>
        </strong> </td>
    </tr>
    <tr  class="ui-state-default"> 
      <td width="4%" align="center"> 
        <?=$this->lang->line('no');?>
      </td>
      <td width="18%" align="center"> 
        <?=$this->lang->line('produk');?>
      </td>
      <td width="8%" align="center">
        <?=$this->lang->line('supplier');?>
      </td>
      <td width="8%" align="center"> 
        <?=$this->lang->line('lap_acc_mr');?>
      </td>
      <td width="12%" align="center"> 
        <?=$this->lang->line('lap_cetak_form_keluar_barang');?>
      </td>
      <td width="16%" align="center"> 
        <?=$this->lang->line('lap_keluarkan_barang');?>
      </td>
      <td width="23%" align="center"> 
        <?=$this->lang->line('isi').' '.$this->lang->line('daftar').' '.$this->lang->line('lap_pemakaian_barang');?>
      </td>
      <td width="19%" align="center"> 
        <?=$this->lang->line('lap_kemabali_barang_sisa');?>
      </td>
    </tr>
    <!-- ===================== untuk looping datanya =================== -->
    <?	
		$no=1;
		if($jumlah_data_produk > 0): //if tiga (jumlah data produk)
			for ($j=0; $j < sizeof($pro[$i]);$j++): // for dua (data produk)
				$status_ditolak = 0; // 0= tidak ditolak, 1= ditolak
	?>
    <tr  bgcolor="lightgray" valign="middle"> 
      <td align="center" class="ui-state-active"> 
        <?=$no?>
      </td>
      <td valign="top" align="left"> 
        <a title="<?=$this->lang->line('keperluan').' : '.$pro[$i][$j]['ket_keperluan']?>"> 
			<?=$pro[$i][$j]['pro_name']?>
		</a>
        <br> <font color="#0000FF" size="-3" title="<?=$this->lang->line('keperluan').' : '.$pro[$i][$j]['ket_keperluan']?>" > 
        <? /*
				if ($pro[$i][$j]['nama_pemasok'] != '')
					echo '('.$pro[$i][$j]['nama_pemasok'].', '.$pro[$i][$j]['legalitas_pemasok'].')';
			*/
		?>
        </font> </td>
      <td align="center">
	  	<font size="-2">
        <?
			if ($pro[$i][$j]['nama_pemasok'] != '') {
				echo $pro[$i][$j]['nama_pemasok'].', '.$pro[$i][$j]['legalitas_pemasok'];
			} else {
				echo $this->lang->line('general');
			}				
		?>
		</font>
      </td>
      <td align="center"> 
        <?	// cek status ACC MR
				if ($pro[$i][$j]['status_acc'] == 1)
				{
					echo '<img src="./asset/img_source/centang.gif" />';
				}
				else if ($pro[$i][$j]['status_acc'] == 2)
				{
					echo '<img src="./asset/img_source/centang.gif" /><br>';
					echo '<a href=# title="'.$pro[$i][$j]['ket_persetujuan'].'" ><font color=blue size=-3>'.$pro[$i][$j]['ket_status_acc_mr'].'</font></a>';
				}
				else if ($pro[$i][$j]['status_acc'] == 3)
				{	
					echo '<img src="./asset/img_source/centang.gif" /><br>';
					echo '<a href=# title="'.$pro[$i][$j]['ket_persetujuan'].'" ><font color=blue size=-3>'.$pro[$i][$j]['ket_status_acc_mr'].'</font></a>';
				}
				else if ($pro[$i][$j]['status_acc'] == 4)
				{
					echo '<img src="./asset/img_source/centang.gif" /><br>';
					echo '<a href=# title="'.$pro[$i][$j]['ket_persetujuan'].'" ><font color=blue size=-3>'.$pro[$i][$j]['ket_status_acc_mr'].'</font></a>';
				}
				else if ($pro[$i][$j]['status_acc'] == 5)
				{ 
					$status_ditolak = 1; // 0= tidak ditolak, 1= ditolak
					echo '<img src="./asset/img_source/ditolak.gif" height="10"/><br>';
					echo '<a href=# title="'.$pro[$i][$j]['ket_persetujuan'].'" ><font color=blue size=-3>'.$pro[$i][$j]['ket_status_acc_mr'].'</font></a>';
				}
				else
				{
					echo '<font color="red">'.$this->lang->line('tunggu').'</font>';
				}
			
			?>
      </td>
      <td align="center"> 
        <? // cek status cetak form keluar barang
				if ($status_ditolak == 0 )  // if status tidak ditolak
				{	
					if ($pro[$i][$j]['cetak_form'] == 1)
					{
						echo '<img src="./asset/img_source/centang.gif" />';
					}
					else 
					{
						echo '<font color="red">'.$this->lang->line('tunggu').'</font>';
					}			
				} else 	if ($status_ditolak == 1 )  // if status ditolak		
				{
					echo '<img src="./asset/img_source/ditolak.gif" height="10"/><br>';
				} else {
					echo '<font color=blue size=-3>'.$this->lang->line('status_tidak_diketahui').'</font>';
				} // akhir status ditolak
			?>
      </td>
      <td align="center"> 
        <?  	// cek status keluarkan barang
				if ($status_ditolak == 0 )  // if status tidak ditolak
				{	
					if ($pro[$i][$j]['status_keluar_barang'] == 1 || $pro[$i][$j]['realisasi_barang'] !=0 )
					{
						echo '<img src="./asset/img_source/centang.gif" />';
					}
					else if ($pro[$i][$j]['status_keluar_barang'] == 1 && $pro[$i][$j]['status_keluar_barang'] != '')
					{
						echo '<img src="./asset/img_source/centang.gif" /><br>';
						echo '<a href=# title="'.$this->lang->line('tooltip_laporan_alasan').'" ><font color=blue size=-3>'.$this->lang->line('alasan').'</font></a>';					
					}
					else 
					{
						echo '<font color="red">'.$this->lang->line('tunggu').'</font>';
					}			
				} else 	if ($status_ditolak == 1 )  // if status ditolak		
				{
					echo '<img src="./asset/img_source/ditolak.gif" height="10"/><br>';
				} else {
					echo '<font color=blue size=-3>'.$this->lang->line('status_tidak_diketahui').'</font>';
				} // akhir status ditolak
			?>
      </td>
      <td align="center"> 
        <? // input pemakaian barang
				if ($status_ditolak == 0 )  // if status tidak ditolak
				{	
					if ( $pro[$i][$j]['qty_use'] > 0)
					{
						$sisa_pemakaian = $pro[$i][$j]['realisasi_barang'] - $pro[$i][$j]['qty_use'];
						echo '<img src="./asset/img_source/centang.gif" />';
						echo '<br><a href=# ><font color=blue size=-3>'.$this->lang->line('pemakaian').' '.$this->general->digit_number($pro[$i][$j]['satuan_id'],$pro[$i][$j]['qty_use']).' '.$pro[$i][$j]['nama_satuan'].'</font></a>';
						echo '<br><a href=# ><font color=blue size=-3>'.$this->lang->line('sisa').' '.$this->general->digit_number($pro[$i][$j]['satuan_id'],$sisa_pemakaian).' '.$pro[$i][$j]['nama_satuan'].'</font></a>';	
					}
					else if ( $pro[$i][$j]['qty_use'] == 0 && $pro[$i][$j]['is_closed'] == 1 )
					{
						echo '<font color=blue size=-3>'.$this->lang->line('tidak_dipakai').'</font>';
					}
					else 			
					{
						echo '<font color="red">'.$this->lang->line('tunggu').'</font>';
					}			
				} else 	if ($status_ditolak == 1 )  // if status ditolak		
				{
					echo '<img src="./asset/img_source/ditolak.gif" height="10"/><br>';
				} else {
					echo '<font color=blue size=-3>'.$this->lang->line('status_tidak_diketahui').'</font>';
				} // akhir status ditolak
			?>
      </td>
      <td align="center"> 
        <? // input pemakaian barang
				if ($status_ditolak == 0 )  // if status tidak ditolak
				{
					$sisa_pemakaian = $pro[$i][$j]['realisasi_barang'] - $pro[$i][$j]['qty_use'];	
					if ($sisa_pemakaian == 0 && $pro[$i][$j]['qty_use'] > 0)
					{
						echo '<font color=blue size=-3>'.$this->lang->line('barang_terpakai_semua').'</font>';
					}else {

					if ( $pro[$i][$j]['is_closed'] == 1)
					{
						echo '<img src="./asset/img_source/centang.gif" />';
						echo '<br><a href=# ><font color=blue size=-3>'.$this->lang->line('dikembalikan').' '.$this->general->digit_number($pro[$i][$j]['satuan_id'],$sisa_pemakaian).' '.$pro[$i][$j]['nama_satuan'].'</font></a>';	
					}
					else 
					{
						echo '<font color="red">'.$this->lang->line('tunggu').'</font>';
					}
					
					}
				} else 	if ($status_ditolak == 1 )  // if status ditolak		
				{
					echo '<img src="./asset/img_source/ditolak.gif" height="10"/><br>';
				} else {
					echo '<font color=blue size=-3>'.$this->lang->line('status_tidak_diketahui').'</font>';
				} // akhir status ditolak
			?>
      </td>
    </tr>
    <?
		$no++;
		endfor; // endfor (data produk)
		else: // elsetiga ( jumlah data produk )
		?>
    <tr > 
      <td colspan="8" align="center" bgcolor="#FFFFFF"> <font color="#FF0000"> 
        <strong> 
        <?=$this->lang->line('lap_tabel_tidak_ada_data');?>
        </strong> </font> </td>
    </tr>
    <?
		 endif; // endif tiga (jumlah data produk )	
		endfor; // endfor ( looping mr )
	?>
    <!-- ===================== akhir untuk looping datanya =================== -->
    <? } else { ?>
    <tr > 
      <td colspan="8" align="center" bgcolor="#FFFFFF"> <font color="#FF0000"> 
        <strong> 
        <?=$this->lang->line('lap_tabel_tidak_ada_data');?>
        </strong> </font> </td>
    </tr>
    <? }			
		else : //else cari_status ?>
    <tr > 
      <td colspan="8" align="center" bgcolor="#FFFFFF"> <font color="#FF0000"> 
        <strong> 
        <?=$this->lang->line('lap_tabel_pilih_kriteria');?>
        </strong> </font> </td>
    </tr>
    <?php endif; // endif cari_status?>
  </table>
</center>

