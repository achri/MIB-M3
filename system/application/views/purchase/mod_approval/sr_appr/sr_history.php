<table class="table" width='800'>
		<tr class='ui-widget-header' align="center">
			<td>no<?php //echo $this->lang->line('rfq_tabel_sup_name');?></td>
			<td>so date<?php //echo $this->lang->line('rfq_tabel_sup_adrs');?></td>
			<td>so no<?php //echo $this->lang->line('rfq_tabel_sup_term');?></td>
			<td>supplier<?php //echo $this->lang->line('rfq_tabel_sup_days');?></td>
			<td>harga</td>
			<td>satuan</td>
			<td>qty order</td>
			<td>qty terima</td>
			<td>qty retur</td>
			<td>sisa</td>
		</tr>
	<?php
	$i = 0;
	if ($get_history->num_rows() > 0):
		foreach ($get_history->result() as $hist): 
			$i = $i +1;
			if ($hist->cur_symbol == 'Rp'){
				$price = number_format($hist->price,2,',','.');
			}else{
				$price = number_format($hist->price,4,'.',',');
			}
			echo "<tr class='x' align='left'>
					<td>".$i.".</td>
					<td>".$hist->date."</td>
					<td>".$hist->so_no."</td>
					<td>".$hist->legal_name.". ".$hist->sup_name."</td>
					<td>".$hist->cur_symbol.". ".$price."</td>
					<td>".$hist->satuan_name."</td>
					<td>".$hist->qty."</td>
					<td>".$hist->qty_terima."</td>
					<td>".$hist->qty_retur."</td>
					<td>".$hist->sisa."</td>
				</tr>";
		endforeach;
	else:
		echo "Belum ada data";
	endif;
	?>
</table>