<table class="table" width='800'>
		<tr class='ui-widget-header' align="center">
			<td>No</td>
			<td>Tgl GR</td>
			<td>NO GR</td>
			<td>Nama Produk</td>
			<td>Kuantitas</td>
			<td>Harga</td>
			<td>Total</td>
			<td>Pemohon</td>
		</tr>
	<?php
	$i = 1;
	if ($adj_history->num_rows() > 0):
		foreach ($adj_history->result() as $hist): 
			$total = $hist->qty * $hist->price;
			echo "<tr class='x' align='left'>
					<td align='right'>".$i."</td>
					<td align='center'>".$hist->date_edit."</td>
					<td align='center'>".$hist->gr_no."</td>
					<td>".$hist->pro_name."</td>
					<td align='center'>".$this->general->digit_number($hist->um_id,$hist->qty).' '.$hist->satuan_name."</td>
					<td align='center'>".$hist->cur_symbol.'. '.number_format($hist->price,$hist->cur_digit)."</td>
					<td align='center'>".$hist->cur_symbol.'. '.number_format($total,$hist->cur_digit)."</td>
					<td align='center'>".$hist->usr_name."</td>
					
				</tr>";
		$i++;
		endforeach;
	else:
		echo "Belum ada data";
	endif;
	?>
</table>