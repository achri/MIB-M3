<br>
<div style="font-size: 10pt; min-height:365px; min-width:99%; padding: 5px; margin: -5px" class="ui-widget-content ui-corner-all">

<div align="center">
<H2>Selamat datang diprogram Material Management Module ( M3 )</H2>
<hr width="720px" size="6px" class="ui-widget-content ui-state-error">
<br><br>
<div style="padding:2px; width:500px;" class="ui-widget-header ui-corner-tr ui-corner-tl">Anda login sebagai "<span id="motivation_title"><?=$USR_NAME?></span>"</div>
<div id="user_info" style="padding:2px; width:500px;" class="ui-widget-content ui-corner-br ui-corner-bl">
    <table width="500px">
		<tr>
			<td>Anda Login pada</td>
			<td>:</td>
			<td><font color="green">IP <?=$NEWIP_LOG?></font>, <font color="red">Tgl <?=$NEWDATE_LOG?></font> <font color="darkblue">Jam <?=$NEWTIME_LOG?></font></td>
		</tr>
		<tr>
			<td>Login Terakhir</td>
			<td>:</td>
			<td><font color="green">IP <?=$LASTIP_LOG?></font>, <font color="red">Tgl <?=$LASTDATE_LOG?></font> <font color="darkblue">Jam <?=$LASTTIME_LOG?></font></td>
		</tr>
		<tr>
			<td>Logout Terakhir</td>
			<td>:</td>
			<td><font color="green">IP <?=$OFFIP_LOG?></font>, <font color="red">Tgl <?=$OFFDATE_LOG?></font> <font color="darkblue">Jam <?=$OFFTIME_LOG?></font></td>
		</tr>
		<tr>
			<td colspan="3" align="center"><div class="ui-widget-content ui-corner-all">Untuk mengubah profil anda <a href="index.php/mod_master/master_user_change/index/<?=$USR_ID?>">Klik Disini</a></div></td>
		</tr>
	</table>
</div>
<br>
<div style="padding:2px; width:500px;" class="ui-widget-header ui-corner-tl ui-corner-tr" id="motivation_title">Motivasi hari ini :</div>
<div style="padding:2px; width:500px;font-size: 15pt; color:red" class="ui-widget-content ui-corner-br ui-corner-bl" id="motivation_word">
<?=$MOTIVATION?>
</div>
</div>

</div>