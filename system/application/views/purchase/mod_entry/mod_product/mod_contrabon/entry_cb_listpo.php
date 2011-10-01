<h3><?=$page_title?> <?=$page_title_next?></h3>
<table width="60%"  border="0" cellpadding="1" cellspacing="1" class="ui-widget-content ui-corner-all">
		<tr class="ui-widget-header">
		  <td colspan="3">Daftar PO</td>
		</tr>
		<tr class="ui-widget-header">
		  <td width="10%" align="center">No</td>
		  <td width="50%" align="center">No PO</td>
		  <td width="40%" align="center">Status PO</td>
	    </tr>
		<?php 
		if ($list_po->num_rows() > 0):
			$no = 1;
			foreach ($list_po->result() as $rows):
		?>
		<tr bgcolor="lightgray">
		  <td valign="top" align="right" class="ui-state-active"><?=$no ?>. &nbsp;</td>
		  <td valign="top" align="center"><a href="index.php/<?=$link_controller?>/list_gr/<?=$rows->po_id?>/<?=$rows->sup_id?>"><?=$rows->po_no?></a></td>
		  <td valign="top" align="center"><?=$rows->status?></td>
		</tr>
		<?php 
			$no++;
			endforeach;
		endif;
		?>
		<tr>
		  <td colspan="7">&nbsp;</td>
		</tr>
		<tr>
		  <td colspan="7" align="center"><INPUT TYPE="button" value="Kembali ke daftar supplier" onclick="location.href='index.php/<?=$link_controller?>/index'"></td>
		</tr>
</table>