<?PHP
/*
	Tanggal : 17 Maret 2010
	Perbaikan
	1. perbaikan status yang ditunda	
*/

?>

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
	$('#cari_no_pr').val('');

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
				<input type=hidden name="cari_no_pr" value="<?=$cari_no_pr?>">				
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
				<td width="99"><?=$this->lang->line('no').' '.$this->lang->line('lap_pr');?></td>
				<td width="168">: <input type="text" name="cari_no_pr" id="cari_no_pr" value="<?=$cari_no_pr?>" /></td>
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
<? if ($cari_tahun != '0' || $cari_bulan != 0 || $cari_no_pr != ''): ?>
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
  
  
  <? if ($cari_no_pr!= ''):?>
  <tr>
    <td><?=$this->lang->line('no').' '.$this->lang->line('lap_pr');?></td>
    <td>:</td>
    <td><font color="red" ><?=$cari_no_pr?>    </font></td>
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
<? if ($cari_status !=''):?>

		<? if ($data_penelusuran->num_rows() > 0){ 			
		//	$no=1;
			foreach($data_penelusuran->result() as $row):
		?>
		<tr>
			<td colspan="16">&nbsp;</td>		
		</tr>
		<tr  class="ui-state-default">
		  <td colspan="16" align="left">
			 <strong>
		  		<?=$this->lang->line('no')?> <?=$this->lang->line('lap_pr')?> :		  
		  		<?=$row->pr_no?>
			 </strong>		   </td>
	    </tr>
		
		<tr  class="ui-state-default">
		  <td width="4%" align="center"><?=$this->lang->line('no');?></td>
		  <td width="8%" align="center"><?=$this->lang->line('produk');?></td>
		  <td width="8%" align="center"><?=$this->lang->line('lap_acc_pr');?></td>
		  <td width="8%" align="center"><?=$this->lang->line('lap_pr_ke_rfq');?></td>
		  <td width="8%" align="center"><?=$this->lang->line('lap_cetak_rfq');?></td>
		  <td width="8%" align="center"><?=$this->lang->line('lap_rfq_final');?></td>
		  <td width="8%" align="center"><?=$this->lang->line('lap_acc_rfq');?></td>
		  <td width="8%" align="center"><?=$this->lang->line('lap_cetak_po');?></td>
		  <td width="8%" align="center"><?=$this->lang->line('lap_bpb_gudang');?></td>
		  <td width="8%" align="center"><?=$this->lang->line('lap_cetak_bpb');?></td>
		  <td width="8%" align="center"><?=$this->lang->line('cek_bpb_kurs');?></td>
		  <td width="8%" align="center"><?=$this->lang->line('cek_bpb_akhir');?></td>		 
		  <td width="8%" align="center"><?=$this->lang->line('lap_adjusment_po');?></td>		 
		  <td width="8%" align="center"><?=$this->lang->line('lap_buat_kb');?></td>
		  <td width="8%" align="center"><?=$this->lang->line('lap_cetak_kb');?></td>
		  <td width="8%" align="center"><?=$this->lang->line('pembayaran');?></td>
	    </tr>
		
		<!-- ===================== untuk looping datanya =================== -->
		
		<?	
			$pr_id=$row->pr_id;
			$no=1;
			$sql_pro  = "select pr.pr_no, prd.pro_id, pro.pro_name, pr.pr_status, prd.rfq_id,
								prd.requestStat, rfq.rfq_status, rfq.rfq_printStat, rfq_stat,
								po.po_printStat, prd.po_id, prd.qty_terima, gr.gr_printStatus,
								gr.gr_status, gr.con_id, cb.con_printStat, cb.con_payVal,
								prd.qty, prd.auth_no, gr.kur_status, cb.con_value, cb.cur_id,
								prd.pr_appr_note as ket_persetujuan, prd.pty_id as keperluan_pr,
								prd.emergencyStat, mc.cur_symbol, mc.cur_digit, prd.buy_via,
								prd.description as ket_pr, cb.con_ppn_payVal, cb.con_ppn_value,
								(select distinct(grdh.document) from prc_gr_detail_history as grdh 
								 where grdh.document = 'ADJ' and grdh.gr_id = gr.gr_id ) as status_adj
						from prc_pr as pr 
						left join prc_pr_detail as prd on prd.pr_id = pr.pr_id
						left join prc_master_product as pro on pro.pro_id = prd.pro_id
						left join prc_rfq as rfq on rfq.rfq_id = prd.rfq_id
						left join prc_po as po on po.po_id = prd.po_id
						left join prc_gr as gr on gr.po_id = po.po_id					
						left join prc_contrabon as cb on cb.con_id = gr.con_id
						left join prc_master_currency as mc on mc.cur_id = prd.cur_id						
						where pr.pr_id = '$pr_id'
						order by pr.pr_no asc";						
			$data_produk=$this->db->query($sql_pro); // eksekusi query
			foreach($data_produk->result() as $row_pro):
			
			$status_keperluan 		= $this->general->status('keperluan',$row_pro->keperluan_pr); // untuk status keperluan
			$status_acc_pr 			= $this->general->status('acc_pr',$row_pro->requestStat); // untuk status acc_pr
			$status_acc_rfq 		= $this->general->status('acc_rfq',$row_pro->requestStat); // untuk status acc_pr
			$status_acc_rfq_final 	= $this->general->status('acc_rfq',$row_pro->rfq_stat); // untuk status acc_pr
			$status_emergency 		= $this->general->status('status_emergency',$row_pro->emergencyStat); // untuk status emergency
			
			$status_ditolak = 0; // 0= tidak ditolak, 1= ditolak
			$status_ditunda = 0; // 0= tidak ditolak, 1= ditolak
			
			// untuk warna produk yang emergency
			if ( $row_pro->emergencyStat == 1 ){
				$warna_tulisan= 'color="red"';
			}else {
				$warna_tulisan= '';
			}
			
			
		?>		
		<tr  bgcolor="lightgray" valign="middle"> 
		  <td align="center" class="ui-state-active"><?=$no?></td>
		  <td valign="top" align="left" >
			  <font <?=$warna_tulisan?> title="<?=$status_emergency?>">		
			 	 <?=$row_pro->pro_name?> <br>
			  </font>
			 
			  <font size="-3" color="blue" title="<?=$this->lang->line('keterangan').' : '.$row_pro->ket_pr?>">
				  (<?=$status_keperluan?>)
			  </font>
		  </td>
		  <td align="center">
		  	<?	// cek status ACC PR
				if ($row_pro->requestStat == 1)
				{
					echo '<img src="./asset/img_source/centang.gif" />';
				}
				else if ($row_pro->requestStat == 2)
				{
					echo '<img src="./asset/img_source/centang.gif" /><br>';
					echo '<a title="'.$row_pro->ket_persetujuan.'" ><font color=blue size=-3>'.$status_acc_pr.'</font></a>';
				}
				else if ($row_pro->requestStat == 3)
				{
					echo '<img src="./asset/img_source/centang.gif" /><br>';
					echo '<a title="'.$row_pro->ket_persetujuan.'" ><font color=blue size=-3>'.$status_acc_pr.'</font></a>';
				}
				else if ($row_pro->requestStat == 4)
				{
					$status_ditunda = 1; 
					echo '<img src="./asset/img_source/centang.gif" /><br>';
					echo '<a title="'.$row_pro->ket_persetujuan.'" ><font color=blue size=-3>'.$status_acc_pr.'</font></a>';
				}
				else if ($row_pro->requestStat == 5)
				{ 
					$status_ditolak = 1;
					echo '<img src="./asset/img_source/ditolak.gif" height="10"/><br>';
					echo '<a title="'.$row_pro->ket_persetujuan.'" ><font color=blue size=-3>'.$status_acc_pr.'</font></a>';
				}
				else
				{
					$status_blm_diputuskan = 0;
					echo '<font color="red">'.$this->lang->line('tunggu').'</font>';
				}			
			?>
		  </td>		  	
 		  <td align="center">
			<?  // cek status ubah PR ke RFQ
				if ($status_ditolak == 0 )  // if status tidak ditolak 
				{					
					if ($status_ditunda == 0)  // if status ditunda 
					{	
						if ($row_pro->buy_via == 'po') // if pembelian lewat po
						{
							if ($row_pro->rfq_id != 0)
							{
								echo '<img src="./asset/img_source/centang.gif" />';
							}
							else 
							{
								echo '<font color="red">'.$this->lang->line('tunggu').'</font>';
							}
						}else if ($row_pro->buy_via == 'pcv' && $status_acc_pr != $this->lang->line('status_tidak_diketahui') ) // if pembelian lewat pcv
						{
							echo '<font color=blue size=-3>'.$this->lang->line('pembelian').' '.$this->lang->line('lewat').' '.$this->lang->line('pcv').'</font>';					
						}else {
							echo '<font color="red">'.$this->lang->line('tunggu').'</font>';
						} // akhir status pembelian lewat po
					
					} else 	if ($status_ditunda == 1)  // if status ditunda		
					{
						echo '<img src="./asset/img_source/spinner.gif" height="10"/><br>';					
						echo '<font color=blue size=-3>'.$this->lang->line('dalam_proses').'</font>';
					} else {
						echo '<font color=blue size=-3>'.$this->lang->line('status_tidak_diketahui').'</font>';
					} // akhir status ditunda
				} else 	if ($status_ditolak == 1 )  // if status ditolak		
				{
					echo '<img src="./asset/img_source/ditolak.gif" height="10"/>';
				} else {
					echo '<font color=blue size=-3>'.$this->lang->line('status_tidak_diketahui').'</font>';
				} // akhir status ditolak
				
			?>		  
		  </td>
		  <td align="center">
		  	<? // cek status cetak RFQ
				if ($status_ditolak == 0 )  // if status tidak ditolak
				{
					if ($status_ditunda == 0)  // if status ditunda 
					{	
						if ($row_pro->buy_via == 'po') // if pembelian lewat po
						{
							if ($row_pro->rfq_id != 0 && $row_pro->rfq_printStat==1)
							{
								echo '<img src="./asset/img_source/centang.gif" />';
							}
							else 
							{
								echo '<font color="red">'.$this->lang->line('tunggu').'</font>';
							}								
						}else if ($row_pro->buy_via == 'pcv' && $status_acc_pr != $this->lang->line('status_tidak_diketahui') ) // if pembelian lewat pcv
						{
							echo '<font color=blue size=-3>'.$this->lang->line('pembelian').' '.$this->lang->line('lewat').' '.$this->lang->line('pcv').'</font>';					
						}else {
							echo '<font color="red">'.$this->lang->line('tunggu').'</font>';
						} // akhir status pembelian lewat po
					} else 	if ($status_ditunda == 1)  // if status ditunda		
					{
						echo '<img src="./asset/img_source/spinner.gif" height="10"/><br>';					
						echo '<font color=blue size=-3>'.$this->lang->line('dalam_proses').'</font>';
					} else {
						echo '<font color=blue size=-3>'.$this->lang->line('status_tidak_diketahui').'</font>';
					} // akhir status ditunda
				} else 	if ($status_ditolak == 1 )  // if status ditolak		
				{
					echo '<img src="./asset/img_source/ditolak.gif" height="10"/>';
				} else {
					echo '<font color=blue size=-3>'.$this->lang->line('status_tidak_diketahui').'</font>';
				} // akhir status ditolak						
			?>
		  </td>
 		  
      <td align="center"> 
        <?  	// cek status input RFQ Final 							
				if ($status_ditolak == 0 )  // if status tidak ditolak
				{
					if ($status_ditunda == 0)  // if status ditunda 
					{	
						if ($row_pro->buy_via == 'po') // if pembelian lewat po
						{
							if ($row_pro->rfq_id != 0 && $row_pro->rfq_stat==1 || $row_pro->rfq_stat==5)
							{
								echo '<img src="./asset/img_source/centang.gif" />';
							}
							else if ($row_pro->rfq_id != 0 && $row_pro->rfq_stat==2)
							{	
								$status_ditunda=1;							
								echo '<img src="./asset/img_source/centang.gif" /><br>';
								echo '<a title="'.$this->lang->line('tooltip_laporan_alasan').'" ><font color=blue size=-3>'.$status_acc_rfq_final.'</font></a>';					
							}
							else if ($row_pro->rfq_id != 0 && $row_pro->rfq_stat==3)
							{
								$status_ditolak=1;
								echo '<img src="./asset/img_source/ditolak.gif" height="10" /><br>';
								echo '<a title="'.$this->lang->line('tooltip_laporan_alasan').'" ><font color=blue size=-3>'.$status_acc_rfq_final.'</font></a>';
							}
							else 
							{
								echo '<font color="red">'.$this->lang->line('tunggu').'</font>';
							}			
						}else if ($row_pro->buy_via == 'pcv' && $status_acc_pr != $this->lang->line('status_tidak_diketahui') ) // if pembelian lewat pcv
						{
							echo '<font color=blue size=-3>'.$this->lang->line('pembelian').' '.$this->lang->line('lewat').' '.$this->lang->line('pcv').'</font>';					
						}else {
							echo '<font color="red">'.$this->lang->line('tunggu').'</font>';
						} // akhir status pembelian lewat po
					} else 	if ($status_ditunda == 1)  // if status ditunda		
					{
						echo '<img src="./asset/img_source/spinner.gif" height="10"/><br>';					
						echo '<font color=blue size=-3>'.$this->lang->line('dalam_proses').'</font>';
					} else {
						echo '<font color=blue size=-3>'.$this->lang->line('status_tidak_diketahui').'</font>';
					} // akhir status ditunda
				} else 	if ($status_ditolak == 1 )  // if status ditolak		
				{
					echo '<img src="./asset/img_source/ditolak.gif" height="10"/>';
				} else {
					echo '<font color=blue size=-3>'.$this->lang->line('status_tidak_diketahui').'</font>';
				} // akhir if ditolak		
			?>
	  </td>
      <td align="center">
	  		<?  	// ACC RFQ Final
				if ($status_ditolak == 0 )  // if status tidak ditolak
				{
					if ($status_ditunda == 0)  // if status ditunda 
					{	
						if ($row_pro->buy_via == 'po') // if pembelian lewat po
						{
							if ($row_pro->rfq_id != 0 && $row_pro->rfq_stat==5)
							{
								echo '<img src="./asset/img_source/centang.gif" />';
							}
							else 
							{
								echo '<font color="red">'.$this->lang->line('tunggu').'</font>';
							}			
						}else if ($row_pro->buy_via == 'pcv' && $status_acc_pr != $this->lang->line('status_tidak_diketahui') ) // if pembelian lewat pcv
						{
							echo '<font color=blue size=-3>'.$this->lang->line('pembelian').' '.$this->lang->line('lewat').' '.$this->lang->line('pcv').'</font>';					
						}else {
							echo '<font color="red">'.$this->lang->line('tunggu').'</font>';
						} // akhir status pembelian lewat po
					} else 	if ($status_ditunda == 1)  // if status ditunda		
					{
						echo '<img src="./asset/img_source/spinner.gif" height="10"/><br>';					
						echo '<font color=blue size=-3>'.$this->lang->line('dalam_proses').'</font>';
					} else {
						echo '<font color=blue size=-3>'.$this->lang->line('status_tidak_diketahui').'</font>';
					} // akhir status ditunda
				} else 	if ($status_ditolak == 1 )  // if status ditolak		
				{
					echo '<img src="./asset/img_source/ditolak.gif" height="10"/>';
				} else {
					echo '<font color=blue size=-3>'.$this->lang->line('status_tidak_diketahui').'</font>';
				} // akhir if ditolak
			?>
			</td>
 		
 		  
      <td align="center">
        <?  // cek status cetak PO
				if ($status_ditolak == 0 )  // if status tidak ditolak
				{
					if ($status_ditunda == 0)  // if status ditunda 
					{	
						if ($row_pro->buy_via == 'po') // if pembelian lewat po
						{
							if ($row_pro->po_id != 0 && $row_pro->po_printStat==1)
							{
								echo '<img src="./asset/img_source/centang.gif" />';
							}
							else 
							{
								echo '<font color="red">'.$this->lang->line('tunggu').'</font>';
							}			
						}else if ($row_pro->buy_via == 'pcv' && $status_acc_pr != $this->lang->line('status_tidak_diketahui') ) // if pembelian lewat pcv
						{
							echo '<font color=blue size=-3>'.$this->lang->line('pembelian').' '.$this->lang->line('lewat').' '.$this->lang->line('pcv').'</font>';					
						}else {
							echo '<font color="red">'.$this->lang->line('tunggu').'</font>';
						} // akhir status pembelian lewat po
					} else 	if ($status_ditunda == 1)  // if status ditunda		
					{
						echo '<img src="./asset/img_source/spinner.gif" height="10"/><br>';					
						echo '<font color=blue size=-3>'.$this->lang->line('dalam_proses').'</font>';
					} else {
						echo '<font color=blue size=-3>'.$this->lang->line('status_tidak_diketahui').'</font>';
					} // akhir status ditunda
				} else 	if ($status_ditolak == 1 )  // if status ditolak		
				{
					echo '<img src="./asset/img_source/ditolak.gif" height="10"/>';
				} else {
					echo '<font color=blue size=-3>'.$this->lang->line('status_tidak_diketahui').'</font>';
				} // akhir if ditolak				
			?>
      </td>
		  <td align="center">
		  	<?  // cek status input BPB oleh gudang
				if ($status_ditolak == 0 )  // if status tidak ditolak
				{
					if ($status_ditunda == 0)  // if status ditunda 
					{	
						if ($row_pro->buy_via == 'po') // if pembelian lewat po
						{
							if ($row_pro->po_id != 0 && $row_pro->qty_terima == $row_pro->qty)
							{
								echo '<img src="./asset/img_source/centang.gif" />';
							}
							else if ($row_pro->po_id != 0 && $row_pro->qty_terima < $row_pro->qty && $row_pro->qty_terima > 0 )
							{
								$selisih_jumlah_barang =  $row_pro->qty - $row_pro->qty_terima;
								echo '<img src="./asset/img_source/centang.gif" /><br>';
								echo '<a title="'.$this->lang->line('tooltip_laporan_kurang').' '.$selisih_jumlah_barang.'" ><font color=blue size=-3>'.$this->lang->line('kurang').'<br>[- '.$selisih_jumlah_barang.' ]</font></a>';
							}
							else if ($row_pro->po_id != 0 && $row_pro->qty_terima > $row_pro->qty && $row_pro->qty_terima > 0 )
							{
								$selisih_jumlah_barang =  $row_pro->qty_terima - $row_pro->qty;
								echo '<img src="./asset/img_source/centang.gif" /><br>';
								echo '<a title="'.$this->lang->line('tooltip_laporan_lebih').' '.$selisih_jumlah_barang.'" >';
								echo '<font color=blue size=-3>'.$this->lang->line('lebih');
								echo '<br>[+ '.$selisih_jumlah_barang.' ]<br>';
								echo $this->lang->line('no_auth').'<br>'.$row_pro->auth_no.'</font></a>';
							}
							else
							{
								echo '<font color="red">'.$this->lang->line('tunggu').'</font>';
							}			
						}else if ($row_pro->buy_via == 'pcv' && $status_acc_pr != $this->lang->line('status_tidak_diketahui') ) // if pembelian lewat pcv
						{
							echo '<font color=blue size=-3>'.$this->lang->line('pembelian').' '.$this->lang->line('lewat').' '.$this->lang->line('pcv').'</font>';					
						}else {
							echo '<font color="red">'.$this->lang->line('tunggu').'</font>';
						} // akhir status pembelian lewat po
					} else 	if ($status_ditunda == 1)  // if status ditunda		
					{
						echo '<img src="./asset/img_source/spinner.gif" height="10"/><br>';					
						echo '<font color=blue size=-3>'.$this->lang->line('dalam_proses').'</font>';
					} else {
						echo '<font color=blue size=-3>'.$this->lang->line('status_tidak_diketahui').'</font>';
					} // akhir status ditunda
				} else 	if ($status_ditolak == 1 )  // if status ditolak		
				{
					echo '<img src="./asset/img_source/ditolak.gif" height="10"/>';
				} else {
					echo '<font color=blue size=-3>'.$this->lang->line('status_tidak_diketahui').'</font>';
				} // akhir if ditolak								
			?>
		  </td>
 		  <td align="center">
 		  	<?  // cek status cetak	 BPB
				if ($status_ditolak == 0 )  // if status tidak ditolak
				{
					if ($status_ditunda == 0)  // if status ditunda 
					{	
						if ($row_pro->buy_via == 'po') // if pembelian lewat po
						{
							if ($row_pro->gr_printStatus == 1)
							{
								echo '<img src="./asset/img_source/centang.gif" />';
							}
							else 
							{
								echo '<font color="red">'.$this->lang->line('tunggu').'</font>';
							}			
						}else if ($row_pro->buy_via == 'pcv' && $status_acc_pr != $this->lang->line('status_tidak_diketahui') ) // if pembelian lewat pcv
						{
							echo '<font color=blue size=-3>'.$this->lang->line('pembelian').' '.$this->lang->line('lewat').' '.$this->lang->line('pcv').'</font>';					
						}else {
							echo '<font color="red">'.$this->lang->line('tunggu').'</font>';
						} // akhir status pembelian lewat po
					} else 	if ($status_ditunda == 1)  // if status ditunda		
					{
						echo '<img src="./asset/img_source/spinner.gif" height="10"/><br>';					
						echo '<font color=blue size=-3>'.$this->lang->line('dalam_proses').'</font>';
					} else {
						echo '<font color=blue size=-3>'.$this->lang->line('status_tidak_diketahui').'</font>';
					} // akhir status ditunda
				} else 	if ($status_ditolak == 1 )  // if status ditolak		
				{
					echo '<img src="./asset/img_source/ditolak.gif" height="10"/>';
				} else {
					echo '<font color=blue size=-3>'.$this->lang->line('status_tidak_diketahui').'</font>';
				} // akhir if ditolak
			?>
		  </td>
		  <td align="center">
		  	<?  // cek status cek BPB kurs
				if ($status_ditolak == 0 )  // if status tidak ditolak
				{
					if ($status_ditunda == 0)  // if status ditunda 
					{	
						if ($row_pro->buy_via == 'po') // if pembelian lewat po
						{
							if ($row_pro->kur_status== 1)
							{
								echo '<img src="./asset/img_source/centang.gif" /><br>';
								echo '<font color=blue size=-3>('.$row_pro->cur_symbol.')</font>';					
							}
							else 
							{
								echo '<font color="red">'.$this->lang->line('tunggu').'</font>';
							}			
						}else if ($row_pro->buy_via == 'pcv' && $status_acc_pr != $this->lang->line('status_tidak_diketahui') ) // if pembelian lewat pcv
						{
							echo '<font color=blue size=-3>'.$this->lang->line('pembelian').' '.$this->lang->line('lewat').' '.$this->lang->line('pcv').'</font>';					
						}else {
							echo '<font color="red">'.$this->lang->line('tunggu').'</font>';
						} // akhir status pembelian lewat po
					} else 	if ($status_ditunda == 1)  // if status ditunda		
					{
						echo '<img src="./asset/img_source/spinner.gif" height="10"/><br>';					
						echo '<font color=blue size=-3>'.$this->lang->line('dalam_proses').'</font>';
					} else {
						echo '<font color=blue size=-3>'.$this->lang->line('status_tidak_diketahui').'</font>';
					} // akhir status ditunda
				} else 	if ($status_ditolak == 1 )  // if status ditolak		
				{
					echo '<img src="./asset/img_source/ditolak.gif" height="10"/>';
				} else {
					echo '<font color=blue size=-3>'.$this->lang->line('status_tidak_diketahui').'</font>';
				} // akhir if ditolak
			?>
		  </td>
		  <td align="center">
   		  	<?  // cek status cek BPB akhir
				if ($status_ditolak == 0 )  // if status tidak ditolak
				{
					if ($status_ditunda == 0)  // if status ditunda 
					{	
						if ($row_pro->buy_via == 'po') // if pembelian lewat po
						{
							if ($row_pro->gr_status== 1)
							{
								echo '<img src="./asset/img_source/centang.gif" />';
							}
							else if ($row_pro->gr_status== 2 || $row_pro->gr_status== 3)
							{
								echo '<img src="./asset/img_source/centang.gif" /><br>';
								echo '<a title="'.$this->lang->line('tooltip_laporan_diubah_harga_satuan').'" ><font color=blue size=-3>'.$this->lang->line('diubah').'</font></a>';
							}
							else 
							{
								echo '<font color="red">'.$this->lang->line('tunggu').'</font>';
							}			
						}else if ($row_pro->buy_via == 'pcv' && $status_acc_pr != $this->lang->line('status_tidak_diketahui') ) // if pembelian lewat pcv
						{
							echo '<font color=blue size=-3>'.$this->lang->line('pembelian').' '.$this->lang->line('lewat').' '.$this->lang->line('pcv').'</font>';					
						}else {
							echo '<font color="red">'.$this->lang->line('tunggu').'</font>';
						} // akhir status pembelian lewat po
					} else 	if ($status_ditunda == 1)  // if status ditunda		
					{
						echo '<img src="./asset/img_source/spinner.gif" height="10"/><br>';					
						echo '<font color=blue size=-3>'.$this->lang->line('dalam_proses').'</font>';
					} else {
						echo '<font color=blue size=-3>'.$this->lang->line('status_tidak_diketahui').'</font>';
					} // akhir status ditunda
				} else 	if ($status_ditolak == 1 )  // if status ditolak		
				{
					echo '<img src="./asset/img_source/ditolak.gif" height="10"/>';
				} else {
					echo '<font color=blue size=-3>'.$this->lang->line('status_tidak_diketahui').'</font>';
				} // akhir if ditolak
				
			?>
		 </td>
		 <td align="center">
		 	<? // adjusment PO
				if ($status_ditolak == 0 )  // if status tidak ditolak
				{
					if ($status_ditunda == 0)  // if status ditunda 
					{	
						if ($row_pro->buy_via == 'po') // if pembelian lewat po
						{
							if ($row_pro->gr_status == 3) 
							{
								echo '<img src="./asset/img_source/centang.gif" />';
							}else if ($row_pro->status_adj == '')
							{
								echo '<font color=blue size=-3>'.$this->lang->line('tidak_ada_penyesuaian').'</font>';					
							} else if ($row_pro->status_adj == 'ADJ' ) {
								echo '<font color="red">'.$this->lang->line('tunggu').'</font>';							
							} else {
								echo '<font color=blue size=-3>'.$this->lang->line('status_tidak_diketahui').'</font>';	
							}						  						
							
						}else if ($row_pro->buy_via == 'pcv' && $status_acc_pr != $this->lang->line('status_tidak_diketahui') ) // if pembelian lewat pcv
						{
							echo '<font color=blue size=-3>'.$this->lang->line('pembelian').' '.$this->lang->line('lewat').' '.$this->lang->line('pcv').'</font>';					
						}else {
							echo '<font color="red">'.$this->lang->line('tunggu').'</font>';
						} // akhir status pembelian lewat po
					} else 	if ($status_ditunda == 1)  // if status ditunda		
					{
						echo '<img src="./asset/img_source/spinner.gif" height="10"/><br>';					
						echo '<font color=blue size=-3>'.$this->lang->line('dalam_proses').'</font>';
					} else {
						echo '<font color=blue size=-3>'.$this->lang->line('status_tidak_diketahui').'</font>';
					} // akhir status ditunda
				} else 	if ($status_ditolak == 1 )  // if status ditolak		
				{
					echo '<img src="./asset/img_source/ditolak.gif" height="10"/>';
				} else {
					echo '<font color=blue size=-3>'.$this->lang->line('status_tidak_diketahui').'</font>';
				} // akhir if ditolak	
		  	?>
		  </td>
 		  <td align="center">
		     <?   // cek status buat kontrabon
			 	if ($status_ditolak == 0 )  // if status tidak ditolak
				{
					if ($status_ditunda == 0)  // if status ditunda 
					{	
						if ($row_pro->buy_via == 'po') // if pembelian lewat po
						{
							if ($row_pro->con_id != 0)
							{
								echo '<img src="./asset/img_source/centang.gif" />';
							}
							else 
							{
								echo '<font color="red">'.$this->lang->line('tunggu').'</font>';
							}			
						}else if ($row_pro->buy_via == 'pcv' && $status_acc_pr != $this->lang->line('status_tidak_diketahui') ) // if pembelian lewat pcv
						{
							echo '<font color=blue size=-3>'.$this->lang->line('pembelian').' '.$this->lang->line('lewat').' '.$this->lang->line('pcv').'</font>';					
						}else {
							echo '<font color="red">'.$this->lang->line('tunggu').'</font>';
						} // akhir status pembelian lewat po
					} else 	if ($status_ditunda == 1)  // if status ditunda		
					{
						echo '<img src="./asset/img_source/spinner.gif" height="10"/><br>';					
						echo '<font color=blue size=-3>'.$this->lang->line('dalam_proses').'</font>';
					} else {
						echo '<font color=blue size=-3>'.$this->lang->line('status_tidak_diketahui').'</font>';
					} // akhir status ditunda
				} else 	if ($status_ditolak == 1 )  // if status ditolak		
				{
					echo '<img src="./asset/img_source/ditolak.gif" height="10"/>';
				} else {
					echo '<font color=blue size=-3>'.$this->lang->line('status_tidak_diketahui').'</font>';
				} // akhir if ditolak				
			?>
		  </td>
		  <td align="center">
			<?  // cek status cetak kontrabon
				if ($status_ditolak == 0 )  // if status tidak ditolak
				{
					if ($status_ditunda == 0)  // if status ditunda 
					{	
						if ($row_pro->buy_via == 'po') // if pembelian lewat po
						{
							if ($row_pro->con_printStat == 1)
							{
								echo '<img src="./asset/img_source/centang.gif" />';
							}
							else 
							{
								echo '<font color="red">'.$this->lang->line('tunggu').'</font>';
							}			
						}else if ($row_pro->buy_via == 'pcv' && $status_acc_pr != $this->lang->line('status_tidak_diketahui') ) // if pembelian lewat pcv
						{
							echo '<font color=blue size=-3>'.$this->lang->line('pembelian').' '.$this->lang->line('lewat').' '.$this->lang->line('pcv').'</font>';					
						}else {
							echo '<font color="red">'.$this->lang->line('tunggu').'</font>';
						} // akhir status pembelian lewat po
					} else 	if ($status_ditunda == 1)  // if status ditunda		
					{
						echo '<img src="./asset/img_source/spinner.gif" height="10"/><br>';					
						echo '<font color=blue size=-3>'.$this->lang->line('dalam_proses').'</font>';
					} else {
						echo '<font color=blue size=-3>'.$this->lang->line('status_tidak_diketahui').'</font>';
					} // akhir status ditunda
				} else 	if ($status_ditolak == 1 )  // if status ditolak		
				{
					echo '<img src="./asset/img_source/ditolak.gif" height="10"/>';
				} else {
					echo '<font color=blue size=-3>'.$this->lang->line('status_tidak_diketahui').'</font>';
				} // akhir if ditolak
			?>
		  </td>
 		  <td align="center">
  			<?  // cek status pembayaran
				if ($status_ditolak == 0 )  // if status tidak ditolak
				{
					if ($status_ditunda == 0)  // if status ditunda 
					{	
						if ($row_pro->buy_via == 'po') // if pembelian lewat po
						{
							$selisih_pembayaran 	= $row_pro->con_value - $row_pro->con_payVal; // cek selisih pembayaran
							$selisih_pembayaran_ppn = $row_pro->con_ppn_value - $row_pro->con_ppn_payVal; //cek selish pembayaran ppn
							if ($row_pro->con_payVal != 0 && $selisih_pembayaran == 0)
							{
								echo '<img src="./asset/img_source/centang.gif" /><br>';
								echo '<font color=blue size=-3>'.$this->lang->line('lunas').'</font>';	
							}
							else if ($row_pro->con_payVal != 0 && $selisih_pembayaran > 0)
							{
								echo '<img src="./asset/img_source/centang.gif" /><br>';
								if ( $ppn_status == 'ppn_') {
									echo '<a title="'.$this->lang->line('tooltip_laporan_sisa_tunggakan').' = '.$this->lang->line('utama').' : '.$row_pro->cur_symbol.'. '.number_format($selisih_pembayaran,$row_pro->cur_digit).' ; '.$this->lang->line('ppn').' : '.$row_pro->cur_symbol.'. '.number_format($selisih_pembayaran_ppn,$row_pro->cur_digit).'" ><font color=blue size=-3>'.$this->lang->line('belum_lunas').'</font></a>';
								}else {
									echo '<a title="'.$this->lang->line('tooltip_laporan_sisa_tunggakan').' = '.$row_pro->cur_symbol.'. '.number_format($selisih_pembayaran,$row_pro->cur_digit).'" ><font color=blue size=-3>'.$this->lang->line('belum_lunas').'</font></a>';
								}
							}
							else 
							{
								echo '<font color="red">'.$this->lang->line('tunggu').'</font>';
							}			
						}else if ($row_pro->buy_via == 'pcv' && $status_acc_pr != $this->lang->line('status_tidak_diketahui') ) // if pembelian lewat pcv
						{
							echo '<font color=blue size=-3>'.$this->lang->line('pembelian').' '.$this->lang->line('lewat').' '.$this->lang->line('pcv').'</font>';					
						}else {
							echo '<font color="red">'.$this->lang->line('tunggu').'</font>';
						} // akhir status pembelian lewat po
					} else 	if ($status_ditunda == 1)  // if status ditunda		
					{
						echo '<img src="./asset/img_source/spinner.gif" height="10"/><br>';					
						echo '<font color=blue size=-3>'.$this->lang->line('dalam_proses').'</font>';
					} else {
						echo '<font color=blue size=-3>'.$this->lang->line('status_tidak_diketahui').'</font>';
					} // akhir status ditunda
				} else 	if ($status_ditolak == 1 )  // if status ditolak		
				{
					echo '<img src="./asset/img_source/ditolak.gif" height="10"/>';
				} else {
					echo '<font color=blue size=-3>'.$this->lang->line('status_tidak_diketahui').'</font>';
				} // akhir if ditolak				
			?>
		</td>		
	    </tr>		
		<?
			$no++;
			endforeach; // data produk
			endforeach; // data pr
		?>
		<!-- ===================== akhir untuk looping datanya =================== -->		
		
	<? } else { ?>
		<tr >
			<td colspan="16" align="center" bgcolor="#FFFFFF">
				<font color="#FF0000">
					<strong>
						<?=$this->lang->line('lap_tabel_tidak_ada_data');?>
					</strong>				</font>			</td>
		</tr>
		<? }			
		else : //else cari_status ?>
			<tr >
				<td colspan="16" align="center" bgcolor="#FFFFFF">
					<font color="#FF0000">
						<strong>
							<?=$this->lang->line('lap_tabel_pilih_kriteria');?>
						</strong>					</font>				</td>
			</tr>
		<?php endif; // endif cari_status?>
    </table>
</center>

