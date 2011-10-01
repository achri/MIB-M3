<script language="javascript">
$(document).ready(function(){
	$('#pro_name').focus().autocomplete('index.php/<?=$link_controller?>/list_autocomplate/name',{
		minChars: 3,
		matchCase: true		
	}).result(function(event,item) {
		$('#pro_code').val(item[1]);
		$('#pro_id').val(item[2]);
	}).css('text-transform','uppercase').attr('autocomplete','off');

	$('#pro_code').autocomplete('index.php/<?=$link_controller?>/list_autocomplate/code',{
		minChars: 3,
		matchCase: true
	}).result(function(event,item) {
		$('#pro_name').val(item[1]);
		$('#pro_id').val(item[2]);
	}).css('text-transform','uppercase').attr('autocomplete','off');
	
	$('#Search').click(function(){
		var pro_id = $('#pro_id').val();
		if (pro_id != ''){
			$('#result').load('index.php/<?=$link_controller?>/get_statistik/'+pro_id,function(data){
				if (data) {
				$('#result').html(data).show();
				}
				else{
				$('#result').hide();
				}
				
			});
		}
	});
});

</script>
<h2><?=$page_title?></h2>
<div align="center" style="width:500px">
<div class="ui-widget-content ui-corner-all" style="float:left">
	<table align="center">
	<tr>
		<td><?=$this->lang->line('pro_name')?></td><td>:</td>
		<td>
		<input type="text" id="pro_name" class="pro_auto_name" name="pro_name">
		<input type="hidden" id="pro_id">
		</td>
	</tr>
	<tr>
		<td><?=$this->lang->line('pro_code')?></td><td>:</td>
		<td>
		<input type="text" id="pro_code" class="pro_auto_code pro_mask" name="pro_code">
		<input type="hidden" id="is_join">
		</td>
	</tr>
	<tr>
		<td colspan="3" align="center"><input type="button" value="<?=$this->lang->line('search')?>" id="Search">&nbsp;<input type="button" id="Reset" value="<?=$this->lang->line('clear')?>"></td>
	</tr>
	</table>
</div>
<div style="float:right" class="ui-widget-content ui-corner-all">
	<div id="result"></div>
</div>
</div>

	