<html>
<head>
<div id="headerdiv" >
<font size="10pt">
<?php 
	echo $extraHeadContent;
	echo $extraHeadContent2;
	$name = $this->session->userdata('client_name');
	$image = $this->session->userdata('client_image');
	$versi = $this->session->userdata('module_version').$this->session->userdata('module_revision');
	$type = $this->session->userdata('module_type');
	$package = $this->session->userdata('module_package');
	$program = $this->session->userdata('module_program');
	if ($image != ''){
		echo "<img border='0' src='".base_url()."/uploads/client/".$image."' width='850px'>";
	} else {
		echo $name;
	}
?>
</font>
</div>
<script type=\"text/javascript\">
$(document).ready(function () {
	$('#usr_id').focus();
});
</script>
</head>
<?php 
$this->load->view('login/loginform');
?>
<div id="footerdiv" >
&copy; <?php echo date('Y'); ?> MIB
<br>
<center><i><strong><?php echo $package?>&nbsp;<?php echo $type?></strong>&nbsp;<?php echo $versi?></i></center>
</div>

</html>