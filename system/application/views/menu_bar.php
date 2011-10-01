<?php 
		$user = $this->session->userdata('usr_id');
		$cat = $this->session->userdata('ucat_id');
		$i = 0;
		$parent = 0;
		$retmenu = '';
		$menu = $this->tbl_menu->get_allMenu($parent,$i);
?>
<div class="ui-widget-content ui-corner-br ui-corner-bl" align="" style="padding-left:10px;padding-top:5px;margin-top:-1px"><DIV ID=myMenuID></DIV></div>
	 <script language="JavaScript" type="text/javascript">
		var myMenu =
		[
		['<img src="asset/javascript/jscookmenu/ThemeOffice/home.png"', '<?=($this->lang->line('home_title'));?>', 'index.php', null, 'Home'],_cmSplit,
		
<?php 
	echo $this->tbl_menu->get_Ret();
?>

	_cmSplit,['<img src="asset/javascript/jscookmenu/ThemeOffice/users.png"', 'Keluar', 'index.php/login/log_out', null, 'Log Off'],];
		cmDraw ('myMenuID', myMenu, 'hbr', cmThemeOffice, 'ThemeOffice');
		</script>
<div id="main_content" style="padding:5px">