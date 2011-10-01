<?php
class appr_adjustment_po extends MY_Controller {
	public static $link_view, $link_controller, $user_id;
	function appr_adjustment_po() {
		parent::MY_Controller();
		$this->load->model(array('tbl_gr','tbl_pr','flexi_model'));
		$this->load->library(array('general','treeview','pro_code','flexigrid','flexi_engine','general'));
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
		
		self::$link_controller = 'mod_approval/appr_adjustment_po';
		self::$link_view = 'purchase/mod_approval/adj_po_appr';
 		$data['link_controller'] = self::$link_controller;
		$data['link_view'] = self::$link_view;
		
		$data['page_title'] = $this->lang->line('appr_adjustment_po_title');
		
		$this->load->vars($data);
	}
	
	// QUERY UNTUK NON FLEXIGRID ATO FLEXIGRID
	function flexigrid_sql($flexi=TRUE,$count='g.gr_id',$where=FALSE) {
		$sql = "select g.gr_id, g.gr_no, g.gr_id, date_format(g.gr_date, '%d-%m-%Y') as gr_date, g.gr_suratJalan, p.po_no, s.sup_name {COUNT_STR} 
        from prc_gr as g inner join prc_po as p on p.po_id = g.po_id
		inner join prc_master_supplier as s on p.sup_id = s.sup_id 
		where g.gr_status = 2 and g.gr_printStatus='1' and g.gr_type='rec' {SEARCH_STR}";
		return $this->flexi_model->generate_sql($sql,$count,$where,$flexi);
	}
	
