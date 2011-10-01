<script language="javascript">
function check_reorder(vals){
	if (vals == 0) {
		$("#min_reorder").attr("disabled","disabled").removeAttr("enabled");
	}else{
		$("#min_reorder").attr("enabled","enabled").removeAttr("disabled");
	}
};

	var button = $('.photos'), interval;
	var upload = new AjaxUpload('#getgambar',{
		//action: 'upload-test.php', // I disabled uploads in this example for security reasons
		action: 'index.php/<?=$link_controller?>/ajaxupload', 
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
		onChange: function(file, extension){
			var cat_code = $('#cat_code').val();
			var pro_name = $('#pro_name').val();
			if (cat_code == '' || pro_name == ''){
				alert ('Kategori atau Nama Produk belum di tentukan !!!');
				$('#tabs_form').tabs('select',0);
				return false;
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
			$('#photos').load('index.php/<?=$link_controller?>/show_photo/'+response);
			//alert(response);
			
			$(".photos img").tooltip({
				delay: 200,
				showURL: false,
				//track:true,
				fixPNG: true,
				extraClass:'ui-widget-header ui-corner-all',
				bodyHandler: function() {
					return $("<img/>").css({
						width: 'auto', height: '300px'
					}).attr("src", this.src);
				}
				//,top: -10,
				//left: '-100%'
			});
			
			return false;
		}
	});
	
	$(".photos img").tooltip({
		delay: 200,
		showURL: false,
		track:true,
		fixPNG: true,
		extraClass:'ui-widget-header ui-corner-all',
		bodyHandler: function() {
			return $("<img/>").css({
				width: 'auto', height: '300px'
			}).attr("src", this.src);
		}
		//,top: -10,
		//left: '-100%'
	});


<? if (isset($pro_data->row()->pro_is_reorder)):?>
check_reorder(<?=$pro_data->row()->pro_is_reorder?>);
<? endif;?>
</script>
<table width="100%"  border="0" cellpadding="2" cellspacing="2" >
	    <tr>
			 <td width="18%" valign="middle" class="labelcell"><?=$this->lang->line('pro_tipe')?></td>
			 <td width="2%" align="center" valign="middle" class="labelcell">:</td>
			 <td width="20%" valign="middle" class="fieldcell">
			  <SELECT NAME="pro_type" class="required" style="width:235px">
			  <option value="0">--Pilih Tipe--</option>
			  <option value="L" <?=(($status=='EDIT')&&($pro_data->row()->pro_type=='L'))?('SELECTED'):('')?>>Local</option>
			  <option value="I" <?=(($status=='EDIT')&&($pro_data->row()->pro_type=='I'))?('SELECTED'):('')?>>Import</option>
			  </SELECT>
		  </td>
			 <td width="18%" align="right" valign="middle" class="labelcell"><?=$this->lang->line('pro_picture')?></td>
			 <td width="2%" align="center" valign="middle" class="labelcell">:</td>
		  <td valign="middle" class="fieldcell">
		  
		  <!--INPUT TYPE="file" NAME="userfile" ID="pro_image" style="width:230px"-->
		 <!--input type="file" id="getgambar" value="0"-->
		 <input type="button" id="getgambar" value="Unggah" class="ui-corner-all ui-widget-header"> <label id="namefile"><?=($status=='EDIT')?($pro_data->row()->pro_image):('')?></label>
		 <input type="hidden" id="gambar" name="gambar" value="<?=($status=='EDIT')?($pro_data->row()->pro_image):('')?>">
		 <input type="hidden" name="gambar_awal" value="<?=($status=='EDIT')?($pro_data->row()->pro_image):('')?>">
		  
		  </td>
  </tr>
			<tr>
			 <td valign="middle" class="labelcell"><?=$this->lang->line('pro_leadtime')?></td>
			 <td align="center" valign="middle" class="labelcell">:</td>
			 <td valign="middle" class="fieldcell"><input id="pro_lead_time" name="pro_lead_time" type="text" value="<?=($status=='EDIT')?($pro_data->row()->pro_lead_time):('')?>" style="width:230px" class="number"></td>
			 <td colspan="3" rowspan="7" height="230" align="right" valign="middle">
			  	<div id="photo_prev" class="ui-corner-all ui-widget-content" align="center" style="width:85%;height:100%;overflow:auto;">			
			 	<table width="100%" height="100%" id="center_img" cellpadding="0" cellspacing="0"></tr><td align="center" valign="middle"><div id="photos" class="photos">
				<?=($status=='EDIT')?($this->pictures->thumbs($pro_data->row()->pro_id,225,225)):('')?>	 
				</div></td></tr></table>	 
			 	</div>
			 </td>
			</tr>
			<tr valign="middle">
			 <td class="labelcell"><?=$this->lang->line('pro_reorder')?></td>
			 <td align="center" class="labelcell">:</td>
			 <td class="fieldcell">
			  <!-- SELECT NAME="pro_is_reorder" onchange="check_reorder(this.value);"style="width:235px" >
			   <option value="0" <//?=(($status=='EDIT')&&($pro_data->row()->pro_is_reorder=='0'))?('SELECTED'):('')?>>Tidak</option>
			   <option value="1" <//?=(($status=='EDIT')&&($pro_data->row()->pro_is_reorder=='1'))?('SELECTED'):('')?>>Ya</option>
			  </SELECT-->
			  <small>
			  <input type="radio" checked NAME="pro_is_reorder" class="check_reorder" value="0" <?=(($status=='EDIT')&&($pro_data->row()->pro_is_reorder==0))?('checked'):('')?> onclick="check_reorder(this.value);"/>Tidak
			  <input type="radio" NAME="pro_is_reorder" class="check_reorder" value="1" <?=(($status=='EDIT')&&($pro_data->row()->pro_is_reorder==1))?('checked'):('')?> onclick="check_reorder(this.value);"/>Ya
			  </small>
			 </td>
			</tr>
			<tr valign="middle">
			  <td class="labelcell"><?=$this->lang->line('pro_minqty')?></td>
			  <td align="center" class="labelcell">:</td>
			  <td class="fieldcell"><input disabled name="pro_min_reorder" ID="min_reorder" type="text" value="<?=($status=='EDIT')?($pro_data->row()->pro_min_reorder):('')?>" style="width:230px" class="number"></td>
			</tr>
			<tr valign="middle">
			  <td class="labelcell"><?=$this->lang->line('pro_maxqty')?></td>
			  <td align="center" class="labelcell">:</td>
			  <td class="fieldcell"><!-- SELECT disabled="disabled" NAME="pro_max_type" id="max_type" style="width:65px">
			   <option value="Q" <?//=(($status=='EDIT')&&($pro_data->row()->pro_max_type=='Q'))?('SELECTED'):('')?>>Qty</option>
			   <option value="V" <?//=(($status=='EDIT')&&($pro_data->row()->pro_max_type=='V'))?('SELECTED'):('')?>>Value</option>
			  </SELECT--><input name="pro_max_reorder" ID="max_reorder" type="text" value="<?=($status=='EDIT')?($pro_data->row()->pro_max_reorder):('')?>"style="width:230px" class="number"></td>
			</tr>
			<tr valign="middle">
			  <td valign="top" class="labelcell"><?=$this->lang->line('pro_spek')?></td>
			  <td align="center" valign="top" class="labelcell">:</td>
			  <td valign="top" class="fieldcell"><TEXTAREA NAME="pro_spek" ID="pro_spek" ROWS="2" style="width:230px"><?=($status=='EDIT')?($pro_data->row()->pro_spek):('')?></TEXTAREA>
			  </td>
  </tr>
			<tr valign="middle">
			  <td valign="top" class="labelcell"><?=$this->lang->line('pro_remark')?></td>
			  <td align="center" valign="top" class="labelcell">:</td>
			  <td valign="top" class="fieldcell"><TEXTAREA NAME="pro_remark" ID="pro_remark" ROWS="2" style="width:230px"><?=($status=='EDIT')?($pro_data->row()->pro_remark):('')?></TEXTAREA>
			  </td>
  </tr>
</table>
