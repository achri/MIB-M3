<?php
echo ": <select name='prov' id='prov'>
	<option value=''>[Pilih - Provinsi]</option>";
	if ($list_prov->num_rows() > 0):
		foreach ($list_prov->result() as $row):		 
			echo "<option value='$row->provinsi_id' >$row->provinsi_name</option>";
		endforeach;
		else:
			echo "Empty";
		endif;
	echo "</select>";
?>