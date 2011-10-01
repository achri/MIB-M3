<?php
class setup_adjustment extends MY_Controller {
	public static $link_view, $link_controller, $link_controller_product, $user_id;
	function setup_adjustment() {
		parent::MY_Controller();
		$this->load->model(array('tbl_sys_counter','tbl_produk','tbl_category','tbl_unit','tbl_currency','tbl_adjustment','flexi_model'));
		$this->load->library(array('general','treeview','pro_code','flexigrid','flexi_engine','general'));
		$this->load->helper(array('flexigrid','html'));
		$this->config->load('flexigrid');
		$this->config->load('tables');
		
		$this->lang->load('mod_master/adjustment','bahasa');
		$this->lang->load('mod_master/produk','bahasa');
		$this->lang->load('mod_master/supplier','bahasa');
		//$this->lang->load('mod_master/adjustment','bahasa');
		$this->lang->load('general','bahasa');
		$this->lang->load('tables','bahasa');
		$this->lang->load('label','bahasa');
		
		$arrayCSS = array (
		'asset/css/themes/start/ui.all.css',
		'asset/javascript/jQuery/dataTables/css/jquery.dataTables_q.css',
		'asset/css/table/DataView.css',
		'asset/css/form/form_view.css',
		'asset/css/product.css',
		'asset/javascript/jQuery/dynatree/skin-vista/ui.dynatree.css',
		'asset/javascript/jQuery/flexigrid/css/flexigrid.css',
		'asset/javascript/jQuery/autocomplete/jquery.autocomplete.css',
		'asset/css/table/DataView.css'
		);
		
		$arrayJS = array (
		'asset/javascript/jQuery/dataTables/js/jquery.dataTables.js',
		'asset/javascript/jQuery/tables/jquery.jeditable.js',
		'asset/javascript/jQuery/jquery.cookie.js',
		'asset/javascript/jQuery/dynatree/jquery.dynatree.js',
		'asset/javascript/jQuery/flexigrid/js/flexigrid.js',
		'asset/javascript/jQuery/form/jquery.form.js',
		'asset/javascript/jQuery/form/jquery.maskedinput.js',
		'asset/javascript/jQuery/form/jquery.validate.js',
		'asset/javascript/jQuery/form/jquery.validate-addon.js',
		'asset/javascript/jQuery/form/cmxforms.js',
		'asset/javascript/jQuery/form/loader.js',
		'asset/javascript/jQuery/autocomplete/lib/jquery.bgiframe.min.js',
		'asset/javascript/jQuery/autocomplete/lib/jquery.ajaxQueue.js',
		'asset/javascript/jQuery/autocomplete/lin/thickbox-compressed.js',
		'asset/javascript/jQuery/autocomplete/jquery.autocomplete.js',
		'asset/javascript/jQuery/form/jquery.watermark.js',
		);
		
		$data['extraHeadContent'] = '';
		
		foreach ($arrayCSS as $css):
			$data['extraHeadContent'] .= '<link type="text/css" rel="stylesheet" href="'.base_url().$css.'"/>';
		endforeach;
		foreach ($arrayJS as $js):
			$data['extraHeadContent'] .= '<script type="text/javascript" src="'.base_url().$js.'"/></script>';
		endforeach;
		
		self::$link_controller = 'mod_setup/setup_adjustment';
		self::$link_view = 'purchase/mod_setup/adjustment';
		self::$link_controller_product = 'mod_master/master_produk';
 		$data['link_controller'] = self::$link_controller;
		$data['link_view'] = self::$link_view;
		$data['link_controller_product'] = self::$link_controller_product;
		
		// THIS SESSION USER LOGIN
		self::$user_id = $this->session->userdata("usr_id");
		
		$data['page_title'] = $this->lang->line('adjustment_title');
		
		$this->load->vars($data);
	}
	
	// QUERY UNTUK NON FLEXIGRID ATO FLEXIGRID
	function flexigrid_sql($sql,$flexi=TRUE,$count='pro.pro_id',$where=FALSE) {
		return $this->flexi_model->generate_sql($sql,$count,$where,$flexi);
	}
	
