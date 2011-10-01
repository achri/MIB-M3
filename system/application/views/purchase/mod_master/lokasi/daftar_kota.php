<script type="text/javascript">
	$(document).ready(function()  {
		$('.editkota').editable('index.php/lokasi/kota_update',{
			indicator : 'Saving...',
			tooltip   : 'Click to edit...',
			width : '150px'
		});
	}).css('text-transform','uppercase');
</script>
<?php
		if ($list->num_rows() > 0){
			echo "<table width='100%'>";
				foreach ($list->result() as $kota):
					echo "<tr class='ui-state-default'>
							<td width='200' >
							<div style='float:left' class='editkota' id='$kota->kota_id'>$kota->kota_name</div>
						<div style='float:right'>
						";
			echo "<a href='javascript:void(0)' onclick=\"del_lokasi(".$kota->kota_id.",'kota');\"><img border='0' src='".base_url()."asset/img_source/button_empty.png'></a>";
			echo "		</div>	
							</td>
						</tr>";
				endforeach;
			echo "</table>";
		}else{
				echo "Belum ada data";
		}
?>