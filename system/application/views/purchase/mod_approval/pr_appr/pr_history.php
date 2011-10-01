<table class="table" width='800'>
		<tr class='ui-widget-header' align="center">
			<td rowspan=2>No<?php //echo $this->lang->line('rfq_tabel_sup_name');?></td>
			<td rowspan=2>Tanggal PO<?php //echo $this->lang->line('rfq_tabel_sup_adrs');?></td>
			<td rowspan=2>No PO<?php //echo $this->lang->line('rfq_tabel_sup_term');?></td>
			<td rowspan=2>Pemasok<?php //echo $this->lang->line('rfq_tabel_sup_days');?></td>
			<td rowspan=2>Harga</td>
			<td rowspan=2>Satuan</td>
			<td colspan=4>Kuantitas</td>
		</tr>
		<tr class='ui-widget-header' align="center">
			<td>Order</td>
			<td>Terima</td>
			<td>Retur</td>
			<td>Sisa</td>
		</tr>
	<?php
	$i = 0;
	if ($get_history->num_rows() > 0):
		foreach ($get_history->result() as $hist): 
			$i = $i +1;
			/*
			if ($hist->cur_symbol == 'Rp'){
				$price = number_format($hist->price,2,',','.');
			}else{
				$price = number_format($hist->price,4,'.',',');
			}
			*/
			echo "<tr class='x' align='left'>
					<td>".$i.".</td>
					<td>".$hist->date."</td>
					<td>".$hist->po_no."</td>
					<td>".$hist->sup_name.' ,'.$hist->legal_name."</td>
					<td>".$hist->cur_symbol.". ".number_format($hist->price,$hist->cur_digit)."</td>
					<td>".$hist->satuan_name."</td>
					<td>".number_format($hist->qty,$hist->satuan_format)."</td>
					<td>".number_format($hist->qty_terima,$hist->satuan_format)."</td>
					<td>".number_format($hist->qty_retur,$hist->satuan_format)."</td>
					<td>".number_format($hist->sisa,$hist->satuan_format)."</td>
				</tr>";
		endforeach;
	else:
		echo "<tr><td colspan='10'>-= Belum ada data =-</td></tr>";
	endif;
	?>
</table>