	function flexigrid_ajax($cat_code='')
	{		
		$this->flexigrid->validate_post('pro.pro_id','asc');//,$valid_fields);
		$sql = "select distinct * {COUNT_STR} 
        from prc_master_product as pro 
        inner join prc_inventory as inv on pro.pro_id = inv.pro_id 
		inner join prc_master_satuan as sat on sat.satuan_id = pro.um_id 
        where pro.pro_status = 'active' {SEARCH_STR} group by pro.pro_code";
		if ($cat_code != '')
			$sql .= '';
		$records = $this->flexigrid_sql($sql);
		
		$this->output->set_header($this->config->item('json_header'));
		
		if ($records['count'] > 0):		
			foreach ($records['result']->result() as $row)
			{
				$cat_name=implode('/',$this->pro_code->set_split_code($row->pro_code,'cat_name'));
				$record_items[] = array(
				$row->pro_id, // TABLE ID
				$row->pro_code,
				$row->pro_name,
				"<div style='width:100%;padding:0px'>
				<div style='float:left;padding:0px'>".$row->satuan_name."</div>
				<div style='float:right;padding:0px'>".number_format($row->inv_end,$row->satuan_format)."</div>
				</div>",
				);
			}
		else: 
			$record_items[] = array('0','null','null','empty','empty');
		endif;
		
		$this->output->set_output($this->flexigrid->json_build($records['count'],$record_items));
	}
	
	function flexigrid_builder($title,$width,$height,$rp,$pro_code='') {
		/* FIELD
		 * 0 - display name
		 * 1 - width
		 * 2 - sortable
		 * 3 - align
		 * 4 - searchable (2 -> yes and default, 1 -> yes, 0 -> no.)
		 */
		//$colModel['no'] = array($this->lang->line('no'),20,FALSE,'center',0,false,'flexEdit');
		$colModel['pro_code'] = array($this->lang->line('pro_code'),100,TRUE,'center',1,false,'flexEdit');
		$colModel['pro_name'] = array($this->lang->line('pro_name'),250,TRUE,'left',2,false,'flexEdit');
		$colModel['inv_end'] = array('Stok akhir',120,TRUE,'left',1,false,'flexEdit');
		//$colModel['is_stockJoin'] = array($this->lang->line('is_stockJoin'),90, TRUE,'center',0,false,'flexEdit');
			
		
		/* BUILD FLEXIGRID
		 * build_grid_js(<div id>,<ajax function>,<field model>,<first field selection>,<order by>,<configuration>,<button>);
		 */
		
		if ($pro_code != ''):
			$ajax_model = site_url(self::$link_controller."/flexigrid_ajax/".$pro_code);
		else:
			$ajax_model = site_url(self::$link_controller."/flexigrid_ajax");
		endif;
		
		return build_grid_js('product_list',$ajax_model,$colModel,'pro_code','asc',$this->flexi_engine->flexi_params($width,$height,$rp,$title));
		
	}
	
	function flex_get_id($pro_id) {
		$result = $this->tbl_produk->get_product(array('pro_id'=>$pro_id));
		if ($result->num_rows() > 0):
			echo '[{ "pro_code" : "'.$result->row()->pro_code.'","pro_name" : "'.$result->row()->pro_name.'","pro_id" : "'.$result->row()->pro_id.'","is_join" : "'.$result->row()->is_stockJoin.'" }]';
		else:
			echo '[{ "pro_code" : "", "pro_name" : "", "pro_id" : "", "is_join" : "" }]';
		endif;
	}
	
	function index() {
		$data['content'] = self::$link_view.'/master_adj_main';
		$this->load->view('index',$data);
	}
	
	function tree() {
		$this->load->view(self::$link_controller.'/master_adj_tree');		
	}
	
