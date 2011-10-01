<?php
class appr_good_return extends MY_Controller {
	public static $link_view, $link_controller, $user_id;
	function appr_good_return() {
		parent::MY_Controller();
		$this->load->model(array('tbl_good_return','tbl_gr','tbl_pr','flexi_model'));
		$this->load->library(array('general','treeview','pro_code','flexigrid','flexi_engine','general'));
		$this->load->helper(array('flexigrid','html'));
		$this->config->load('flexigrid');
		$this->config->load('tables');
		
		//$this->lang->load('mod_approval/appr_goodreturn','bahasa');
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
		
		self::$link_controller = 'mod_approval/appr_good_return';
		self::$link_view = 'purchase/mod_approval/ret_appr';
 		$data['link_controller'] = self::$link_controller;
		$data['link_view'] = self::$link_view;
		
		$data['page_title'] = $this->lang->line('appr_goodreturn_title');
		
		$this->load->vars($data);
	}
	
	// QUERY UNTUK NON FLEXIGRID ATO FLEXIGRID
	function flexigrid_sql($flexi=TRUE,$count='g.ret_id',$where=FALSE) {
		$sql = "select *, date_format(ret_date,'%d-%m-%Y') as ret_date {COUNT_STR} 
        from prc_good_return as g 
		inner join prc_po as p on p.po_id = g.po_id
		inner join prc_master_supplier as sup on sup.sup_id = p.sup_id
		where g.ret_status = 0 and g.ret_printStatus='0' {SEARCH_STR}";
		return $this->flexi_model->generate_sql($sql,$count,$where,$flexi);
	}
	
	function flexigrid_ajax($cat_code='')
	{		
		$valid_fields = array('ret_id','ret_no','ret_date','po_no','sup_name');
		
		$this->flexigrid->validate_post('g.ret_id','asc',$valid_fields);

		$records = $this->flexigrid_sql();
		
		$this->output->set_header($this->config->item('json_header'));
		
		if ($records['count'] > 0):		
			foreach ($records['result']->result() as $row)
			{
				$record_items[] = array(
				$row->ret_id, // TABLE ID
				$row->ret_date,
				$row->ret_no,
				$row->po_no,
				$row->sup_name,
				'<a href=\'javascript:void(0)\' onclick=\'open_retur('.$row->ret_id.')\'><img border=\'0\' src=\'./asset/img_source/icon_info.gif\'></a>'
				);
			}
		else: 
			$record_items[] = array('0','null','null','empty','empty','null','null');
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
		$colModel['ret_date'] = array('Tgl Retur',100,TRUE,'center',1);
		$colModel['ret_no'] = array('No Retur',100,TRUE,'center',2);
		$colModel['po_no'] = array('No PO',100,TRUE,'center',1);
		$colModel['sup_name'] = array('Pemasok',250,TRUE,'left',1);
		//$colModel['gr_suratJalan'] = array('No Surat Jalan',150,TRUE,'center',1);
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
		
		return build_grid_js('flex_adj',$ajax_model,$colModel,'ret_date','asc',$this->flexi_engine->flexi_params($width,$height,$rp,$title));
		
	}
	
	function index() {
		$records = $this->flexigrid_sql(false);
		if ($records->num_rows() > 0):		
			$data['js_grid'] = $this->flexigrid_builder('Daftar Retur Barang',700,210,8);
		else:
			$data['js_grid'] = $this->lang->line('list_empty');
		endif;
		$data['content'] = self::$link_view.'/appr_retur_main';
		$this->load->view('index',$data);
	}
	
