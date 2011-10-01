<script language="javascript">
$("#dialog_kosong").dialog({
	autoOpen: false,
	modal: true,
	buttons: {
	'<?=$this->lang->line('jquery_button_close');?>': function() {
		$(this).dialog('close');
			location.href = 'index.php/<?=$link_controller?>/index';
	}
}
});


function win_alasan() {
	$("#form_entry").ajaxSubmit({
		url: 'index.php/<?=$link_controller?>/close_po',
		type: 'post',
		data: $(this).formSerialize(),
		success: function(data) {
			alert('PO berhasil ditutup');
			//$('#dialog_kosong').dialog('open');
			location.href = 'index.php/<?=$link_controller?>/index';
			
		}
	});
	}
</script>
<h3><?=$title_page?></h3>

<form method="post" action="index.php/<?=$link_controller?>/get_detail/<?=$po_id?>/<?=$po_no?>/<?=$po_status?>/excel" >
	<div align="right" class="noprint">
		<input type="submit" value="<?=$this->lang->line('lap_salin_ke_excel');?>">
		<input type="button" id="print" value="<?=$this->lang->line('lap_print');?>" onclick="window.print();">	
	</div>

	<!--  ================ bwt ngirim selesi ke excelnya =========== -->
	<input type="hidden" name="po_id_excel" value="<?=$po_id_excel?>">
	<input type="hidden" name="po_status_excel" value="<?=$po_status_excel;?>">
	<!-- ========================================================================== -->	

</form>

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
  <table width="100%"  border="0" cellpadding="1" cellspacing="1" class="ui-widget-content ui-corner-all">
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
    <tr bgcolor="lightgrey" valign="middle"> 
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
<table width="100%"  border="0" cellpadding="1" cellspacing="1" class="ui-widget-content ui-corner-all">
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
		<tr bgcolor="lightgrey" valign="middle" >
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
		<br>
		<div align="center" class="ui-widget-content ui-corner-all noprint">
		  <INPUT TYPE="hidden" name="po_id" value="<?=$po_id?>">
		  <INPUT TYPE="hidden" name="po_note" id="po_note" value="test">
		  <?php if ($po_status !=1 ){?>
		  <INPUT TYPE="button" value="<?=$this->lang->line('lap_detail_button_tutup');?>" onclick="win_alasan();">
		  <?php }?>
		 
		  <INPUT TYPE="button" value="<?=$this->lang->line('lap_detail_button_kembali');?>" onclick="document.location='index.php/<?=$link_controller?>'">
		</div>
		
</form>

<div id="dialog_kosong" title="<?=($this->lang->line('jquery_dialog_peringatan'));?>">
	<p><?php echo ($this->lang->line('bank_form_error'));?></p>
</div>

