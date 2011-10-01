<?php
class setup_aktivasi extends MY_Controller {
	public static $link_view, $link_controller, $link_controller_product;
	function setup_aktivasi() {
		parent::MY_Controller();
		$this->load->model(array('tbl_produk','tbl_category','tbl_unit','tbl_currency','tbl_inventory','flexi_model'));
		$this->load->library(array('treeview','pro_code','flexigrid','flexi_engine','upload','image_lib','imgupload','pictures'));
		$this->load->helper(array('flexigrid','html'));
		$this->config->load('flexigrid');
		$this->config->load('tables');
		
		$this->lang->load('mod_master/produk','bahasa');
		$this->lang->load('mod_master/aktivasi','bahasa');
		$this->lang->load('mod_master/supplier','bahasa');
		$this->lang->load('tables','bahasa');
		$this->lang->load('general','bahasa');
		$this->lang->load('label','bahasa');
		
		$arrayCSS = array (
		'asset/css/themes/start/ui.all.css',
		'asset/javascript/jQuery/dataTables/css/jquery.dataTables_q.css',
		'asset/css/table/DataView.css',
		'asset/css/form/form_view.css',
		'asset/css/product.css',
		'asset/javascript/jQuery/dynatree/skin-vista/ui.dynatree.css',
		'asset/javascript/jQuery/flexigrid/css/flexigrid.css',
		'asset/javascript/jQuery/autocomplete/jquery.autocomplete.css'
		);
		
		$arrayJS = array (
		'asset/javascript/jQuery/dynatree/jquery.dynatree.js',
		'asset/javascript/jQuery/flexigrid/js/flexigrid.js',
		'asset/javascript/jQuery/form/jquery.form.js',
		'asset/javascript/jQuery/autocomplete/jquery.autocomplete.js',
		//'asset/javascript/jQuery/form/jquery.watermark.js',
		'asset/javascript/jQuery/form/jquery.autoNumeric.js',
		'asset/javascript/helper/autoNumeric.js',
		'asset/javascript/jQuery/content/jquery.blockui.js',
		);
		
		$data['extraHeadContent'] = '';
		
		foreach ($arrayCSS as $css):
			$data['extraHeadContent'] .= '<link type="text/css" rel="stylesheet" href="'.base_url().$css.'"/>';
		endforeach;
		foreach ($arrayJS as $js):
			$data['extraHeadContent'] .= '<script type="text/javascript" src="'.base_url().$js.'"/></script>';
		endforeach;
		
		self::$link_controller = 'mod_setup/setup_aktivasi';
		self::$link_view = 'purchase/mod_setup/aktivasi';
		self::$link_controller_product = 'mod_master/master_produk';
 		$data['link_controller'] = self::$link_controller;
		$data['link_view'] = self::$link_view;
		$data['link_controller_product'] = self::$link_controller_product;
		
		$data['page_title'] = $this->lang->line('activate_qty_title');
		
		$this->load->vars($data);
	}
	
	function flexigrid_sql($code,$flexi=TRUE,$count='pro_id',$where=FALSE) {
		$sql = "select * {COUNT_STR} from prc_master_product 
		where pro_status = 'non active' and date_setup = '00-00-0000 00:00:00' ";
		if ($code!='')
			$sql .= " and pro_code like '$code%'";
		$sql .= "{SEARCH_STR}";
		return $this->flexi_model->generate_sql($sql,$count,$where,$flexi);
	}
	
	function flexigrid_ajax($cat_code = '')
	{
		$valid_fields = array('pro_id','pro_code','pro_name','is_stockJoin');
		
		$this->flexigrid->validate_post('pro_id','asc',$valid_fields);

		$records = $this->flexigrid_sql($cat_code);
		
		$this->output->set_header($this->config->item('json_header'));
		
		if ($records['count'] > 0):		
		//$no = 1;
			foreach ($records['result']->result() as $row)
			{
				if($row->is_stockJoin == 1):
					$stockJoin = $this->lang->line('is_Join');
				else:
					$stockJoin = $this->lang->line('not_Join');
				endif;
				
				$cat_name=implode('/',$this->pro_code->set_split_code($row->pro_code,'cat_name'));
				$record_items[] = array(
				$row->pro_id, // TABLE ID
				//$no,
				$row->pro_code,
				$row->pro_name,
				$stockJoin
				);
				//$no++;
			}
		else: 
			//$records['count'] += 1;
			$record_items[] = array('0','null','null','empty','empty');
		endif;
		
		$this->output->set_output($this->flexigrid->json_build($records['count'],$record_items));
	}
	
