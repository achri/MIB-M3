<script type="text/javascript">

$(document).ready(function() {

	var button = $('#photos'), interval;
	var upload = new AjaxUpload('#getgambar',{
		//action: 'upload-test.php', // I disabled uploads in this example for security reasons
		action: 'index.php/<?php echo $link_controller;?>/ajaxupload', 
		name: 'userfile',
		onSubmit : function(file, ext){
			upload.setData({'gambar': $('#gambar').val()});
			if (! (ext && /^(jpg|png|jpeg|gif)$/i.test(ext))){
                // extension is not allowed
                alert('Error: invalid file extension');
                // cancel upload
                return false;
            } else {
				// change button text, when user selects file			
				button.text('Uploading');
				
				// If you want to allow uploading only 1 file at time,
				// you can disable upload button
				this.disable();
				
				// Uploding -> Uploading. -> Uploading...
				interval = window.setInterval(function(){
					var text = button.text();
					if (text.length < 13){
						button.text(text + '.');					
					} else {
						button.text('Uploading');				
					}
				}, 200);
			}
		},
		onComplete: function(file, response){
			button.text('Upload');
						
			window.clearInterval(interval);
						
			// enable upload button
			this.enable();
			
			// input file name
			$('#gambar').val(response);	
			$('#namefile').text(response);
			
			// add file to the list
			$('#photos').load('index.php/<?php echo $link_controller;?>/show_photo/'+response);
			//alert(response);
			
			$(".photos img").tooltip({
				delay: 200,
				showURL: false,
				//track:true,
				fixPNG: true,
				extraClass:'ui-widget-header ui-corner-all',
				bodyHandler: function() {
					return $("<img/>").css({
						width: 'auto', height: 'auto'
					}).attr("src", this.src);
				}
				//,top: -10,
				//left: '-100%'
			});
			
			//return false;
		}
	});
	
	$(".photos img").tooltip({
		delay: 200,
		showURL: false,
		//track:true,
		fixPNG: true,
		extraClass:'ui-widget-header ui-corner-all',
		bodyHandler: function() {
			return $("<img/>").css({
				width: 'auto', height: 'auto'
			}).attr("src", this.src);
		}
		//,top: -10,
		//left: '-100%'
	});

	// CHECK ALL SUB PARENT IF PARENT IS CHECKED
	$('.parent_check,.sub_parent_check').click(function() {
		var checked = $(this).attr('checked');
		
		var id_parent = $(this).attr('prnt_no');
		var stats = $(this).attr('check_stats');
		
		if (checked) {
			//$(this).attr('checked',true);
			$('.check_'+stats+'_'+id_parent).attr('checked',true);
		}else {
			var count_check = $(this+':checked').length;
			if (stats == 'sub' || count_check <= 1) {
				//$(this).attr('checked',false);
				$('.check_'+stats+'_'+id_parent).attr('checked',false);
			}
		}
		
	});
		// Tabs
	$('#usertabs').tabs();
	/*$("#menutree").treeTable({
		  expandable: <?
					echo $expandable;
					?>
	});*/


// ==============bwt dialognya =======================
$("#add_shortcut_berhasil").dialog({
		autoOpen: false,
		modal: true,
		buttons: {
	'<?=$this->lang->line('jquery_button_close');?>': function() {
			$(this).dialog('close');
			
			}
		}
		});

	
	
	
	
	$("#add_shortcut").dialog({
		autoOpen: false,
		modal: true});
		
	$("#dialog_tambah").dialog({
		autoOpen: false,
		modal: true,
		buttons: {
		'<?=$this->lang->line('jquery_button_close');?>': function() {
			$(this).dialog('close');
			location.href='index.php/<?php echo $link_controller;?>/index';
			}
		}
		});
		
$("#dialog_data_duplikat").dialog({
	autoOpen: false,
	modal: true,
	buttons: {
	'<?=$this->lang->line('jquery_button_close');?>': function() {
		$(this).dialog('close');
	}
}
});


		
$("#dialog_kosong").dialog({
	autoOpen: false,
	modal: true,
	buttons: {
	'<?=$this->lang->line('jquery_button_close');?>': function() {
		$(this).dialog('close');
	}
}
});

$("#dialog_batal").dialog({
	autoOpen: false,
	modal: true,
	buttons: {		
	
	'<?=$this->lang->line('jquery_button_kembali');?>': function() {			
		$(this).dialog('close');			

	},
	'<?=$this->lang->line('jquery_button_cancel');?>': function() {
		$(this).dialog('close');
		location.href='index.php/<?=($log_ids != '')?(''):($link_controller)?>';
	}
}
});


$("#dialog_rubah").dialog({
	autoOpen: false,
	modal: true,
	buttons: {
'<?=$this->lang->line('jquery_button_close');?>': function() {
		$(this).dialog('close');
		location.href='index.php/<?=($log_ids != '')?(''):($link_controller)?>';
		}
	}
	});

//==============akhir dialognya =======================
	
		$('#user_frm').submit(function(){
		
			$('.saving').attr('disabled',true);
			$('.dialog_konfirmasi').dialog({
				title:'KONFIRMASI',
				autoOpen: false,
				bgiframe: true,
				width: 'auto',
				height: 'auto',
				resizable: false,
				//draggable: false,
				modal:true,
				position:['right','top'],
				buttons : { 
					'<?=$this->lang->line('back')?>' : function() {
						$('.saving').attr('disabled',false);
						$('#tabs').attr('enable',0);
						$(this).dialog('close');
					},
					'<?=$this->lang->line('ok')?>' : function() {
						$(this).dialog('close');
						
						$.ajax({
							type: 'POST',
							url: '<?=$action?>',
							data: $('#user_frm').serialize(),
							cache: false,
							success: function(data) {
								if (data == 'sukses'){
									if ('<?=$proses?>' == 'Edit'){
										$('#dialog_rubah').dialog('open');
									}else{
										$('#dialog_tambah').dialog('open');
									}			
								}else{
									
									if (data == 'ada'){
										$('#dialog_data_duplikat').dialog('open');	
									}else{
										$('#dialog_kosong').dialog('open');			
										$('#user_kosong').text(data);
									}
								}	
								$('.saving').attr('disabled',false);
							}
						});
						
					}
				}
			}).html('').html('<?=$this->lang->line('confirm')?>').dialog('open');
			
			return false;
		});
		
	function limit_access() {
		$('#logid').attr('readonly',true);
		$('.log_akses').hide();
		$('#usertabs').data('disabled.tabs',[1]);
	}
	
	<? if ($log_ids != ''): ?>
		limit_access();
	<? endif; ?>
			
});


