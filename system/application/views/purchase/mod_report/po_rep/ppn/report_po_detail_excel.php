<?php 
// ============== bwt bisa di download,, bwaan php =======================
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename='.$this->lang->line('lap_nama_file_detail').$po_no.'.xls');
header('Cache-Control: max-age=0');
?>

<h3><?=$title_page?></h3>
<form name="form_entry" id="form_entry">

  <table width="100%"  border="0" cellspacing="2" cellpadding="2">
    <tr> 
      <td width="9%" class="head_title"><?=$this->lang->line('lap_no_po');?></td>
      <td width="32%" class="head_title">: <?=$po_no?></td>
      <td width="14%">&nbsp;</td>
      <td width="10%" class="head_title"><?=$this->lang->line('supplier');?></td>
      <td class="head_title">: <?=$sup_name?>, <?=$legal_name?></td>
    </tr>
    <tr> 
      <td class="head_title"><?=$this->lang->line('lap_tanggal');?></td>
      <td class="head_title">: <?=$po_date?></td>
      <td>&nbsp;</td>
      <td><?=$this->lang->line('lap_detail_status_po');?></td>
      <td>: <?=$this->general->status('buka_tutup',$po_status)?></td>
    </tr>
    <tr> 
      <td colspan="5">&nbsp;</td>
    </tr>
  </table>
<font size='-3'>
  <table width="100%"  border="1" cellpadding="0" cellspacing="0" class="ui-widget-content ui-corner-all">
    <tr bgcolor="#CCCCCC" class="ui-state-default"> 
      <td colspan="15"><?=$this->lang->line('lap_detail_daftar_pesanan');?></td>
    </tr>
    <tr bgcolor="#CCCCCC" class="ui-state-default"> 
      <td width="3%" rowspan="2" align="center"><?=$this->lang->line('no');?></td>
      <td width="22%" rowspan="2" align="center"><?=$this->lang->line('lap_detail_barang_kode');?></td>
      <td width="4%" rowspan="2" align="center"><?=$this->lang->line('satuan');?></td>
      <td width="4%" rowspan="2" align="center"><?=$this->lang->line('pesan');?></td>
      <td width="4%" rowspan="2" align="center"><?=$this->lang->line('terima');?></td>
      <td width="4%" rowspan="2" align="center"><?=$this->lang->line('retur');?></td>
      <td width="9%" rowspan="2" align="center">+/-</td>
      <td width="4%" rowspan="2" align="center"> 
        <?=$this->lang->line('no');?>
        <?=$this->lang->line('otorisasi');?>
      </td>
      <td width="4%" rowspan="2" align="center"> 
        <?=$this->lang->line('keterangan');?>
        <?=$this->lang->line('otorisasi');?>
      </td>
      <td width="4%" rowspan="2" align="center"><?=$this->lang->line('mata_uang')?></td>
      <td width="4%" rowspan="2" align="center"> 
        <?=$this->lang->line('harga');?>
        <?=$this->lang->line('satuan');?>
      </td>
      <td colspan="2" align="center"><?=$this->lang->line('diskon')?></td>
      <td rowspan="2" align="center"><?=$this->lang->line('nilai').' '.$this->lang->line('ppn')?></td>
      <td rowspan="2" align="center"> 
        <?=$this->lang->line('harga');?>
        <?=$this->lang->line('sub_total');?>
      </td>
    </tr>
    <tr bgcolor="#CCCCCC" class="ui-state-default"> 
      <td width="3%" align="center">%</td>
      <td align="center"><?=$this->lang->line('nilai');?></td>
    </tr>
    <?php
		$no = 1;
		foreach ($po_detail->result() as $row_po):?>
    <tr  valign="middle"> 
      <td  align="center" class="ui-state-active" > 
        <?=$no?>
      </td>
      <td  align="left"> 
        <?=$row_po->pro_name?>
        <br> 
        <?='('.$row_po->pro_code.')'?>
      </td>
      <td  align="center"><?=$row_po->satuan_name?></td>
      <td  align="right"><?=$this->general->digit_number($row_po->um_id,$row_po->qty)?></td>
      <td  align="right"><?=$this->general->digit_number($row_po->um_id,$row_po->qty_terima)?></td>
      <td  align="right">
	  	<font color="red"> 
        	<?=$this->general->digit_number($row_po->um_id,$row_po->qty_retur)?>
		</font>
      </td>
      <td  align="right">
	  	<div style="float:left"> 
          <?=$row_po->qty_status?>
        </div>
        <?=$this->general->digit_number($row_po->um_id,$row_po->qty_remain)?>
      </td>
      <td  align="center"> 
	  	<font color="#FF0000"> 
        	<?=$row_po->auth_no?>
        </font>
	  </td>
      <td  align="right"><?=$row_po->auth_note?></td>
      <td  align="center"><?=$row_po->cur_symbol.'. '?></td>
      <td  align="right"><?=number_format($row_po->harga_satuan,$row_po->cur_digit)?></td>
      <td align="center" ><?=$row_po->discount?></td>
      <td align="right" ><?=number_format($row_po->diskon,$row_po->cur_digit)?></td>
      <td align="right" ><?=number_format($row_po->nilai_ppn,$row_po->cur_digit)?></td>
      <td align="right" ><?=number_format($row_po->sub_total,$row_po->cur_digit)?></td>
    </tr>
    <?php
		$no++;
		endforeach;?>
 <strong>
    <tr bgcolor="lightgrey"   class="ui-state-active" > 
      <td colspan="12" align="right"  ><?=$this->lang->line('total');?> : </td>
      <td align="right" ><?=number_format($tot_diskon,$row_po->cur_digit)?></td>
      <td align="right" ><?=number_format($tot_nilai_ppn,$row_po->cur_digit)?></td>
      <td align="right" ><?=number_format($total,$row_po->cur_digit)?></td>
    </tr>
  <strong>
  </table>
