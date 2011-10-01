<table class="table" width='100%'>
		<tr class='ui-widget-header' align="center">
			<td>Tahun<?php //echo $this->lang->line('rfq_tabel_sup_name');?></td>
			<td>Satuan<?php //echo $this->lang->line('rfq_tabel_sup_adrs');?></td>
			<td>Total masuk<?php //echo $this->lang->line('rfq_tabel_sup_adrs');?></td>
			<td>Total keluar<?php //echo $this->lang->line('rfq_tabel_sup_term');?></td>
			<td>Stok akhir<?php //echo $this->lang->line('rfq_tabel_sup_days');?></td>
			<td width='30%'>Detil history<?php //echo $this->lang->line('rfq_tabel_sup_days');?></td>
		</tr>
	<?php
	if ($hist->num_rows() > 0):
		$now = date('Y');
		foreach ($hist->result() as $row): 
			echo "<tr align='left'>
					<td align='center'>".$now."</td>
					<td align='center'>".$row->satuan_name."</td>
					<td align='right'>".number_format($row->inv_in,$row->satuan_format)."</td>
					<td align='right'>".number_format($row->inv_out,$row->satuan_format)."</td>
					<td align='right'>".number_format($row->inv_end,$row->satuan_format)."</td>
					<td align='center'> <select id='det".$row->inv_id."'>
							<option value='1'>1 bulan terakhir</option>
							<option value='2'>2 bulan terakhir</option>
							<option value='3'>3 bulan terakhir</option>
							<option value='".$now."'>semua bulan</option>
						</select>
					<input type='button' onclick='opendetail(".$row->inv_id.",".$row->pro_id.")' value='Proses'> 
					</td>
				</tr>";
			
		endforeach;
	else:
		echo "Data kosong";
	endif;
	?>
</table>