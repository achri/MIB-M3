<?php
	$ok = "<img src='".base_url()."/asset/img_source/centang.gif'>";
?>
<script language="javascript">
	function print_this(bkbk_id) {
		window.print();
		$.ajax({
			url:'index.php/<?=$link_controller?>/after_print/'+bkbk_id+'/0',
			type:'POST',
			success: function(data) {
				//alert(data);
				if(data) {
					$('#print').hide();
					//alert(data);
					//back_this();
				}
			}
		});
		return false;
	}
	
	function back_this() {
		location.href = 'index.php/<?=$link_controller?>/index';
	}
	
	$('input:checkbox').attr('disabled',true);
	
</script>

<style type="text/css">
.dotted tr td {
	border:1px dotted;
}
.solid tr td {
	border:1px solid #CCCCCC;
}
.tbl_in {
	border:1px solid #CCCCCC;
}
.col_in {
	background:#FFFF99;
	border: 1px solid #CCCCCC;
}
.border_down td {
	border-bottom: 1px solid #CCCCCC;
}
</style>

<?php 
if ($bkbk_list->num_rows() > 0):
$data_print = $bkbk_list->row();
?>
<div class="noprint">
<h2>MENU CETAK : PEMBAYARAN</h2>
</div>
<div align="center">
<table width="900" border="0" cellpadding="1" cellspacing="1" bgcolor="#CCCCCC">
<tr bgcolor="#FFFFFF">
	<td>
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tr bgcolor="#FFFFFF">
			<td valign="bottom">
				<table width="55%" border="0" cellpadding="3" cellspacing="0" style="float:left">
				<tr>
					<td width="500px" valign="bottom" nowrap><strong><font size="5pt"><?php echo $this->session->userdata('client_name');?></font></strong></td>
					<td valign="bottom" nowrap><strong><font size="4pt"><i>BUKTI PENGELUARAN KAS</i></font></strong></td>
				</tr>
			  </table>
			  <table width="38%" border="0" cellpadding="3" cellspacing="0" style="float:right">
				<tr>
			 		<td align="right" valign="bottom" nowrap class="col_in">No. BKBK :</td>
					<td width="200px" valign="bottom" nowrap><?=$data_print->bkbk_no?></td>
					<td align="right" valign="bottom" nowrap class="col_in">Tgl. BKBK :</td>
					<td width="100px" valign="bottom" nowrap><?=$data_print->bkbk_date?></td>
				</tr>
			  </table>
			</td>
		</tr>
		<tr>
			<td height="1px"></td>
		</tr>
		</table>
		
		<table class="master" width="100%" border="0" cellpadding="0" cellspacing="0">
		<tr bgcolor="#FFFFFF">
			<td>
				<table width="100%" border="0" cellpadding="3" cellspacing="0" class="tbl_in">
				<tr>
					<td width="150px" valign="top" nowrap class="col_in"><strong><i>Dibayar kepada</i></strong></td>
					<td width="30px" valign="top"></td>
					<td class="col_in" width="150px" align="right" valign="top" nowrap>Nama perusahaan :</td>
					<td valign="top" nowrap>.....................................</td>
					<td class="col_in" width="150px" align="right" valign="top" nowrap>Nama perorangan :</td>
					<td valign="top" nowrap>.....................................</td>
				</tr>
				
			  </table>
			</td>
		</tr>
		<tr>
			<td height="1px"></td>
		</tr>
		<tr>
			<td>
				<table width="100%" border="0" cellpadding="3" cellspacing="0" class="tbl_in">
				<tr class="border_down">
					<td width="150px" valign="top" nowrap class="col_in"><strong><i>Untuk keperluan</i></strong></td>
					<td align="right" valign="top" nowrap>Pembayaran pemasok :</td>
					<td valign="top" nowrap width="10px"><input type="checkbox" checked/></td>
					<td align="right" valign="top" nowrap>Nama pemasok :</td>
					<td width="150px" valign="top" nowrap><strong><?=$data_print->sup_name?></strong></td>
					<td align="right" valign="top" nowrap>No kontra bon :</td>
					<td width="100px" valign="top" nowrap><strong><i>
					<?php
					foreach ($bkbk_list->result() as $con_res):
						$con_list[] = $con_res->con_no;
					endforeach;
					echo implode($con_list,'<br>');
					?>	</i></strong></td>
				</tr>
				<tr class="border_down">
					<td colspan="2" align="right" valign="top" nowrap>Pembayaran bunga / pinjaman :</td>
					<td valign="top" nowrap><input type="checkbox" /></td>
					<td align="right" valign="top" nowrap>Rekap kas kecil :</td>
					<td valign="top" nowrap><input type="checkbox" /></td>
					<td align="right" valign="top" nowrap>Nomor :</td>
					<td valign="top" nowrap> ........................</td>
				</tr>
				<tr>
					<td colspan="2" align="right" valign="top" nowrap>Pengontanan :</td>
					<td valign="top" nowrap><input type="checkbox" /></td>
					<td align="right" valign="top" nowrap>Pembayaran lain-lain :</td>
					<td valign="top" nowrap><input type="checkbox" /> ..............................</td>
					<td valign="top" nowrap></td>
					<td valign="top" nowrap></td>
				</tr>
			  </table>
			</td>
		</tr>
		<tr>
			<td height="1px"></td>
		</tr>
		<tr>
			<td>
			<table width="100%" border="0" cellpadding="3" cellspacing="0" style="border:1px dotted">
				<tr>
					<td width="150px" valign="bottom" nowrap class="col_in"><strong><i>Keterangan</i></strong></td><td></td>
				</tr>
				<tr>
					<td colspan="2" valign="top">
					<p align="justify"><font size="4px"><?=$data_print->memo?></font></p>
				  </td>
				</tr>
			</table>
			</td>
		</tr>
		<tr>
			<td height="1px"></td>
		</tr>
		<tr>
			<td>
			<table width="100%" border="0" cellpadding="3" cellspacing="0" class="tbl_in">
				<tr>
					<td>
					<table width="100%" border="0" cellpadding="3" cellspacing="0" class="tbl_in">
					<tr>
					<td width="150px" valign="top" nowrap class="col_in"><strong><i>Jumlah (Angka)</i></strong></td>
					<td width="180px" valign="top"><strong><?=$data_print->cur_symbol?>.<?=number_format($data_print->con_dibayar,$data_print->cur_digit)?></strong></td>
					<td width="100px" valign="top" nowrap class="col_in"><strong><i>Terbilang</i></strong></td>
					<td valign="top"><strong><i><?=strtoupper($this->general->terbilang($data_print->con_dibayar,$data_print->cur_digit,$data_print->cur_short))?></i></strong></td>
				</tr>
				
				<tr>
					<td width="150px" valign="top" nowrap class="col_in"><strong><i>Jumlah PPN(Angka)</i></strong></td>
					<td width="180px" valign="top"><strong>Rp.<?=number_format($data_print->ppn_dibayar,2)?></strong></td>
					<td width="100px" valign="top" nowrap class="col_in"><strong><i>Terbilang</i></strong></td>
					<td valign="top"><strong><i><?=strtoupper($this->general->terbilang($data_print->ppn_dibayar,2,'Rupiah'))?></i></strong></td>
				</tr>
					</table>					
					
					</td>
				</tr>
				<tr><td><table width="100%" border="0" cellpadding="3" cellspacing="0" class="tbl_in">
                  <tr>
                    <td width="150px" valign="middle" nowrap="nowrap" class="col_in"><strong><i>Jenis pembayaran</i></strong></td>
                    <td width="300px" valign="middle"><table width="100%" border="0" cellpadding="3" cellspacing="0">
                        <tr>
                          <td align="right" valign="middle" nowrap="nowrap">Tunai :</td>
                          <td width="10px" valign="middle"><input name="checkbox" type="checkbox" <?=($data_print->bkbk_methode == 'CASH')?('checked'):('')?> />						  </td>
                          <td align="right" valign="middle" nowrap="nowrap">Giro/Cek :</td>
                          <td width="10px" valign="middle"><input name="checkbox2" type="checkbox" <?=($data_print->bkbk_methode == 'CEK/GIRO')?('checked'):('')?>/></td>
                          <td align="right" valign="middle" nowrap="nowrap">Transfer :</td>
                          <td width="10px" valign="middle">
						  <input type="checkbox" <?=($data_print->bkbk_methode == 'TRANSFER')?('checked'):('')?> /></td>
                          <td>&nbsp;</td>
                        </tr>
                    </table></td>
                    <td width="8px" valign="middle" nowrap="nowrap" class="col_in"><strong><i>Mata uang</i></strong></td>
                    <td valign="middle"><table width="100%" border="0" cellpadding="3" cellspacing="0">
                        <tr>
                          <td align="right" valign="middle" nowrap="nowrap">US$  :</td>
                          <td width="10px" valign="middle">
						  <input type="checkbox" <?=($data_print->cur_id == 2)?('checked'):('')?> /></td>
                          <td align="right" valign="middle" nowrap="nowrap">Rupiah :</td>
                          <td width="10px" valign="middle">
						  <input type="checkbox" <?=($data_print->cur_id == 1)?('checked'):('')?> /></td>
                          <td align="right" valign="middle" nowrap="nowrap">Lain-lain :</td>
                          <td valign="middle"> <input type="checkbox" /> 
                          ...........</td>
                          <td>&nbsp;</td>
                        </tr>
                    </table></td>
                  </tr>
                </table></td></tr>
				<tr>
					<td colspan="4">
						<table width="100%" border="0" cellpadding="3" cellspacing="0" class="tbl_in">
							<tr class="border_down">
								<td width="150px" valign="top" nowrap class="col_in"><strong><i>Nomor Giro/ Cek</i></strong></td>
								<td width="130px" valign="top"><?=($data_print->cek_no != '')?($data_print->cek_no):(' - ')?></td>
								<td width="150px" align="right" valign="top" nowrap>Tgl jatuh tempo :</td>
								<td width="100px" valign="top"><?=($data_print->cek_tempo != '')?($data_print->cek_tempo):(' - ')?></td>
								<td width="150px" align="right" valign="top" nowrap>Rekening :</td>
								<td width="" valign="top"><?=($data_print->cek_rekening != '')?($data_print->cek_rekening):(' - ')?></td>
							</tr>
							<!--tr class="border_down">
								<td width="150px" valign="bottom" nowrap></td>
								<td> ...........................</td>
								<td width="150px" align="right" valign="bottom" nowrap>Tgl Jatuh Tempo :</td>
								<td> ..../..../........ </td>
								<td width="150px" align="right" valign="bottom" nowrap>Nama Bank :</td>
								<td> ...........................</td>
							</tr-->
							<tr >
								<td width="150px" valign="top" nowrap class="col_in"><strong><i>Nomor transfer slip</i></strong></td>
								<td valign="top"><?=($data_print->transfer_nomor != '')?($data_print->transfer_nomor):(' - ')?></td>
								<td width="150px" align="right" valign="top" nowrap>Transfer biaya :</td>
								<td><?=($data_print->transfer_biaya != '')?($data_print->transfer_biaya):(' - ')?></td>
								<td width="150px" align="right" valign="top" nowrap>Nama pemasok :</td>
								<td valign="top"><?=($data_print->transfer_supplier != '')?($data_print->transfer_supplier):(' - ')?></td>
							</tr>
							<tr>
							<td colspan="5" align="right">Transfer rekening :</td>
							<td ><?=($data_print->transfer_rekening != '')?($data_print->transfer_rekening):(' - ')?></td>
							</tr>
					  </table>					</td>
				</tr>
			</table>
			</td>
		</tr>
		<tr>
			<td height="1px"></td>
		</tr>
		<tr>
			<td>
			<table class="tbl_in" width="100%" border="0" cellpadding="3" cellspacing="0">
			<tr>
			<td width="350px" class="col_in"><strong><i>Journal entry ( Wajib diisi sama akunting )</i></strong></td>
			<td>&nbsp;</td>
			</tr>
			</table>
			</td>
		</tr>
		<tr>
			<td height="1px"></td>
		</tr>
		<tr>
			<td>
			<table class="tbl_in dotted" width="100%" border="0" cellpadding="3" cellspacing="0">
			<tr class="col_in">
				<td width="5%" align="center">No</td>
				<td align="center">Tanggal</td>
				<td align="center">Nama akun</td>
				<td width="15%" align="center">COA</td>
				<td width="15%" align="center">Dr</td>
				<td width="15%" align="center">Cr</td>
			</tr>
			<tr>
				<td align="right">1</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td align="right">2</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td align="right">3</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td align="right">4</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td align="right">5</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td align="right">6</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			</table>
			</td>
		</tr>
		<tr>
			<td height="1px"></td>
		</tr>
		<tr>
			<td>
			<table class="tbl_in solid" width="100%" border="0" cellpadding="3" cellspacing="0">
			<tr>
				<td width="10%" align="center">PENERIMA UANG</td>
				<td width="10%" align="center">KEPALA AKUNTING</td>
				<td width="10%" align="center">DIREKSI</td>
				<td width="" colspan="2" align="center">KASIR</td>
			</tr>	
			<tr>
				<td rowspan="3">&nbsp;</td>
				<td rowspan="3">&nbsp;</td>
				<td rowspan="3">&nbsp;</td>
				<td width="10%" align="center" class="col_in">Nama</td>
				<td width="10%" align="center" class="col_in">Paraf</td>
			</tr>
			<tr>
				<td align="center"><?=$usr_name?></td>
				<td rowspan="2">&nbsp;</td>
			</tr>
			<tr>
				<td align="center">Tanggal: <?=date('d-m-Y')?></td>
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
<?php if ($data_print->bkbk_printStatus != 1):?>
<input type="button" id="print" value="<?=$this->lang->line('print')?>" onclick="print_this('<?=$bkbk_id?>');">&nbsp;
<?php endif;?>
<input type="button" id="cancel" value="<?=$this->lang->line('back')?>" onclick="back_this();">
</div>
<?php 
	endif;
	?>