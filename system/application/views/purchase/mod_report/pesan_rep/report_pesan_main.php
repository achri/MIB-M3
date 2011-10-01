
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
	$('#cari_no_po').val('');
	$('#cari_pemesan').val('');	
	$('#cari_no_kb').val('');
	$('#cari_alasan').val('');		
	$('#cari_nama_barang').val('');	
	$('#cari_kode_barang').val('');
	$('#cari_no_sj').val('');		
	$('#cari_pemasok').val('');
	$('#caro_no_po').val('');	
}
</script>


<h3><?=$title_page?></h3>
<div class="noprint">

<? if ($cari_status !=''):?>	
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
				<input type=hidden name="cari_no_po" value="<?=$cari_no_po?>">
				<input type=hidden name="cari_pemesan" value="<?=$cari_pemesan?>">
				<input type=hidden name="cari_alasan" value="<?=$cari_alasan?>">				
				<input type=hidden name="cari_nama_barang" value="<?=$cari_nama_barang?>">
				<input type=hidden name="cari_kode_barang" value="<?=$cari_kode_barang?>">
				<input type=hidden name="cari_no_sj" value="<?=$cari_no_sj?>">
				
				<input type=hidden name="cari" value="<?=$cari_status?>">
				

		</form>	
	</td>
    <td>
</td>
  </tr>
</table>
<!-- ==================== akhir button bwt ekxport & cetak ======================== -->


