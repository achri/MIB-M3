<?php
class master_produk extends MY_Controller {
	public static $link_view, $link_controller;
	function master_produk() {
		parent::MY_Controller();
		$this->load->model(array('tbl_produk','tbl_category','tbl_unit','flexi_model'));
		$this->load->library(array('treeview','pro_code','flexigrid','flexi_engine','upload','image_lib','imgupload','pictures'));
		$this->load->helper(array('flexigrid','html'));
		$this->config->load('flexigrid');
		$this->config->load('tables');
		
		$this->lang->load('mod_master/produk','bahasa');
		$this->lang->load('mod_master/satuan','bahasa');
		$this->lang->load('mod_master/supplier','bahasa');
		$this->lang->load('tables','bahasa');
		$this->lang->load('general','bahasa');
		$this->lang->load('label','bahasa');
		
		$arrayCSS = array (
		'asset/css/themes/start/ui.all.css',
		//'asset/javascript/jQuery/dataTables/css/jquery.dataTables.css',
		'asset/css/table/DataView.css',
		'asset/css/form/form_view.css',
		'asset/css/master_product.css',
		'asset/javascript/jQuery/dynatree/skin-vista/ui.dynatree.css', 
		'asset/javascript/jQuery/flexigrid/css/flexigrid.css', // FLEXIGRID
		//'asset/javascript/jQuery/ajaxupload/style.css', // AJAXUPLOAD 
		'asset/javascript/jQuery/tooltip/jquery.tooltip.css',
		);
		
		$arrayJS = array (
		//'asset/javascript/jQuery/dataTables/js/jquery.dataTables.js',
		'asset/javascript/jQuery/tables/jquery.jeditable.js',
		'asset/javascript/jQuery/jquery.cookie.js',
		'asset/javascript/jQuery/dynatree/jquery.dynatree.js',
		'asset/javascript/jQuery/flexigrid/js/flexigrid.js',
		'asset/javascript/jQuery/form/jquery.form.js',
		'asset/javascript/jQuery/content/jquery.blockui.js',
		//'asset/javascript/jQuery/form/jquery.validate.js',
		//'asset/javascript/jQuery/form/jquery.validate-addon.js',
		//'asset/javascript/jQuery/form/cmxforms.js'
		'asset/javascript/jQuery/form/jquery.autoNumeric.js',
		'asset/javascript/helper/autoNumeric.js',
		'asset/javascript/jQuery/ajaxupload/ajaxupload.js',
		'asset/javascript/jQuery/tooltip/jquery.bgiframe.js',
		'asset/javascript/jQuery/tooltip/jquery.dimensions.js',
		'asset/javascript/jQuery/tooltip/jquery.tooltip.js',
		);
		
		$data['extraHeadContent'] = '';
		
		foreach ($arrayCSS as $css):
			$data['extraHeadContent'] .= '<link type="text/css" rel="stylesheet" href="'.base_url().$css.'"/>';
		endforeach;
		foreach ($arrayJS as $js):
			$data['extraHeadContent'] .= '<script type="text/javascript" src="'.base_url().$js.'"/></script>';
		endforeach;
		
		// LINK CONTROLLER DAN VIEW
		self::$link_controller = 'mod_master/master_produk';
		self::$link_view = 'purchase/mod_master/produk';
		$data['link_view'] = self::$link_view;
		$data['link_controller'] = self::$link_controller;
		
		// JUDUL HALAMAN
		$data['page_title'] = $this->lang->line('product_title');
		
		$this->load->vars($data);
	}
	
	function index() {
		$data['content'] = self::$link_view.'/master_pro_main';
		$records = $this->tbl_produk->get_product();
		if ($records->num_rows() > 0):		
			$data['js_grid'] = $this->flexigrid_builder($this->lang->line('flex_produk'),880,210,8);
		else:
			$data['js_grid'] = $this->lang->line('list_empty');
		endif;
		$this->load->view('index',$data);	
	}
	
