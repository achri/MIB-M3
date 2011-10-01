<script language="javascript">

function open_sup(sup_id) {
	
	$('#tabs').tabs('select',0);
	var len = $('#tabs').tabs('length');
	if (len <= 1) {
		$('#tabs').tabs('add' ,'index.php/<?=$link_controller?>/appr_form/'+sup_id,'Detail Pemasok',1);
	}
	$('#tabs').tabs('select',1);
	$('#tabs').tabs('disable',0);
	return false;
}

function close_sup() {
	$('#tabs').tabs('enable',0);
	$('#tabs').tabs('select',0);
	$('#tabs').tabs('remove',1);
	return false;
}

function save_status() {
	$('#supplier_list').flexReload();
}

$(document).ready(function (){
	// Tabs
	$('#tabs').tabs();
});
</script>
<!-- Tabs -->
<h2><?=$page_title?></h2>
<div id="tabs">
	<ul>
		<li><a href="#tabs1"><span>Daftar Pemasok</span></a></li>
	</ul>
	<div id="tabs1">
		<?=$this->load->view($link_view.'/appr_desupplier_list')?>
	</div>
</div>