	function flexigrid_builder($title,$width,$height,$rp,$cat_code='') {
		/* FIELD
		 * 0 - display name
		 * 1 - width
		 * 2 - sortable
		 * 3 - align
		 * 4 - searchable (2 -> yes and default, 1 -> yes, 0 -> no.)
		 */
		//$colModel['no'] = array($this->lang->line('no'),20,FALSE,'center',0,false,'flexEdit');
		$colModel['pro_code'] = array($this->lang->line('pro_code'),100,TRUE,'center',1,false,'flexEdit');
		$colModel['pro_name'] = array($this->lang->line('pro_name'),290,TRUE,'left',2,false,'flexEdit');
		$colModel['is_stockJoin'] = array($this->lang->line('is_stockJoin'),70, TRUE,'center',0,false,'flexEdit');
			
		
		/* BUILD FLEXIGRID
		 * build_grid_js(<div id>,<ajax function>,<field model>,<first field selection>,<order by>,<configuration>,<button>);
		 */
		
		if ($cat_code!=''):
			$ajax_model = site_url(self::$link_controller."/flexigrid_ajax/".$cat_code);
		else:
			$ajax_model = site_url(self::$link_controller."/flexigrid_ajax");
		endif;
		
		return build_grid_js('product_list',$ajax_model,$colModel,'pro_code','asc',$this->flexi_engine->flexi_params($width,$height,$rp,$title));
		
	}
	
	function flex_get_id($pro_id) {
		$result = $this->tbl_produk->get_product(array('pro_id'=>$pro_id));
		if ($result->num_rows() > 0):
			$rep = str_replace('"',"",$result->row()->pro_name);
			echo '[{ "pro_code" : "'.$result->row()->pro_code.'","pro_name" : "'.$rep.'","pro_id" : "'.$result->row()->pro_id.'","is_join" : "'.$result->row()->is_stockJoin.'" }]';
		else:
			echo '[{ "pro_code" : "", "pro_name" : "", "pro_id" : "", "is_join" : "" }]';
		endif;
	}
	
	function index() {
		$data['content'] = self::$link_view.'/master_aktivasi_main';
		$this->load->view('index',$data);
	}
	
	function tree() {
		$this->load->view(self::$link_controller.'/master_aktivasi_tree');		
	}
	
	function inventory_list($cat_id) {
		$where_cat['cat_id'] = $cat_id;		
		if ($cat_id!='all'):
			$cat_code = $this->tbl_category->get_category($where_cat)->row()->cat_code;
				$pro_code = $this->flexigrid_sql($cat_code,false);
				if ($pro_code->num_rows() > 0):
					$data['js_grid'] = $this->flexigrid_builder($this->lang->line('flex_produk_aktivasi'),519,260,11,$cat_code);
					$this->load->view(self::$link_view.'/master_aktivasi_list',$data);
				else:
					$data['js_grid'] = br(8).$this->lang->line('list_empty');
					$this->load->view(self::$link_view.'/master_aktivasi_list',$data);
				endif;
		else:
			if ($this->flexigrid_sql('',false)->num_rows() > 0):
				$data['js_grid'] = $this->flexigrid_builder($this->lang->line('flex_produk_aktivasi'),519,260,11);
			else:
				$data['js_grid'] = br(8).$this->lang->line('list_empty');
			endif;
			$this->load->view(self::$link_view.'/master_aktivasi_list',$data);
		endif;
	}
	
