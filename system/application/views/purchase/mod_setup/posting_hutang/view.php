<h3><?=$page_title?></h3>


<form name="form_entry" method="post" action="index.php/<?=$link_controller?>/index" >
	<table width="100%" cellspacing="2" cellpadding="2">
	<tr>
			<td width="13%"><?=$this->lang->line('tahun');?></td>
			<td width="5%">: </td>
			<td width="82%"><select name="search_year" style="width:150px" onchange="document.form_entry.submit();">
              <option value="0" selected="selected"><?=$this->lang->line('combo_box_tahun');?></option>
              <?php 
			  $sql = "select distinct thn from prc_sys_counter";
			  foreach ($this->db->query($sql)->result() as $y):
			  ?>
              <option value="<?=$y->thn?>" <?=($search_year == $y->thn)?('SELECTED="selected"'):('')?>>
              <?=$y->thn?>
              </option>
              <?php endforeach;?>
            </select></td>
		</tr>
		<tr>
			<td width="10%"><?=$this->lang->line('bulan');?></td>
			<td width="5%">: </td>
			<td>
				<SELECT NAME="search_month">
					<option value="0"><?=$this->lang->line('combo_box_bulan');?></option>
					<!-- looping untuk bulannya -->
					<? foreach($data_bulan->result() as $row): ?>
						<option value="<?=$row->mon_num?>"><?=$row->mon_name?>
</option>
					<? endforeach;?>
					<!-- looping untuk bulannya -->
				</SELECT>
			</td>
		</tr>
		<tr>
			<td></td>
			<td></td>
			<td><INPUT TYPE="submit" name="post"  value="<?=$this->lang->line('button_post')?>"></td>
		</tr>
	</table>
</form>