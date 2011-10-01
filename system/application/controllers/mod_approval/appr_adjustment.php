<?php
class appr_adjustment extends MY_Controller {
	public static $link_view, $link_controller, $user_id;
	function appr_adjustment() {
		parent::MY_Controller();
		$this->load->model(array('tbl_adjustment','tbl_inventory','flexi_model'));
		$this->load->library(array('general','treeview','pro_code','flexigrid','flexi_engine'));
		$this->load->helper(array('flexigrid','html'));
		$this->config->load('flexigrid');
		$this->config->load('tables');
		
		$this->lang->load('mod_approval/appr_adjustment','bahasa');
		//$this->lang->load('mod_master/produk','bahasa');
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
		
		self::$link_controller = 'mod_approval/appr_adjustment';
		self::$link_view = 'purchase/mod_approval/adj_appr';
 		$data['link_controller'] = self::$link_controller;
		$data['link_view'] = self::$link_view;
		
		$data['page_title'] = $this->lang->line('appr_adjustment_title');
		
		$this->load->vars($data);
	}
	
	// QUERY UNTUK NON FLEXIGRID ATO FLEXIGRID
	function flexigrid_sql($flexi=TRUE,$count='adj.adj_id',$where=FALSE) {
		$sql = "select *, date_format(adj.date_create,'%d-%m-%Y') as adj_date {COUNT_STR} 
		from prc_adjustment as adj
		inner join prc_sys_user as usr on usr.usr_id = adj.adj_requestor 
		inner join prc_master_departemen as dep on dep.dep_id = usr.dep_id 
		where adj.adj_status = 1 and 
		(select count(adj_det.adj_id) as jml_app from prc_adjustment_detail as adj_det where adj.adj_id = adj_det.adj_id and adj_det.is_approve = 0) > 0 {SEARCH_STR}";
		return $this->flexi_model->generate_sql($sql,$count,$where,$flexi);
	}
	