	function treecat_root() {
		echo $this->treeview->generate_tree('',true);
	}
	
	function treecat_node($key) {
		echo $this->treeview->generate_tree($key);
	}
	
	function join($pro_id) {
		$pro_list = $this->tbl_produk->get_product(array('pro_id'=>$pro_id));
		$curr_list = $this->tbl_currency->get_currency();		
		$satuan_id = $pro_list->row()->um_id;
		$cat_name = $this->pro_code->set_split_code($pro_list->row()->pro_code,'cat_name');
		$data['cat_name'] = $cat_name[1].'/'.$cat_name[2].'/'.$cat_name[3];
		$data['digit_satuan'] = $this->tbl_unit->get_unit(array('satuan_id'=>$satuan_id))->row()->satuan_format; 
		$data['satuan_name'] = $this->tbl_unit->get_unit(array('satuan_id'=>$satuan_id))->row()->satuan_name;
		$data['pro_id'] = $pro_id;
		$data['pro_data'] = $pro_list;
		$data['curr_data'] = $curr_list;
		$this->load->view(self::$link_view.'/master_aktivasi_general',$data);
		//echo $satuan_id;
	}
	
	function inventory_save($stats) {
		$pro_id = $this->input->post('pro_id');
		$data['inv_document'] = $this->lang->line('setup_qty_doc');
		if ($stats=='isnotjoin'):
			$sup_id = $this->input->post('sup_id');
			$inv_tgl = $this->input->post('tgl');
			$inv_bln = $this->input->post('bln');
			$inv_thn = $this->input->post('thn');
			$inv_begin = $this->input->post('saldo');
			$inv_end = $this->input->post('saldo');
			//$inv_price = $this->input->post('inv_price');
			//$cur_id = $this->input->post('cur_id');
			
			for($i=0;$i<sizeof($sup_id);$i++):
				$sukses[$i] = true;
				$data['pro_id'] = $pro_id;
				$data['sup_id'] = $sup_id[$i];
				$data['inv_transDate'] = date_format(date_create($inv_tgl[$i]),'Y-m-d H:i:s');//;$inv_thn[$i].'-'.$inv_bln[$i].'-'.$inv_tgl[$i];
				$data['inv_begin'] = $inv_begin[$i];
				$data['inv_end'] = $inv_end[$i];
				//$data['cur_id'] = $cur_id[$i];
				//$data['inv_price'] = $inv_price[$i];
				$data['date_setup'] = date('Y-m-d H:i:s');
				//$data['bal_price'] = $inv_price[$i] * $inv_end[$i];
				// BALANCE PRICE
				//$data['bal_price'] = $data['inv_price'];

				if ($this->tbl_inventory->save_inventory($data)):
					/*
					$data_h['inv_id'] = $this->db->Insert_Id();
					$data_h['pro_id'] = $pro_id;
					$data_h['sup_id'] = $sup_id[$i];
					$data_h['inv_transDate'] = date_format(date_create($inv_tgl[$i]),'Y-m-d H:i:s');//$inv_thn[$i].'-'.$inv_bln[$i].'-'.$inv_tgl[$i];
					$data_h['inv_begin'] = $inv_begin[$i];
					$data_h['inv_end'] = $inv_end[$i];
					//$data_h['cur_id'] = $cur_id[$i];
					//$data_h['inv_price'] = $inv_price[$i];
					//$data_h['bal_price'] = $inv_price[$i] * $inv_end[$i];
					$data_h['inv_document'] = $data['inv_document'];
					// BALANCE PRICE
					//$data_h['bal_price'] = $data_h['inv_price'];

					if (!$this->tbl_inventory->save_inv_history($data_h)):*/
						//$sukses[$i] = false;
					//endif;
				else:
					$sukses[$i] = false;
				endif;
			endfor;	

			if (!in_array(false,$sukses)):
				if ($this->tbl_produk->pro_edit($data['pro_id'],array('date_setup'=>$data['date_setup']))):
					//echo 'SUKSES';
					$get_pro = $this->db->query("select * from prc_master_product where pro_id = '".$data['pro_id']."'");
					if ($get_pro->num_rows() > 0):
						echo $get_pro->row()->pro_code;
					endif;
				endif;
			else:
				$data['pro_id'] = $pro_id;
			endif;
		else:
			$data['pro_id'] = $pro_id;
			//$data['inv_price'] = $this->input->post('inv_price');
			$data['inv_transDate'] = date_format(date_create($this->input->post('inv_transDate')),'Y-m-d');
			$data['inv_begin'] = $this->input->post('saldo');
			$data['inv_end'] = $this->input->post('saldo');
			//$data['cur_id'] = $this->input->post('cur_id');
			$data['date_setup'] = date('Y-m-d H:i:s');		

			//$data['bal_price'] = $this->input->post('inv_price') * $this->input->post('saldo');
			
			// Ballance Price
			//$data['bal_price'] = $data['inv_price'];
			
			if ($this->tbl_inventory->save_inventory($data)):
				$data_h['pro_id'] = $pro_id;
				$data_h['inv_id'] = $this->db->Insert_Id();
				
				$data_h['inv_transDate'] = date_format(date_create($this->input->post('inv_transDate')),'Y-m-d');
				$data_h['inv_begin'] = $this->input->post('saldo');
				$data_h['inv_end'] = $this->input->post('saldo');
				$data_h['inv_document'] = $data['inv_document'];
				

				if ($this->tbl_produk->pro_edit($data_h['pro_id'],array('date_setup'=>$data['date_setup'])))
					//if ($this->tbl_inventory->save_inv_history($data_h))
						$get_pro = $this->db->query("select * from prc_master_product where pro_id = '".$data_h['pro_id']."'");
						if ($get_pro->num_rows() > 0):
							echo $get_pro->row()->pro_code;
						endif;
						//echo 'SUKSES';
			endif;
			//echo $data['inv_transDate'];
			
			
		endif;
	}
	
