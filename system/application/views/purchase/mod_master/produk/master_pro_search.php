<script language="javascript">
$(document).ready(function() {
	$('.form_cari').submit(function() {
		var pro_name = $('#s_name').val();
		
		if (pro_name != '') {
			$.ajax({
				type: 'POST',
				url : 'index.php/<?=$link_controller?>/produk_search',
				data: $('.form_cari').serialize(),
				success : function(data) {
					$('#produk_result').html(data);
					$('#show_result').show();
					return false;
				}	
			});	
		}
			
		return false;
	});

	$('#b_add').click(function(){	
		var pro_name = $('#s_name').val();
		pro_name = escape(pro_name);
		$('#tabs').tabs('url',1,'index.php/<?=$link_controller?>/produk_add_tabs/INSERT/'+pro_name);
		$('#tabs').tabs('load',1);
	});
});
</script>
<div id="panel_search">
	
		<form id="cari_tambah" class="form_cari">
			<table border="0" align="center" width="80%">
			<thead>
			<tr>
			<th align="right" class="labelcell">
			<?=$this->lang->line('produk_cari_nama')?></th>
			<th align="center">
					<input style="text-transform: uppercase" id="s_name" class="text ui-widget-content ui-corner-all" name="pro_name" style="width:230px" type="text" />			</th>
			<th align="left">
					<input type="submit" value="<?=$this->lang->line('produk_cari_cari')?>"/>			</th>
			</tr>
			</thead>
		  </table>
		</form>
		<div id="show_result" style="display:none">
		<hr>
		<div id="produk_result"></div>
		<hr>
		<div align="center"><input type="button" id='b_add' value="<?=$this->lang->line('produk_tambah_baru')?>"></div>
		</div>
</div>