	function flexigrid_ajax()
	{		
		$valid_fields = array('pro_id','pro_code','pro_name','pro_code','pro_status','satuan_name');
		$this->flexigrid->validate_post('pro_id','asc',$valid_fields);
		
		$sql = "select * {COUNT_STR} from prc_master_product inner join prc_master_satuan as sat on sat.satuan_id = um_id {SEARCH_STR}";
		$records = $this->flexi_model->generate_sql($sql,'pro_id',TRUE);
				
		$this->output->set_header($this->config->item('json_header'));
		
		if ($records['count'] > 0):		
		$no = 1;
			foreach ($records['result']->result() as $row)
			{
				$del = "<a alt='Delete' style='cursor:pointer' onclick='pro_del(\"".$row->pro_id."\",\"".$row->pro_name."\")'><img border='0' src='".base_url()."asset/img_source/button_empty.png'></a>";
				$pro_name = "<span width='97%' id='".$row->pro_id."' class='change_name' >".$row->pro_name."</span>";
				if ($row->pro_status == 'active'):
					$del = nbs(3);
					$pro_name = $row->pro_name;
				endif;
				$cat_name=implode(' / ',$this->pro_code->set_split_code($row->pro_code,'cat_name'));
				
				$record_items[] = array(
				$row->pro_id, // TABLE ID
				//$no,
				$row->pro_code,
				$pro_name,
				$cat_name,
				$row->satuan_name,
				($row->pro_status == 'active')?($this->lang->line('active')):($this->lang->line('nonactive')),			
				"<a style='cursor:pointer' onclick='tabs_edit(".$row->pro_id.")'><img border='0' src='".base_url()."asset/img_source/button_edit.png'></a>".
				nbs(5).$del
				
				);
				//$no++;
			}
		else: 
			//$records['count'] += 1;
			$record_items[] = array('0','null','null','empty','empty','empty','n/a');
		endif;
		
		$this->output->set_output($this->flexigrid->json_build($records['count'],$record_items));
	}
	
	function flexigrid_builder($title,$width,$height,$rp,$where='') {

		//$colModel['no'] = array($this->lang->line('no'),20,FALSE,'center',0);
		$colModel['pro_code'] = array($this->lang->line('pro_code'),80,TRUE,'center',2);
		$colModel['pro_name'] = array($this->lang->line('pro_name'),230,TRUE,'left',1,false,'flexEdit');
		$colModel['cat_id'] = array($this->lang->line('cat_id'),280, TRUE,'left',1);
		$colModel['satuan_name'] = array('Satuan',90,TRUE,'center',1);
		$colModel['pro_status'] = array($this->lang->line('pro_status'),50,TRUE,'center',1);			
		$colModel['actions'] = array($this->lang->line('action'),50, FALSE, 'center',0);		
		
		if (is_array($where)):
			$uri_array = $this->uri->assoc_to_uri($where);
			$ajax_model = site_url(self::$link_controller."/flexigrid_ajax/".$uri_array);
		else:
			$ajax_model = site_url(self::$link_controller."/flexigrid_ajax");
		endif;
		
		return build_grid_js('product_list',$ajax_model,$colModel,'pro_code','asc',$this->flexi_engine->flexi_params($width,$height,$rp,$title));
		
	}
	
	function produk_search($pro_name = '',$pro_code = '') {	
		if (empty($pro_name)):
			$like['pro_name']=$this->input->post('pro_name');
			$data['list_product']=$this->tbl_produk->get_product(false,$like);
			$this->load->view(self::$link_view.'/master_pro_search_list',$data);
		else:
			$where['pro_name']=$pro_name;
			$where['pro_code']=$pro_code;
			$get_produk = $this->tbl_produk->get_product($where);
			if ($get_produk->num_rows() > 0):
				echo 'Ada produkny';
			endif;
		endif;
	}
	
	function produk_add_search() {
		$this->load->view(self::$link_view.'/master_pro_search');			
	}

