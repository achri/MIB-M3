<script language="javascript">
var val_stats=false;

function cek_valid() {
	if(val_stats==false) {
		var num_sel = $('#form_entry input:checked').length;
		var tipe_byr = $('#form_entry select').val();
		if (num_sel < 1) {
			$('#dialog_info').text('').append('<h4>Pilih Minimal 1 Kontrabon !!!</h4>').dialog('open');		
		}
		else if (tipe_byr == '0') {
			$('#dialog_info').text('').append('<h4>Pilih Tipe Pembayaran !!!</h4>').dialog('open');
		}
		else {
			val_stats = true;
		}
	}

	return val_stats;
}

$(document).ready(function() {
	$('#form_entry > *').attr('title',' ');
	$('#dialog_info').dialog({
		title: 'INFORMASI',
		autoOpen: false,
		bgiFrame: true,
		draggable: false,
		sizeable: false,
		resizable: false,
		buttons: {
			'Keluar':function() {
				$(this).dialog('close');
			}
		}
	});
});
</script>
<div id="dialog_info">
	<H4>DATA KONTRA BON PEMASOK <strong><?=$sup_name?></strong></H4>
</div>
<H3><?=$page_title?></H3>
<div class="ui-widget-content ui-corner-all">
<br>
<form id="form_entry" action="index.php/<?=$link_controller?>/list_payment" method="post" onsubmit="return cek_valid();">
<table width="90%" align="center" border="0" cellpadding="1" cellspacing="1" class="ui-widget-content ui-corner-all">
		<tr class="ui-widget-header">
		  <td colspan="9">Daftar Kontra Bon</td>
		</tr>
		<tr class="ui-widget-header">
		  <td width="5%" align="center"></td>
		  <td width="15%" align="center">No Kontra Bon</td>
		  <td width="15%" align="center">Tgl <br>Kontra Bon</td>
		  <td width="15%" align="center">Tgl Jatuh Tempo</td>
		  <td width="15%" align="center">Total</td>
		  <td width="15%" align="center">Dibayar</td>
		  <td width="15%" align="center">Sisa</td>
	    </tr>
		<?php 
		if ($list_gr->num_rows() > 0):
		$no = 1;
		foreach ($list_gr->result() as $row_gr):
		?>
		<tr bgcolor="lightgray">
		  <td align="center" class="ui-state-active">
		  <INPUT TYPE="checkbox" NAME="con_id[]" value="<?=$row_gr->con_id?>" ID="con_id_<?=$no?>"></td>
		  <td align="center"><?=$row_gr->con_no?></td>
		  <td align="center"><?=$row_gr->con_date?></td>
		  <td align="center"><?=$row_gr->con_dueDate?></td>
		  <td valign="top" align="left">
		   <table width="100%" border="0" cellspacing="0" cellpadding="0">
		     <tr>
			  <td align="left"><?=$row_gr->cur_symbol?></td>
			  <td align="right"><?=number_format($row_gr->con_value,$row_gr->cur_digit)?></td>
			 </tr>
		   </table>
		  </td>
		  <td valign="top" align="left">
		   <table width="100%" border="0" cellspacing="0" cellpadding="0">
		     <tr>
			  <td align="left"><?=$row_gr->cur_symbol?></td>
			  <td align="right"><?=number_format($row_gr->con_payVal,$row_gr->cur_digit)?></td>
			 </tr>
		   </table>
		  </td>
			<td valign="top" align="left">
		   <table width="100%" border="0" cellspacing="0" cellpadding="0">
		     <tr>
			  <td align="left"><?=$row_gr->cur_symbol?></td>
			  <td align="right"><?=number_format($row_gr->con_value-$row_gr->con_payVal,$row_gr->cur_digit)?></td>
			 </tr>
		   </table>
		  </td>
		</tr>
		<?php 
		$no++;
		endforeach;
		endif;
		?>
</table>
<br>
<table width="90%" align="center" border="0" cellpadding="1" cellspacing="1">
        <tr>
          <td>
		  <INPUT TYPE="hidden" name="sup_id" value="<?=$sup_id?>">
		  </td>
		</tr>
		<tr>
		  <td>Metoda Pembayaran : 
			<SELECT NAME="payment_method">
			  <option value="0">-[Pilih Metode Pembayaran]-</option>
			  <option value="cash">- Cash</option>
			  <option value="transfer">- Transfer</option>
			  <option value="cek/giro">- Cek / Giro</option>
			</SELECT>
		  <INPUT TYPE="submit" id="buat_pay" value="Buat Pembayaran">
		  <INPUT TYPE="button" value="Batal" onclick="location.href='index.php/<?=$link_controller?>/index'">		  
		  </td>
		</tr>
</table>
</form>
<br>
</div>