	function flexigrid_ajax($cat_code='')
	{		
		$this->flexigrid->validate_post('g.gr_id','asc');//,$valid_fields);

		$records = $this->flexigrid_sql();
		
		$this->output->set_header($this->config->item('json_header'));
		
		if ($records['count'] > 0):		
			foreach ($records['result']->result() as $row)
			{
				$record_items[] = array(
				$row->gr_id, // TABLE ID
				$row->gr_date,
				$row->gr_no,
				$row->po_no,
				$row->sup_name,
				$row->gr_suratJalan,
				'<a href=\'javascript:void(0)\' onclick=\'open_adj('.$row->gr_id.')\'><img border=\'0\' src=\'./asset/img_source/icon_info.gif\'></a>'
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
		$colModel['gr_date'] = array('Tgl GR',100,TRUE,'center',1);
		$colModel['gr_no'] = array('No GR',100,TRUE,'center',2);
		$colModel['po_no'] = array('No PO',150,TRUE,'center',1);
		$colModel['sup_name'] = array('Pemasok',150,TRUE,'center',1);
		$colModel['gr_suratJalan'] = array('No Surat Jalan',150,TRUE,'center',1);
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
		
		return build_grid_js('flex_adj',$ajax_model,$colModel,'gr_date','asc',$this->flexi_engine->flexi_params($width,$height,$rp,$title));
		
	}
	
	function index() {
		$records = $this->flexigrid_sql(false);
		if ($records->num_rows() > 0):		
			$data['js_grid'] = $this->flexigrid_builder('Daftar Penyesuaian PO',880,210,8);
		else:
			$data['js_grid'] = $this->lang->line('list_empty');
		endif;
		$data['content'] = self::$link_view.'/appr_adj_po_main';
		$this->load->view('index',$data);
	}
	
	function show_adjustment($gr_id) {
		$sql_adj = "select g.gr_id,g.gr_no,dep.dep_name,g.gr_suratJalan, date_format(g.gr_date,'%d-%m-%Y') as gr_date, date_format(gd.date_edit,'%d-%m-%Y') as date_edit,
         p.po_id, p.po_no, s.sup_name, s.sup_id , usr.usr_name 
		 from prc_gr as g
         inner join prc_po as p on g.po_id = p.po_id
		 inner join prc_gr_detail_history as gd on g.gr_id = gd.gr_id
		 inner join prc_sys_user as usr on gd.usr_id = usr.usr_id 
		 inner join prc_master_departemen as dep on dep.dep_id = usr.dep_id 
		 inner join prc_master_supplier as s on s.sup_id = p.sup_id
		 where g.gr_id = '".$gr_id."' and g.gr_type='rec'";
		
		$sql_adj_det = "select d.pro_id, d.cur_id, cur.cur_symbol,pd.um_id, sat.satuan_format, p.pro_id, p.pro_name, p.pro_code, u.satuan_name,
		(select v.qty from prc_gr_detail as v where v.gr_id = g.gr_id and v.pro_id = d.pro_id) as qty_sebelum, 
		(select w.qty from prc_gr_detail_history as w where w.gr_id = g.gr_id and w.pro_id = d.pro_id and w.document = 'ADJ' order by date_edit desc limit 1) as qty_sesudah, 
		(select v.price from prc_gr_detail as v where v.gr_id = g.gr_id and v.pro_id = d.pro_id) as price_sebelum, 
		(select w.price from prc_gr_detail_history as w where w.gr_id = g.gr_id and w.pro_id = d.pro_id and w.document = 'ADJ' order by date_edit desc limit 1) as price_sesudah 
		from prc_gr as g
        inner join prc_gr_detail as d on g.gr_id = d.gr_id
		inner join prc_master_product as p on p.pro_id = d.pro_id
		inner join prc_pr_detail as pd on pd.po_id = g.po_id and pd.pro_id = d.pro_id
		inner join prc_master_satuan as u on pd.um_id = u.satuan_id 
		inner join prc_master_currency as cur on d.cur_id = cur.cur_id 
		inner join prc_master_satuan as sat on pd.um_id = sat.satuan_id
		where g.gr_id='".$gr_id."' and g.gr_type='rec'";

		$data['list_adj'] =  $this->db->query($sql_adj);
		$data['list_adj_det'] =  $this->db->query($sql_adj_det);
		
		//$data['content'] = self::$link_view.'/appr_adj_cek';
		$this->load->view(self::$link_view.'/appr_adj_po_cek',$data);
	}
	
	function get_history($pro_id) {
		$sql = 	"select g.gr_no, d.qty, d.price, date_format(d.date_edit,'%d-%m-%Y') as date_edit, us.usr_name, 
		cur.cur_symbol, cur.cur_digit, u.satuan_name, pd.um_id, p.pro_name 
		from prc_gr_detail_history as d 
		inner join prc_gr as g on g.gr_id = d.gr_id 
		inner join prc_master_product as p on p.pro_id = d.pro_id 
		inner join prc_pr_detail as pd on pd.po_id = g.po_id and pd.pro_id = d.pro_id
		inner join prc_master_satuan as u on pd.um_id = u.satuan_id 
		inner join prc_master_currency as cur on d.cur_id = cur.cur_id 
		inner join prc_master_satuan as sat on pd.um_id = sat.satuan_id
		inner join prc_sys_user as us on d.usr_id = us.usr_id
		where d.pro_id = $pro_id and d.document = 'ADJ' order by d.date_edit";
		
		$data['adj_history'] = $this->db->query($sql);
		
		$this->load->view(self::$link_view.'/appr_adj_po_history',$data);
	}
	
	function set_approve() {
		$gr_id = $this->input->post('gr_id');
		$po_id = $this->input->post('po_id');
		$gr_no = $this->input->post('gr_no');
		$pro_id = $this->input->post('pro_id');
		$stats = $this->input->post('stats');
		$alasan = $this->input->post('alasan');
		$qty_sesudah = $this->input->post('qty_sesudah');
		$price_sesudah = $this->input->post('price_sesudah');
		
		$arr_cek = array();
		
		for ($i=0 ; $i < sizeOf($pro_id) ; $i++):
			//echo $gr_id.'|'.$pro_id[$i].'|'.$qty_sesudah[$i].'|'.$price_sesudah[$i].'|'.$alasan[$i].'|'.$stats[$i];
			
			$arr_cek[$i] = false;
			$where_det['gr_id'] = $gr_id;
			$where_det['pro_id'] = $pro_id[$i];
			if ($stats == 3):
				//$update_det['qty'] = $qty_sesudah[$i];		
				$update_det['price'] = $price_sesudah[$i];	
				// UPDATE PR
				//$where_pr_det['po_id'] = $po_id;
				//$where_pr_det['pro_id'] = $pro_id[$i];
				//$data_pr_det['qty_terima'] = $qty_sesudah[$i];
				//$this->tbl_pr->update_pr_detail($where_pr_det,$data_pr_det);
			else:
				$update_det['keterangan'] = $alasan;
			endif;
			
			if ($this->tbl_gr->update_gr_det($where_det,$update_det)):	
				$where['gr_id'] = $gr_id;
				$update['gr_status'] = $stats;
				if ($this->tbl_gr->update_gr($where,$update)):
					$arr_cek[$i] = true;
				endif;
			endif;
			
		endfor; 
	
		if (!in_array(false,$arr_cek)):
			echo $gr_no;
		endif;
	
	}
}
?>