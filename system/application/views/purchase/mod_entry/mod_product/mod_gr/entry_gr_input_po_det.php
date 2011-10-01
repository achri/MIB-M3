<script language="javascript">
var row_no;

function link_retur(rowno,gr_no,pro_name,qty,alasan) {
		row_no = rowno;
		$('#proname_retur').html(pro_name);
		$('#grno_retur').html(gr_no);
		$('#qty_retur').val(qty);
		$('#alasan_retur').val(alasan);
		$('#dlg_alasan').dialog('open');
	}
	
	function cek_retur() {
		$return = false;
		$('.cek_retur').each(function() {
			var val = $(this).val();
			
		});
		//return
	}

$(document).ready(function(){
	masking('.number');
	
	masking_reload('.number');
	$('#retur').click(function() {
		if ($(this).attr('status') == 'retur') {
			$('.default,#batal').hide();
			$('.retur,#retur_save').show();
			//$('#adj_stat').val('2');
			$(this).attr('status','batal');
			$(this).val('Batal');
		} else {
			$('.default,#batal').show();
			$('.retur,#retur_save').hide();
			$('.input_retur').val('0');
			$(this).attr('status','retur');
			$(this).val('Perubahan');
		}
	});
	
	$('.btn_retur').click(function(){
		row_no = $(this).attr('rowno');
		var gr_no = $(this).attr('grno');
		var pro_name = $(this).attr('proname');
		$('#proname_retur').html(pro_name);
		$('#grno_retur').html(gr_no);
		$('#qty_retur').val('');
		$('#alasan_retur').val('');
		$('#dlg_alasan').dialog('open');
	});
	
	$('#dlg_alasan').dialog({
		autoOpen: false,
		bgiframe: true,
		width: 'auto',
		height: 'auto',
		resizable: false,
		position: 'center',
		modal: true,
		buttons: {
			"<?=$this->lang->line('cancel')?>": function() {
				$('.hidden_retur_'+row_no).val('');
				$("#btn_retur_"+row_no).show();
				$("#show_qty_"+row_no).hide();
				$(this).dialog('close');
			},
			"<?=$this->lang->line('ok')?>": function() {
				var get_proname = $('#proname_retur').text();
				var get_grno = $('#grno_retur').text();
				var get_qty = $('#qty_retur').val();
				var get_alasan = $('#alasan_retur').val();
				if (get_qty != '' && get_alasan != '') {
					$("#show_qty_"+row_no).html('<a href="javascript:void(0)" onclick="link_retur('+row_no+',\''+get_grno+'\',\''+get_proname+'\',\''+get_qty+'\',\''+get_alasan+'\')">'+get_qty+'</a>').show();
					$("#btn_retur_"+row_no).hide();
					$("#qty_retur_"+row_no).val(get_qty);
					$("#alasan_retur_"+row_no).val(get_alasan);
					$(this).dialog('close');
				}
				
			}
		}
	});
	
	$('#retur_save').click(function() {
		masking('.number');
		var form = $('#form_entry');	
		//$(form).submit(function() {
			// KONFIRMASI
			$('#saving').attr('disabled',true);
			//$('#tabs').attr('disable',0);
			$('.dialog_konfirmasi').dialog({
				title:'KONFIRMASI',
				autoOpen: false,
				bgiframe: true,
				width: 'auto',
				height: 'auto',
				resizable: false,
				//draggable: false,
				modal:true,
				position:['right','top'],
				buttons : { 
					'<?=$this->lang->line('back')?>' : function() {
						$('#saving').attr('disabled',false);
						//$('#tabs').attr('enable',0);
						$(this).dialog('close');
					},
					'<?=$this->lang->line('ok')?>' : function() {
						unmasking('.number');
						
						$(form).ajaxSubmit({
							type : 'POST'
							,url : 'index.php/<?=$link_controller?>/buat_retur'
							,data: $(form).formSerialize()
							//,beforeSubmit: validasi_spesifik
							,success : function(data) {
								//alert(data);
								
								var info;	
								if(data) {
									info = '<strong>Selamat... Permintaan Retur Barang berhasil dilakukan <br> No Retur :<font color="red"> '+data+' </font></strong>';
									$('#dlg_confirm').text('').append(info).dialog('open');
									$('#tabs').attr('enable',0);
									//alert(data);
								}else {
									info = '<STRONG>Maaf... Permintaan Retur Tidak Berhasil dilakukan</STRONG>';
									$('#dlg_confirm').text('').append(info).dialog('open');
								}
								$('#saving').attr('disabled',false);
								return false;
							}
						});
						
						$(this).dialog('close');
					}
				}
			}).html('').html('<?=$this->lang->line('confirm')?>').dialog('open');
			return false;
		//});
		/*
		$('#form_entry').ajaxSubmit({
			url: 'index.php/<?=$link_controller?>/buat_retur'
			,type: 'POST'
			,data: $('#form_entry').formSerialize()
			,success: function(data) {
				
				//alert(data);
				//$('#content').html(data);
			}
		});
		*/
		
	});
	
	$('#dlg_confirm').dialog({
		autoOpen: false,
		bgiframe: true,
		width: 'auto',
		height: 'auto',
		resizable: false,
		draggable: false,
		modal:true,
		position:'center',
		//show: 'drop',
		//hide: 'drop',
		buttons: {
			"Keluar" : function() {
				//tabs_awal();
				location.href = 'index.php/<?=$link_controller?>/index/good_return';
				$('#dlg_confirm').dialog('close');
				return false;
			}
		}
	/*,
		close: function(ev) {
				$(this).dialog('close');
				tabs_awal();
				return false;
		}*/	
	});
	/*
	$('#gr_create').click(function() {
		
		$('#form_entry').ajaxSubmit({
			url: 'index.php/<?=$link_controller?>/form_gr'
			,type: 'POST'
			,data: $('#form_entry').formSerialize()
			,success: function(data) {
				$('#content').html(data);
			}
		});
		
		//location.href = 'index.php/<?=$link_controller?>/form_gr';
		return false;
	});
	*/
	$('.cek_jml').keyup(function(ev){
		var $item = $(this);
		var $crow_id = $item.attr('id');
		var $jml = $item.attr('qty_before');
		var $show = $item.attr('qty_show');
		var $val = $item.val();
		var row_id = $item.attr('row_id');
		$val = parseFloat($val.replace(/,/g,''));
		if(isNaN($val)){
			$(this).val('');
		}
		else {
			if (Number($val) > Number($jml)) {
				var info = '<font color="red">Kuantitas melebihi jumlah terima !!!</font>';
				$('.dialog_informasi').html('').html(info).bind('dialogclose', function(event, ui) {
						//$item.val('');
						$(this).dialog('close');
				}).dialog('open');
				return false;			
			}
			/*
			setTimeout(function(){
				if (Number($val) < Number($jml)) {
					$('#rows_id').val(row_id);
					$('#dlg_auth_c').dialog('open');
				}
				return false;
			},2000);	
			*/		
		}
		//alert(Number($val)+' '+Number($jml));
		return false;
	});
});
</script>
<div id="dlg_confirm" title="INFORMASI"></div>
<div id="content">
<div id="dlg_alasan" title="FORM RETUR BARANG">
	<table border="0">
	<tr>
		<td>Nama Produk</td><td>:</td><td><div id="proname_retur"></div></td>
	</tr>
	<tr>
		<td>GR NO</td><td>:</td><td><div id="grno_retur"></div></td>
	</tr>
	<tr>
		<td>Kuantitas</td><td>:</td><td><input id="qty_retur" class="number"></td>
	</tr>
	<tr>
		<td>Alasan</td><td>:</td><td><textarea rows="5" cols="10" id="alasan_retur" style="overflow: auto"></textarea></td>
	</tr>
	</table>