	function produk_add_tabs($stats,$id='') {
		//if ($stats=='EDIT'):
			$where = array('pro_id'=>$id);
			$data['pro_id'] = $id;
			$data['pro_data'] = $this->tbl_produk->get_product($where);
			if ($data['pro_data']->num_rows()>0):
				$pro_code = $data['pro_data']->row()->pro_code;
				$level = $this->pro_code->set_split_code($pro_code,'level');
				$parent = $this->pro_code->set_split_code($pro_code,'parent');
				//$cat_code = $this->pro_code->set_split_code($pro_code,'cat_code');
				$cat_name = $this->pro_code->set_split_code($pro_code,'cat_name');
				foreach($level as $lvl):
					$data['lvl_code'.$lvl]=$parent[$lvl];
					$data['lvl_name'.$lvl]=$cat_name[$lvl];
				endforeach;
				
				$pro_idcode = explode('.',$pro_code);
				$data['lvl_code4'] = $pro_idcode[3];
			endif;
			
		//else:
			$data['pro_name'] = $id;
			
		//endif;
		$data['status'] = $stats;
		$data['unit_data'] = $this->tbl_unit->get_unit();
		$this->load->view(self::$link_view.'/master_pro_tabs',$data);
		
	}
	
	function produk_treecat_root() {
		echo $this->treeview->generate_tree();
	}
	
	function produk_treecat_node($key) {
		echo $this->treeview->generate_tree($key);
	}
	
	function produk_node_catid($cat_id) {
		if ($cat_id!=''):
			$pro_code = $this->tbl_produk->get_cat_code($cat_id);
		endif;
		
		// JSON STRUKTUR
		$json = '[{"parent":"parent"';		
		$level = $this->pro_code->set_split_code($pro_code,'level');
		$parent = $this->pro_code->set_split_code($pro_code,'parent');
		$cat_code = $this->pro_code->set_split_code($pro_code,'cat_code');
		$cat_name = $this->pro_code->set_split_code($pro_code,'cat_name');
		foreach($level as $lvl):
			$json .= ',"lv'.$lvl.'_code":"'.$parent[$lvl].'"';
			$json .= ',"lv'.$lvl.'_name":"'.$cat_name[$lvl].'"';
			$json .= ',"lv'.$lvl.'_catcode":"'.$cat_code[$lvl].'"';
		endforeach;
		if (count($level)>=3):
		
			$like['pro_code']=$cat_code[3];
			$get_pro = $this->tbl_produk->get_product(false,$like,false,'DESC');
			if ($get_pro->num_rows()>0):
				$pro_id = substr($get_pro->row()->pro_code,9,3)+1;
				$zero='';
				if(strlen($pro_id)>=1):
					$zero='00';	
				elseif (strlen($pro_id)==2):
					$zero='0';
				endif;
				$json .= ',"pro_idcode":"'.str_pad($pro_id,3,$zero,STR_PAD_LEFT).'"';
			else:
				$json .= ',"pro_idcode":"001"';
			endif;
			
		endif;
		$json .= '}]';
		
		echo $json;
		
	}
	
	function produk_name_change() {
		$pro_id =  $this->input->post('id');
		$pro_name =  strtoupper($this->input->post('value'));
		$this->tbl_produk->pro_name_change($pro_id, $pro_name);
		echo $pro_name;
	}
	
	function produk_delete($pro_id) {
		if ($this->tbl_produk->pro_delete($pro_id)):
			echo 'DELETE SUKSES';	
		endif;
	}
	
	function upload_produk() {
		$image = 'empty';
		$pro_code = $this->input->post('pro_code');
		$img_file = $this->imgupload->upload_this($pro_code,'./uploads/produk/');
		if ($img_file != false):
			$image = $img_file;
		endif;
		echo $image;
	}
	