function setpass(obj){
	if(obj.value == "unreset"){
		document.forms["user_frm"].pas1.disabled=false;
		document.forms["user_frm"].pas2.disabled=false;
		document.forms["user_frm"].lihat.disabled=false;
		document.forms["user_frm"].reset.value= 'reset';
		document.forms["user_frm"].pas1.value='';
		document.forms["user_frm"].pas2.value='';
	}else{
		document.forms["user_frm"].pas1.disabled=true;
		document.forms["user_frm"].pas2.disabled=true;
		document.forms["user_frm"].lihat.disabled=true;
		document.forms["user_frm"].reset.value= 'unreset';
		document.forms["user_frm"].pas1.value='*****';
		document.forms["user_frm"].pas2.value='*****';
	}
}

function setpass2(obj){
	if(obj.value == "sembunyikan"){		
		document.forms["user_frm"].lihat.value= 'lihat';
		document.forms["user_frm"].pas1.type='text';
		document.forms["user_frm"].pas2.type='text';
	}else{		
		document.forms["user_frm"].lihat.value= 'sembunyikan';
		document.forms["user_frm"].pas1.type='password';
		document.forms["user_frm"].pas2.type='password';
	}
}


function batal(){
	$('#dialog_batal').dialog('open');			
}

//============== shortcut add departemen ===============
function add_dep(){
	$.ajax({
		type: 'POST',
		url: 'index.php/<?php echo $link_controller_departement;?>/dep_frm',
		data: $(this).serialize(),
		success: function(data) {
			$('#frmview').html(data);
			$('#stat').val('2');
			$('#add_shortcut').dialog('open');
	}
	});
	return false;
}

//============== shortcut add jabatan ===============
function add_jab(){
	$.ajax({
		type: 'POST',
		url: 'index.php/<?php echo $link_controller_jabatan;?>/jabatan_frm',
		data: $(this).serialize(),
		success: function(data) {
			$('#frmview').html(data);
			$('#stat').val('2');
			$('#add_shortcut').dialog('open');
	}
	});
	return false;
}
	
</script>

<?php 
if ($proses == 'Edit'){
	$usr = $list_user->row();
	
		$dep = $this->tbl_departemen->get_departemen($usr->dep_id)->row();
		$jab = $this->tbl_jabatan->get_jabatan($usr->ttl_id)->row();
}