	function flexigrid_ajax($cat_code='')
	{		
		$this->flexigrid->validate_post('adj.adj_id','asc');//,$valid_fields);

		$records = $this->flexigrid_sql();
		
		$this->output->set_header($this->config->item('json_header'));
		
		if ($records['count'] > 0):		
			foreach ($records['result']->result() as $row)
			{
				$record_items[] = array(
				$row->adj_id, // TABLE ID
				$row->adj_no,
				$row->adj_date,
				$row->dep_name,
				'<a href=\'javascript:void(0)\' onclick=\'open_adj('.$row->adj_id.')\'><img border=\'0\' src=\'./asset/img_source/icon_info.gif\'></a>'
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
		$colModel['adj_no'] = array('No Penyesuaian',100,TRUE,'center',1);
		$colModel['adj_date'] = array('Tanggal',100,TRUE,'center',2);
		$colModel['dep_name'] = array('Departemen',150,TRUE,'left',1);
		$colModel['opsi'] = array('Opsi',50,TRUE,'center',1);
		//$colModel['is_stockJoin'] = array($this->lang->line('is_stockJoin'),90, TRUE,'center',0,false,'flexEdit');
			
		
		/* BUILD FLEXIGRID
		 * build_grid_js(<div id>,<ajax function>,<field model>,<first field selection>,<order by>,<configuration>,<button>);
		 */
		
		if ($pro_code != ''):
			$ajax_model = site_url(self::$link_controller."/flexigrid_ajax/".$pro_code);
		else:
			$ajax_model = site_url(self::$link_controller."/flexigrid_ajax");
		endif;
		
		return build_grid_js('flex_adj',$ajax_model,$colModel,'adj_no','asc',$this->flexi_engine->flexi_params($width,$height,$rp,$title));
		
	}
	
	function index() {
		$records = $this->flexigrid_sql(false);
		if ($records->num_rows() > 0):		
			$data['js_grid'] = $this->flexigrid_builder('Daftar Penyesuaian',880,210,8);
		else:
			$data['js_grid'] = $this->lang->line('list_empty');
		endif;
		$data['content'] = self::$link_view.'/appr_adj_main';
		$this->load->view('index',$data);
	}
	
	function show_adjustment($adj_id) {
		$sql_adj = "select *, date_format(adj.date_create,'%d-%m-%Y') as adj_date 
		from prc_adjustment as adj
		inner join prc_sys_user as usr on usr.usr_id = adj.adj_requestor 
		inner join prc_master_departemen as dep on dep.dep_id = usr.dep_id 
		where adj.adj_status = 1 and adj.adj_id = $adj_id order by adj.date_create";
		
			$sql_adj_det = "select *, adj_det.pro_id as adj_pro_id, adj_det.sup_id as adj_sup_id,
			date_format(adj_det.date_opname,'%d-%m-%Y') as adj_date 
			from prc_adjustment_detail as adj_det
			left join prc_master_supplier as sup on sup.sup_id = adj_det.sup_id
			inner join prc_master_product as pro on pro.pro_id = adj_det.pro_id
			inner join prc_master_satuan as sat on sat.satuan_id = pro.um_id
			inner join prc_adjustment as adj on adj.adj_id = adj_det.adj_id
			inner join prc_sys_user as usr on usr.usr_id = adj.adj_requestor 
			inner join prc_master_departemen as dep on dep.dep_id = usr.dep_id 
			left join prc_master_supplier_product as sup_pro on sup_pro.pro_id = adj_det.pro_id and sup_pro.sup_id = adj_det.sup_id
			inner join prc_inventory as inv on inv.pro_id = adj_det.pro_id and inv.sup_id = adj_det.sup_id 
			where adj.adj_status = 1 and adj.adj_id = $adj_id order by adj_det.pro_id, adj_det.date_opname";

		$data['list_adj'] =  $this->db->query($sql_adj);
		$data['list_adj_det'] =  $this->db->query($sql_adj_det);
		
		//$data['content'] = self::$link_view.'/appr_adj_cek';
		$this->load->view(self::$link_view.'/appr_adj_cek',$data);
	}
	
	function get_history($pro_id) {
		$sql = 	"select *, 
		date_format(adj.date_create,'%d-%m-%Y') as adj_date,
		date_format(adj_det.date_opname,'%d-%m-%Y') as opname_date  
		from prc_adjustment_detail as adj_det
		left join prc_master_supplier as sup on sup.sup_id = adj_det.sup_id
		inner join prc_master_product as pro on pro.pro_id = adj_det.pro_id
		inner join prc_master_satuan as sat on sat.satuan_id = pro.um_id
		inner join prc_adjustment as adj on adj.adj_id = adj_det.adj_id
		inner join prc_sys_user as usr on usr.usr_id = adj.adj_requestor 
		inner join prc_master_departemen as dep on dep.dep_id = usr.dep_id 
		inner join prc_master_legality as pt on pt.legal_id = sup.legal_id
		where adj.adj_status = 1 and adj_det.pro_id = $pro_id";
		
		$data['adj_history'] = $this->db->query($sql);
		
		$this->load->view(self::$link_view.'/appr_adj_history',$data);
	}
	
	function set_approve() {
		$inv_id = $this->input->post('inv_id');
		$adj_id = $this->input->post('adj_id');
		$adj_no = $this->input->post('adj_no');
		$pro_id = $this->input->post('pro_id');
		$pro_name = $this->input->post('pro_name');
		$sup_id = $this->input->post('sup_id');	
		$stats = $this->input->post('stats');
		$alasan = $this->input->post('alasan');
		
		$qty_stok = $this->input->post('qty_stok');
		$qty_opname = $this->input->post('qty_opname');
		$inv_end = $this->input->post('inv_end');
		
		$arr_cek = array();
		
		for ($i=0 ; $i < sizeOf($pro_id) ; $i++):
			
			// PROSES ADJUSTMENT
			if ($qty_opname[$i] > $qty_stok[$i]):
				$qty_adj[$i] = $qty_opname[$i] - $qty_stok[$i];
				$qty_end[$i] = $inv_end[$i] + $qty_adj[$i];
				$inv_type[$i] = "inv_in";
			elseif ($qty_opname[$i] < $qty_stok[$i]):
				$qty_adj[$i] = $qty_stok[$i] - $qty_opname[$i];
				$qty_end[$i] = $inv_end[$i] - $qty_adj[$i];
				$inv_type[$i] = "inv_out";
			endif;
			
			//echo $inv_id[$i].'|'.$pro_id[$i].'|'.$sup_id[$i].'|'.$stats[$i].'|'.$alasan[$i].'|'.$qty_stok[$i].'|'.$qty_opname[$i].'|'.$qty_adj[$i].'|'.$qty_end[$i].'|'.$inv_type[$i].'|'.$inv_end[$i].'<br>';
			
			$where['adj_id'] = $adj_id;
			$where['pro_id'] = $pro_id[$i];
			$where['sup_id'] = $sup_id[$i];
			$update['is_approve'] = $stats[$i];
			$update['appr_note'] = $alasan[$i];
			if ($this->tbl_adjustment->update_adj_detail($where,$update)):
				// UPDATE INVENTORY
				$where_inv['inv_id'] = $inv_id[$i];
				$where_inv['pro_id'] = $pro_id[$i];
				$where_inv['sup_id'] = $sup_id[$i];
				$data_inv['inv_begin'] = $inv_end[$i];
				if ($inv_type[$i] == 'inv_in'):
					$data_inv['inv_in'] = $qty_adj[$i];
					$data_inv['inv_out'] = 0;
				else:
					$data_inv['inv_in'] = 0;
					$data_inv['inv_out'] = $qty_adj[$i];
				endif;
				$data_inv['inv_end'] = $qty_end[$i];
				$data_inv['inv_document'] = $adj_no;
				if ($this->tbl_inventory->update_inventory($where_inv,$data_inv)):
					$data_inv_his['inv_id'] = $inv_id[$i];
					$data_inv_his['pro_id'] = $pro_id[$i];
					$data_inv_his['sup_id'] = $sup_id[$i];
					$data_inv_his['inv_begin'] = $inv_end[$i];
					if ($inv_type[$i] == 'inv_in'):
						$data_inv_his['inv_in'] = $qty_adj[$i];
						$data_inv_his['inv_out'] = 0;
					else:
						$data_inv_his['inv_in'] = 0;
						$data_inv_his['inv_out'] = $qty_adj[$i];
					endif;
					$data_inv_his['inv_end'] = $qty_end[$i];
					$data_inv_his['inv_document'] = $adj_no;	
					if ($this->tbl_inventory->save_inv_history($data_inv_his)):
						$arr_cek[$i] = true;
					endif;
				endif;
			endif;
			
		endfor; 
	
		if (!in_array(false,$arr_cek)):
			echo $adj_no;
		endif;
	
	}
}
?>