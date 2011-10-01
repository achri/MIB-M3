


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
	$('#cari_pemasok').val('');
	$('#cari_no_bkbk').val('');	
}
</script>

<h3><?=$title_page?></h3>
<div class="noprint">

<? if ($cari_status !=''):?>	
<div align="right">
	 <form  method="post" action="index.php/<?=$link_controller?>/excel" >
	
	<!-- ====================== bwt seleksi,, klo kosong datanya eksport g aktid ========= -->
		<? if ($jumlah_data != 0){ ?>
				<input type="submit" value="<?=$this->lang->line('lap_salin_ke_excel');?>">	
				<input type="button" id="print" value="<?=$this->lang->line('lap_print');?>" onclick="window.print();">

		<? }else { ?>
				<input type="submit" value="<?=$this->lang->line('lap_salin_ke_excel');?>" disabled="disabled">	
				<input type="button" id="print"  disabled="disabled" value="<?=$this->lang->line('lap_print');?>" onclick="window.print();">

		
		<? } ?>
		
		
	<!-- =============================================================================== -->

				<!-- lier cara ngirimnya,, pkae yg ini dulu aj ahhhhh -->			
				<input type=hidden name="cari_tahun" value="<?=$cari_tahun?>">
				<input type=hidden name="cari_bulan" value="<?=$cari_bulan?>">
				<input type=hidden name="cari_pemasok" value="<?=$cari_pemasok?>">
				<input type=hidden name="cari_no_bkbk" value="<?=$cari_no_bkbk?>">
				<input type=hidden name="cari_status" value="<?=$cari_status?>">
			&nbsp;
	</form>
</div> <!-- akhir div button cetak dan excel -->
 <? endif; ?><!-- endif cari status -->

   <div  style="width:99%" class="ui-widget-content ui-corner-all">
    <form  name="form_entry" method="post" action="index.php/<?=$link_controller?>/index" >


   <table width="901" >
   	<tr>
   	  <td width="300">	<b><?=$this->lang->line('lap_judul_cari');?></b></td>
   	  <td width="279">&nbsp;</td>
   	  <td width="306" align="center">	  </td>
 	  </tr>
   	<tr>
		<td>    
	        <table width="104%" cellspacing="2" cellpadding="2">
              <tr> 
                <td width="27%" height="50">
                  <?=$this->lang->line('tahun');?>                </td>
                <td width="73%">: 
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
              </tr>
              <tr>
                <td><?=$this->lang->line('lap_no_bkbk');?></td>
                <td>:
                <input type="text" name="cari_no_bkbk" id="cari_no_bkbk" style="width:150px" value="<?=$cari_no_bkbk?>"/></td>
              </tr>
            </table>	</td>
	
		<td><table width="104%" cellspacing="2" cellpadding="2">
            <tr> 
              <td width="10%" height="50">
                <?=$this->lang->line('bulan');?>              </td>
              <td width="85%">: 
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
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
          </table></td>
		<td >
		<table width="104%" cellspacing="2" cellpadding="2">
            <tr> 
              <td width="18%" height="50">
                <?=$this->lang->line('supplier');?>              </td>
              <td width="82%">:
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
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td align="right">
					<input type="button" name="bersihkan" value="<?=$this->lang->line('button_bersihkan');?>" onclick="bersihkanFilter()"/>
			  		<input name="cari" type="submit" value="<?=$this->lang->line('cari');?>" />
			   </td>
            </tr>
          </table>
		
		</td>
	</tr>
   	<tr>
   	  <td>&nbsp;</td>
   	  <td>&nbsp;</td>
   	  <td align="right">&nbsp;</td>
   	</tr>
   </table>
   
    </form>
	
  </div>
</div>


<div class="clr"></div>

<? if ($cari_bulan != '0' || $cari_tahun != '0' ||  $cari_pemasok != '0' || $cari_no_bkbk != ''): ?>
	<table width="340" border="0">
  <tr>
    <td colspan="3"><?=$this->lang->line('lap_berdasarkan')?></td>
    </tr>
  
  
   <? if ($cari_tahun != '0'): ?>
  <tr>
    <td width="113"><?=$this->lang->line('tahun')?></td>
    <td width="191">:<font color="red" >
      <?=$cari_tahun?>
    </font></td>
    <td width="22">&nbsp;</td>
  </tr>
  <? endif;?>
  
   <? if ($cari_bulan != '0'): ?>
  <tr>
    <td width="113"><?=$this->lang->line('bulan')?></td>
    <td width="191">:<font color="red" >
      <?=$data_bulan[$cari_bulan]?>
    </font></td>
    <td width="22">&nbsp;</td>
  </tr>
  <? endif;?>

   <? if ($cari_pemasok!= '0'): ?>
  <tr>
    <td width="113"><?=$this->lang->line('supplier')?></td>
    <td width="191">:<font color="red" >
      <?=$nama_pemasok?>
    </font></td>
    <td width="22">&nbsp;</td>
  </tr>
  <? endif;?>

     <? if ($cari_no_bkbk != ''): ?>
  <tr>
    <td width="113"><?=$this->lang->line('lap_no_bkbk')?></td>
    <td width="191">:<font color="red" >
      <?=$cari_no_bkbk?>
    </font></td>
    <td width="22">&nbsp;</td>
  </tr>
  <? endif;?>
</table>
<? endif;?>



<br />

<? if ($cari_status !=''):?>
	<div align="right"> <?=$this->lang->line('lap_ada');?><font color="red" > <?=$jumlah_data?>  </font> <?=$this->lang->line('lap_data');?></div>
 <? endif; ?><!-- endif cari status -->

