<script type="text/javascript">
	$(document).ready(function()  {
		$('.editprov').editable('index.php/lokasi/prov_update',{
			indicator : 'Saving...',
			tooltip   : 'Click to edit...',
			width : '150px'
		}).css('text-transform','uppercase');
	});
</script>
<?php
	if ($list->num_rows() > 0){
		echo "<table width='100%'>";
			foreach ($list->result() as $prov):
				echo "<tr class='ui-state-default'>
						<td width='15'><a href='javascript:void(0)' onclick=open_kota('$prov->provinsi_id')><div id='but1_$prov->provinsi_id'><img border='0' src='./asset/img_source/add1.gif'></div></a></td>
						<td width='200'>
						<div style='float:left' class='editprov' id='$prov->provinsi_id'>$prov->provinsi_name</div>
						<div style='float:right'>
						";
			echo "<a href='javascript:void(0)' onclick=\"del_lokasi(".$prov->provinsi_id.",'provinsi');\"><img border='0' src='".base_url()."asset/img_source/button_empty.png'></a>";
			echo "		</div>						
						</td>
					</tr>
					<tr style='display:none' id='tr1_$prov->provinsi_id'>
						<td></td>
						<td></td>
						<td><div id='r1_$prov->provinsi_id'></div><input type='hidden' id='t1_$prov->provinsi_id' value='0'></td>
					</tr>";
			endforeach;
		echo "</table>";
	}else{
			echo "Belum ada data";
	}
?>