	function inventory_list($cat_id) {
		$where_cat['cat_id'] = $cat_id;		
		if ($cat_id!='all'):
			$cat_code = $this->tbl_category->get_category($where_cat)->row()->cat_code;
				$where_pro['pro_status'] = 'non active';
				$like_pro['pro_code'] = $cat_code;
				$pro_code = $this->tbl_produk->get_product($where_pro,$like_pro);
				if ($pro_code->num_rows() > 0):
					$data['js_grid'] = $this->flexigrid_builder($this->lang->line('flex_produk'),513,260,11,$cat_code);
					$this->load->view(self::$link_view.'/master_adj_list',$data);
				else:
					$data['js_grid'] = br(8).$this->lang->line('list_empty');
					$this->load->view(self::$link_view.'/master_adj_list',$data);
				endif;
		else:
			if ($this->tbl_produk->get_product()->num_rows() > 0):
				$data['js_grid'] = $this->flexigrid_builder($this->lang->line('flex_produk'),513,260,11);
			else:
				$data['js_grid'] = br(8).$this->lang->line('list_empty');
			endif;
			$this->load->view(self::$link_view.'/master_adj_list',$data);
		endif;
	}
	
	function treecat_root() {
		echo $this->treeview->generate_tree('',true);
	}
	
	function treecat_node($key) {
		echo $this->treeview->generate_tree($key);
	}
	
	function saveAdjustment($stats,$pro_id) {
		$session = $this->session->userdata('sess_prmr_no');
		$usr_id = $this->session->userdata('usr_id');
		$proses = false;
		
		// INSERT ADJUSTMENT
		$where_adj['adj_no'] = $session;
		$get_adj = $this->tbl_adjustment->get_adj($where_adj);
		if ($get_adj->num_rows() < 1):
			$insert_adj['adj_requestor'] = $usr_id;
			$insert_adj['adj_no'] = $session;
			$this->tbl_adjustment->insert_adj($insert_adj);
		endif;
						
		// INSERT ADJUSTMENT DETAIL
		$where_adj['adj_no'] = $session;
		$get_adj_det = $this->tbl_adjustment->get_adj($where_adj);
		if ($get_adj_det->num_rows() > 0):
			// CHECK SUPPLIER			
			if ($stats == 'spesifik'):
				$sup_id = $this->input->post('sup_id');
				for ($i = 0; $i < sizeOf($sup_id); $i++):
					$insert_adj_det['adj_id'] = $get_adj_det->row()->adj_id;
					$insert_adj_det['pro_id'] = $pro_id;
					$insert_adj_det['sup_id'] = $sup_id[$i];
					if ($this->tbl_adjustment->get_adj_detail($insert_adj_det)->num_rows() < 1):
						if ($this->tbl_adjustment->insert_adj_detail($insert_adj_det)):
							$proses = true;
						endif;
					endif;
				endfor;
			else:
				$insert_adj_det['adj_id'] = $get_adj_det->row()->adj_id;
				$insert_adj_det['pro_id'] = $pro_id;
				if ($this->tbl_adjustment->get_adj_detail($insert_adj_det)->num_rows() < 1):
					if ($this->tbl_adjustment->insert_adj_detail($insert_adj_det)):
						$proses = true;
					endif;
				endif;
			endif;
		endif;
		
		if ($proses==TRUE) echo 'sukses'; 
	}
	