?>
<form id="user_frm">	
<input type="hidden" name="usrid" id="usrid" maxlength="100" value="<?php if (isset($usr->usr_id)){ echo $usr->usr_id; } ?>"/>
<input type="hidden" name="usrimage" id="usrimage" maxlength="100" value="<?php if (isset($usr->usr_image)){ echo $usr->usr_image; } ?>"/>	
		<table>
				<tr>
					<td width="48%">
					<table>
						<tr>
							<td class="labelcell"><?php echo ($this->lang->line('user_input_id')); ?></td>
							<td class="fieldcell">: <input type="text" name="logid" id="logid" maxlength="100" value="<?php if (isset($usr->usr_login)){ echo $usr->usr_login; } ?>"/></td>
						</tr>
						<tr>
							<td class="labelcell"><?php echo ($this->lang->line('user_input_nama')); ?></td>
							<td class="fieldcell">: <input type="text" name="nama" id="nama" maxlength="100" value="<?php if (isset($usr->usr_name)){ echo $usr->usr_name; } ?>"/></td>
						</tr>
					</table>
					</td>
					<td width="4%"></td>
					<td width="48%" valign ="top">
					<table class="log_akses">
						<tr>
							<td class="labelcell"><?php echo ($this->lang->line('user_input_dep')); ?></td>
							<td class="fieldcell">: 
							<select name="departemen" id="deplist"><option value="<?php if (isset($usr->dep_id)){ echo $usr->dep_id; } ?>"><?php if (isset($usr->dep_id)){ echo $dep->dep_name; }else{ echo $this->lang->line('combo_box_departemen'); } ?></option>
								<?php 
								if ($list_dep->num_rows() > 0):
								foreach ($list_dep->result() as $dep): 
									echo "<option value='$dep->dep_id'>$dep->dep_name</option>";
								endforeach;
								else:
									echo $this->lang->line('data_empty'); // klo kosong ga data
								endif;
								?>
							</select>
							<a href="javascript:void(0)" onclick="add_dep()"><img border="0" src="./asset/img_source/add1.gif"></a>
							</td>
						</tr>
						<tr> 
							<td class="labelcell"><?php echo ($this->lang->line('user_input_jab')); ?></td>
							<td class="fieldcell">: <select name="jabatan" id="jablist">
							<option value="<?php if (isset($usr->ttl_id)){ echo $usr->ttl_id; } ?>"><?php if (isset($usr->ttl_id)){ echo $jab->jab_name; }else{ echo $this->lang->line('combo_box_jabatan'); } ?></option>
								<?php 
								if ($list_jab->num_rows() > 0):
								foreach ($list_jab->result() as $jab): 
									echo "<option value='$jab->jab_id'>$jab->jab_name</option>";
								endforeach;
								else:
									echo $this->lang->line('data_empty'); // klo kosong ga data
								endif;
								?>
							</select>
							<a href="javascript:void(0)" onclick="add_jab()"><img border="0" src="./asset/img_source/add1.gif"></a>
							</td>
						</tr>
					</table>
					</td>
				<tr>
			</table>
			<br>
		<!-- Tabs -->
		<div id="usertabs">
			<ul>
				<li><a href="#general"><?php echo ($this->lang->line('tab_umum')); ?></a></li>
				<li><a href="#wewenang"><?php echo ($this->lang->line('user_tab_wewenang')); ?></a></li>
			</ul>
			<div id="general">
				<table><tr><td valign="top">
					<table width="500px" border='0' >
					<?php 
						if (isset($usr->usr_login)){
							// bwt ngereset password 
							echo "<tr>
								<td class='labelcell'>".$this->lang->line('user_input_reset_password')."</td>
								<td class='fieldcell' colspan='2'>: <input type='checkbox' name='reset' id='reset' value='unreset' onClick='setpass(this)' /></td>
								</tr>"; 

							// bwt ngeliatin password
							echo "<tr>
								<td class='labelcell'>".$this->lang->line('user_input_lihat_password')."</td>
								<td class='fieldcell' colspan='2'>: <input type='checkbox' name='lihat' id='lihat' value='sembunyikan' onClick='setpass2(this)' disabled=true/></td>
								</tr>";
						}
						else{ 
							// bwt ngeliatin password
							echo "<tr>
									<td class='labelcell'>".$this->lang->line('user_input_lihat_password')."</td>
								<td class='fieldcell' colspan='2'>: <input type='checkbox' name='lihat' id='lihat' value='sembunyikan' onClick='setpass2(this)' /></td>
								</tr>"; 
						}
					?>
						<tr>
							<td class="labelcell" width="40%"><?php echo ($this->lang->line('user_input_pas1')); ?></td>
							<td class="fieldcell">: <input type="password" name="pas1" id="pas1" maxlength="100" value="<?php if (isset($usr->usr_login)){ echo "*****"; }?>" <?php if (isset($usr->usr_login)){ echo "disabled"; }?> /></td>
						</tr>
						<tr>
							<td class="labelcell"><?php echo ($this->lang->line('user_input_pas2')); ?></td>
							<td class="fieldcell">: <input type="password" name="pas2" id="pas2" maxlength="100" value="<?php if (isset($usr->usr_login)){ echo "*****"; }?>" <?php if (isset($usr->usr_login)){ echo "disabled"; }?> /></td>
						</tr>
						<tr>
							<td class="labelcell"><?php echo ($this->lang->line('user_input_photo')); ?></td>
							<td class="fieldcell">: 
							<input type="button" id="getgambar" value="Unggah" class="ui-corner-all ui-widget-header"> <label id="namefile"><?=($proses=='Edit')?($usr->usr_image):('')?></label>
							<input type="hidden" id="gambar" name="usrimage" value="<?=($proses=='Edit')?($usr->usr_image):('')?>">
							<input type="hidden" name="usrimage_awal" value="<?=($proses=='Edit')?($usr->usr_image):('')?>">
							<!--input type="file" name="usrimg" /--></td>
						</tr>
					<!--?php 
						if (isset($usr->usr_login)){ 
							if ($usr->usr_image == ''){
								$usr->usr_image = 'nobody.gif';
							}
							echo "<tr>
								<td class='labelcell'></td>
								<td class='fieldcell' colspan='2'>&nbsp; &nbsp;<img src='".base_url()."uploads/img/".$usr->usr_image."'></td>
								</tr>"; 
						}
					?-->
					</table>
				</td>
				<td valign="middle" align="center">
					<table border='0' width="120px" height="120px" cellpadding="0" cellspacing="0">
					<tr>
					<td>
					<div id="photo_prev" class="ui-corner-all ui-widget-content" align="center" style="width:120px;height:120px;overflow:auto;">			
						<table width="100%" height="100%" id="center_img" cellpadding="0" cellspacing="0"></tr><td align="center" valign="middle"><div id="photos" class="photos"><?=($proses=='Edit')?($this->pictures->thumbs_ajax($usr->usr_image,110,110,'./uploads/user/')):('&nbsp;')?></div></td></tr></table>	 
					</div>
					</td>
					</tr>
					</table>
				</td>
				</tr></table>
			</div>
			<div id="wewenang" style="height: 200px; overflow: auto;">		
				<?php
				$set_menu = '';
				$data['usrid'] = '';
				if (isset($usr->usr_id)){
					$data['usrid'] = $usr->usr_id;
				}
				$this->load->view($link_view.'/user_menu',$data);
				if (isset($usr->usr_id)){
					$get_menu = array();
					$sql_u = "select * from prc_sys_user_menu where usr_id = '".$usr->usr_id."'";
					$get_u = $this->db->query($sql_u);
					if ($get_u->num_rows() > 0):
						foreach ($get_u->result() as $row)
							$get_menu[] = $row->menu_id;
						$set_menu = implode(',',$get_menu);
					endif;
				}
				else {
					$set_menu = '';
				}
				?>
				<input type="hidden" id="menu" name="menu" value="<?=$set_menu?>">
			</div>
		</div>
