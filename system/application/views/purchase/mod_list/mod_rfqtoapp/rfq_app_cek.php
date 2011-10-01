<script type="text/javascript">
$(document).ready(function () {
		
		$("#history").dialog({
			modal: true,
			autoOpen: false,
			bgiframe: false,
			width: 'auto',
			height: 'auto',
			resizable: false,
			draggable: false,
			show: 'drop',
			hide: 'drop',
			buttons: {
				"Keluar": function() {
					$(this).dialog('close');
				}
			}
		});

});

function open_hist(pro_id, code){
	//alert (pro_id + '-' + code);
	//$('#history').dialog('open');
	triger = $('#t'+pro_id).val();
	if (triger == 0){
		$.ajax({
			type: 'POST',
			url: 'index.php/<?php echo $link_controller;?>/get_history/'+pro_id,
			data: $(this).serialize(),
			success: function(data) {
				$('#slide'+pro_id).show();
				$('#t'+pro_id).val('1');
				$('#cont'+pro_id).html(data);
				//$('#resultsup').html(data);
				//$('#history').dialog('open')
			}
		});
		return false;
	}else{
		$('#slide'+pro_id).hide();
		$('#t'+pro_id).val('0');
		$('#cont'+pro_id).text('');
	}
}

function opendetail(cat_id, pro_id ){
	detail = $('#det'+cat_id).val();
	$.ajax({
		type: 'POST',
		url: 'index.php/<?php echo $link_controller;?>/get_detail_history/'+detail+'/'+pro_id,
		data: $(this).serialize(),
		success: function(data) {
			$('#result').html(data);
			$('#history').dialog('open')
		}
	});
	return false;
}

function batal(){
	window.location = 'index.php/<?php echo $link_controller;?>/index';
}
</script>

<?php 
	$cont = $get_rfq->row();
?>
<form id="app_rfq">
<center>
<div class="ui-corner-all headers">
<table>
	<tr>
		<td class="labelcell" width="150"><?php echo $this->lang->line('rfq_label_no');?></td>
		<td class="labelcell2">: <?php echo $cont->rfq_no;?> <input type="hidden"  name="rfq_id" value="<?php echo $cont->rfq_id; ?>"> </td>
		<td width="20%"></td>
		<td class="labelcell" width="100"><?php echo $this->lang->line('rfq_label_cetak');?></td>
		<td class="labelcell2">: <?php echo $cont->rfq_date_print;?></td>
	</tr>
	<tr>
		<td class="labelcell"><?php echo $this->lang->line('rfq_label_tgl');?></td>
		<td class="labelcell2">: <?php echo $cont->rfq_date;?></td>
		<td></td>
		<td class="labelcell"><?php echo $this->lang->line('rfq_label_oleh');?></td>
		<td class="labelcell2">: <?php echo $cont->usr_name;?></td>
	</tr>
</table>
</div>
<br>
<table width="90%" id="dataview" class="table" colspan='4' cellpadding='1' cellspacing='1'>
	<tr class='ui-widget-header'>
		<td align='center' width="80">Kode<?php //echo($this->lang->line('kategori_col_0')); ?></td>
		<td align='center' width="30%">Kategori<?php //echo($this->lang->line('grup_col_0')); ?></td>
		<td align='center' width="">Produk<?php //echo($this->lang->line('kategori_col_3')); ?></td>
		<td align='center' width="5%">Opsi</td>
	</tr>
<?php 
	foreach ($get_rfq->result() as $dtlcont):
		$code = explode('.', $dtlcont->pro_code);
		$code = $code[0];
		$catid = $this->Tbl_category->get_catid($code)->row();
		$catid->cat_id;
		echo "<tr class='x'>
					<td align='center'>".$dtlcont->pro_code."</td>
					<td>".$dtlcont->cat_name."</td>
					<td>".$dtlcont->pro_name."</td>
					<td align='center'><a href='javascript:void(0)' onclick='open_hist(".$dtlcont->pro_id.",".$catid->cat_id.")'><img src='".base_url()."asset/img_source/icon_lkp.gif' border='0'></a></td>
			  </tr>
			  <tr style='display : none' id='slide".$dtlcont->pro_id."'>
			  		<td class='normal' colspan='4'><input type='hidden' value='0' id='t".$dtlcont->pro_id."'><div id='cont".$dtlcont->pro_id."'></div></td>
			  </tr>";
	endforeach;
?>
</table>
</form>
</center>

<div id="history" title="Data Produk">
	<div id="result"> </div>
	<input type="hidden" id="indpro">
</div>