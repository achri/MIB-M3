</div>
</div>
<div id="pagefootwidth" class="noprint ui-widget-content ui-corner-all">
<?php
	$client_name = $this->session->userdata('client_name');
	$client_image = $this->session->userdata('client_image');
	$module_version = $this->session->userdata('module_version').$this->session->userdata('module_revision');
	$module_type = $this->session->userdata('module_type');
	$module_package = $this->session->userdata('module_package');
	$module_program = $this->session->userdata('module_program');
?>
	<div class="noprint">
	<table width="100%">
	<tr>
		<td width="30%" align="left">&nbsp;<strong><?php echo $client_name;?></strong></td>
		<td align="center">&copy;&nbsp;<?=date('Y')?>&nbsp;MIB</td>
		<td width="30%" align="right"><i><b><?php echo $module_package;?>&nbsp;<?php echo $module_type;?></b>&nbsp;<?php echo $module_version;?></i>&nbsp;</td>
	</tr>
	</table>
	</div>
</div>
</div>
<div class="informasi" title="INFORMASI"></div>
<div class="dialog_informasi" title="INFORMASI" style="align:left"></div>
<div class="dialog_konfirmasi" title="KONFIRMASI"></div>
<div class="dialog_notice dialog_validasi" title="PERINGATAN"></div>
</body>
</html>