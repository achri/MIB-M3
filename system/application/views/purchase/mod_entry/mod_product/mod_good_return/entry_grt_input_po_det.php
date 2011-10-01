<h3>MENU TERIMA BARANG OLEH GUDANG : DETIL PO <strong><?=$po_list->row()->po_no?></strong></h3>
<form name="form_entry" action="index.php/<?=$link_controller?>/form_gr/<?=$page_stats?>" method="post">
<div class="ui-widget-content ui-corner-all">
<br>
<table align="center" width="99%"  border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td width="10%" class="ui-widget-header">PO No</td>
	<td width="5%" class="head_title">:</td>
    <td width="30%" class="head_title_content"><?=$po_list->row()->po_no?></td>
    <td width="15%">&nbsp;</td>
    <td width="11%" class="ui-widget-header">Supplier</td>
	<td width="5%" class="head_title">:</td>
    <td width="30%" class="head_title_content"><?=$po_list->row()->sup_name?></td>
  </tr>
  <tr>
    <td class="ui-widget-header">Tgl PO</td>
	<td width="5%" class="head_title">:</td>
    <td class="head_title_content"><?=$po_list->row()->po_date?></td>
    <td>&nbsp;</td>
    <td></td>
    <td></td>
	<td></td>
  </tr>
  <tr>
    <td colspan="7">&nbsp;</td>
  </tr>
</table>

<table align="center" width="99%"  border="0" cellpadding="1" cellspacing="1" class="ui-widget-content ui-corner-all">
        <tr class="ui-widget-header">
		  <td colspan="7">Daftar Pesanan (PO)</td>
		</tr>
		<tr class="ui-widget-header">
		  <td width="5%" align="center">No</td>
		  <td width="45%" align="center">Barang/Kode</td>
		  <td width="8%" align="center">Satuan</td>
		  <td width="8%" align="center">Pesan</td>
		  <td width="8%" align="center">Terima</td>
		  <td width="8%" align="center">Retur</td>
		  <td width="18%" align="center">+/-</td>
	    </tr>
		<!--{section name=x loop=$po_detail}-->
		<?php 
		$pdet_no = 1;
		foreach ($po_det->result() as $row_pdet):?>
		<tr bgcolor="lightgray">
		  <td valign="top" align="right" class="ui-state-active"><?=$pdet_no?></td>
		  <td valign="top" align="left"><?=$row_pdet->pro_name?> (<?=$row_pdet->pro_code?>)</td>
		  <td valign="top" align="center"><?=$row_pdet->satuan_name?></td>
		  <td valign="top" align="right"><?=$row_pdet->qty?></td>
		  <td valign="top" align="right"><?=$row_pdet->qty_terima?></td>
		  <td valign="top" align="right"><font color="red"><?=$row_pdet->qty_retur?></font></td>
		  <td valign="top" align="right"><div style="float:left"><?=$row_pdet->qty_status?></div><?=$row_pdet->qty_remain?></td>
		</tr>
		<?php 
		$pdet_no++;
		endforeach;?>
		<!--{/section}-->
</table>
<br />
<table align="center" width="99%"  border="0" cellpadding="1" cellspacing="1" class="ui-widget-content ui-corner-all">
        <tr class="ui-widget-header">
		  <td colspan="9">Daftar Terima Barang / Barang Retur</td>
		</tr>
		<tr class="ui-widget-header">
		  <td width="5%" align="center">No</td>
		  <td width="45%" align="center">Barang/Kode</td>
		  <td width="15%" align="center">Tgl Terima / Retur</td>
		  <td width="9%" align="center">Keterangan</td>
		  <td width="8%" align="center">Nomor</td>
		  <td width="10%" align="center">S.Jalan</td>
		  <td width="10%" align="center">Jml.Terima</td>
		  <td width="10%" align="center">Jml.Retur</td>
		  <?php if ($page_stats == 'gr_auth_list'):?>
		  <td valign="top" align="center">Otorisasi</td>
		  <?php endif;?>
	    </tr>
		<?php 
		if ($gr_list->num_rows()>0):
		$grdet_no = 1;
		foreach ($gr_list->result() as $row_gr):
		?>
		<tr bgcolor="lightgray">
		  <td valign="top" align="right" class="ui-state-active"><?=$grdet_no?>.</td>
		  <td valign="top" align="left"><?=$row_gr->pro_name?> (<?=$row_gr->pro_code?>)</td>
		  <td valign="top" align="center"><?=$row_gr->gr_date?></td>
		  <td valign="top" align="center"><?=($row_gr->gr_type=='rec')?('Terima'):('Retur')?></td>
		  <td valign="top" align="center"><?=$row_gr->gr_no?></td>
		  <td valign="top" align="center"><?=$row_gr->gr_suratJalan?></td>
		  <td valign="top" align="right"><?=($row_gr->gr_type=='rec')?(number_format($row_gr->qty,2)):('0.00')?></td>
		  <td valign="top" align="right"><?=($row_gr->gr_type=='ret')?(number_format($row_gr->qty,2)):('0.00')?></td>
		  <?php if ($page_stats == 'gr_auth_list'):?>
		  <td valign="top" align="right"><?=($row_gr->auth_no!='')?($row_gr->auth_no):('-')?></td>
		  <?php endif;?>
		</tr>
		<?php 
		$grdet_no++;
		endforeach;
		else:
		?>
		<tr>
		 <td align="center" colspan="8">&nbsp;</td>
		</tr>
		<tr>
		 <td align="center" colspan="8"><font color="red">--Tidak ada data--</font></td>
		</tr>
		<?php endif;?>
		<tr>
		  <td colspan="7">&nbsp;</td>
		</tr>
		</table>
		<br>
		<div align="center">
		  <INPUT TYPE="hidden" name="po_no" value="<?=$po_list->row()->po_no?>">
		  <?php 
		  $where['po_no'] = $po_list->row()->po_no;
		  $where['po_status'] = '0';
		  if($this->tbl_po->get_po($where)->num_rows() > 0 AND $page_stats=='gr_input'):
		  ?>
		  <INPUT TYPE="submit" value="<?=$btn_create_gr?>">
		  <?php endif;?>
		  <INPUT TYPE="button" value="<?=$btn_cancel?>" onclick="document.location='index.php/<?=$link_controller?>/index/<?=$page_stats?>'">
	
	    </div>
	    <br>
</table>
</div>
</form>
<div id="calendar-container"></div>
