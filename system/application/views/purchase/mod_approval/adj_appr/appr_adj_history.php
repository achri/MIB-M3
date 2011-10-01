<table class="table" width='800'>
		<tr class='ui-widget-header' align="center">
			<td>No</td>
			<td>Tgl<br>Penyesuaian</td>
			<td>No Penyesuaian</td>
			<td>Pemasok</td>
			<td>Tgl Opname</td>
			<td>Reality</td>
			<td>Opname</td>
			<td>Satuan</td>
			
		</tr>
	<?php
	$i = 1;
	if ($adj_history->num_rows() > 0):
		foreach ($adj_history->result() as $hist): 
			echo "<tr class='x' align='left'>
					<td align='right'>".$i."</td>
					<td align='center'>".$hist->adj_date."</td>
					<td align='center'>".$hist->adj_no."</td>
					<td>".$hist->legal_name.". ".$hist->sup_name."</td>
					<td align='center'>".$hist->opname_date."</td>
					<td align='center'>".$this->general->digit_number($hist->um_id,$hist->qty_stock)."</td>
					<td align='center'>".$this->general->digit_number($hist->um_id,$hist->qty_opname)."</td>
					<td align='center'>".$hist->satuan_name."</td>
					
				</tr>";
		$i++;
		endforeach;
	else:
		echo "Belum ada data";
	endif;
	?>
</table>