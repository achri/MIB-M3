<script language="javascript">
$(document).ready(function(){
	$('#product_tree').dynatree({
		title: "<?=$this->lang->line('tree_root_category')?>",
	    rootVisible: true,
	    persist: false,
	    selectMode: 1,
	    keyboard: true,
	    autoFocus: false,
		activeVisible: true,
		//autoCollapse: true,
	    fx: { height: "toggle", duration: 200 },
	    strings: {
	    	loading: "<?=$this->lang->line('tree_loading')?>",
	        loadError: "<?=$this->lang->line('tree_error')?>"
	    },
	    onLazyRead: function(dtnode){
			dtnode.appendAjax({
				url: "index.php/<?=$link_controller?>/produk_treecat_node/"+dtnode.data.key,
				data: {
					key: dtnode.data.key,
					mode: "branch"
				}
			});
	    },
		initAjax: {
			url: "index.php/<?=$link_controller?>/produk_treecat_root",
			data: {
				key: "root",
				mode: "baseFolders"
			}
		},
		onActivate: function(dtnode) {
			level_clear();
			get_json(dtnode.data.key);
			$('#cat_id').val(dtnode.data.key);
			$('.pilih').hide();
			$('.pilihan').show();
			return false;				
		}
	});
	<?php if ($status != 'EDIT'):?>
	$('.pilihan').hide();
	$('.pilih').show();
	<?php endif;?>
	
});
</script>
<ul style="list-style:none;">
			<li>
			<?=$this->lang->line('produk_general_judul')?>
			</li>
			<li>
				<div id="treeb">
				<div id="product_tree" style="overflow: auto;height: 120px;border-color: gray;border-top-style: dotted;border-bottom-style: dotted;border-width: 1px"></div>
				</div>
			</li>
			<li>
			<hr />
			<div class="pilih" align="center" style="display:none"><small><font color="red">PILIH "KATEGORI > KELAS > GRUP" dan isi nama "PRODUK" ...</font></small></div>
			
			<table width="100%" border="1" cellpadding="0" cellspacing="0" class="table fieldCellTable pilihan ui-widget-content ui-corner-all">
			<tr class="ui-widget-header">
				<td colspan="5" align="center"><strong><?=$this->lang->line('produk_general_pilih')?></strong></td>
			</tr>
			<tr class="ui-state-default">
				<td width="5%" rowspan="2" valign="bottom" class="ui-state-active">Value</td>
				<td width="22%" align="center" valign="top" class="fieldCellCol"><span class="style1">
			    <?=$this->lang->line('produk_general_kategori')?>
				</span></td>
				<td width="22%" align="center" valign="top" class="fieldCellCol"><span class="style1">
			    <?=$this->lang->line('produk_general_kelas')?>
				</span></td>
				<td width="22%" align="center" valign="top" class="fieldCellCol"><span class="style1">
			    <?=$this->lang->line('produk_general_grup')?>
				</span></td>
				<td align="center" valign="top" class="fieldCellCol"><span class="style1">
			    <?=$this->lang->line('produk_general_detail')?>
				</span></td>
			</tr>
			
			<tr>
			  <td align="center" valign="top"><div id="lv1_name"><?=($status=='EDIT')?($lvl_name1):('')?></div></td>
			  <td align="center" valign="top"><div id="lv2_name"><?=($status=='EDIT')?($lvl_name2):('')?></div></td>
			  <td align="center" valign="top"><div id="lv3_name"><?=($status=='EDIT')?($lvl_name3):('')?></div></td>
				<td align="center" valign="top">
				<input type="hidden" name="cat_code" id="cat_code" value="<?=($status=='EDIT')?($lvl_code1.'.'.$lvl_code2.'.'.$lvl_code3):('')?>">
				<input type="hidden" name="pro_id" id="pro_id" class="required" value="<?=($status=='EDIT')?($pro_id):('')?>">
				<input style="text-transform: uppercase" id="pro_name" class="required ui-widget-content ui-corner-all" name="pro_name" type="text" value="<?=($status=='EDIT')?($pro_data->row()->pro_name):($pro_name)?>" style="width:90%">
				<input type="hidden" name="cat_id" id="cat_id" value="<?=($status=='EDIT')?($pro_data->row()->cat_id):('')?>">			  
				<input type="hidden" name="cat_id_org" id="cat_id_org" value="<?=($status=='EDIT')?($pro_data->row()->cat_id):('')?>">
				<input type="hidden" name="pro_code" id="pro_code">
				</td>
			</tr>
			
			<tr>
				<td width="5%" class="ui-state-active">Kode</td>
			  <td align="center" valign="top"><div id="lv1_code"><?=($status=='EDIT')?($lvl_code1):('')?></div></td>
			  <td align="center" valign="top"><div id="lv2_code"><?=($status=='EDIT')?($lvl_code2):('')?></div></td>
			  <td align="center" valign="top"><div id="lv3_code"><?=($status=='EDIT')?($lvl_code3):('')?></div></td>
				<td align="center" valign="top"><div id="pro_idcode"><?=($status=='EDIT')?($lvl_code4):('')?></div>
				<input type="hidden" name="pro_ids" id="pro_ids" class="required" value="<?=($status=='EDIT')?($lvl_code4):('')?>">			  </td>
			</tr>
			</table>
			
			</li>
		</ul>