	function listAdjustment() {
		$session = $this->session->userdata('sess_prmr_no');
		
		$adj_list = $this->db->query("select distinct pro.pro_id, pro.pro_code, pro.pro_name, 
		pro.um_id, sat.satuan_id, sat.satuan_name, pro.is_stockJoin, adj.adj_id
		from prc_adjustment_detail as d 
        inner join prc_adjustment as adj on d.adj_id = adj.adj_id
		inner join prc_master_product as pro on d.pro_id = pro.pro_id 
		inner join prc_master_satuan as sat on pro.um_id = sat.satuan_id 
		where adj.adj_no='".$session."' order by d.pro_id");
		$data['adj_list'] = $adj_list;
		$data['adj_id'] = $adj_list->row()->adj_id;
		$this->load->view(self::$link_view.'/master_adj_request',$data);
		
	}
	
	function cek_tabs() {
		$session = $this->session->userdata('sess_prmr_no');
		
		$where_adj['adj_no'] = $session;
		$get_adj = $this->tbl_adjustment->get_adj($where_adj);
		if ($get_adj->num_rows() > 0):
			$where_adj_det['adj_id'] = $get_adj->row()->adj_id;
			if ($this->tbl_adjustment->get_adj_detail($where_adj_det)->num_rows() > 0)
				echo 'Ada';
		endif;
		
	}
	
	function buatAdjustment() {
		// CREATE COUNTER
		$thn = date('Y');
		$thn_min = date('y');
		$bln = date('n');
		$bln_max = date('m');
		
		// CEK COUNTER
		$where_sys['thn'] = $thn;
		$where_sys['bln'] = $bln;
		$get_counter = $this->tbl_sys_counter->get_counter($where_sys);
		if ($get_counter->num_rows() > 0):
			$adj_no = $get_counter->row()->adj_no;
		else:
			$this->tbl_sys_counter->insert_counter($where_sys);
			$adj_no = 1;
		endif;
		
		// SET ADJUSTMENT NUMBER
		$pad_no = str_pad($adj_no, 5, "0", STR_PAD_LEFT);
		// ADJUSTMENT NUMBER
		$adj_doc_no = $this->lang->line('adj_doc_no');
		$set_adj_no = $thn_min.'/'.$bln_max.'/'.$adj_doc_no.$pad_no; 
		
		// MASTER VARIABLE
		$session = $this->session->userdata('sess_prmr_no');
		$adj_id = $this->input->post('adj_id');
		$pro_id = $this->input->post('pro_id');
		$is_join = $this->input->post('join');
		$proses = false;
		$cek_data = array();
		
		$sup_id = $this->input->post('sup_id');
		$qty = $this->input->post('qty');
		$qty_opname = $this->input->post('qty_opname');
		$tgl = $this->input->post('tgl');
		$cek_opname = $this->input->post('cek_opname');
		$alasan = $this->input->post('alasan');
		
		for ($i = 0;$i < sizeof($pro_id);$i++):
			// KARTU STOK GENERAL
			if ($is_join[$i] == '1'):
				//echo $i.'.'.$adj_id.' | '.$pro_id[$i].' | '.$is_join[$i].' | '.$qty[$i].' | '.$qty_opname[$i].' | '.$tgl[$i].' | '.$cek_opname[$i].' | '.$alasan[$i].'<br>';
				
				// UPDATE ADJUSTMENT DETAIL
				
				$adj_join['qty_stock'] = $qty[$i];
				$adj_join['qty_opname'] = $qty_opname[$i];
				$adj_join['date_opname'] = date_format(date_create($tgl[$i]),'Y-m-d');
				$adj_join['check_opname'] = $cek_opname[$i];
				$adj_join['description'] = $alasan[$i];
				$adj_join_where['adj_id'] = $adj_id;
				$adj_join_where['pro_id'] = $pro_id[$i];
					
				if ($this->tbl_adjustment->update_adj_detail($adj_join_where,$adj_join))
					$cek_data[$i] = 1;
				else
					$cek_data[$i] = 0;
			
			// KARTU STOK SPESIFIK
			else:
				for ($ii = 0;$ii < sizeof($sup_id[$i]);$ii++):
					//echo $i.'.'.$ii.'. '.$adj_id.' | '.$pro_id[$i].' | '.$is_join[$i].' | '.$sup_id[$i][$ii].' | '.$qty[$i][$ii].' | '.$qty_opname[$i][$ii].' | '.$tgl[$i][$ii].' | '.$cek_opname[$i][$ii].' | '.$alasan[$i][$ii].'<br>';

					// UPDATE ADJUSTMENT DETAIL
					
					$adj_det_njoin['qty_stock'] = $qty[$i][$ii];
					$adj_det_njoin['qty_opname'] = $qty_opname[$i][$ii];
					$adj_det_njoin['date_opname'] = date_format(date_create($tgl[$i][$ii]),'Y-m-d');
					$adj_det_njoin['check_opname'] = $cek_opname[$i][$ii];
					$adj_det_njoin['description'] = $alasan[$i][$ii];
					$adj_det_njoin_where['adj_id'] = $adj_id;
					$adj_det_njoin_where['pro_id'] = $pro_id[$i];
					$adj_det_njoin_where['sup_id'] = $sup_id[$i][$ii];
					
					if($this->tbl_adjustment->update_adj_detail($adj_det_njoin_where,$adj_det_njoin))
						$cek_data[$i] = 1;
					else
						$cek_data[$i] = 0;
					
				endfor;
			endif;
		endfor;
		
		// UPDATE ADJUSTMENT
		if (!in_array(0,$cek_data)):
			$adj_where['adj_id'] = $adj_id;
			$adj_data['adj_no'] = $set_adj_no;
			$adj_data['adj_status'] = 1;
			
			if($this->tbl_adjustment->update_adj($adj_where,$adj_data)):
				$proses = true;
			endif;
		endif;
		
		
		// UPDATE COUNTER
		if ($proses):
			$get_counter = $this->tbl_sys_counter->get_counter($where_sys);
			if ($get_counter->num_rows() > 0):
				$update_counter['adj_no'] = $get_counter->row()->adj_no + 1;
				$this->tbl_sys_counter->update_counter($where_sys,$update_counter);
			endif;
			// ADJUSTMENT NO
			echo $set_adj_no;			
		endif;
		
	}
	
	function prosesAdjustment($stats) {
		$data['pro_id'] = $this->input->post('pro_id');
		$data['inv_document'] = 'SETUP';
		if ($stats=='isnotjoin'):
			$sup_id = $this->input->post('sup_id');
			$inv_tgl = $this->input->post('tgl');
			$inv_bln = $this->input->post('bln');
			$inv_thn = $this->input->post('thn');
			$inv_begin = $this->input->post('saldo');
			$inv_end = $this->input->post('saldo');
			$inv_price = $this->input->post('inv_price');
			$cur_id = $this->input->post('cur_id');
			$sukses = true;
			
			for($i=0;$i<sizeof($sup_id);$i++):
				$data['sup_id'] = $sup_id[$i];
				$data['inv_transDate'] = date_format(date_create($inv_tgl[$i]),'Y-m-d');//;$inv_thn[$i].'-'.$inv_bln[$i].'-'.$inv_tgl[$i];
				$data['inv_begin'] = $inv_begin[$i];
				$data['inv_end'] = $inv_end[$i];
				$data['cur_id'] = $cur_id[$i];
				$data['inv_price'] = $inv_price[$i];
				$data['bal_price'] = $inv_price[$i] * $inv_end[$i];

				if ($this->tbl_inventory->save_inventory($data)):
					$data_h['inv_id'] = $this->db->Insert_Id();
					$data_h['sup_id'] = $sup_id[$i];
					$data_h['inv_transDate'] = date_format(date_create($inv_tgl[$i]),'Y-m-d');//$inv_thn[$i].'-'.$inv_bln[$i].'-'.$inv_tgl[$i];
					$data_h['inv_begin'] = $inv_begin[$i];
					$data_h['inv_end'] = $inv_end[$i];
					$data_h['cur_id'] = $cur_id[$i];
					$data_h['inv_price'] = $inv_price[$i];
					$data_h['bal_price'] = $inv_price[$i] * $inv_end[$i];
					$data_h['pro_id'] = $data['pro_id'];
					$data_h['inv_document'] = 'SETUP';
					if (!$this->tbl_inventory->save_inv_history($data_h)):
						$sukses = false;
					endif;
				endif;
			endfor;	

			if ($sukses):
				if ($this->tbl_produk->pro_edit($data['pro_id'],array('pro_status'=>'active'))):
					//echo 'SUKSES';
					$get_pro = $this->db->query("select * from prc_master_product where pro_id = '".$data['pro_id']."'");
					if ($get_pro->num_rows() > 0):
						echo $get_pro->row()->pro_code;
					endif;
				endif;
			endif;
		else:
			$data['inv_price'] = $this->input->post('inv_price');
			$data['inv_transDate'] = date_format(date_create($this->input->post('inv_transDate')),'Y-m-d');
			$data['inv_begin'] = $this->input->post('saldo');
			$data['inv_end'] = $this->input->post('saldo');
			$data['cur_id'] = $this->input->post('cur_id');
			$data['bal_price'] = $this->input->post('inv_price') * $this->input->post('saldo');
			
			if ($this->tbl_inventory->save_inventory($data)):
				$data['inv_id'] = $this->db->Insert_Id();
				if ($this->tbl_produk->pro_edit($data['pro_id'],array('pro_status'=>'active')))
					if ($this->tbl_inventory->save_inv_history($data))
						$get_pro = $this->db->query("select * from prc_master_product where pro_id = '".$data['pro_id']."'");
						if ($get_pro->num_rows() > 0):
							echo $get_pro->row()->pro_code;
						endif;
						//echo 'SUKSES';
			endif;
			//echo $data['inv_transDate'];
		endif;
	}
	
	function hapusAdjDet($adj_id,$pro_id) {
		$where['adj_id'] = $adj_id;
		$where['pro_id'] = $pro_id;
		if ($this->tbl_adjustment->delete_adj_detail($where))
			echo 'success';
	}
	
	function hapusAdjustment($adj_id) {
		$where['adj_id'] = $adj_id;
		if ($this->tbl_adjustment->delete_adj_detail($where))
			if ($this->tbl_adjustment->delete_adj($where))
				echo 'success';
	}
	
	function hapusAdjDetSup($adj_id,$pro_id,$sup_id) {
		$where['adj_id'] = $adj_id;
		$where['pro_id'] = $pro_id;
		$where['sup_id'] = $sup_id;
		if ($this->tbl_adjustment->delete_adj_detail($where))
			echo 'success';
	}
	
	function list_autocomplate($stats) {
		//$pro_code = $this->input->post('pro_code');
		//$pro_name = $this->input->post('pro_name');
		$q = $this->input->get('q');
		
		if ($stats == 'name')
			$like_pro['pro_name']=$q;
		else
			$like_pro['pro_code']=$q;
		
		$where_pro['pro_status']='non active';
		$qres = $this->tbl_produk->get_product($where_pro,$like_pro);
		
		if ($qres->num_rows() > 0):
			foreach ($qres->result() as $rows):
					if ($stats == 'name'):
						if (strpos(strtolower($rows->pro_name), $q) !== false):
							echo "$rows->pro_name|$rows->pro_code|$rows->pro_id|$rows->is_stockJoin\n";
						endif;
					else:
						if (strpos(strtolower($rows->pro_code), $q) !== false):
							echo "$rows->pro_code|$rows->pro_name|$rows->pro_id|$rows->is_stockJoin\n";
						endif;
					endif;
			endforeach;
		endif;
	}
	
	function cek_stokjoin($pro_id) {
		$sql = "select * 
		from prc_inventory as inv
		inner join prc_master_supplier as sup on sup.sup_id = inv.sup_id 
		inner join prc_master_legality as leg on leg.legal_id = sup.legal_id 
		where inv.pro_id = $pro_id group by inv.sup_id";
		$get_sup = $this->db->query($sql);
		if ($get_sup->num_rows() > 0):
			$data['sup_pro_list'] = $get_sup;
			$this->load->view(self::$link_view.'/master_adj_supplier_list',$data);
		else:
			return false;
		endif;		
	}
	
	function cek_supplier() {
		$sup_id = $this->input->post('sup_id');
		for ($i=0 ; $i < sizeOf($sup_id); $i++):
			echo $i.'.'.$sup_id[$i].'|';
		endfor;
	}
}
?>