<script language="javascript">
//$(document).ready(function() {
	$('#view_supp :input, ').attr('readonly',true);
//});
</script>
<table id="view_supp">
<tr>
	<td width="48%">
	<table>
	<tr>
		<td class="labelcell">Nama Pemasok</td>
		<td class="fieldcell">: <input type="text" name="idsupp" id="idsupp" maxlength="100" value="<?=$data_sup->sup_name?>"/></td>
	</tr>
	<tr>
		<td class="labelcell">Legalitas</td>
		<td class="fieldcell">: <input type="text" name="legal" id="legal" maxlength="100" value="<?=$data_sup->legal_name?>"/>
		</td>
	</tr>
	<tr>
		<td class="labelcell">NPWP</td>
		<td class="fieldcell">: <input type="text" name="npwp" id="npwp" maxlength="19" alt="npwp" value="<?=$data_sup->sup_npwp?>"/></td>
	</tr>
	<tr>
		<td class="labelcell">Alamat</td>
		<td class="fieldcell">: <textarea rows="2" cols="18" name='alamat' id='alamat'><?=$data_sup->sup_address?></textarea></td>
	</tr>
	<tr>
		<td class="labelcell">Negara</td>
		<td class="fieldcell">: <input type="text" name="negara" id="negara" maxlength="100" value="<?=$data_sup->negara_name?>"/>
		</td>
	</tr>
	<tr>
		<td class="labelcell">Provinsi</td>
		<td class="fieldcell">: <input type="text" name="provinsi" id="provinsi" maxlength="100" value="<?=$data_sup->provinsi_name?>"/>
		</td>
	</tr>
	<tr>
		<td class="labelcell">Kota</td>
		<td class="fieldcell">: <input type="text" name="kota" id="kota" maxlength="100" value="<?=$data_sup->kota_name?>"/>
		</td>
	</tr>
	</table>
	</td>
	<td width="4%"></td>
	<td width="48%" valign ="top">
	<table>
	<tr>
		<td class="labelcell">Telepon Kantor 1</td>
		<td class="fieldcell">: <input type="text" name="phone1" id="phone1" maxlength="100" value="<?=$data_sup->sup_phone1?>"/></td>
	</tr>
	<tr>
		<td class="labelcell">Telepon Kantor 2</td>
		<td class="fieldcell">: <input type="text" name="phone2" id="phone2" maxlength="100" value="<?=$data_sup->sup_phone2?>"/></td>
	</tr>
	<tr>
		<td class="labelcell">Telepon Kantor 3</td>
		<td class="fieldcell">: <input type="text" name="phone3" id="phone3" maxlength="100" value="<?=$data_sup->sup_phone3?>"/></td>
	</tr>
	<tr>
		<td class="labelcell">HP</td>
		<td class="fieldcell">: <input type="text" name="handphone" id="handphone" value="<?=$data_sup->sup_handphone?>"/></td>
	</tr>
	<tr>
		<td class="labelcell">Fax</td>
		<td class="fieldcell">: <input type="text" name="fax" id="fax" maxlength="100" value="<?=$data_sup->sup_fax?>"/></td>
	</tr>
	<tr>
		<td class="labelcell">Email</td>
		<td class="fieldcell">: <input type="text" name="email" id="email" maxlength="100" value="<?=$data_sup->sup_email?>"/></td>
	</tr>
	</table>
	</td>
</tr>
<tr><td>&nbsp;</td></tr>
<tr>
	<td colspan=3 class="labelcell">
	<div class="ui-widget-content ui-corner-all labelcell" style="border:2px solid red;padding:5px;overflow:auto">
	<i>Keterangan Non Aktif Pemasok</i> : <br><br>
	<div style="padding-left:25px"><?=$data_sup->deactive_note?></div>
	<br>
	</div>
	</td>
</tr>
</table>
<br>
<div align="center" class="ui-widget-content ui-corner-all">
<input type="button" onclick="close_sup();" value="Batal">
</div>