</font>
<!-- akhir tabel pertama -->
<br />
<table width="100%"  border="1" cellpadding="0" cellspacing="0" class="ui-widget-content ui-corner-all">
        <tr bgcolor="#CCCCCC" class="ui-state-default">
		  <td colspan="8"><?=$this->lang->line('lap_detail_daftar_rec_retrn');?></td>
		</tr>
		<tr bgcolor="#CCCCCC" class="ui-state-default">
		  <td width="3%" align="center"><?=$this->lang->line('no');?></td>
		  <td width="41%" align="center"><?=$this->lang->line('lap_detail_barang_kode');?></td>
		  <td width="13%" align="center"><?=$this->lang->line('lap_detail_tgl_good_rec_rtrn');?></td>
		  <td width="7%" align="center"><?=$this->lang->line('lap_detail_rec_rtrn');?></td>
		  <td width="7%" align="center"><?=$this->lang->line('lap_detail_nomor');?></td>
		  <td width="8%" align="center"><?=$this->lang->line('lap_detail_s_jalan');?></td>
		  <td width="8%" align="center"><?=$this->lang->line('lap_detail_jum_terima');?></td>
		  <td width="13%" align="center"><?=$this->lang->line('lap_detail_jum_retur');?></td>
	    </tr>
		<?php 
		$no = 1;
		if ($bpb->num_rows() > 0):
		foreach ($bpb->result() as $row_bpb):?>
		<tr  valign="middle" >
		  <td  align="center" class="ui-state-active"><?=$no?></td>
		  <td  align="left"><?=$row_bpb->pro_name?><br><?='('.$row_bpb->pro_code.')'?></td>
		  <td  align="center"><?=$row_bpb->gr_date?></td>
		  
      <td  align="center"><?=$this->general->status('rec',$row_bpb->gr_type)?></td>
		  <td  align="center"><?=$row_bpb->gr_no?></td>
		  <td  align="left"><?=$row_bpb->gr_suratJalan?></td>
		  <td  align="right"><?=($row_bpb->gr_type=='rec')?($this->general->digit_number($row_po->um_id,$row_bpb->qty)):('-')?></td>
		  <td  align="right"><?=($row_bpb->gr_type=='ret')?($this->general->digit_number($row_po->um_id,$row_bpb->qty)):('-')?></td>
		</tr>
		
		<?php 
			$no++;
		endforeach;
		else:
		?>
		<tr bgcolor="lightgrey">
		 <td align="center" colspan="8"><font color="red"><?=$this->lang->line('lap_tabel_tidak_ada_data');?></font></td>
		</tr>
		<?php endif;?>
</table>
</form>