</div>
<h3>MENU TERIMA BARANG OLEH GUDANG : DETIL NO PO <strong><?=$po_list->row()->po_no?></strong></h3>
<form id="form_entry" name="form_entry" action="index.php/<?=$link_controller?>/form_gr/<?=$page_stats?>" method="post">
<div class="ui-widget-content ui-corner-all">
<br>
<table align="center" width="99%"  border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td width="12%" class="ui-widget-header">No PO</td>
	<td width="1%" class="head_title">:</td>
    <td width="30%" class="head_title_content"><?=$po_list->row()->po_no?>
	<input type="hidden" value="<?=$po_id?>" name="po_id">
	</td>
    <td width="">&nbsp;</td>
    <td width="12%" class="ui-widget-header">Pemasok</td>
	<td width="1%" class="head_title">:</td>
    <td width="30%" class="head_title_content"><strong><?=$po_list->row()->legal_name?>. <?=$po_list->row()->sup_name?></strong></td>
  </tr>
  <tr>
    <td class="ui-widget-header">Tanggal PO</td>
	<td class="head_title">:</td>
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
		  <td colspan="7">Daftar pesanan (PO)</td>
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
		  <td valign="top" align="right"><?=number_format($row_pdet->qty,$row_pdet->satuan_format)?></td>
		  <td valign="top" align="right"><?=number_format($row_pdet->qty_terima,$row_pdet->satuan_format)?></td>
		  <td valign="top" align="right"><font color="red"><?=number_format($row_pdet->qty_retur,$row_pdet->satuan_format)?></font></td>
		  <td valign="top" align="right"><div style="float:left"><?=number_format($row_pdet->qty_status,$row_pdet->satuan_format)?></div><?=number_format($row_pdet->qty_remain,$row_pdet->satuan_format)?></td>
		</tr>
		<?php 
		$pdet_no++;
		endforeach;?>
		<!--{/section}-->
