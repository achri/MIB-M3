<?php
class Entry_inventory extends MY_Controller {
	public static $link_view, $link_controller;
	function Entry_inventory() {
		parent::MY_Controller();
		$this->load->model(array('tbl_produk','tbl_category','tbl_unit','tbl_inventory','tbl_pr','tbl_rfq','tbl_mr','tbl_sys_counter','tbl_prc_type','tbl_supplier','tbl_sup_produk','flexi_model'));
		$this->load->library(array('treeview','pro_code','flexigrid','pictures','session','flexi_engine','general'));
		$this->load->helper(array('flexigrid','html','date','jq_plugin'));
		$this->config->load('flexigrid');
		$this->config->load('tables');
		
		// LANGUAGE
		$this->lang->load('mod_entry/inventory','bahasa');
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
		
		self::$link_controller = 'mod_entry/entry_inventory';
		self::$link_view = 'purchase/mod_entry/mod_product/mod_inventory';
		$data['link_controller'] = self::$link_controller;
		$data['link_view'] = self::$link_view;
		
		$data['page_title'] = $this->lang->line('inventory_title');
		
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
				$valid_fields = array('pro_code','pro_name','end_stock','inv_price');
				$this->flexigrid->validate_post($qvalidate,'asc',$valid_fields);
				
				if (isset($uri_array['pro_code']))
					$pro_code = $uri_array['pro_code'];
					
				$sql = "select *,
				(select sum(inv_end) from prc_inventory where pro_id = pro.pro_id) as end_stock 
				{COUNT_STR} 
				from prc_master_product as pro 
				inner join prc_master_satuan as sat on sat.satuan_id = pro.um_id 
				where pro.pro_status = 'active' ";
				
				if (isset($uri_array['pro_code']))
					$sql .="and pro.pro_code like '$pro_code%' ";
					
				$sql .= "{SEARCH_STR}";
				
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
				inv_his.inv_begin as inv_begin,
				inv_his.inv_in as inv_in,
				inv_his.inv_out as inv_out,
				inv_his.inv_end as inv_end 
				{COUNT_STR} 
				from prc_inventory_history as inv_his
				left join prc_master_product as pro on pro.pro_id = inv_his.pro_id 
				left join prc_master_satuan as sat on sat.satuan_id = pro.um_id 
				where inv_his.pro_id = $pro_id and inv_his.sup_id = $sup_id 
				{SEARCH_STR}";
				
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
						/*
						$sql = "select * from prc_inventory as inv
						left join prc_master_currency as cur on cur.cur_id = inv.cur_id 
						where inv.pro_id = $row->pro_id and inv_price >= 0 order by inv.inv_transDate desc";
						$get_price = $this->flexigrid_sql($sql,false,'inv.pro_id');
						*/
						
						// LAST BUY
						/*
						$get_price = $this->tbl_rfq->rfq_content_price($row->pro_id);
						if ($get_price['price2']->num_rows() > 0):
						$set_price2 = $get_price['price2']->row();
						$set_price1 = $get_price['price1']->row();
						$lastbuy = $set_price2->price;
						$cur_digit = $set_price2->cur_digit;
						$cur_symbol = $set_price2->cur_symbol;
						else:
						$lastbuy = 0;
						$cur_digit = '2';
						$cur_symbol = 'Rp';
						endif;
						*/
						
						$get_price = "select inv.inv_price, cur.cur_digit, cur.cur_symbol  
						from prc_inventory as inv
						inner join prc_master_currency as cur on cur.cur_id = inv.cur_id
						where inv.pro_id = $row->pro_id 
						order by inv.inv_transDate desc
						";
						
						$rows = $this->db->query($get_price)->row();
						
						$record_items[] = array(
							$row->pro_id, // TABLE ID
							//$no,
							$row->pro_code,
							$row->pro_name,
							'<div style="width:100%;padding:0px"><div style="float:left;padding:0px">'.$row->satuan_name.'</div><div style="float:right;padding:0px">'.number_format($row->end_stock,$row->satuan_format).'</div></div>',
							'<div style="width:100%;padding:0px"><div style="float:left;padding:0px">'.$rows->cur_symbol.'</div><div style="float:right;padding:0px">'.number_format($rows->inv_price,$rows->cur_digit).'</div></div>',
							
						);
					break;
					case 'inv_history_detail' :
						$datetime = date_create($row->inv_transDate);
						$dateis = date_format($datetime, 'd-m-Y');
						$record_items[] = array(
							$row->inv_id,
							//$no,
							$dateis,
							$row->inv_document,						
							number_format($row->inv_begin,$row->satuan_format),
							number_format($row->inv_in,$row->satuan_format),
							number_format($row->inv_out,$row->satuan_format),
							number_format($row->inv_end,$row->satuan_format),
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
	
	function flexigrid_builder($title,$width,$height,$rp,$where='',$qstats='',$qvalidate='',$sort='asc') {
		/* FIELD
		 * 0 - display name
		 * 1 - width
		 * 2 - sortable
		 * 3 - align
		 * 4 - searchable (2 -> yes and default, 1 -> yes, 0 -> no.)
		 */
		
		switch ($qstats):
			case 'product' : 
				$colModel['pro_code'] = array($this->lang->line('inv_pro_code'),80,TRUE,'center',1,false,'flexEdit');
				$colModel['pro_name'] = array($this->lang->line('inv_pro_name'),210,TRUE,'left',2,false,'flexEdit');				
				$colModel['end_stock'] = array($this->lang->line('inv_stock_end'),80,TRUE,'center',0,false,'flexEdit');		
				$colModel['inv_price'] = array($this->lang->line('inv_last_buy'),80,FALSE,'center',0,false,'flexEdit');				
				$table_id = 'product_list';
			break;
		 	case 'inv_history_detail' :
				$colModel['inv_transDate'] = array($this->lang->line('inv_transDate'),80,TRUE,'center',2);
				$colModel['inv_document'] = array($this->lang->line('inv_document'),120,TRUE,'left',1);
				$colModel['inv_begin'] = array($this->lang->line('inv_begin'),100,TRUE,'right',1);
				$colModel['inv_in'] = array($this->lang->line('inv_in'),100,TRUE,'right',1);
				$colModel['inv_out'] = array($this->lang->line('inv_out'),100,TRUE,'right',1);
				$colModel['inv_end'] = array($this->lang->line('inv_end'),100,TRUE,'right',1);
				$colModel['dep_name'] = array($this->lang->line('dep_name'),140,TRUE,'left',0);
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
		return build_grid_js($table_id,$ajax_model,$colModel,$qvalidate,$sort,$params);
		
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
		$data['content'] = self::$link_view.'/entry_inv_main';
		$this->load->view('index',$data);
	}
	
	function tree() {
		$this->load->view(self::$link_view.'/entry_inv_tree');		
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
					$this->load->view(self::$link_view.'/entry_inv_list',$data);
				else:
					$data['js_grid'] = br(8).$this->lang->line('list_empty');
					$this->load->view(self::$link_view.'/entry_inv_list',$data);
				endif;
		else:
			$where['pro_status'] = 'active';
			if ($this->tbl_produk->get_product($where)->num_rows() > 0):
				$data['js_grid'] = $this->flexigrid_builder($this->lang->line('flex_produk'),519,260,11,$where,$qstats,$qvalidate);
			else:
				$data['js_grid'] = br(8).$this->lang->line('list_empty');
			endif;
			$this->load->view(self::$link_view.'/entry_inv_list',$data);
		endif;
	}
	
	function treecat_root() {
		echo $this->treeview->generate_tree('',true);
	}
	
	function treecat_node($key) {
		echo $this->treeview->generate_tree($key);
	}
	
	function kartu_stok($pro_id){
		// TABEL B -> PO YANG SEDANG PROSES
		$sql_po = "select p.po_no, d.qty, s.sup_name,leg.legal_name, (
				SELECT sum(qty) 
				FROM prc_gr_detail AS r 
				 inner join prc_gr as g on r.gr_id = g.gr_id
				WHERE r.pro_id = d.pro_id and g.po_id = d.po_id
				) AS terima, (
				SELECT (d.qty - sum(qty)) 
				FROM prc_gr_detail AS r 
				 inner join prc_gr as g on r.gr_id = g.gr_id
				WHERE r.pro_id = d.pro_id and g.po_id = d.po_id
				) AS sisa, sat.satuan_name, sat.satuan_format 
				from prc_pr_detail as d
		        inner join prc_po as p on d.po_id = p.po_id
				inner join prc_master_supplier as s on p.sup_id = s.sup_id
				left join prc_master_legality as leg on leg.legal_id = s.legal_id 
				inner join prc_master_satuan as sat on sat.satuan_id = d.um_id  
				where p.po_status = 0 and d.pro_id = '$pro_id'";
				
				/*
				inner join prc_good_return as grt on grt.po_id = p.po_id 
				inner join prc_good_return_detail as grtd on grtd.ret_id = grt.ret_id and grtd.pro_id = '$pro_id'
				*/
		
		// TABEL C -> PR DAN RFQ
		$sql_rfq = "select pd.qty, r.rfq_no, pd.requestStat, pr.pr_no, date_format(pr.pr_date, '%d-%m-%Y') as pr_date, u.usr_name
				from prc_pr_detail as pd
				inner join prc_pr as pr on pd.pr_id = pr.pr_id
				inner join prc_sys_user as u on u.usr_id = pr.pr_requestor
				left join prc_rfq as r on pd.rfq_id = r.rfq_id 
				where pd.pro_id = '$pro_id' and (pd.requestStat='0' or pd.requestStat='1' or pd.requestStat='2' or pd.requestStat='4')
				and (pd.rfq_stat='0' or pd.rfq_stat='1' or pd.rfq_stat='3') and pr.pr_status = 1 and pd.pcv_stat=0";//(pr.is_approved='0' or pr.is_approved='1')";
		$sql_limit = " limit 2";
		
		$sql_satuan = "select * from prc_master_satuan as sat, prc_master_product as pro where pro.um_id= sat.satuan_id and pro.pro_id ='$pro_id'";
		
		// TABEL A -> HISTORY STOK
		$sql_his = "
		select *,inv.sup_id as sup_id,
		inv.inv_begin as inv_begin, 
		inv.inv_in as inv_in, 
		inv.inv_out as inv_out, 
		inv.inv_end as inv_end 
		from prc_inventory as inv
		left join prc_master_product as pro on pro.pro_id = inv.pro_id 
		left join prc_master_satuan as sat on sat.satuan_id = pro.um_id 
		left join prc_master_supplier as sup on sup.sup_id = inv.sup_id
		left join prc_master_legality as leg on leg.legal_id = sup.legal_id 
		where inv.pro_id = $pro_id
		order by sup.sup_name, inv.inv_transDate	
		";
		
		$data['history_inv'] = $this->db->query($sql_his);
		
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
				
		$this->load->view(self::$link_view.'/entry_inv_stock',$data);
	}
		
	function product_history($pro_id,$sup_id,$doc) {
		$qstats = 'inv_history_detail';
		$qvalidate = 'inv_transDate';
		$sort = 'desc';
		$where['pro_id'] = $pro_id;
		$where['sup_id'] = $sup_id;
		
		$sql = "select sup.sup_id,sup.sup_name,leg.legal_name 
		from prc_master_supplier as sup
		left join prc_master_legality as leg on sup.legal_id = leg.legal_id
		where sup.sup_id = $sup_id";
		$get_sup = $this->flexigrid_sql($sql,false,'sup_id');
		
		$arr_doc = array('History','History');
		
		$supplier = $arr_doc[$doc];
		
		if ($get_sup->num_rows() > 0):
			$rep = str_replace("'",'',$get_sup->row()->sup_name);
			$supplier = ' Pemasok : <font color="red">'.$get_sup->row()->legal_name.'. '.$rep.'</font>';
		endif;
		
		$data['js_grid'] = $this->flexigrid_builder('Daftar '.$supplier,880,370,10,$where,$qstats,$qvalidate,$sort);
		
		$this->load->view(self::$link_view.'/entry_inv_history',$data);
	}
	
	function PRorMR($type) {
		$session = $this->session->userdata('sess_prmr_no');
		
		$data['pty_list'] = $this->tbl_prc_type->get_prc_type();
		$data['mrt_list'] = $this->tbl_prc_type->get_mr_type();
		$data['type'] = $type;
		
		if ($type == 'PR'):
			$pr_list = $this->db->query("select d.*, date_format(d.delivery_date,'%d-%m-%Y') as delivery_date, pro.pro_code, pro.pro_name, 
			pro.um_id, sat.satuan_id, sat.satuan_name, sat.satuan_format  
			from prc_pr_detail as d 
            inner join prc_pr as p on d.pr_id = p.pr_id
			inner join prc_master_product as pro on d.pro_id = pro.pro_id 
			inner join prc_master_satuan as sat on pro.um_id = sat.satuan_id 
			where p.pr_no='".$session."' order by d.pr_reqTime");
			$data['pr_list'] = $pr_list;
			if($pr_list->num_rows() > 0):
				$this->load->view(self::$link_view.'/entry_inv_pr',$data);
			endif;
		else:
			$mr_list = $this->db->query("select d.*, date_format(d.delivery_date,'%d-%m-%Y') as delivery_date, 
			 pro.pro_code, pro.pro_name, pro.um_id, pro.is_stockJoin, sup.sup_name, sat.satuan_id, sat.satuan_name, sat.satuan_format, leg.legal_name 
			 from prc_mr_detail as d 
             inner join prc_mr as p on d.mr_id = p.mr_id
			 inner join prc_master_product as pro on d.pro_id = pro.pro_id 
			 left join prc_master_supplier as sup on d.sup_id = sup.sup_id
			 left join prc_master_legality as leg on leg.legal_id = sup.legal_id
			 inner join prc_master_satuan as sat on pro.um_id = sat.satuan_id 
			 where p.mr_no='".$session."' order by d.mr_reqTime");	 
			$data['mr_list'] = $mr_list;
			if($mr_list->num_rows() > 0):
				$this->load->view(self::$link_view.'/entry_inv_mr',$data);
			endif;
		endif;
		//$data['um_list'] = $this->tbl_unit->get_unit();	
		//$this->load->view(self::$link_view.'/entry_inv_prmr',$data);
		
	}
	
	function savePRorMR($type,$pro_id) {
		$session = $this->session->userdata('sess_prmr_no');
		$usr_id = $this->session->userdata('usr_id');
	
		$datetime = date('Y-m-d H:i:s');
		$thn = date('Y');
		$bln = date('n');
		$proses = false;
		$proses = '';
		$stok = false;
		
		if ($type == 'PR'):
			$where_pr['pr_no'] = $session;
			$get_pr = $this->tbl_pr->get_pr($where_pr);
			if ($get_pr->num_rows() == 0):
				$insert_pr['pr_requestor'] = $usr_id;
				$insert_pr['pr_no'] = $session;
				$insert_pr['pr_date'] = $datetime;
				// INSERT PR
				$this->tbl_pr->insert_pr($insert_pr);
			endif;
						
			// INSERT PR DETAIL
			$where_pr['pr_no'] = $session;
			$get_pr = $this->tbl_pr->get_pr($where_pr);
			if ($get_pr->num_rows() > 0):
				$insert_pr_det['pr_id'] = $get_pr->row()->pr_id;
				$insert_pr_det['pro_id'] = $pro_id;
				//$insert_pr_det['requestStat'] = '1';
				if ($this->tbl_pr->get_pr_detail($insert_pr_det)->num_rows() == 0):
					if ($this->tbl_pr->insert_pr_detail($insert_pr_det)):
						$proses = true;
					endif;
				endif;
			else:
				//$proses .= 'PR CEK GATOT';
			endif;
		else:		
			// CEK STOK
			$sql = "select sum(inv_end) as stok_akhir from prc_inventory where pro_id = $pro_id";
			$stok_akhir = $this->db->query($sql)->row()->stok_akhir;
			
			if ($stok_akhir > 0):
			
				$where_mr['mr_no'] = $session;
				$get_mr = $this->tbl_mr->get_mr($where_mr);
				if ($get_mr->num_rows() == 0):
					$insert_mr['mr_requestor'] = $usr_id;
					$insert_mr['mr_no'] = $session;
					$insert_mr['mr_date'] = $datetime;
					// INSERT MR
					$this->tbl_mr->insert_mr($insert_mr);
				endif;	
				// INSERT MR DETAIL
				$where_mr['mr_no'] = $session;
				$get_mr = $this->tbl_mr->get_mr($where_mr);
				if ($get_mr->num_rows() > 0):
					$insert_mr_det['mr_id'] = $get_mr->row()->mr_id;
					$insert_mr_det['pro_id'] = $pro_id;
					//$insert_mr_det['requestStat'] = '1';
					if ($this->tbl_mr->get_mr_detail($insert_mr_det)->num_rows() == 0):
						if ($this->tbl_mr->insert_mr_detail($insert_mr_det)):
							$proses = true;
						endif;
					endif;
				else:
					//$proses .= 'MR CEK GATOT';
				endif;
			
			else:
				$stok = true;
			endif;
		endif;
		
		//echo $stok_akhir;
		if ($stok==TRUE) {echo 'kosong';}
		else if ($proses==TRUE) { echo 'sukses'; }
	}
	
	function usulPR() {
	
		$pro_id = $this->input->post('pr_pro_id');
		$qty = $this->input->post('pr_qty');
		$um_id = $this->input->post('pr_um_id');
		$delivery_date = $this->input->post('pr_delivery_date');
		$description = $this->input->post('pr_description');

		// PR DETAIL
		$pr_id = $this->input->post('pr_id');
		$buy_via = $this->input->post('pr_buy_via');
		$emergencyStat = $this->input->post('pr_emergencyStat');
		$pty_id = $this->input->post('pr_pty_id');
		
		// SERVICE
		$so_id = $this->input->post('pr_so_id');
		
		for($i=0;$i<sizeof($pro_id);$i++):
					
			$where['pr_id'] = $pr_id;
			$where['pro_id'] = $pro_id[$i];
			
			$update['so_id'] = $so_id[$i];
			
			$update['pty_id'] = $pty_id[$i];
			$update['qty'] = $qty[$i];
			$update['um_id'] = $um_id[$i];
			 
			if ($delivery_date[$i] != ''):
				$update['delivery_date'] = date_format(date_create($delivery_date[$i]),'Y-m-d');
			endif;
			
			$update['buy_via'] = $buy_via[$i];
			$update['emergencyStat'] = $emergencyStat[$i];
			$update['description'] = trim($description[$i]);
			
			if ($this->tbl_pr->update_pr_detail($where,$update)):
				echo "sukses";
			endif;
		endfor;
		
	}
	
	function usulMR() {
		
		$is_join = $this->input->post('mr_is_join');
		$pro_id = $this->input->post('mr_pro_id');
		$qty = $this->input->post('mr_qty');
		$qty_stok = $this->input->post('mr_pro_stok');
		
		$satuan = $this->input->post('mr_um_id');
		
		$delivery_date = $this->input->post('mr_delivery_date');
		$description = $this->input->post('mr_description');
		
		$mr_id = $this->input->post('mr_id');
		$sup_id = $this->input->post('mr_sup_id');	
		$mct_id = $this->input->post('mr_mct_id');
		
		// SERVICE
		$so_id = $this->input->post('mr_so_id');
		
		for($i=0;$i<sizeof($pro_id);$i++):
			
			// AMBIL NILAI SELECT SATUAN
			$exp_satuan = explode('_',$satuan[$i]);
			$um_id[$i] = $exp_satuan[0];
			
			$where['mr_id'] = $mr_id;
			$where['pro_id'] = $pro_id[$i];
			
			$update['so_id'] = $so_id[$i];
			
			$update['sup_id'] = $sup_id[$i];
			$update['qty'] = $qty[$i];				
			$update['um_id'] = $um_id[$i];
			
			if ($delivery_date[$i] != ''):
				$update['delivery_date'] = date_format(date_create($delivery_date[$i]),'Y-m-d');
			endif;
			
			$update['mct_id'] = $mct_id[$i];
			$update['description'] = trim($description[$i]);
			
			if ($this->tbl_mr->update_mr_detail($where,$update)):
				echo "sukses";
			endif;
			
		endfor;
		
	}	
	
	function cek_tabs() {
		$session = $this->session->userdata('sess_prmr_no');
		//echo $session;
		
		$where_pr['pr_no'] = $session;
		$get_pr = $this->tbl_pr->get_pr($where_pr);
		if ($get_pr->num_rows() > 0):
			$where_pr_det['pr_id'] = $get_pr->row()->pr_id;
			$get_pr_det = $this->tbl_pr->get_pr_detail($where_pr_det)->num_rows();
		else:
			$get_pr_det = 0;
		endif;
		
		$where_mr['mr_no'] = $session;
		$get_mr = $this->tbl_mr->get_mr($where_mr);
		if ($get_mr->num_rows() > 0):
			$where_mr_det['mr_id'] = $get_mr->row()->mr_id;
			$get_mr_det = $this->tbl_mr->get_mr_detail($where_mr_det)->num_rows();
		else:
			$get_mr_det = 0;
		endif;
		
		if ($get_pr_det > 0) $pr = 1; else $pr = 0;
		if ($get_mr_det > 0) $mr = 1; else $mr = 0;
		echo '[{
		"PR":"'.$pr.'",
		"MR":"'.$mr.'",
		"PR_DATA":"'.$get_pr_det.'",
		"MR_DATA":"'.$get_mr_det.'"
		}]';	
	}
	
	function prosesMR() {
		$datetime = date('Y-m-d H:i:s');
		$thn = date('Y');
		$thn_min = date('y');
		$bln = date('n');
		$bln_max = date('m');
		
		// CEK COUNTER
		
		$where_sys['thn'] = $thn;
		$where_sys['bln'] = $bln;
		$get_counter = $this->tbl_sys_counter->get_counter($where_sys);
		if ($get_counter->num_rows() > 0)
			$mr_no = $get_counter->row()->mr_no;
		else
			if ($this->tbl_sys_counter->insert_counter($where_sys))
				$mr_no = 1;
		
		$is_join = $this->input->post('mr_is_join');
		$pro_id = $this->input->post('mr_pro_id');
		$qty = $this->input->post('mr_qty');
		$qty_stok = $this->input->post('mr_pro_stok');
		
		$satuan = $this->input->post('mr_um_id');
		
		$delivery_date = $this->input->post('mr_delivery_date');
		$description = $this->input->post('mr_description');
		
		$mr_id = $this->input->post('mr_id');
		$sup_id = $this->input->post('mr_sup_id');	
		$mct_id = $this->input->post('mr_mct_id');
		
		// SERVICE
		$so_id = $this->input->post('mr_so_id');
		
		for($i=0;$i<sizeof($pro_id);$i++):
			// AMBIL NILAI SELECT SATUAN
			$exp_satuan = explode('_',$satuan[$i]);
			$um_id[$i] = $exp_satuan[0];
			//$um_val[$i] = $exp_satuan[1];
			
			// KONVERSI SATUAN MULTI (non multi X 1);
			//$qty[$i] = $qty[$i] * $um_val[$i];
			
			// VALIDASI STOK
			/*
			if ($qty[$i] > $qty_stok[$i]):
				$error[] = 'STOK TIDAK MENCUKUPI';
			endif;
			*/
			
			//echo $i.'MR='.$mr_id[$i].'PRO='.$pro_id[$i].'SUP='.$sup_id[$i].'QTY='.$qty[$i].'QTY_STOK='.$qty_stok[$i].'UM_VALUE='.$um_val[$i].'UM='.$um_id[$i].'TGL='.$delivery_date[$i].'MCT='.$mct_id[$i].'DESC='.$description[$i].'<br>';			
			
			$where['mr_id'] = $mr_id;
			$where['pro_id'] = $pro_id[$i];
			
			$update['so_id'] = $so_id[$i];
			
			$update['sup_id'] = $sup_id[$i];
			$update['qty'] = $qty[$i];				
			$update['um_id'] = $um_id[$i];
			$update['delivery_date'] = date_format(date_create($delivery_date[$i]),'Y-m-d');
			$update['mct_id'] = $mct_id[$i];
			$update['description'] = trim($description[$i]);
			
			if ($this->tbl_mr->update_mr_detail($where,$update)):
				$update_his['mr_id'] = $mr_id;
				$update_his['pro_id'] = $pro_id[$i];
				$update_his['sup_id'] = $sup_id[$i];
				$update_his['qty'] = $qty[$i];				
				$update_his['um_id'] = $um_id[$i];
				
				$update_his['so_id'] = $so_id[$i];
				
				$update_his['delivery_date'] = date_format(date_create($delivery_date[$i]),'Y-m-d');
				//$update_his['mct_id'] = $mct_id[$i];
				$update_his['description'] = trim($description[$i]);
				$this->tbl_mr->insert_mr_history($update_his);
			endif;
			
		endfor;
		
		// MR
		$session = $this->session->userdata('sess_prmr_no');
		$where_mr['mr_no'] = $session;
		$get_mr = $this->tbl_mr->get_mr($where_mr);
		if ($get_mr->num_rows() > 0):
			$mr_num = str_pad($mr_no, 4, "0", STR_PAD_LEFT); 
			// MR NUMBER
			$mr_doc_no = $this->lang->line('mr_doc_no');
			$update_mr['mr_no'] = $thn_min.'/'.$bln_max.'/'.$mr_doc_no.$mr_num;
			$update_mr['mr_status'] = '1';
			if ($this->tbl_mr->update_mr($where_mr,$update_mr)):
				$MR_stats = true;
				$prmrno['mr_no_final'] = $update_mr['mr_no'];
			endif;
		endif;
		
		$this->proses_prmr($where_sys,'MR',$prmrno);	
	}	
	
	function prosesPR() {
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
			$pr_no = $get_counter->row()->pr_no;
			$mr_no = $get_counter->row()->mr_no;
		else:
			$this->tbl_sys_counter->insert_counter($where_sys);
			$pr_no = 1;
			$mr_no = 1;
		endif;
	
		$pro_id = $this->input->post('pr_pro_id');
		$qty = $this->input->post('pr_qty');
		$um_id = $this->input->post('pr_um_id');
		$delivery_date = $this->input->post('pr_delivery_date');
		$description = $this->input->post('pr_description');

		// PR DETAIL
		$pr_id = $this->input->post('pr_id');
		$buy_via = $this->input->post('pr_buy_via');
		$emergencyStat = $this->input->post('pr_emergencyStat');
		$pty_id = $this->input->post('pr_pty_id');
		
		// SERVICE
		$so_id = $this->input->post('pr_so_id');
		
		for($i=0;$i<sizeof($pro_id);$i++):
			
			$where['pr_id'] = $pr_id;
			$where['pro_id'] = $pro_id[$i];
			
			$update['so_id'] = $so_id[$i];
			
			$update['pty_id'] = $pty_id[$i];
			$update['qty'] = $qty[$i];
			$update['um_id'] = $um_id[$i];
			$update['delivery_date'] = date_format(date_create($delivery_date[$i]),'Y-m-d');
			$update['buy_via'] = $buy_via[$i];
			$update['emergencyStat'] = $emergencyStat[$i];
			$update['description'] = trim($description[$i]);
			
			if ($this->tbl_pr->update_pr_detail($where,$update)):
				$update_his['pr_id'] = $pr_id;
				$update_his['pro_id'] = $pro_id[$i];
				$update_his['pty_id'] = $pty_id[$i];
				$update_his['qty'] = $qty[$i];
				$update_his['um_id'] = $um_id[$i];
				
				$update_his['so_id'] = $so_id[$i];
				
				$update_his['delivery_date'] = date_format(date_create($delivery_date[$i]),'Y-m-d');
				$update_his['buy_via'] = $buy_via[$i];
				$update_his['emergencyStat'] = $emergencyStat[$i];
				$update_his['description'] = trim($description[$i]);
				$this->tbl_pr->insert_pr_history($update_his);
			endif;
		endfor;
			
		// PR
		$session = $this->session->userdata('sess_prmr_no');
		$where_pr['pr_no'] = $session;
		$get_pr = $this->tbl_pr->get_pr($where_pr);
		if ($get_pr->num_rows() > 0):
			$pr_num = str_pad($pr_no, 4, "0", STR_PAD_LEFT); 
			// PR NUMBER
			$pr_doc_no = $this->lang->line('pr_doc_no');
			$update_pr['pr_no'] = $thn_min.'/'.$bln_max.'/'.$pr_doc_no.$pr_num;
			$update_pr['pr_status'] = '1';
			if ($this->tbl_pr->update_pr($where_pr,$update_pr)):
				$PRorMR_stats = true;
				$prmrno['pr_no_final'] = $update_pr['pr_no'];
			endif;
		endif;
		
		$this->proses_prmr($where_sys,'PR',$prmrno);
		
	}
	
	function proses_prmr($where_sys,$type,$prmrno) {
		// UPDATE COUNTER
		$get_counter = $this->tbl_sys_counter->get_counter($where_sys);
		if ($get_counter->num_rows() > 0):
			$dlg_data =  '<STRONG>Selamat... '.$type.' berhasil dibuat <br />
				No '.$type.' : <font color="red">';
			if ($type=='PR'):
				$update_counter['pr_no'] = $get_counter->row()->pr_no + 1;
				$dlg_data .= $prmrno['pr_no_final'].'</font></STRONG>';
			else:
				$update_counter['mr_no'] = $get_counter->row()->mr_no + 1;
				$dlg_data .= $prmrno['mr_no_final'].'</font></STRONG>';
			endif;
			$this->tbl_sys_counter->update_counter($where_sys,$update_counter);
			$dlg_title = $type.' GAGAL DIPROSES';
		else:
			//echo 'GATOT';		
			$dlg_data = '<STRONG>Maaf... Data '.$type.' tidak berhasil ditambahkan</STRONG>';
			$dlg_title = 'GAGAL';
		endif;

		$where_prmr[strtolower($type).'_requestor'] = $this->session->userdata('usr_id');
		$where_prmr[strtolower($type).'_status'] = '0';
		$get_prmr_temp = $this->tbl_pr->get_prmr($where_prmr,$type);

		if ($get_prmr_temp->num_rows() > 0):
			foreach ($get_prmr_temp->result() as $row_prmr):
				if ($type=='PR'):
					$where_prmr_det['pr_id'] = $row_prmr->pr_id;
				else:
					$where_prmr_det['mr_id'] = $row_prmr->mr_id;
				endif;
				$del_prmr_det = $this->tbl_pr->delete_prmr_det($where_prmr_det,$type);
				$del_prmr = $this->tbl_pr->delete_prmr($where_prmr_det,$type);
			endforeach;
			
		endif;			

		echo $dlg_data;
	}
	
	function sup_add($row_id,$pro_id) {
		$data['row_id'] = $row_id;
		//$where['pro_id'] = $pro_id;
		$sql = "select * from prc_inventory as inv 
		inner join prc_master_supplier as sup on inv.sup_id = sup.sup_id 
		inner join prc_master_legality as leg on leg.legal_id = sup.legal_id 
		inner join prc_master_product as pro on inv.pro_id = pro.pro_id 
		inner join prc_master_satuan as sat on pro.um_id = sat.satuan_id
		where inv.pro_id = $pro_id
		order by inv.sup_id
		";
		$data['sup_list'] = $this->db->query($sql);//$this->tbl_inventory->get_inv_sup($where);
		//$data['id_row'] = '#row_';
		$this->load->view(self::$link_view.'/entry_inv_supplier_list',$data);
	}
	
	function list_so($row_id,$type) {
		$data['row_id'] = $row_id;
		$data['type'] = $type;
		$sql = "select so_id, so_no, date_format(so_date,'%d/%m/%Y') as so_date from prc_so
		where so_status = 0 and so_printStat = 1
		order by so_no
		";
		$data['so_list'] = $this->db->query($sql);
		$this->load->view(self::$link_view.'/entry_inv_so',$data);
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
	
	function del_prmr_row($stats,$prmr_id,$pro_id) {
		//$stats = strtoupper($stats);
		if ($stats=='pr'):
			$wherepr['pr_id'] = $prmr_id;
			$wherepr['pro_id'] = $pro_id;
			if ($this->tbl_pr->delete_prmr_det($wherepr,$stats)):
				echo 'sukses';
			endif;
			$where_pr['pr_id'] = $prmr_id;
			if ($this->tbl_pr->get_prmr_det($where_pr,$stats)->num_rows() == 0):
				$this->tbl_pr->delete_prmr($where_pr,$stats);
			endif;
		else:
			$wheremr['mr_id'] = $prmr_id;
			$wheremr['pro_id'] = $pro_id;
			if ($this->tbl_pr->delete_prmr_det($wheremr,$stats)):
				echo 'sukses';
			endif;
			$where_mr['mr_id'] = $prmr_id;
			if ($this->tbl_pr->get_prmr_det($where_mr,$stats)->num_rows() == 0):
				$this->tbl_pr->delete_prmr($where_mr,$stats);
			endif;
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
