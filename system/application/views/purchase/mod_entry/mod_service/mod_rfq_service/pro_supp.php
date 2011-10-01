<script type="text/javascript">
$(document).ready(function() {
	$(".opensup").click(function() {
		$(this).each(function(){
			var id = $(this).attr('id');
			var name = $('#gsup_'+id).val();
			var pro = $('#indpro').val();
			var tid = $('#tid_'+id).val();
			var tname = $('#tname_'+id).val();
			var tdays = $('#tdays_'+id).val();
	
			$('#pay_'+pro).val(tname);
			$('#hari_'+pro).val(tdays);
			$('#tsup'+pro).val(name);
			$('#id_sup_'+pro).val(id);
			$('#supplier').dialog('close');
		});
	});
});
</script>
<table class="table" width='500'>
		<tr class='ui-widget-header' align="center">
			<td>Nama Pemasok</td>
			<td>Alamat Pemasok</td>
			<td>Term Pemasok</td>
			<td><?php echo $this->lang->line('rfq_tabel_sup_days');?></td>
		</tr>
	<?php
	if ($suppro->num_rows() > 0):
		foreach ($suppro->result() as $sup): 
			echo "<tr class='x opensup' id='".$sup->sup_id."' align='left'>
					<td>".$sup->legal_name.". ".$sup->sup_name."</td>
					<td>".$sup->sup_address."<input type='hidden' id='gsup_".$sup->sup_id."' value='".$sup->legal_name.". ".$sup->sup_name."'></td>
					<td>".$sup->term_id_name."
							<input type='hidden' id='tid_".$sup->sup_id."' value='".$sup->term_id."'>
							<input type='hidden' id='tname_".$sup->sup_id."' value='".$sup->term_id_name."'>
					</td>
					<td>".$sup->term_days."
							<input type='hidden' id='tdays_".$sup->sup_id."' value='".$sup->term_days."'>
					</td>
				</tr>";
		endforeach;
	else:
		echo "Empty";
	endif;
	?>
	</table>