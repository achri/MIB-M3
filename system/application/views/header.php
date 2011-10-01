<?php
header('HTTP/1.0 200 OK'); // stoopid IIS
header('Content-Type: text/html; Charset=UTF-8');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php echo $this->load->view('headerContent');?>
<style type="text/css" rel="stylesheet">
.ui-dialog-titlebar-close{
    display: none;
}
</style>
<title> <?=($this->lang->line('head_title'));?></title>
</head>
<body>
<div id="pagewidth" class="ui-widget-content ui-corner-all">
    <div id="main_head" class="ui-widget-header ui-corner-tr ui-corner-tl">
	<div style="float:left;padding-left:10px"><font size=6pt>MATERIAL MANAGEMENT MODULE</font></div>
	<?php echo $this->load->view('userPanel');?>
	</div>
    <div id="content">