	function produk_insert() {
		$proses = false;
		
		$cat_code = $this->input->post('cat_code');
		
		$sql = "select max(pro_code) as get_id from prc_master_product where pro_code like '$cat_code%'";
		
		$get_pro_no = $this->db->query($sql);
		
		$pro_no = 1;
		if ($get_pro_no->num_rows() > 0):
			if ($get_pro_no->row()->get_id != ''):
				$apro_no = explode('.',$get_pro_no->row()->get_id);
				$pro_no = $apro_no[3] + 1;
			endif;
		endif;
		
		$data['pro_code'] = $cat_code.'.'.str_pad($pro_no,3,0,STR_PAD_LEFT);
		
		$data['pro_name'] = strtoupper($this->input->post('pro_name'));
		//$data['pro_status'] = $this->input->post('active');
		$data['pro_type'] = $this->input->post('pro_type');
		$data['pro_lead_time'] = $this->input->post('pro_lead_time');
		$data['pro_is_reorder'] = $this->input->post('pro_is_reorder');
		$data['pro_min_reorder'] = $this->input->post('pro_min_reorder');
		$data['pro_max_reorder'] = $this->input->post('pro_max_reorder');
		$data['pro_max_type'] = $this->input->post('pro_max_type');
		$data['cat_id'] = $this->input->post('cat_id');
		$um = explode('_',$this->input->post('um_id'));
		$data['um_id'] = $um[0];
		$data['pro_spek'] = $this->input->post('pro_spek');
		$data['pro_remark'] = $this->input->post('pro_remark');
		$data['is_stockjoin'] = $this->input->post('is_stockJoin');
		
		$data['pro_image'] = $this->input->post('gambar');
		
		$um_sub = $this->input->post('um_sub');
		
		//echo $data['pro_code'];
		
		/*
		$img_file = $this->imgupload->upload_this($data['pro_code'],'./uploads/produk/');
		if ($img_file != false):
			$data['pro_image'] = $img_file;
		endif;
		*/
		
		if ($this->tbl_produk->pro_insert($data)):
			$where['pro_code'] = $data['pro_code'];
			$pro_id = $this->tbl_produk->get_product($where)->row()->pro_id;
	
			$this->produk_um_sub($pro_id);
			$this->produk_supp($pro_id,'insert');
			$proses = true;
					
		endif;

		if ($proses) echo $data['pro_code'];
		//$pro_lock = $this->db->query("UNLOCK TABLES");
	}
	
	function produk_edit() {
		$proses = false;
		
		$pro_id = $this->input->post('pro_id');
		
		$cat_code = $this->input->post('cat_code');
		
		$cat_id = $this->input->post('cat_id');
		$cat_id_org = $this->input->post('cat_id_org');
		
		$sql = "select max(pro_code) as get_id, cat_id from prc_master_product where pro_id = $pro_id and pro_code like '$cat_code%'";
		
		$get_pro_no = $this->db->query($sql);
		
		$pro_no = 1;
		if ($get_pro_no->num_rows() > 0):
			$apro_no = explode('.',$get_pro_no->row()->get_id);
			if ($get_pro_no->row()->cat_id != $cat_id_org):
				$pro_no = $apro_no[3] + 1;
			else:
				$pro_no = $apro_no[3];
			endif;
			
		endif;
		
		$data['pro_code'] = $cat_code.'.'.str_pad($pro_no,3,0,STR_PAD_LEFT);

		$data['pro_name'] = strtoupper($this->input->post('pro_name'));
		//$data['pro_status'] = $this->input->post('active');
		$data['pro_type'] = $this->input->post('pro_type');
		$data['pro_lead_time'] = $this->input->post('pro_lead_time');
		$data['pro_is_reorder'] = $this->input->post('pro_is_reorder');
		$data['pro_min_reorder'] = $this->input->post('pro_min_reorder');
		$data['pro_max_reorder'] = $this->input->post('pro_max_reorder');
		$data['pro_max_type'] = $this->input->post('pro_max_type');
		$data['cat_id'] = $this->input->post('cat_id');
		$um = explode('_',$this->input->post('um_id'));
		$data['um_id'] = $um[0];
		$data['pro_spek'] = $this->input->post('pro_spek');
		$data['pro_remark'] = $this->input->post('pro_remark');
		$data['is_stockjoin'] = $this->input->post('is_stockJoin');
		
		if ($this->input->post('gambar') != $this->input->post('gambar_awal')):
			$data['pro_image'] = $this->input->post('gambar');
		endif;
		
		$um_sub = $this->input->post('um_sub');
		
		/*
		$img_file = $this->imgupload->upload_this($data['pro_code'],'./uploads/produk/');
		if ($img_file != false):
			$data['pro_image'] = $img_file;
		endif;
		*/
		
		if ($this->tbl_produk->pro_edit($pro_id,$data)):
			$this->produk_um_sub($pro_id);
			$this->produk_supp($pro_id,'edit');
			$proses = true;
		endif;
		
		if ($proses) echo $data['pro_code'];
		
	}
	
