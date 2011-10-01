<META HTTP-EQUIV="Expires" CONTENT="-1">
<META HTTP-EQUIV="Content-Type" CONTENT="html" />
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<link rel="stylesheet" href="<?=base_url()?>asset/css/themes/start/ui.all.css" type="text/css" />
<link rel="stylesheet" href="<?=base_url()?>asset/css/general.css" type="text/css" />
<link type="text/css" rel="stylesheet" media="print" href="<?=base_url()?>asset/css/print/print_template.css" />
<link type="text/css" rel="stylesheet" media="screen" href="<?=base_url()?>asset/css/print/normal_template.css" />

<script type="text/javascript" src="<?=base_url()?>asset/javascript/jQuery/core/jquery-1.3.2.js"></script>
<script type="text/javascript" src="<?=base_url()?>asset/javascript/jQuery/ui-1.7.2/jquery-ui-1.7.2.custom.js"></script>
<script type="text/javascript" src="<?=base_url()?>asset/javascript/jcontroler.js"></script>
<script type="text/javascript" src="<?=base_url()?>asset/javascript/jquery.bgiframe.js"></script>
<script type="text/javascript" src="<?=base_url()?>asset/javascript/jquery.cookie.js"></script>

<script src="<?=base_url()?>asset/javascript/jQuery/form/jquery.form.js" type='text/javascript'></script>

<script src="<?=base_url()?>asset/javascript/jQuery/form/jquery.autoNumeric.js" type='text/javascript'></script>
<script src="<?=base_url()?>asset/javascript/helper/autoNumeric.js" type='text/javascript'></script>
<script src="<?=base_url()?>asset/javascript/helper/dialog.js" type='text/javascript'></script>
<script src="<?=base_url()?>asset/javascript/helper/validasi.js" type='text/javascript'></script>

<link href="<?=base_url()?>asset/javascript/jQuery/dynatree/prettify.css" rel="stylesheet">
<script src="<?=base_url()?>asset/javascript/jQuery/dynatree/prettify.js" type='text/javascript'></script>
<link href='<?=base_url()?>asset/javascript/jQuery/dynatree/sample.css' rel='stylesheet' type='text/css'>
<script src='<?=base_url()?>asset/javascript/jQuery/dynatree/sample.js' type='text/javascript'></script>

<link href="<?=base_url()?>asset/javascript/jQuery/tooltip/jquery.tooltip.css" rel="stylesheet">
<script src="<?=base_url()?>asset/javascript/jQuery/tooltip/jquery.tooltip.js" type='text/javascript'></script>

<script type="text/javascript" src="<?php echo base_url()?>asset/javascript/jscookmenu/JSCookMenu.js"></script>
<link type="text/css" rel="stylesheet" href="<?php echo base_url()?>asset/javascript/jscookmenu/ThemeOffice/theme.css" />
<script type="text/javascript" src="<?php echo base_url()?>asset/javascript/jscookmenu/ThemeOffice/theme.js"></script>

<base href="<?php echo base_url(); ?>">

<?php
	if (isset($extraHeadContent)) {
		echo $extraHeadContent;
	}
?>
<script language="javascript">
$(document).ready(function() {
	/* DISABLE FUNCTION BROWSER BACK BUTTON */
	//window.history.forward(1);
});
</script>