<center>

<input class="saving" type="submit" value="
<?php
if (isset($usr->usr_id)){
	echo $this->lang->line('user_button_update');
}else{
	echo $this->lang->line('user_button_submit');
} 
?>"/>
<?php 
if (isset($usr->usr_id)){
	echo "<input type='button' value='".$this->lang->line('user_button_cancel')."' Onclick='batal()'/>";
}
?>
</center>
</form>

<!-- ========= bwt dialognya ======= -->

<div id="dialog_kosong" title="<?=($this->lang->line('jquery_dialog_peringatan'));?>">
	 <p id="user_kosong" > </p>
</div>

<div id="dialog_data_duplikat" title="<?=($this->lang->line('jquery_dialog_peringatan'));?>">
	<?=($this->lang->line('user_input_error_duplikat'));?> 
</div>

<div id="dialog_tambah" title="<?=($this->lang->line('jquery_dialog_konfirmasi'));?>">
	<?php echo ($this->lang->line('user_tmbh_judul_input'));?> <!-- <FONT COLOR="red"><b><p id="dep_nama"> </p> </b></FONT>  --><?php echo ($this->lang->line('jquery_dialog_tambah_berhasil'));?>
</div>
<div id="dialog_batal" title="<?=($this->lang->line('jquery_dialog_peringatan'));?>">
	<?php echo ($this->lang->line('ajax_batal_rubah'));?> 
</div>


<div id="dialog_rubah" title="<?=($this->lang->line('jquery_dialog_konfirmasi'));?>">
	<?php echo ($this->lang->line('user_tmbh_judul_input'));?> <!-- <FONT COLOR="red"><b><p id="name"> </p> </b></FONT> --><?php echo ($this->lang->line('jquery_dialog_rubah_berhasil'));?>
</div>

<div id="add_shortcut" title="<?=($this->lang->line('jquery_dialog_shortcut'));?>">
	<div id="frmview"></div>
</div>

<div id="add_shortcut_berhasil" title="<?=($this->lang->line('jquery_dialog_konfirmasi'));?>">
	<div id="shortcut_behasil"></div>
</div>