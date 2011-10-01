<?php
class service_inventory extends MY_Controller {
	public static $link_view, $link_controller;
	function service_inventory() {
		parent::MY_Controller();
		$this->load->model(array('tbl_produk','tbl_category','tbl_unit','tbl_inventory','tbl_sr','tbl_mr','tbl_sys_counter','tbl_prc_type','tbl_supplier','tbl_sup_produk','flexi_model'));
		$this->load->library(array('treeview','pro_code','flexigrid','pictures','session','flexi_engine','general'));
		$this->load->helper(array('flexigrid','html','date','jq_plugin'));
		$this->config->load('flexigrid');
		$this->config->load('tables');
		
		// LANGUAGE
		$this->lang->load('mod_entry/inventory','bahasa');
		$this->lang->load('mod_entry/po','bahasa');
		$this->lang->load('mod_entry/rfq','bahasa');
		$this->lang->load('mod_entry/pr_rfq','bahasa');
		$this->lang->load('mod_master/aktivasi','bahasa');
		$this->lang->load('mod_master/produk','bahasa');
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
		'asset/javascript/jQuery/form/jquery.autoNumeric.js',
		'asset/javascript/helper/autoNumeric.js',
		);
		
		$data['extraHeadContent'] = '';
		
		foreach ($arrayCSS as $css):
			$data['extraHeadContent'] .= '<link type="text/css" rel="stylesheet" href="'.base_url().$css.'"/>';
		endforeach;
		foreach ($arrayJS as $js):
			$data['extraHeadContent'] .= '<script type="text/javascript" src="'.base_url().$js.'"/></script>';
		endforeach;
		
		self::$link_controller = 'mod_service/service_inventory';
		self::$link_view = 'purchase/mod_entry/mod_service/mod_inventory';
		$data['link_controller'] = self::$link_controller;
		$data['link_view'] = self::$link_view;
		
		$data['page_title'] = $this->lang->line('inventory_service_title');
		
		// LANG NYA
		$data['dlg_title_confirm'] = $this->lang->line('dlg_title_confirm'); 
		$data['dlg_title_info'] = $this->lang->line('dlg_title_info'); 
		$data['dlg_info_confirm'] = $this->lang->line('confirm');
		$data['dlg_info_delete'] = $this->lang->line('info_delete');
		