</table>
<br />
<table align="center" width="99%"  border="0" cellpadding="1" cellspacing="1" class="ui-widget-content ui-corner-all">
        <tr class="ui-widget-header">
		  <td colspan="9">Daftar terima barang</td>
		</tr>
		<tr class="ui-widget-header">
		  <td width="5%" align="center">No</td>
		  <td width="45%" align="center">Barang/Kode</td>
		  <td width="15%" align="center">Tgl terima barang</td>
		  <td width="9%" align="center">Keterangan</td>
		  <td width="8%" align="center">No BPB</td>
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
		  <td valign="top" align="center"><div><?=$row_gr->gr_suratJalan?></div></td>
		  <td valign="top" align="right"><?=($row_gr->gr_type=='rec')?(number_format($row_gr->qty,$row_gr->satuan_format)):(number_format('0',$row_gr->satuan_format))?></td>
		  <td valign="top" align="right"><?=($row_gr->gr_type=='ret')?(number_format($row_gr->retur,$row_gr->satuan_format)):(number_format('0',$row_gr->satuan_format))?></td>
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
		 <td align="center" colspan="8"><font color="red">--Tidak ada data--</font></td>
		</tr>
		<?php endif;?>
		</table>
<?php if($page_stats=='good_return'):?>
<br />
<table align="center" width="99%"  border="0" cellpadding="1" cellspacing="1" class="ui-widget-content ui-corner-all">
        <tr class="ui-widget-header">
		  <td colspan="9">Daftar barang akan diretur</td>
		</tr>
		<tr class="ui-widget-header">
		  <td width="5%" align="center">No</td>
		  <td width="38%" align="center">Barang/Kode</td>
		  <td width="10%" align="center">No BPB</td>
		  <td width="10%" align="center">Jml.Terima</td>
		  <td width="10%" align="center">Jml.Retur</td>
		  <td width="45%" align="center">Keterangan</td>
	    </tr>
		<?php 
		if ($ret_list->num_rows()>0):
		$ret_no = 1;
		foreach ($ret_list->result() as $row_ret):
		?>
		<tr bgcolor="lightgray">
		  <td valign="top" align="right" class="ui-state-active"><?=$ret_no?>.</td>
		  <td valign="top" align="left">
		  <input type="hidden" value="<?=$row_ret->pro_id?>" name="pro_id[]">
		  <?=$row_ret->pro_name?> (<?=$row_ret->pro_code?>)</td>
		  <td valign="top" align="center"><?=$row_ret->gr_no?></td>
		  <td valign="top" align="right"><?=($row_ret->gr_type=='rec')?(number_format($row_ret->qty,$row_ret->satuan_format)):(number_format('0',$row_gr->satuan_format))?></td>
		  <td valign="top" align="center">
		  <input digit_decimal="<?=$row_ret->satuan_format?>" size="10" id="qty_retur_<?=$ret_no?>" type="text" name="qty_retur[]" class="number cek_jml" qty_before="<?=$row_ret->qty?>" row_id="<?=$ret_no?>" qty_show="<?=number_format($row_ret->qty,$row_ret->satuan_format)?>">
		  <input id="price_retur_<?=$ret_no?>" type="hidden" name="price_retur[]" value="<?=$row_ret->price?>">
		  <input id="discount_retur_<?=$ret_no?>" type="hidden" name="discount_retur[]" value="<?=$row_ret->discount?>">
		  <input id="cur_retur_<?=$ret_no?>" type="hidden" name="cur_retur[]" value="<?=$row_ret->cur_id?>">
		  <input id="kurs_retur_<?=$ret_no?>" type="hidden" name="kurs_retur[]" value="<?=$row_ret->kurs?>">
		  </td>
		  <td valign="top" align="center"><input size="30" id="alasan_retur_<?=$ret_no?>" type="text" name="alasan_retur[]"></td>
		</tr>
		<?php 
		$ret_no++;
		endforeach;
		else:
		?>
		<tr>
		 <td align="center" colspan="8"><font color="red">--Tidak ada data--</font></td>
		</tr>
		<?php endif;?>
		</table>
	<?php endif;?>
		<br>
		<div align="center">
		  <INPUT TYPE="hidden" name="po_no" value="<?=$po_list->row()->po_no?>">
		  <?php if($page_stats=='good_return'):?>
		  <INPUT TYPE="button" value="<?=$btn_retur_save?>" id="retur_save">
		  <!--INPUT TYPE="button" value="<//?=$btn_retur?>" id="retur" status="retur"-->
		  <?php 
		  endif;
		  $where['po_no'] = $po_list->row()->po_no;
		  $where['po_status'] = '0';
		  if($this->tbl_po->get_po($where)->num_rows() > 0 AND $page_stats=='gr_input'):
		  ?>
		  <INPUT TYPE="submit" value="<?=$btn_create_gr?>" id="gr_create">
		  <?php endif;?>
		  <INPUT TYPE="button" id="batal" value="<?=$btn_back?>" onclick="document.location='index.php/<?=$link_controller?>/index/<?=$page_stats?>'">
	
	    </div>
	    <br>
</table>
</div>
</form>
<div id="calendar-container"></div>
</div>
