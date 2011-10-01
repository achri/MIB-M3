<script language="javascript">
	function print_this(con_id,status,count) {
		window.print();
		$.ajax({
			url:'index.php/<?=$link_controller?>/after_print/'+con_id+'/'+status+'/'+count,
			type:'POST',
			success: function(data) {
				//$('.informasi').html(data);
				if(data == 'ok') {
					//back_this();
					$('#print').hide();
				}
				else {
					$('#content_print').html(data);
				}
			}
		});
		return false;
	}
	
	function back_this() {
		location.href = 'index.php/<?=$link_controller?>/index/<?=$print_status?>';
	}
	
</script>
<?php 
if ($print_con->num_rows() > 0):
$data_print = $print_con->row();
$print_count = $print_con->row()->con_printCount + 1;
?>
<div class="noprint">
<h2><?=$page_title?> : <?=$this->lang->line('print_view')?></h2>
</div>
<div align="left">
<table width="870" border="0" cellpadding="1" cellspacing="1" bgcolor="#CCCCCC">
	<tr bgcolor="#FFFFFF" >
	<td>
		<table width="100%" cellpadding="0" cellspacing="0" border="0"  >
			<tr  >
				<td nowrap>
					<table width="100%" cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td nowrap><span style="font-size: 16pt; font-weight:bold;">KONTRA BON</span><br></td>
							<td nowrap rowspan="2" align="center">
								
							</td>
						</tr>
						<tr     ><td nowrap>&nbsp;</td></tr>
						<tr>
					<td nowrap valign="top" align="left">
						<table cellpadding="0" cellspacing="0" border="0" width="100%">
						<tr>
							<td width="30%">Telah diterima dari</td>
							<td width="5%" align="center">:</td>
							<td width="58%"><?=$data_print->legal_name?>. <?=$data_print->sup_name?></td>
						</tr>
						<tr>
							<td>Surat jalan/Faktur</td>
							<td align="center">:</td>
							<td><?=$data_print->jumlah_gr?> lembar</td>
						</tr>
						<tr>
							<td>Perincian</td>
							<td align="center">:</td>
							<td>&nbsp;</td>
						</tr>
						</table>
						</td>
						<td valign="top" align="right">
						<table border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td width="30%">No KB</td>
										<td width="5%" align="center">&nbsp;:&nbsp;</td>
										<td width="58%" ><span style="font-size:12pt;font-weight:bold;"><?=$data_print->con_no?> <? if ($print_status != 0) echo '(r'.$print_count.')';?></span></td>
									</tr>
									<tr>
										<td  >No PO</td>
										<td>&nbsp;:&nbsp;</td>
										<td  ><?=$data_print->po_no?></td>
									</tr>
									<tr>
										<td>Tanggal Cetak</td>
										<td>&nbsp;:&nbsp;</td>
										<td><?php echo date('d-m-Y');?></td>
									</tr>
									
								</table>
						</td>
						<td>&nbsp;</td>
						
						</tr>
					</table>
				</td>
			</tr>
			<tr><td>&nbsp;</td></tr>
			<tr>
			  <td>
				
				<table width="100%" border="1" cellspacing="0" cellpadding="2">
					<tr valign="top" bgcolor="#FFFFFF" align="center">
						<td width="2%" nowrap>
							<strong>No</strong>
						</td>
						<td width="20%" nowrap >
							<strong>No faktur pemasok</strong>
						</td>
						<td width="20%" nowrap>
							<strong>No surat jalan</strong>
						</td>
						<td width="15%" nowrap>
							<strong>No BPB</strong>
						</td>
						<td width="15%" nowrap>
							<strong>Tgl barang tiba</strong>
						</td>
						<td width="20%" nowrap>
							<strong>Nilai <br> ( <?php echo $cur_symbol;?> )</strong>
						</td>
						<?php
						if ($data_print->cur_id != 1):
						?>
						<td width="15%" nowrap>
							<strong>Kurs <br> ( Rp )</strong>
						</td>
						<td width="15%" nowrap>
							<strong>Total harga <br> ( Rp )</strong>
						</td>
						<?php
						endif;
						?>
					</tr>
					<?php 
					if($print_gr->num_rows() > 0):
					$no = 1;
					foreach ($print_gr->result() as $row_gr):
					?>
					<?php //if($row_gr->gr_type=='rec'):?>
					<tr valign="top" bgcolor="#FFFFFF">
						<td  nowrap><?=$no?>.</td>
						<td  nowrap><?=$row_gr->gr_fakturSup?></td>
						<td  nowrap><?=$row_gr->gr_suratJalan?></td>
						<td  nowrap><?=$row_gr->gr_no?></td>
						<td  nowrap><?=$row_gr->gr_date?></td>
						<td align="right" nowrap><?=number_format($row_gr->gr_value,$row_gr->cur_digit)?></td>
						<?php
						if ($data_print->cur_id != 1):
						?>
						<td align="right" nowrap><?=number_format($row_gr->kurs,2)?></td>
						<td align="right"><?=number_format($row_gr->kurs_value,2)?></td>
						<?php
						endif;
						?>
					</tr>
					<?php
					
					$no++;
					endforeach;
					endif;
					
					//RETUR
					$retur = $this->tbl_good_return->get_print_retur_bon($row_gr->con_id,$row_gr->po_id);
					if ($retur->row()->ret_no != ''):
						foreach ($retur->result() as $row_ret):
					?>
					<tr bgcolor="#FFFFFF">
						<td nowrap></td>
						<td colspan="6" nowrap>&nbsp;&nbsp;&nbsp;<i>Retur - <?=$row_ret->ret_no?>&nbsp;-&nbsp;<?=$row_ret->ret_date?></i></td>
						<td align="right" nowrap>(<?=$row_ret->cur_symbol?>. <?=number_format($row_ret->total_retur,2)?>)</td>
					</tr>
					<?php 
						endforeach;
					endif;
					?>
					<tr bgcolor="#FFFFFF">
						<td colspan="<?=($data_print->cur_id != 1)?('8'):('6')?>" nowrap>
						</td>
					</tr>
					<tr valign="top" bgcolor="#FFFFFF" >
						<td colspan="5" align="right" nowrap>
							<strong>Total nilai:	</strong>
						</td>
						<td align="right" nowrap>
						<?php 
							$total = number_format($print_tot->row()->tot_gr_value,$print_tot->row()->cur_digit);	
							echo "<STRONG> $total </STRONG>";
						?>
						</td>
						<?php
						if ($data_print->cur_id != 1):
						?>
						<td></td>
						<td align="right">
						<?php 
							$tot_seluruh_kurs = number_format($print_tot->row()->tot_kurs_value,2);
							echo "<STRONG> $tot_seluruh_kurs </STRONG>";
						?>
						</td>
						<?php endif;?>
					</tr>
					
					<tr bgcolor="#FFFFFF">
						<td colspan="10" nowrap>
						<?php 
							echo $notes;
						?>
						</td>
					</tr>
					<tr valign="top" bgcolor="#FFFFFF" >
						<td colspan="10" nowrap> 
							<table cellpadding="0" cellspacing="0" border="0" width="100%">
								<!--{if $sudah}-->
								<!-- tr align="top">
									<td width="10%" valign="top" nowrap>Terbilang Rp</td>
									<td width="1%" valign="top">:</td>
									<td colspan="2"><i>
									<!{$CON_VALUE_BILANGAN}->
									</i></td>
								</tr-->
								<!--{/if}-->
								<tr    >
									<td width="10%" nowrap></td>
									<td width="1%" nowrap></td>
									<td width="60%" nowrap>
									<!--//?php 
									$term_credit = $data_print->term_days;
									$status_po   = $data_print->po_status;
									if($status_po == 1) {
										$back_date     = mktime(0, 0, 0, date("m")  , date("d")+$term_credit, date("Y"));
										$str_back_date = date("d-m-Y", $back_date);
								
									}
									else {
										$str_back_date = "(PO Belum Tutup)";
									}
									echo $str_back_date;
									?-->
									</td>
									<td align="center" nowrap>Penerima</td>
								</tr>
								<tr    >
									<td colspan="4" nowrap><br/></td>
								</tr>
								<tr    >
									<td colspan="3" nowrap></td>
									<td align="center" nowrap>(<strong><u> <?=$data_print->con_penerima?> </u></strong>)</td>
								</tr>
							</table>
						</td>
					</tr>
					
				</table>
			</td>
		   </tr>
		</table>
	
	</td>
	</tr>
</table>
</div>

<br>
<div align="left" class="noprint">
<?php 
	if ($print_status == 0):
		if ($data_print->con_printStat != 1):?>
<input type="button" id="print" value="<?=$this->lang->line('print')?>" onclick="print_this('<?=$con_id?>','<?=$print_status?>','<?=$print_count?>');">&nbsp;
<?		endif;
	else:?>
<input type="button" id="print" value="<?=$this->lang->line('print')?>" onclick="print_this('<?=$con_id?>','<?=$print_status?>','<?=$print_count?>');">&nbsp;
<?php endif;?>
<input type="button" id="cancel" value="<?=$this->lang->line('back')?>" onclick="back_this();">
</div>
<?php 
	endif;
	?>