	function produk_supp($pro_id,$stats) {
		$return = false;
		$is_join = $this->input->post('is_stockjoin');
		if ($is_join==0):
			$sup_id = $this->input->post('sup_id');
			$sup_code = $this->input->post('sup_code');
			if ($sup_id[0] != ''):
				$where['pro_id'] = $pro_id;
				$this->tbl_produk->pro_supcat_del($where);
				for($i = 0; $i < sizeof($sup_id) ; $i++):
					//$sup_cek = $this->input->post('radio_'.$i+1);
					//if ($sup_id[$i]!='' ):
						$data_sup['pro_id'] = $pro_id;
						$data_sup['sup_id'] = $sup_id[$i];
						//if ($sup_cek == 1):
							$data_sup['sup_pro_code'] = $sup_code[$i];
						//endif;	
						$this->tbl_produk->pro_supcat_add($data_sup);
						$return = true;
					//endif;
				endfor;
			else:
				$where['pro_id'] = $pro_id;
				$this->tbl_produk->pro_supcat_del($where);
			endif;
		endif;
		return $return;
	}
	
	function produk_um_sub($pro_id) {	
		$return = false;
		$um_sub = $this->input->post('um_sub');
		$um_sub_val = $this->input->post('um_sub_val');
		if ($um_sub[0] != ''):
			$where['pro_id'] = $pro_id;
			$this->tbl_unit->delete_unit_satuan($where);
			$data_sat['pro_id'] = $pro_id;
			$data_sat['satuan_id'] = $this->input->post('um_id');
			$data_sat['satuan_unit_id'] = $this->input->post('um_id');
			$data_sat['value']	= 1;	
			$this->tbl_unit->insert_unit_satuan($data_sat);
			for ($i = 0; $i < sizeof($um_sub); $i++):
				if ($um_sub[$i]!='' && $um_sub_val[$i]!=''):
					$data_sat['pro_id'] = $pro_id;
					$um = explode('_',$this->input->post('um_id'));
					$data_sat['satuan_id'] = $um[0];
					$data_sat['satuan_unit_id'] = $um_sub[$i];
					$data_sat['value']	= $um_sub_val[$i];	
					$this->tbl_unit->insert_unit_satuan($data_sat);
					$return = true;
				endif;
			endfor;
		else:
			$where['pro_id'] = $pro_id;
			$this->tbl_unit->delete_unit_satuan($where);	
		endif;
		return $return;
	}
	
	function produk_supp_add($row_id,$cat_parent,$pro_id='0') {
		$data['row_id'] = $row_id;
		if ($cat_parent == 'notjoin'):
			$where['prc_master_supplier_product.pro_id'] = $pro_id;
			// pemasok aktif
			//$where['prc_master_supplier.sup_status > '] = 0;
			$data['sup_cat_list'] = $this->tbl_produk->get_sup_pro($where);
			$data['id_row'] = '#notjoin_row_';
		else:	
			$where['prc_master_category.cat_code'] = $cat_parent;
			// pemasok aktif
			//$where['prc_master_supplier.sup_status > '] = 0;
			$data['sup_cat_list'] = $this->tbl_produk->get_cat_sup($where);
			$data['id_row'] = '#sup_row_';
		endif;
		$this->load->view(self::$link_view.'/master_pro_supplier_list',$data);
		//echo $pro_id.'='.$data['row_id'].'='.$cat_parent;
	}
	
	function ajaxupload(){
		
		$temp_folder	= 'uploads/temp/';
		$thumb_folder	= 'uploads/produk/';
		
		$filebefore = $this->input->post('gambar');
		$filename = basename($_FILES['userfile']['name']);
		
		$ext = strrchr($filename,'.');
		
		$rand = mktime();
		$md = md5($rand);
		$filename = substr($md,rand(0,strlen($md)-10),10).$ext;
		
		if ($filebefore != '')
			@unlink($thumb_folder.$filebefore);
		
		if (@move_uploaded_file($_FILES['userfile']['tmp_name'], $thumb_folder.$filename)) {
			/*$config['source_image'] = $temp_folder.$filename;
			$config['new_image'] = $thumb_folder.$filename ;
			$this->image_lib->initialize($config);
			if ($this->image_lib->resize()):
				unlink($temp_folder.$filename);*/
				echo $filename;	
			//endif;
		} 
		
	}
	
	function show_photo($filename,$thumb_folder	= 'uploads/produk/') {
		echo $this->pictures->thumbs_ajax($filename,225,225,$thumb_folder);
	}
	
}
?>