<font size="-2">
<table width="99%"  border="0" cellpadding="0" cellspacing="1" class="ui-widget-content ui-corner-all">
<? if ($cari_status !=''):?>	
		<? if ($data_pembayaran->num_rows() > 0){ ?>

			<tr  class="ui-state-default">
			  <td width="4%" align="center" rowspan="2"><?=$this->lang->line('no');?></td>
				<td width="7%" align="center" rowspan="2"><?=$this->lang->line('lap_tgl_bkbk');?>			   </td>
				<td width="7%" align="center" rowspan="2"><?=$this->lang->line('lap_no_bkbk');?>			    </td>
				<td width="24%" align="center" rowspan="2"><?=$this->lang->line('supplier');?>			   </td>
				<td colspan="2" align="center"><?=$this->lang->line('total');?></td>
				<td colspan="2" align="center"><?=$this->lang->line('ppn');?></td>
				<td align="center" colspan="2"><?=$this->lang->line('jumlah');?>			   </td>
			</tr>
			<tr  class="ui-state-default">
			  <td width="11%" align="center"><?=$this->lang->line('rp')?></td>
			  <td width="10%" align="center"><?=$this->lang->line('us$')?></td>
			  <td width="10%" align="center"><?=$this->lang->line('rp')?></td>
				<td width="10%" align="center"><?=$this->lang->line('us$')?></td>
				<td width="9%" align="center"><?=$this->lang->line('rp')?></td>
				<td width="8%" align="center"><?=$this->lang->line('us$')?></td>
			</tr>
			
			
			
			
		<!-- ================== looping data  ========================= -->
		<? 
			if ( $data_pembayaran->result() > 0){
//			$no=1;// tanpa paging

			$no=$no_pos+1; // pake paging 
			$jumlah_ppn_rp		= 0;
			$jumlah_ppn_dol		= 0;
			$jumlah_total_rp	= 0;
			$jumlah_total_dol	= 0;
			
			foreach ( $data_pembayaran->result() as $row):
			$total_ppn_rp 	= 0;
			$total_ppn_dol 	= 0;			
			?>
			<tr bgcolor="lightgray" >
				<td  class="ui-state-active"align="center"><?=$no?></td>
				<td align="center"><?=$row->bkbk_date?></td>
				<td align="left"><?=$row->bkbk_no?></td>
				<td align="left"><?=$row->sup_name?>, <?=$row->legal_name?></td>
				<td align="right"><?=number_format($row->pay_rp,$this->general->digit_rp())?></td>
				<td align="right"><?=number_format($row->pay_dol,$this->general->digit_dolar())?></td>
				<td align="right">
				<? 
					$total_ppn_rp	= $row->pay_rp * (10/100);
					$jumlah_ppn_rp 	= $jumlah_ppn_rp + $total_ppn_rp;
					echo number_format($total_ppn_rp,$this->general->digit_rp());
				?>
				</td>
				<td align="right">
				<? 
					$total_ppn_dol	= $row->pay_dol * (10/100);
					$jumlah_ppn_dol = $jumlah_ppn_dol + $total_ppn_dol;
					echo number_format($total_ppn_dol,$this->general->digit_dolar());
				?>
				</td>
				<td align="right">
				<?
					$jumlah_rp			= $row->pay_rp + $total_ppn_rp;
					$jumlah_total_rp 	= $jumlah_total_rp + $jumlah_rp;
					echo number_format($jumlah_rp,$this->general->digit_rp());
				?>
				</td>
				<td align="right">
				<?
					$jumlah_dol			= $row->pay_dol + $total_ppn_dol;
					$jumlah_total_dol 	= $jumlah_total_dol + $jumlah_dol;
					echo number_format($jumlah_dol,$this->general->digit_dolar());
				?>
				</td>

 		 	</tr>

		<? 
			$no++;		
			endforeach; 
			
			} else {
			
			?>
			<tr bgcolor="lightgray" >
				<td colspan="10" align="center"><font color="#FF0000"><strong><?=$this->lang->line('lap_tabel_tidak_ada_data');?></strong></font>			   </td>
			</tr>

			
			<?
			} //end if
			?>

		
		<!-- ================== akhir looping data hutang ========================= -->

			<tr  class="ui-state-active">
				<td colspan="4" align="right">
					<strong>
					<?=$this->lang->line('total');?> :					</strong>				</td>
				<td align="right"><?=number_format($tot_rp,$this->general->digit_rp())?></td>
				<td align="right"><?=number_format($tot_dol,$this->general->digit_dolar())?></td>
				<td align="right"><?=number_format($jumlah_ppn_rp,$this->general->digit_rp())?></td>
				<td align="right"><?=number_format($jumlah_ppn_dol,$this->general->digit_dolar())?></td>
				<td align="right"><?=number_format($jumlah_total_rp,$this->general->digit_rp())?>			</td>
				<td align="right"><?=number_format($jumlah_total_dol,$this->general->digit_dolar())?></td>
			</tr>
		<!-- untuk halaman
		<tr><td colspan="6" class="ui-widget-header"> <?=$this->lang->line('halaman');?> : <?=($this->pagination->create_links())?($this->pagination->create_links()):('-')?></td></tr>
			-->
					<? } else { ?>
					<tr  >
				<td colspan="10" align="center"><font color="#FF0000"><strong><?=$this->lang->line('lap_tabel_tidak_ada_data');?></strong></font>			   </td>
			</tr>
			<? }  
			else: // else untuk cari status
			?>
			<tr >
				<td colspan="14" align="center" bgcolor="#FFFFFF"><font color="#FF0000"><strong><?=$this->lang->line('lap_tabel_pilih_kriteria');?></strong></font></td>
			</tr>
			<?php endif; // akhir cari status?>
</table>
</font>
