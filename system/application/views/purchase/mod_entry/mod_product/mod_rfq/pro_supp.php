<script type="text/javascript">
$(document).ready(function() {
	$(".opensup").click(function() {
		$(this).each(function(){
			var no = '<?=$no?>';
			var id = $(this).attr('id');
			var name = $('#gsup_'+id).val();
			var pro = $('#indpro').val();
			var tid = $('#tid_'+id).val();
			var tname = $('#tname_'+id).val();
			var tdays = $('#tdays_'+id).val();
			var cur = $('#tcur_'+id).val();
	
			$('#pay_'+no).val(tname);
			$('#hari_'+no).val(tdays);
			$('#tsup'+no).val(name);
			$('#id_sup_'+no).val(id);
			if (cur == ''){
				$('#cur_'+no).attr('disabled',false).change();
			} else {
				$('#cur_'+no).val(cur).change();
				$('#cur_org_'+no).val(cur);
			}
			
			$('#supplier').dialog('close');
		});
	});
});

$('.black_list').css('background-color','red');
</script>
<div style="height:400px;overflow:auto">
<table class="table" width='500'>
		<tr class='ui-widget-header' align="center">
			<td>Nama Pemasok</td>
			<td>Alamat Pemasok</td>
			<td>Term Pemasok</td>
			<td><?php echo $this->lang->line('rfq_tabel_sup_days');?></td>
			<td>MataUang</td>
		</tr>
	<?php
	if ($suppro->num_rows() > 0):
		foreach ($suppro->result() as $sup): 
			$block = '';
			if($sup->sup_status == 0)
				$block = "black_list";
			else
				$block = 'opensup';
				
			echo "<tr class='x $block' id='".$sup->sup_id."' align='left'>
					<td>".$sup->sup_name.", ".$sup->legal_name."</td>
					<td>".$sup->sup_address."<input type='hidden' id='gsup_".$sup->sup_id."' value='".$sup->legal_name.'. '.$sup->sup_name."'></td>
					<td>".$sup->term_id_name."
							<input type='hidden' id='tid_".$sup->sup_id."' value='".$sup->term_id."'>
							<input type='hidden' id='tname_".$sup->sup_id."' value='".$sup->term_id_name."'>
					</td>
					<td>".$sup->term_days."
							<input type='hidden' id='tdays_".$sup->sup_id."' value='".$sup->term_days."'>
							<input type='hidden' id='tcur_".$sup->sup_id."' value='".$sup->cur_id."'>
					</td>
					<td>
						".$sup->cur_symbol."
					</td>
				</tr>";
		endforeach;
	else:
		echo "Empty";
	endif;
	?>
	</table>
</div>