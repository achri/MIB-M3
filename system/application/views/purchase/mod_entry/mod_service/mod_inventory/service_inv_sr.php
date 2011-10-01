
<script language="javascript">
<?=set_calendar('.kalender',0,'dd-mm-yy');?>

function set_digit(row_id,digit_decimal) {
	var digit_length = '6';
	var $item = $('#sr_qty_'+row_id);
	$item.attr('alt','p'+digit_length+'c3p'+digit_decimal+'S').autoNumeric().blur(function() {
		var strip = $.fn.autoNumeric.Strip(this.id);
		if ($item.val() != '') {
			$item.val($.fn.autoNumeric.Format(this.id,strip));
		}
	});
	
	$item.val('');
	
	return false;
}

$(document).ready(function() {
	masking('.number');
	$('.set_digit').change(function() {
		var satuan = $(this).val();
		var satuan = satuan.split("_");
		var sat_id = satuan[0];
		var sat_format = satuan[2];
		var row_id = $(this).attr('row_id');
		$('#sr_qty_'+row_id).attr('qty_satuan',satuan[1]);
		
		if (sat_format != '') {
			set_digit(row_id,sat_format);
		}
		
		return false;
	});
	
	var form_pr = $('#SR_form');
	form_pr.submit(function() {
		if (validasi('#SR_form')) {
			//$('#tabs').tabs('disabled', [0,1,2]); 
			$('.saving').attr('disabled',true);
			$('.dialog_konfirmasi').dialog({
				title:'<?=$dlg_title_confirm?>',
				autoOpen: false,
				bgiframe: true,
				width: 'auto',
				height: 'auto',
				resizable: false,
				//draggable: false,
				modal:true,
				position:['right','top'],
				buttons : { 
					'<?=$dlg_btn_back?>' : function() {
						//$('#tabs').tabs('enable', [0,1,2]); 
						$('.saving').attr('disabled',false);
						$(this).dialog('close');
					},
					'<?=$dlg_btn_ok?>' : function() {
						unmasking('.number');
						form_pr.ajaxSubmit({
							type : 'POST'
							,url : 'index.php/<?=$link_controller?>/prosesSR/SR'
							,data: form_pr.formSerialize()
							,success : function(data) {
								$("#dlg_confirm").append(data).bind('dialogclose', function(event, ui) {
									location.href='index.php/<?=$link_controller?>/index';
									$('.saving').attr('disabled',false);
								}).dialog('open');
							}
						});
						$(this).dialog('close');
					}
				}
			}).html('').html('<?=$dlg_info_confirm?>').dialog('open');
		}
		return false;
	});
	
});
</script>

<form id="SR_form">
<div class="ui-widget-content ui-corner-all" align="center">

<table width="100%"  border="0" cellpadding="2" cellspacing="1" id="SR_table">
		<tr class="ui-state-default">
		  <td width="2%"></td>
		  <td width="20%" align="center">Kode</td>
		  <td width="30%" align="center">Nama Produk</td>
		  <td width="5%" align="center">Kuantitas</td>
		  <td width="6%" align="center">Satuan</td>
		  <td width="6%" align="center">Keterangan</td>
	    </tr>
		<?php
		if ($sr_list->num_rows() > 0):
		$i=1;
		foreach ($sr_list->result()as $rows):
		?>
		<tr bgcolor="#EEEEEE" id="sr_row_<?=$i?>" class="sr_rows" baris="<?=$i?>">
			<td align="center" class="ui-widget-header ui-priority-primary">
			<!--a id="del_row_<//?=$i?>" class="del_rows" row_id="<//?=$i?>" sr_id="<//?=$rows->sr_id?>" pro_id="<//?=$rows->pro_id?>"><img border='0' src='<//?=base_url()?>asset/img_source/button_empty.png'></a></td-->
			<a class="del_rows" id="sr_del_row_<?=$i?>" onclick="del_rows('sr','<?=$i?>','<?=$rows->sr_id?>','<?=$rows->pro_id?>');"><img border='0' src='<?=base_url()?>asset/img_source/button_empty.png'></a>
			<td valign="top">
				<strong><?=$rows->pro_code?></strong> <br />
				<i>Kategori</i> : <br /> 
				<SELECT NAME="sr_cat[]" ID="service_cat_<?=$i?>" class="required" title="Pilih Kategori Servis">
					<option value="">--Pilih Kategori Servis--</option>
					<option value="in">-Inhouse</option>
					<option value="out">-Outside</option>
				</SELECT>
				<INPUT type="hidden" name="sr_id" value="<?=$rows->sr_id?>">
				<INPUT TYPE="hidden" name="sr_pro_id[]" id="sr_pro_id_<?=$i?>" value="<?=$rows->pro_id?>">
			</td>
			<td valign="top">
				<strong><?=$rows->pro_name?></strong> <br />
				<i>Tipe Servis</i> : <br /> 
				<SELECT NAME="sr_type[]" ID="service_type_<?=$i?>" class="required" title="Pilih Tipe Servis">
					<option value="">--Pilih Tipe Servis--</option>
					<option value="maintain">-Perawatan</option>
					<option value="repair">-Perbaikan</option>
				</SELECT>
			</td>
			<td valign="top" align="center">
				<input id="sr_qty_<?=$i?>" type="text" name="sr_qty[]" size="5" class="required number cek_stok" autocomplete="off" digit_decimal="<?=$rows->satuan_format?>" row_id="<?=$i?>" qty_satuan="1" title="<?=$this->lang->line('qty')?>">
			</td>
			<td valign="top" align="center">
				<select id="sr_um_id_<?=$i?>" name="sr_um_id[]" class="set_digit" row_id="<?=$i?>" >
					<option value="<?=$rows->satuan_id?>_1_<?=$rows->satuan_format?>_<?=$rows->satuan_name?>"><?=$rows->satuan_name?></option>
					<?php 
					// SATUAN
					$um_list = $this->db->query("select sat.satuan_id,sat.satuan_name,sat.satuan_format,sat_pro.value 
					from prc_master_satuan as sat 
					inner join prc_satuan_produk as sat_pro on sat_pro.pro_id = $rows->pro_id and sat_pro.satuan_unit_id = sat.satuan_id 
					order by sat.satuan_id ");
					foreach ($um_list->result() as $um_rows):
					?>
						<option value="<?=$um_rows->satuan_id?>_<?=$um_rows->value?>_<?=$um_rows->satuan_format?>_<?=$um_rows->satuan_name?>"><?=$um_rows->satuan_name?></option>
					<?php 
					endforeach;
					?>
				</select>
			</td>
			<td valign="top" align="center">
				<TEXTAREA NAME="sr_description[]" ID="sr_description_<?=$i?>" ROWS="2" COLS="20"></TEXTAREA>
			</td>
	    </tr>
		<?php 
		$i++;
		endforeach;
		endif;
		?>
		<!-- End Multi Row -->
</table>

	
</div>
	<br>
	<div class="ui-widget-content ui-corner-all" align="center">
		<input id="sr_submit" type="submit" value="<?=$this->lang->line('process')?> <?=$type?>" class="saving">
	</div>	
</form>