		$data['dlg_btn_close'] = $this->lang->line('close');
		$data['dlg_btn_ok'] = $this->lang->line('ok');
		$data['dlg_btn_agree'] = $this->lang->line('agree');
		$data['dlg_btn_back'] = $this->lang->line('back');
		
		
		$this->load->vars($data);
	}

	// QUERY UNTUK NON FLEXIGRID ATO FLEXIGRID
	function flexigrid_sql($sql,$flexi=TRUE,$count='',$where=FALSE) {
		return $this->flexi_model->generate_sql($sql,$count,$where,$flexi);
	}
	
	function flexigrid_ajax($qstats,$qvalidate)
	{
		$uri_array = $this->uri->uri_to_assoc(6);
		
		switch ($qstats):
			case 'product' : 
				$valid_fields = array('pro_code','pro_name','end_stock','price');
				$this->flexigrid->validate_post($qvalidate,'asc',$valid_fields);
				/*
				$like = $uri_array;
				$where['pro_status'] = 'active';
				$records = $this->tbl_produk->get_product($where,$like,true);
				*/
				if (isset($uri_array['pro_code']))
					$pro_code = $uri_array['pro_code'];
				/*	
				$sql = "select * {COUNT_STR} 
				from prc_master_product as pro 
				inner join
				where pro.pro_status = 'active'
				";
				*/
				$sql = "select *,
				FORMAT((select sum(inv_end) from prc_inventory where pro_id = pro.pro_id),sat.satuan_format) as end_stock 
				{COUNT_STR} 
				from prc_master_product as pro 
				inner join prc_master_satuan as sat on sat.satuan_id = pro.um_id 
				where pro.pro_status = 'active' ";
				
				if (isset($uri_array['pro_code']))
					$sql .="and pro.pro_code like '$pro_code%' ";
					
				$sql .= "{SEARCH_STR}";
				//left join prc_master_supplier as sup on sup.sup_id = inv_his.sup_id 
				
				$records = $this->flexigrid_sql($sql,true,'pro.pro_id');
				break;
				
			case 'inv_history_detail' : 
				$valid_fields = array('inv_transDate','inv_document','inv_begin','inv_in','inv_out','inv_end','dep_name');
				$this->flexigrid->validate_post($qvalidate,'desc',$valid_fields);
				$pro_id = $uri_array['pro_id'];
				$sup_id = $uri_array['sup_id'];
				
				$sql = "select *,if (inv_his.inv_in != 0 and inv_his.inv_document != 'SETUP',
				(select dep.dep_name from prc_gr as gr
				inner join prc_pr_detail as pr_det on pr_det.po_id = gr.po_id
				inner join prc_pr as pr on pr.pr_id = pr_det.pr_id
				inner join prc_sys_user as usr on pr.pr_requestor = usr.usr_id
				inner join prc_master_departemen as dep on usr.dep_id = dep.dep_id
				where inv_his.inv_document = gr.gr_no and pr_det.pro_id = inv_his.pro_id
				),
				(select dep.dep_name from prc_good_release as grl
				inner join prc_mr_detail as mr_det on mr_det.grl_id = grl.grl_id
				inner join prc_mr as mr on mr.mr_id = mr_det.mr_id
				inner join prc_sys_user as usr on mr.mr_requestor = usr.usr_id
				inner join prc_master_departemen as dep on usr.dep_id = dep.dep_id
				where inv_his.inv_document = grl.grl_no and mr_det.pro_id = inv_his.pro_id
				)) as dep_name,
				FORMAT(inv_his.inv_begin,sat.satuan_format)as inv_begin,
				FORMAT(inv_his.inv_in,sat.satuan_format)as inv_in,
				FORMAT(inv_his.inv_out,sat.satuan_format)as inv_out,
				FORMAT(inv_his.inv_end,sat.satuan_format)as inv_end 
				{COUNT_STR} 
				from prc_inventory_history as inv_his
				left join prc_master_product as pro on pro.pro_id = inv_his.pro_id 
				left join prc_master_satuan as sat on sat.satuan_id = pro.um_id 
				where inv_his.pro_id = $pro_id and inv_his.sup_id = $sup_id 
				{SEARCH_STR}";
				//left join prc_master_supplier as sup on sup.sup_id = inv_his.sup_id 
				
				$records = $this->flexigrid_sql($sql,true,'inv_his.inv_id');
				break;
				
		endswitch;
		
		$this->output->set_header($this->config->item('json_header'));
		
		if ($records['count'] > 0):		
			$no = 1;
			foreach ($records['result']->result() as $row)
			{
				switch ($qstats):
					case 'product'   :
						//$where_inv['pro_id'] = $row->pro_id; 
						//$get_stock = $this->tbl_inventory->get_inventory($where_inv)->row();
						//$get_supp = $this->tbl_sup_produk->get_pro_supp($row->pro_id)->num_rows();
						/*
						$get_stock = $this->db->query("select * from prc_inventory as inv
						inner join prc_master_currency as cur on inv.cur_id=cur.cur_id
						inner join prc_master_product as pro on inv.pro_id = pro.pro_id
						inner join prc_master_satuan as sat on pro.um_id = sat.satuan_id
						where inv.pro_id = '$row->pro_id' order by inv_transDate asc");
						//$get_stocka = $this->db->query("select sum(inv_end) as stock_end from prc_inventory where pro_id='$pro_id'")->row();
						$cat_name=implode('/',$this->pro_code->set_split_code($row->pro_code,'cat_name'));	
						*/
						$sql = "select * from prc_inventory as inv
						inner join prc_master_currency as cur on cur.cur_id = inv.cur_id 
						where inv.pro_id = $row->pro_id order by inv.inv_transDate desc";
						$get_price = $this->flexigrid_sql($sql,false,'inv.pro_id');
						$record_items[] = array(
							$row->pro_id, // TABLE ID
							//$no,
							$row->pro_code,
							$row->pro_name,
							$row->end_stock.' '.$row->satuan_name,
							$get_price->row()->cur_symbol.'.'.number_format($get_price->row()->inv_price,2)
							/*
							($get_stock->num_rows() > 0)?(number_format($get_stock->row()->inv_end,2).' '.$get_stock->row()->satuan_name):('-'),
							($get_stock->num_rows() > 0)?($get_stock->row()->cur_symbol.'.'.number_format($get_stock->row()->inv_price,2)):('-')*/
							//($row->is_stockJoin == 0)?('Not Join'):('Join')
						);
					break;
					case 'inv_history_detail' :
						$datetime = date_create($row->inv_transDate);
						$dateis = date_format($datetime, 'd-m-Y H:i:s');
						$record_items[] = array(
							$row->inv_id,
							//$no,
							$dateis,
							$row->inv_document,
							//$row->inv_type,
							//($row->sup_name!='')?($row->sup_name):('-'),							
							$row->inv_begin,
							$row->inv_in,
							$row->inv_out,
							$row->inv_end,
							($row->dep_name!='')?($row->dep_name):('-')
						);
					break;
				endswitch;					
				$no++;
			}
		else:
			$record_items[] = array(
							'n/a',
							//'n/a',
							'n/a',
							'n/a',
							'n/a',
							'n/a',
							'n/a',
							'n/a',
							'n/a'
						);
		endif;
		
		$this->output->set_output($this->flexigrid->json_build($records['count'],$record_items));
		
	}
	
	function flexigrid_builder($title,$width,$height,$rp,$where='',$qstats='',$qvalidate='') {
		/* FIELD
		 * 0 - display name
		 * 1 - width
		 * 2 - sortable
		 * 3 - align
		 * 4 - searchable (2 -> yes and default, 1 -> yes, 0 -> no.)
		 */
		
		switch ($qstats):
			case 'product' : 
				//$colModel['no'] = array($this->lang->line('no'),20,FALSE,'center',0,false,'flexEdit');
				$colModel['pro_code'] = array($this->lang->line('pro_code'),80,TRUE,'center',1,false,'flexEdit');
				$colModel['pro_name'] = array($this->lang->line('pro_name'),210,TRUE,'left',2,false,'flexEdit');
				$colModel['stock'] = array($this->lang->line('inv_stock').' '.$this->lang->line('inv_end'),70,TRUE,'center',0,false,'flexEdit');
				$colModel['price'] = array($this->lang->line('inv_price').' '.$this->lang->line('inv_end'),80,TRUE,'left',0,false,'flexEdit');
				//$colModel['is_stockJoin'] = array($this->lang->line('is_stockJoin'),50,TRUE,'center',2,false,'flexEdit');
				$table_id = 'product_list';
			break;
		 	case 'inv_history_detail' :
				//$colModel['no'] = array($this->lang->line('no'),30,FALSE,'center',0);
				$colModel['inv_transDate'] = array($this->lang->line('inv_transDate'),130,TRUE,'center',2);
				$colModel['inv_document'] = array($this->lang->line('inv_document'),120,TRUE,'left',1);
				//$colModel['inv_type'] = array($this->lang->line('inv_type'),35,TRUE,'center',0);
				//$colModel['supp_name'] = array($this->lang->line('supp_name'),140,TRUE,'center',0);
				$colModel['inv_begin'] = array($this->lang->line('inv_begin'),100,TRUE,'right',1);
				$colModel['inv_in'] = array($this->lang->line('inv_in'),100,TRUE,'right',1);
				$colModel['inv_out'] = array($this->lang->line('inv_out'),100,TRUE,'right',1);
				$colModel['inv_end'] = array($this->lang->line('inv_end'),100,TRUE,'right',1);
				$colModel['dep_name'] = array($this->lang->line('dep_name'),140,TRUE,'center',0);
				$table_id = 'history_list';
		 	break;
		endswitch;		
					
		/* BUILD FLEXIGRID
		 * build_grid_js(<div id>,<ajax function>,<field model>,<first field selection>,<order by>,<configuration>,<button>);
		 */
		
		if (is_array($where)):
			$uri_array = $this->uri->assoc_to_uri($where);
			$ajax_model = site_url(self::$link_controller."/flexigrid_ajax/".$qstats."/".$qvalidate."/".$uri_array);
		else:
			$ajax_model = site_url(self::$link_controller."/flexigrid_ajax/".$qstats."/".$qvalidate);
		endif;
		$params = $this->flexi_engine->flexi_params($width,$height,$rp,$title,TRUE);
		return build_grid_js($table_id,$ajax_model,$colModel,$qvalidate,'asc',$params);
		
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
		$data['content'] = self::$link_view.'/service_inv_main';
		$this->load->view('index',$data);
	}
	
	function tree() {
		$this->load->view(self::$link_view.'/service_inv_tree');		
	}
	
	function product_list($cat_id) {
		$where_cat['cat_id'] = $cat_id;	
		$qstats = 'product';
		$qvalidate = 'pro_code';
		if ($cat_id!='all'):
			$cat_code = $this->tbl_category->get_category($where_cat)->row()->cat_code;
				$where_pro['pro_status'] = 'active';
				$like_pro['pro_code'] = $cat_code;
				$pro_code = $this->tbl_produk->get_product($where_pro,$like_pro);
				if ($pro_code->num_rows() > 0):
					$where['pro_code'] = $cat_code;
					$data['js_grid'] = $this->flexigrid_builder($this->lang->line('flex_produk'),519,260,11,$where,$qstats,$qvalidate);
					$this->load->view(self::$link_view.'/service_inv_list',$data);
				else:
					$data['js_grid'] = br(8).$this->lang->line('list_empty');
					$this->load->view(self::$link_view.'/service_inv_list',$data);
				endif;
		else:
			$where['pro_status'] = 'active';
			if ($this->tbl_produk->get_product($where)->num_rows() > 0):
				$data['js_grid'] = $this->flexigrid_builder($this->lang->line('flex_produk'),519,260,11,$where,$qstats,$qvalidate);
			else:
				$data['js_grid'] = br(8).$this->lang->line('list_empty');
			endif;
			$this->load->view(self::$link_view.'/service_inv_list',$data);
		endif;
	}
	
	function treecat_root() {
		echo $this->treeview->generate_tree('',true);
	}
	
	function treecat_node($key) {
		echo $this->treeview->generate_tree($key);
	}
	
	function kartu_stok($pro_id){
		
		$sql_po = "select p.po_no, d.qty, s.sup_name, (
				SELECT sum(qty) 
				FROM prc_gr_detail AS r 
				 inner join prc_gr as g on r.gr_id = g.gr_id
				WHERE r.pro_id = d.pro_id and g.po_id = d.po_id
				) AS terima, (
				SELECT (d.qty - sum(qty)) 
				FROM prc_gr_detail AS r 
				 inner join prc_gr as g on r.gr_id = g.gr_id
				WHERE r.pro_id = d.pro_id and g.po_id = d.po_id
				) AS sisa    
				from prc_pr_detail as d
		        inner join prc_po as p on d.po_id = p.po_id
				inner join prc_master_supplier as s on p.sup_id = s.sup_id
				where p.po_status = 0 and d.pro_id = '$pro_id'";
		
		//---find pr & rfq---
		$sql_rfq = "select pd.qty, r.rfq_no, pd.requestStat, pr.pr_no, date_format(pr.pr_date, '%d-%m-%Y') as pr_date, u.usr_name
				from prc_pr_detail as pd
				inner join prc_pr as pr on pd.pr_id = pr.pr_id
				inner join prc_sys_user as u on u.usr_id = pr.pr_requestor
				left join prc_rfq as r on pd.rfq_id = r.rfq_id 
				where pd.pro_id = '$pro_id' and (pd.requestStat='0' or pd.requestStat='1' or pd.requestStat='2' or pd.requestStat='4')
				and (pd.rfq_stat='0' or pd.rfq_stat='1' or pd.rfq_stat='3') and pr.pr_status = 1 and pd.pcv_stat=0";//(pr.is_approved='0' or pr.is_approved='1')";
		$sql_limit = " limit 2";
		
		$sql_satuan = "select * from prc_master_satuan as sat, prc_master_product as pro where pro.um_id= sat.satuan_id and pro.pro_id ='$pro_id'";
		
		
		$sql_his = "
		select *,inv.sup_id as sup_id,
		FORMAT(inv.inv_begin,sat.satuan_format)as inv_begin, 
		FORMAT(inv.inv_in,sat.satuan_format)as inv_in, 
		FORMAT(inv.inv_out,sat.satuan_format)as inv_out, 
		FORMAT(inv.inv_end,sat.satuan_format)as inv_end 
		from prc_inventory as inv
		left join prc_master_product as pro on pro.pro_id = inv.pro_id 
		left join prc_master_satuan as sat on sat.satuan_id = pro.um_id 
		left join prc_master_supplier as sup on sup.sup_id = inv.sup_id
		where inv.pro_id = $pro_id
		order by inv.inv_transDate	
		";
		
		//$sql_his_limit = $sql_his.' limit 0,2';
		//$sql_his_next = $sql_his.' limit 2,100';
		
		$data['history_inv'] = $this->db->query($sql_his);
		//$data['history_inv_next'] = $this->db->query($sql_his_next);
		//$this->tbl_inventory->get_inv_his($pro_id,false,1,'inv_transDate','DESC');//(array('pro_id'=>$pro_id),false,false,false,1);
		$data['history_po'] = $this->db->query($sql_po.$sql_limit);
		$data['history_po_row'] = $this->db->query($sql_po)->num_rows();
		$data['history_rfq'] = $this->db->query($sql_rfq.$sql_limit);
		$data['history_rfq_row'] = $this->db->query($sql_rfq)->num_rows();
		
		$data['pro_satuan'] = $this->db->query($sql_satuan);
		
		$pro_list = $this->tbl_produk->get_product(array('pro_id'=>$pro_id));	
		$cat_name = $this->pro_code->set_split_code($pro_list->row()->pro_code,'cat_name');
		$data['cat_name'] = $cat_name[1].'/'.$cat_name[2].'/'.$cat_name[3];
		$data['pro_id'] = $pro_list->row()->pro_id;
		$data['pro_data'] = $pro_list;
				
		$this->load->view(self::$link_view.'/service_inv_stock',$data);
	}
		
	function product_history($pro_id,$sup_id,$doc) {
		$qstats = 'inv_history_detail';
		$qvalidate = 'inv_transDate';
		$where['pro_id'] = $pro_id;
		$where['sup_id'] = $sup_id;
		
		$sql = "select sup_id,sup_name from prc_master_supplier where sup_id = $sup_id";
		$get_sup = $this->flexigrid_sql($sql,false,'sup_id');
		
		$arr_doc = array('History','Keluar Barang');
		
		$supplier = $arr_doc[$doc];
		
		if ($get_sup->num_rows() > 0)
			$supplier = ' Pemasok : <font color="red">'.$get_sup->row()->sup_name.'</font>';
		
		$data['js_grid'] = $this->flexigrid_builder('Daftar '.$supplier,900,370,10,$where,$qstats,$qvalidate);
		
		$this->load->view(self::$link_view.'/service_inv_history',$data);
	}
	
	function PRorMR($type) {
		$session = $this->session->userdata('sess_prmr_no');
		
		$data['pty_list'] = $this->tbl_prc_type->get_prc_type();
		$data['type'] = $type;
		
		if ($type == 'SR'):
			$sr_list = $this->db->query("select d.*, pro.pro_code, pro.pro_name, 
			pro.um_id, sat.satuan_id, sat.satuan_name, sat.satuan_format 
			from prc_sr_detail as d 
            inner join prc_sr as p on d.sr_id = p.sr_id
			inner join prc_master_product as pro on d.pro_id = pro.pro_id 
			inner join prc_master_satuan as sat on pro.um_id = sat.satuan_id 
			where p.sr_no='".$session."'");
			$data['sr_list'] = $sr_list;
			if($sr_list->num_rows() > 0):
				//$this->load->view(self::$link_view.'/entry_inv_pr',$data);
				$this->load->view(self::$link_view.'/service_inv_sr',$data);
			endif;
		endif;
		//$data['um_list'] = $this->tbl_unit->get_unit();	
		//$this->load->view(self::$link_view.'/service_inv_sr',$data);
		
	}
	
	function savePRorMR($type,$pro_id) {
	//echo $type.'|'.$pro_id;
	
		$session = $this->session->userdata('sess_prmr_no');
		$usr_id = $this->session->userdata('usr_id');
	
		$datetime = date('Y-m-d H:i:s');
		$thn = date('Y');
		$bln = date('n');
		$proses = false;
		$dup = false;
		$proses = '';
		
		if ($type == 'SR'):
			$where_sr['sr_no'] = $session;
			$get_sr = $this->tbl_sr->get_sr($where_sr);
			if ($get_sr->num_rows() < 1):
				$insert_sr['sr_requestor'] = $usr_id;
				$insert_sr['sr_no'] = $session;
				$insert_sr['sr_date'] = $datetime;
				// INSERT sR
				$this->tbl_sr->insert_sr($insert_sr);
				//$proses .= 'INSERT PR > ';
			endif;
						
			// INSERT PR DETAIL
			$where_sr['sr_no'] = $session;
			$get_sr = $this->tbl_sr->get_sr($where_sr);
			if ($get_sr->num_rows() > 0):
				$insert_sr_det['sr_id'] = $get_sr->row()->sr_id;
				if ($this->tbl_sr->get_sr_detail($insert_sr_det)->num_rows() == 0):
					$insert_sr_det['pro_id'] = $pro_id;
					$insert_pr_det['requestStat'] = '1';
					if ($this->tbl_sr->insert_sr_detail($insert_sr_det)):
						$proses = true;
					endif;
				else:
					$dup = true;
				endif;
			else:
				//$proses .= 'PR CEK GATOT';
			endif;
		endif;
		
		if ($proses==TRUE) echo 'sukses'; 
		else if ($dup==TRUE) echo 'one';
		
	}
	
	function cek_tabs() {
		$session = $this->session->userdata('sess_prmr_no');
		//echo $session;
		
		$where_sr['sr_no'] = $session;
		$get_sr = $this->tbl_sr->get_sr($where_sr);
		if ($get_sr->num_rows() > 0):
			$where_sr_det['sr_id'] = $get_sr->row()->sr_id;
			$get_sr_det = $this->tbl_sr->get_sr_detail($where_sr_det)->num_rows();
		else:
			$get_sr_det = 0;
		endif;
		
		if ($get_sr_det > 0) $sr = 1; else $sr = 0;
		echo '[{
		"SR":"'.$sr.'",
		"SR_DATA":"'.$get_sr_det.'"
		}]';	
	}
	
	function prosesSR($type) {
		$datetime = date('Y-m-d H:i:s');
		$thn = date('Y');
		$thn_min = date('y');
		$bln = date('n');
		$bln_max = date('m');
		$PRorMR_stats = false;
		
		
		// CEK COUNTER
		$where_sys['thn'] = $thn;
		$where_sys['bln'] = $bln;
		$get_counter = $this->tbl_sys_counter->get_counter($where_sys);
		if ($get_counter->num_rows() > 0):
			$sr_no = $get_counter->row()->sr_no;
		else:
			$this->tbl_sys_counter->insert_counter($where_sys);
			$sr_no = 1;
		endif;
		
		$pro_id = $this->input->post('sr_pro_id');
		$qty = $this->input->post('sr_qty');
		$um_id = $this->input->post('sr_um_id');
		//$delivery_date = $this->input->post('sr_delivery_date');
		$description = $this->input->post('sr_description');
		$sr_id = $this->input->post('sr_id');
		//$emergencyStat = $this->input->post('sr_emergencyStat');
		//$pty_id = $this->input->post('sr_pty_id');
		
		$sr_cat = $this->input->post('sr_cat'); 
		$sr_type= $this->input->post('sr_type');
		
		// UPDATE SR AND DETAIL
	
		for($i=0;$i<sizeof($pro_id);$i++):				
				
			$where['sr_id'] = $sr_id;
			$where['pro_id'] = $pro_id[$i];
			
			//$update['pty_id'] = $pty_id[$i];
			$update['qty'] = $qty[$i];
			$update['um_id'] = $um_id[$i];
			//$update['delivery_date'] = date_format(date_create($delivery_date[$i]),'Y-m-d');
			//$update['buy_via'] = $buy_via[$i];
			//$update['emergencyStat'] = $emergencyStat[$i];
			$update['description'] = trim($description[$i]);
			
			$update['service_cat'] = $sr_cat[$i];
			$update['service_type'] = $sr_type[$i];
			
			if ($this->tbl_sr->update_sr_detail($where,$update)):
				$update_his['sr_id'] = $sr_id;
				$update_his['pro_id'] = $pro_id[$i];
				//$update_his['pty_id'] = $pty_id[$i];
				$update_his['qty'] = $qty[$i];
				$update_his['um_id'] = $um_id[$i];
				//$update_his['delivery_date'] = date_format(date_create($delivery_date[$i]),'Y-m-d');
				//$update_his['buy_via'] = $buy_via[$i];
				//$update_his['emergencyStat'] = $emergencyStat[$i];
				$update_his['description'] = trim($description[$i]);
				
				$update_his['service_cat'] = $sr_cat[$i];
				$update_his['service_type'] = $sr_type[$i];
				
				$this->tbl_sr->insert_sr_history($update_his);
			endif;
		endfor;
			
		$session = $this->session->userdata('sess_prmr_no');
		$where_sr['sr_no'] = $session;
		$get_sr = $this->tbl_sr->get_sr($where_sr);
		if ($get_sr->num_rows() > 0):
			$sr_num = str_pad($sr_no, 4, "0", STR_PAD_LEFT); 
			// SR NUMBER
			$sr_doc_no = $this->lang->line('sr_doc_no');
			$update_sr['sr_no'] = $thn_min.'/'.$bln_max.'/'.$sr_doc_no.$sr_num;
			$update_sr['sr_status'] = '1';
			if ($this->tbl_sr->update_sr($where_sr,$update_sr)):
				$PRorMR_stats = true;
				$sr_no_final = $update_sr['sr_no'];
			endif;
		endif;
			
		// UPDATE COUNTER
		$get_counter = $this->tbl_sys_counter->get_counter($where_sys);
		if ($get_counter->num_rows() > 0):
			$dlg_data =  '<STRONG>Selamat... '.$type.' Berhasil dibuat <br />
				'.$type.' NO : <font color="red">';
			if ($type=='SR'):
				$update_counter['sr_no'] = $get_counter->row()->sr_no + 1;
				$dlg_data .= $sr_no_final.'</font></STRONG>';
			endif;
			$this->tbl_sys_counter->update_counter($where_sys,$update_counter);
			$dlg_title = $type.' GAGAL DIPROSES';
		else:
			//echo 'GATOT';		
			$dlg_data = '<STRONG>Maaf... Data '.$type.' Tidak Berhasil Ditambahkan</STRONG>';
			$dlg_title = 'GAGAL';
		endif;

		$where_prmr[strtolower($type).'_requestor'] = $this->session->userdata('usr_id');
		$where_prmr[strtolower($type).'_status'] = '0';
		$get_prmr_temp = $this->tbl_sr->get_prmr($where_prmr,$type);

		if ($get_prmr_temp->num_rows() > 0):
			foreach ($get_prmr_temp->result() as $row_prmr):
				$where_prmr_det['sr_id'] = $row_prmr->sr_id;
				$del_prmr_det = $this->tbl_sr->delete_prmr_det($where_prmr_det,$type);
				$del_prmr = $this->tbl_sr->delete_prmr($where_prmr_det,$type);
			endforeach;
			
		endif;			

		echo $dlg_data;
		// set_dialog_confirm('dlg_confirm','data',$dlg_data,$dlg_title);
	}
	
	function sup_add($row_id,$pro_id) {
		$data['row_id'] = $row_id;
		//$where['pro_id'] = $pro_id;
		$sql = "select * from prc_inventory as inv 
		inner join prc_master_supplier as sup on inv.sup_id = sup.sup_id 
		inner join prc_master_product as pro on inv.pro_id = pro.pro_id 
		inner join prc_master_satuan as sat on pro.um_id = sat.satuan_id
		where inv.pro_id = $pro_id
		order by inv.sup_id
		";
		$data['sup_list'] = $this->db->query($sql);//$this->tbl_inventory->get_inv_sup($where);
		//$data['id_row'] = '#row_';
		$this->load->view(self::$link_view.'/service_inv_supplier_list',$data);
	}
	
	function cek_stok($join,$pro_id,$qty,$sat,$sup_id=''){
		
		if ($join==0):
			$where['pro_id']=$pro_id;
			$where['sup_id']=$sup_id;		
		else:
			$where['pro_id']=$pro_id;
		endif;

		$get_stok = $this->tbl_inventory->get_stok($where);
		if($get_stok->num_rows()>0):
			$where_sat['pro_id']=$pro_id;
			$where_sat['satuan_unit_id']=$sat;
			$get_sat_val = $this->tbl_unit->get_unit_satuan($where_sat,$join=true);
			$stok = $get_stok->row()->inv_end;
			if ($get_sat_val->num_rows() > 0):
				$stok = $stok / $get_sat_val->row()->value;
			endif;

			if($qty>$stok):
				echo number_format($stok,0);
			endif;
		endif;
	}
	
	function cek_digit($satuan_id){
		echo $this->db->query("select satuan_format from prc_master_satuan where satuan_id = $satuan_id")->row()->satuan_format;
	}
	
	function del_prmr_row($sr_id,$pro_id) {
		$sr_det = "delete from prc_sr_detail where sr_id = $sr_id and pro_id = $pro_id";
		$sr = "delete from prc_sr where sr_id = $sr_id";
		if ($this->db->query($sr_det)):
			echo 'sukses';
		endif;
		$sel_sr_det = "select sr_id from prc_sr_det where sr_id = $sr_id";
		if ($this->db->query($sel_sr_det)->num_rows() == 0):
			$this->db->query($sr);
		endif;
		
	}
	
	function list_autocomplate($stats) {
		$q = strtoupper($this->input->get('q'));
		
		if ($stats == 'name'):
			$like_pro['pro_name']=$q;
		else:
			$like_pro['pro_code']=$q;
		endif;
		
		$where_pro['pro_status']='active';
		$qres = $this->tbl_produk->get_product($where_pro,$like_pro);
		
		if ($qres->num_rows() > 0):
			foreach ($qres->result() as $rows):
					if ($stats == 'name'):
						if (strpos($rows->pro_name, $q) !== false):
							echo "$rows->pro_name|$rows->pro_code|$rows->pro_id\n";
						endif;
					else:
						if (strpos($rows->pro_code, $q) !== false):
							echo "$rows->pro_code|$rows->pro_name|$rows->pro_id\n";
						endif;
					endif;
			endforeach;
		endif;
	}
	
}
?>