</div>
<? endif; ?><!-- endif cari status -->


    <form action="index.php/<?=$link_controller?>/index" method="post"  name="form_entry" id="form_entry" >
      <div  style="width:99%" class="ui-widget-content ui-corner-all">
        <table width="901" >
          <tr>
            <td width="306"><b>
              <?=$this->lang->line('lap_judul_cari');?>
            </b></td>
            <td width="272">&nbsp;</td>
            <td width="307" align="center"></td>
          </tr>
          <tr>
            <td><table width="100%" cellspacing="2" cellpadding="2">
                <tr>
                  <td><?=$this->lang->line('tahun');?></td>
                  <td>:                  
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
                  <td width="35%"><?=$this->lang->line('lap_pemesan');?></td>
                  <td width="65%">:
                    <input type="text" style="width:150px" name="cari_pemesan" id="cari_pemesan" value="<?=$cari_pemesan?>" /></td>
                </tr>
                <tr>
                  <td height="26"><font size="-2">
                    <?=$this->lang->line('nama');?>
                    &nbsp;
                    <?=$this->lang->line('barang');?>
                  </font></td>
                  <td>:                  
                  <input type="text" style="width:150px" name="cari_nama_barang" id="cari_nama_barang" value="<?=$cari_nama_barang?>" /></td>
                </tr>
            </table></td>
            <td><table width="100%" cellspacing="2" cellpadding="2">
                <tr>
                  <td><?=$this->lang->line('bulan');?></td>
                  <td>:
			<? if ($cari_tahun != 0 ){ ?>
			  	<select name="cari_bulan" id="cari_bulan" style="width:150px"  >
			<? } else { ?>
			  	<select name="cari_bulan" id="cari_bulan" style="width:150px" disabled="disabled">
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
                  <td width="35%"><?=$this->lang->line('lap_no_po_pcv');?></td>
                  <td width="65%">:
                    <input type="text" style="width:150px" name="cari_no_po" id="caro_no_po" value="<?=$cari_no_po?>" /></td>
                </tr>
                <tr>
                  <td><font size="-2">
                    <?=$this->lang->line('kode');?>
                    &nbsp;
                    <?=$this->lang->line('barang');?>
                  </font></td>
                  <td>:                  
                  <input type="text" style="width:150px" name="cari_kode_barang" id="cari_kode_barang" value="<?=$cari_kode_barang?>" /></td>
                </tr>
            </table></td>
            <td><table width="104%" cellspacing="2" cellpadding="2">
                <tr>
                  <td><font size="-1">
                    <?=$this->lang->line('lap_no_surat_jalan');?>
                  </font></td>
                  <td>:                  
                  <input type="text" style="width:150px" name="cari_no_sj" id="cari_no_sj" value="<?=$cari_no_sj?>" /></td>
                </tr>
                <tr>
                  <td width="42%"><?=$this->lang->line('alasan');?></td>
                  <td width="58%">:                    
                  <input type="text" style="width:150px" name="cari_alasan" id="cari_alasan"  value="<?=$cari_alasan?>" /></td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
            </table></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td align="right">
				<input type="button" name="bersihkan" value="<?=$this->lang->line('button_bersihkan');?>" onclick="bersihkanFilter()"/>
				<input name="cari" type="submit" value="<?=$this->lang->line('cari');?>" />				
			</td>
          </tr>
        </table>
      </div>
    </form>
</div>
<div class="clr"></div>

<? if ($cari_bulan != '0' || $cari_tahun!= 0 || $cari_no_po!= '' || $cari_pemasok!= ''  || $cari_pemesan != '' || $cari_alasan!= '' || $cari_no_sj!= ''): ?>
	<table width="376" border="0">
  <tr>
    <td colspan="3"><?=$this->lang->line('lap_berdasarkan')?></td>
    </tr>
  
  
   <? if ($cari_bulan != '0'): ?>
  <tr>
    <td width="103"><?=$this->lang->line('bulan')?></td>
    <td width="216">:<font color="red" >
      <?=$data_bulan[$cari_bulan]?>
    </font></td>
    <td width="43">&nbsp;</td>
  </tr>
  <? endif;?>
  
     <? if ($cari_tahun != '0'): ?>
  <tr>
    <td width="103"><?=$this->lang->line('tahun')?></td>
    <td width="216">:<font color="red" >
      <?=$cari_tahun?>
    </font></td>
    <td width="43">&nbsp;</td>
  </tr>
  <? endif;?>
  

  <? if ($cari_pemasok != 0):?>
  <? endif;?>

  <? if ($cari_no_po != ''):?>
  <tr>
    <td><?=$this->lang->line('lap_no_po_pcv');?></td>
    <td>:<font color="red" >
      <?=$cari_no_po?>
    </font></td>
    <td>&nbsp;</td>
  </tr>
  <? endif;?>

  <? if ($cari_nama_barang!= ''):?>
  <tr>
    <td><?=$this->lang->line('nama');?>&nbsp;<?=$this->lang->line('barang');?></td>
    <td>:<font color="red" >
      <?=$cari_nama_barang?>
    </font></td>
    <td>&nbsp;</td>
  </tr>
  <? endif;?>

  <? if ($cari_kode_barang!= ''):?>
  <tr>
    <td><?=$this->lang->line('kode');?>&nbsp;<?=$this->lang->line('barang');?></td>
    <td>:<font color="red" >
      <?=$cari_kode_barang?>
    </font></td>
    <td>&nbsp;</td>
  </tr>
  <? endif;?>

  <? if ($cari_pemesan != ''):?>
  <tr>
    <td><?=$this->lang->line('lap_pemesan');?></td>
    <td>:<font color="red" >
      <?=$cari_pemesan?>
    </font></td>
    <td>&nbsp;</td>
  </tr>
  <? endif;?>

  <? if ($cari_alasan!= ''):?>
  <tr>
    <td><?=$this->lang->line('alasan');?></td>
    <td>:<font color="red" >
      <?=$cari_alasan?>
    </font></td>
    <td>&nbsp;</td>
  </tr>
  <? endif;?>



  <? if ($cari_no_sj != ''):?>
  <tr>
    <td><font size="-1"><?=$this->lang->line('lap_no_surat_jalan');?></font></td>
    <td>:<font color="red" >
      <?=$cari_no_sj?>
    </font></td>
    <td>&nbsp;</td>
  </tr>
  <? endif;?>
</table>
<? endif; // akhir dari pencarian berdasrkan ?>



<? if ($cari_status !=''):?>	
 <div align="right"> <?=$this->lang->line('lap_ada');?><font color="red" > <?=$jumlah_data?>  </font> <?=$this->lang->line('lap_data');?></div>
 <? endif; ?><!-- endif cari status -->

<table width="101%"  border="1" cellpadding="0" cellspacing="1" class="ui-widget-content ui-corner-all">
	<? if ($cari_status !=''):?>	
  <tr class="ui-state-default">
    <td width="4%" align="center" rowspan="2">
      <?=$this->lang->line('no');?>
    </td>
    <td width="13%" align="center" rowspan="2"> 
      <?=$this->lang->line('tanggal');?>    </td>
    <td width="8%" align="center" rowspan="2"> 
      <?=$this->lang->line('lap_no_po_pcv');?>    </td>
    <td width="12%" align="center" rowspan="2"> 
      <?=$this->lang->line('lap_nama_barang_kode');?>    </td>
    <td width="11%" align="center" rowspan="2"> 
      <?=$this->lang->line('lap_pemesan');?>    </td>
    <td width="9%" align="center" rowspan="2"> 
      <?=$this->lang->line('jumlah');?>    </td>
    <td width="9%" align="center" rowspan="2"> 
      <?=$this->lang->line('alasan');?>    </td>
    <td align="center" colspan="3">
      <?=$this->lang->line('lap_barang_datang');?>
    </td>
  </tr>
  <tr class="ui-state-default"> 
    <td align="center" width="12%">
      <?=$this->lang->line('tanggal');?>    </td>
    <td align="center" width="9%">
      <?=$this->lang->line('lap_no_sj');?>    </td>
    <td align="center" width="13%">
      <?=$this->lang->line('qty');?>    </td>
  </tr>
  <?php 
  		$no=$no_pos+1;
		if (sizeof($data_bpb) > 0):
		for ($i=0; $i < sizeof($data_bpb);$i++):
		?>
  <tr bgcolor="lightgray">
    <td valign="top" align="center" 
class="ui-state-active" ><?=$no?></td>
    <td valign="top" align="center"> 
      <?=date_format(date_create($data_bpb[$i]['pr_date']),'d-m');?>
      
      <?=date_format(date_create($data_bpb[$i]['pr_date']),'Y');?>
    </td>
    <td valign="top" align="center"> 
      <?php if ($data_bpb[$i]['pcv_id']==0) echo $data_bpb[$i]['po_no'];
			else if ($data_bpb[$i]['po_id']==0) echo $data_bpb[$i]['pcv_no']." (PCV)";
			?>
    </td>
    <td valign="top" align="left">
      <?=$data_bpb[$i]['pro_name']?>
      <br>
      (<?=$data_bpb[$i]['pro_code']?>)</td>
    <td valign="top" align="center">
      <?=$data_bpb[$i]['usr_name']?>
    </td>
    <td valign="top" align="right"> <table width="100%">
        <tr> 
          <td width="80%" align="right">
		
		 
            <?=$this->general->digit_number($data_bpb[$i]['um_id'],$data_bpb[$i]['qty'])?>
			
			
          </td>
          <td width="20%" align="left">
            <?=$data_bpb[$i]['satuan_name']?>
          </td>
        </tr>
      </table></td>
    <td valign="top" align="left">
      <?=$data_bpb[$i]['description']?>
    </td>
    <td valign="top" align="center">
      <?=$data_bpb[$i]['rec_date']?>
    </td>
    <td valign="top" align="center">
      <?=$data_bpb[$i]['rec_sj']?>
    </td>
    <td valign="top" align="right"> <table width="100%">
        <tr> 
          <td width="80%" align="right">
            <?=$this->general->digit_number($data_bpb[$i]['um_id'],$data_bpb[$i]['rec_qty'])?>
          </td>
          <td width="20%" align="left">
            <?=$data_bpb[$i]['satuan_name']?>
          </td>
        </tr>
      </table></td>
  </tr>
  <?php 
  		$no++;
		endfor;
		?>
	<!--  bwt paging	
  <tr>
    <td colspan="10" class="ui-widget-header">
      <?=$this->lang->line('halaman')?>
      : 
      <?=($this->pagination->create_links())?($this->pagination->create_links()):('-'); ?>
    </td>
  </tr>
  -->
  <?php
		else:
		?>
  <tr> 
    <td colspan="10" align="center">
     <font color="#FF0000"><strong>
			    <?=$this->lang->line('lap_tabel_tidak_ada_data');?></strong></font>
    </td>
  </tr>
  <?php endif;
  
  else: // else untuk cari status
			?>
			<tr >
				<td colspan="10" align="center" bgcolor="#FFFFFF"><font color="#FF0000"><strong><?=$this->lang->line('lap_tabel_pilih_kriteria');?></strong></font></td>
			</tr>
			<?php endif; // akhir cari status?>

</table>