	function notjoin($pro_id){
		$sql = "select * from prc_master_supplier_product where pro_id='".$pro_id."'";
		$data['get_row_supplier'] = $this->db->query($sql);
		
		$pro_list = $this->tbl_produk->get_product(array('pro_id'=>$pro_id));
		$curr_list = $this->tbl_currency->get_currency();		
		$satuan_id = $pro_list->row()->um_id;
		$cat_name = $this->pro_code->set_split_code($pro_list->row()->pro_code,'cat_name');
		$data['cat_name'] = $cat_name[1].'/'.$cat_name[2].'/'.$cat_name[3];
		$data['digit_satuan'] = $this->tbl_unit->get_unit(array('satuan_id'=>$satuan_id))->row()->satuan_format; 
		$data['satuan_name'] = $this->tbl_unit->get_unit(array('satuan_id'=>$satuan_id))->row()->satuan_name;
		$data['pro_id'] = $pro_id;
		$data['pro_data'] = $pro_list;
		$data['curr_data'] = $curr_list;
		$this->load->view(self::$link_view.'/master_aktivasi_spesifik',$data);
	}
	
	function list_autocomplate($stats) {
		$q = strtoupper($this->input->get('q'));
		
		if ($stats == 'name')
			$like_pro['pro_name']=$q;
		else
			$like_pro['pro_code']=$q;
		
		$where_pro['pro_status']='non active';
		$where_pro['date_setup']='00-00-0000 00:00:00';
		$qres = $this->tbl_produk->get_product($where_pro,$like_pro);
		
		if ($qres->num_rows() > 0):
			foreach ($qres->result() as $rows):
					if ($stats == 'name'):
						if (strpos($rows->pro_name, $q) !== false):
							echo "$rows->pro_name|$rows->pro_code|$rows->pro_id|$rows->is_stockJoin\n";
						endif;
					else:
						if (strpos($rows->pro_code, $q) !== false):
							echo "$rows->pro_code|$rows->pro_name|$rows->pro_id|$rows->is_stockJoin\n";
						endif;
					endif;
			endforeach;
		endif;
	}
}
?>