<table width="600px" class="ui-widget-content ui-corner-all" border="0" cellpadding="1" cellspacing="1">
<tr class="ui-widget-header">
	<td width="5%">NO</td>
	<td width="10%">NO BPB</td>
	<td width="25%">NILAI</td>
	<td width="10%">KURS</td>
	<td width="25%">TOTAL</td>
	<td width="25%">PPN 10% (Rp)</td>
</tr>
<? 
$no = 1;
$gr_foot = 0;
$gr_tot_foot = 0;
$gr_ppn_foot = 0;
$gr_kur = 0;
foreach ($list_bpb->result() as $row_bpb):
//$gr_ppn = $row_bpb->gr_value + $row_bpb->gr_ppn_value;
$gr_ppn = $row_bpb->gr_value;
$gr_ppn_kurs = $gr_ppn*$row_bpb->kurs;
?>
<tr bgcolor="lightgray">
	<td><?=$no?></td>
	<td><?=$row_bpb->gr_no?></td>
	<td>
	<table width="100%">
	<tr>
		<td align="left"><?=$row_bpb->cur_symbol?>.</td>
		<td align="right"><?=number_format($gr_ppn,$row_bpb->cur_digit)?></td>
	</tr>
	</table>
	</td>
	
	<td>
	<table width="100%">
	<tr>
		<td align="left">Rp.</td>
		<td align="right"><?=number_format($row_bpb->kurs,2)?></td>
	</tr>
	</table>
	</td>
	
	<td>
	<table width="100%">
	<tr>
		<td align="left">Rp.</td>
		<td align="right"><?=number_format($gr_ppn_kurs,2)?></td>
	</tr>
	</table>
	</td>
	
	<td>
	<table width="100%">
	<tr>
		<td align="left">Rp.</td>
		<td align="right"><?=number_format($row_bpb->gr_ppn_value,2)?></td>
	</tr>
	</table>
	</td>
		
</tr>
<? 
$gr_foot = $gr_foot + $gr_ppn;
$gr_tot_foot = $gr_tot_foot + $gr_ppn_kurs;
$gr_ppn_foot = $gr_ppn_foot + $row_bpb->gr_ppn_value;
$gr_kur = $row_bpb->cur_digit;
$no++;
endforeach;?>
<tr class="ui-state-active">
	<td colspan="2" align="right">Total : </td>
	<td>
	<table width="100%">
	<tr>
		<td align="left"><?=$row_bpb_tot->row()->cur_symbol?>.</td>
		<td align="right"><?=number_format($gr_foot,$gr_kur)?></td>
	</tr>
	</table>
	<td>
	<table width="100%">
	<tr>
		<td align="left">&nbsp;</td>
		
	</tr>
	</table>
	</td>
	<td>
	<table width="100%">
	<tr>
		<td align="left">Rp.</td>
		<td align="right"><?=number_format($gr_tot_foot,2)?></td>
	</tr>
	</table>
	</td>
	<td>
	<table width="100%">
	<tr>
		<td align="left">Rp.</td>
		<td align="right"><?=number_format($gr_ppn_foot,2)?></td>
	</tr>
	</table>
	</td>
</tr>
</table>