	function show_retur($ret_id) {
		$sql_ret = "select g.ret_id,g.ret_no,dep.dep_name, date_format(g.ret_date,'%d-%m-%Y') as ret_date,
         p.po_id, p.po_no, s.sup_name, s.sup_id , usr.usr_name 
		 from prc_good_return as g
         inner join prc_po as p on g.po_id = p.po_id
		 inner join prc_sys_user as usr on g.ret_requestor = usr.usr_id 
		 inner join prc_master_departemen as dep on dep.dep_id = usr.dep_id 
		 inner join prc_master_supplier as s on s.sup_id = p.sup_id
		 where g.ret_id = '".$ret_id."'";
		
		$sql_ret_det = "
		select g.ret_id,g.ret_no,g.po_id,p.po_no,gd.pro_id,pro.pro_name,pro.pro_code,pd.um_id,pd.qty_terima,gd.qty,gd.keterangan,sat.satuan_name,sat.satuan_format 
		from prc_good_return as g
		inner join prc_good_return_detail as gd on g.ret_id = gd.ret_id
		inner join prc_master_product as pro on gd.pro_id = pro.pro_id
		inner join prc_po as p on g.po_id = p.po_id
		inner join prc_pr_detail as pd on pd.po_id = p.po_id and pd.pro_id = gd.pro_id
		inner join prc_master_satuan as sat on pd.um_id = sat.satuan_id
		where g.ret_id = '".$ret_id."'";
		
		$data['list_ret'] =  $this->db->query($sql_ret);
		$data['list_ret_det'] =  $this->db->query($sql_ret_det);
		
		//$data['content'] = self::$link_view.'/appr_adj_cek';
		$this->load->view(self::$link_view.'/appr_retur_cek',$data);
	}
	
	function get_history($pro_id) {
		$sql = 	"select g.gr_no, d.qty, d.price, date_format(dh.date_edit,'%d-%m-%Y') as date_edit, us.usr_name, 
		cur.cur_symbol, u.satuan_name, pd.um_id, p.pro_name 
		from prc_gr_detail as d 
		inner join prc_gr_detail_history as dh on d.gr_id = dh.gr_id and d.pro_id = dh.pro_id
		inner join prc_gr as g on g.gr_id = d.gr_id 
		inner join prc_master_product as p on p.pro_id = d.pro_id 
		inner join prc_pr_detail as pd on pd.po_id = g.po_id and pd.pro_id = d.pro_id
		inner join prc_master_satuan as u on pd.um_id = u.satuan_id 
		inner join prc_master_currency as cur on d.cur_id = cur.cur_id 
		inner join prc_master_satuan as sat on pd.um_id = sat.satuan_id
		inner join prc_sys_user as us on dh.usr_id = us.usr_id
		where dh.pro_id = $pro_id and (g.gr_status = '1' or g.gr_status = '3') and dh.document = 'ADJ' order by dh.date_edit";
		
		$data['adj_history'] = $this->db->query($sql);
		
		$this->load->view(self::$link_view.'/appr_retur_history',$data);
	}
	
	function set_approve() {
		$ret_id = $this->input->post('ret_id');
		$po_no = $this->input->post('po_no');
		$pro_id = $this->input->post('pro_id');
		$stats = $this->input->post('stats');
		$qty_ubah = $this->input->post('qty_ubah');
		$alasan = $this->input->post('alasan');
		
		$ret_no = $this->input->post('ret_no');
		
		$arr_cek = array();
		
		$where['ret_id'] = $ret_id;
		$update['ret_status'] = $stats;
		if ($this->tbl_good_return->update_return($where,$update)):
				
			if ($stats == 2):	
				for ($i=0 ; $i < sizeOf($pro_id) ; $i++):
					$arr_cek[$i] = false;
					$where_det['ret_id'] = $ret_id;
					$where_det['pro_id'] = $pro_id[$i];
					$update_det['qty'] = $qty_ubah[$i];
					if ($this->tbl_good_return->update_return_detail($where_det,$update_det)):	
						$arr_cek[$i] = true;
					endif;
				endfor;
			endif; 
			
		endif;
	
		if (!in_array(false,$arr_cek)):
			echo $ret_no;
		endif;
	
	}
}
?>