<table class="table" width='100%'>
		<tr class='ui-widget-header' align="center">
			<td>Tanggal<?php //echo $this->lang->line('rfq_tabel_sup_name');?></td>
			<td>Satuan</td>
			<td>Total masuk<?php //echo $this->lang->line('rfq_tabel_sup_adrs');?></td>
			<td>Total keluar<?php //echo $this->lang->line('rfq_tabel_sup_term');?></td>
			<td>Stok akhir<?php //echo $this->lang->line('rfq_tabel_sup_days');?></td>
		</tr>
	<?php
	if ($detail->num_rows() > 0):
		$now = date('Y');
		foreach ($detail->result() as $row): 
			echo "<tr align='left'>
					<td>".$row->inv_transDate."</td>
					<td align='center'>".$row->satuan_name."</td>
					<td align='right'>".number_format($row->inv_in,$row->satuan_format)."</td>
					<td align='right'>".number_format($row->inv_out,$row->satuan_format)."</td>
					<td align='right'>".number_format($row->inv_end,$row->satuan_format)."</td>
				</tr>";
			
		endforeach;
	else:
		echo "Empty";
	endif;
	?>
</table>