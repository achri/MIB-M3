<h2><?=$page_title?></h2>
<div align="center">
<?php if(!isset($empty)):?>
<table width="90%"  border="0" cellpadding="0" cellspacing="2" class="ui-widget-content ui-corner-all">
	<tr bgcolor="#CCCCCC" class="ui-state-default">
		<td width="10%" align="center"><?=$this->lang->line('no')?></td>
		<td width="40%" align="center"><?=$this->lang->line('con_no')?></td>
		<td width="20%" align="center"><?=$this->lang->line('supp_name')?></td>
		<td width="20%" align="center"><?=$this->lang->line('con_date')?></td>
		<td width="10%" align="center"><?=$this->lang->line('action')?></td>
	</tr>
	<?php 
	if ($print_list->num_rows() > 0):
	$no = 1;
	foreach ($print_list->result() as $print):
	?>
	<tr bgcolor="lightgray">
	  <td valign="top" align="right" class="ui-widget-header"><?=$no?></td>
	  <td valign="top" align="center"><?=$print->con_no?></td>
	  <td valign="top" align="center"><?=$print->sup_name?></td>
	  <td valign="top" align="center"><?=$print->con_date?></td>
	  <td valign="top" align="center"><a href="index.php/<?=$link_controller?>/print_bon_view/<?=$print->con_id?>">
	  <img src="asset/img_source/magnifier.png" border="0"></a></td>
	</tr>
	<?php 
	$no++;
	endforeach;
	else:
	?>
	<tr>
	  <td colspan="7" align="center"><?=$this->lang->line('list_empty')?></td>
	</tr>
	<?php 
	endif;
	?>
	<tr bgcolor="#000000">
	  <td colspan="7"><img src="images/spacer.gif" width="1" height="1"></td>
	</tr>
</table>
<?php else: echo $empty; endif;?>
</div>