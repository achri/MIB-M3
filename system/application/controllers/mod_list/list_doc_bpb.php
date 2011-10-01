<?php
class list_doc_bpb extends MY_Controller {
	private static $link_view, $link_controller, $user_id;
	function list_doc_bpb() {
		parent::MY_Controller();
		
		$this->load->model(array('tbl_gr','tbl_user','tbl_rptnote','tbl_inventory','flexi_model'));
		$this->load->library(array('flexigrid','flexi_engine'));
		$this->load->helper(array('flexigrid','html'));
		$this->load->library('session');
		$this->config->load('tables');
		
		$this->config->load('flexigrid');
		
		// LANGUAGE
		$this->lang->load('tables','bahasa');
		$this->lang->load('general','bahasa');
		$this->lang->load('label','bahasa');
		$this->lang->load('mod_entry/goodreceive','bahasa');
		$this->lang->load('mod_entry/po','bahasa');
		$this->lang->load('mod_master/supplier','bahasa');
		
		$arrayCSS = array (
		'asset/css/themes/start/ui.all.css',
		'asset/css/print_templates.css',
		'asset/css/table/DataView.css',
		'asset/javascript/jQuery/flexigrid/css/flexigrid.css',
		);
		
		$arrayJS = array (
		'asset/javascript/jQuery/flexigrid/js/flexigrid.js',
		);
		
		$data['extraHeadContent'] = '';
		
		foreach ($arrayCSS as $css):
			$data['extraHeadContent'] .= '<link type="text/css" rel="stylesheet" href="'.base_url().$css.'"/>';
		endforeach;
		foreach ($arrayJS as $js):
			$data['extraHeadContent'] .= '<script type="text/javascript" src="'.base_url().$js.'"/></script>';
		endforeach;
		
		self::$link_controller = 'mod_list/list_doc_bpb';
		self::$link_view = 'purchase/mod_list/document_printing/bpb_list';
		$data['link_controller'] = self::$link_controller;
		$data['link_view'] = self::$link_view;
		
		// THIS SESSION USER LOGIN
		self::$user_id = $this->session->userdata("usr_id");
		$user_name	= $this->tbl_user->get_user(self::$user_id)->row()->usr_name;
		
		// THIS VARS
		$data['usr_name'] = $user_name;
		
		$this->load->vars($data);
	}
	
	// QUERY UNTUK NON FLEXIGRID ATO FLEXIGRID
	function flexigrid_sql($flexi=TRUE,$count='gr_id',$where=FALSE) {
		/*$sql = "select g.gr_no, g.gr_id, date_format(g.gr_date, '%d-%m-%Y') as gr_date, g.gr_suratJalan, p.po_no, s.sup_name
            {COUNT_STR}
			from prc_gr as g inner join prc_po as p on p.po_id = g.po_id
			inner join prc_master_supplier as s on p.sup_id = s.sup_id 
			where g.gr_status=1 and g.gr_printStatus='1' {SEARCH_STR}";*/
		$sql = "select g.gr_no, g.gr_id, date_format(g.gr_date, '%d-%m-%Y') as gr_date, g.gr_suratJalan, p.po_no, s.sup_name
			{COUNT_STR}
            from prc_gr as g inner join prc_po as p on p.po_id = g.po_id
			inner join prc_master_supplier as s on p.sup_id = s.sup_id 
			where g.gr_printStatus='1' {SEARCH_STR}";
		return $this->flexi_model->generate_sql($sql,$count,$where,$flexi);
	}
	
	// MEMPERSIAPKAN DATA YANG AKAN DITAMPILKAN
	function flexigrid_ajax()
	{		
		$this->flexigrid->validate_post('gr_id','asc',array('gr_id','gr_date','gr_no','po_no','sup_name','gr_suratJalan'));
		
		$records = $this->flexigrid_sql();
				
		$this->output->set_header($this->config->item('json_header'));
		
		if ($records['count'] > 0):		
			foreach ($records['result']->result() as $row)
			{
				$record_items[] = array(
				$row->gr_id,
				$row->gr_no,
				$row->po_no,
				$row->gr_date,
				$row->sup_name,
				$row->gr_suratJalan,
				'<a href=\'javascript:void(0)\' onclick=\'open_bpb('.$row->gr_id.')\'><img border=\'0\' src=\'./asset/img_source/icon_info.gif\'></a>'
				);
			}
		else: 
			$record_items[] = array('0','null','null','empty','empty','empty');
		endif;
		
		$this->output->set_output($this->flexigrid->json_build($records['count'],$record_items));
	}
	
	// MEMBANGUN DATA FLEXIGRID
	function flexigrid_builder($title,$width,$height,$rp,$where='') {

		//$colModel['no'] = array($this->lang->line('no'),20,FALSE,'center',0);
		$colModel['gr_no'] = array($this->lang->line('gr_no'),100,TRUE,'center',1);
		$colModel['po_no'] = array($this->lang->line('po_no'),100, TRUE,'center',1);
		$colModel['gr_date'] = array($this->lang->line('gr_date'),100,TRUE,'center',2);
		$colModel['sup_name'] = array($this->lang->line('supp_name'),200,TRUE,'left',1);		
		$colModel['gr_suratJalan'] = array($this->lang->line('gr_suratJalan'),150, TRUE, 'left',1);
		$colModel['action'] = array($this->lang->line('action'),50, FALSE, 'center',0);		
		
		if (is_array($where)):
			$uri_array = $this->uri->assoc_to_uri($where);
			$ajax_model = site_url(self::$link_controller."/flexigrid_ajax/".$uri_array);
		else:
			$ajax_model = site_url(self::$link_controller."/flexigrid_ajax");
		endif;
		
		return build_grid_js('print_bpb_list',$ajax_model,$colModel,'gr_no','asc',$this->flexi_engine->flexi_params($width,$height,$rp,$title));
		
	}
	
	function index() {
		/*
		$cek_data = $this->flexigrid_sql(false);//$this->tbl_gr->get_gr_after_print_list(1);
		if ($cek_data->num_rows() > 0):
		//$data['gr_list'] = $this->tbl_gr->get_gr_after_print_list(1);
		$data['js_grid'] = $this->flexigrid_builder();
		else:
		$data['empty'] = $this->lang->line('list_empty');
		endif;
		*/
		$records = $this->flexigrid_sql(false);
		if ($records->num_rows() > 0):		
			$data['js_grid'] = $this->flexigrid_builder('Daftar BPB',800,219,8);
		else:
			$data['js_grid'] = $this->lang->line('list_empty');
		endif;
		$data['content'] = self::$link_view.'/list_bpb_main';
		$this->load->view('index',$data);
	}
	
	function print_bpb_view($gr_id = '') {
		//$gr_no = $a.'/'.$b.'/'.$c;
		$gr_print = $this->tbl_gr->get_gr_print_view($gr_id);
		$data['gr_list'] = $gr_print['gr_list'];
		$data['po_det_list'] = $gr_print['po_det_list'];
		$data['gr_data'] = $this->tbl_gr->get_gr_data(array('gr_id'=>$gr_id));
		//$data['content'] = self::$link_view.'/list_bpb_view';
		//$this->load->view('index',$data);
		$this->load->view(self::$link_view.'/list_bpb_view',$data);
	}
	
	function set_print($gr_id) {
		$print_date		= date('Y-m-d');
		
		$where['gr_id']=$gr_id;
		$get_gr = $this->tbl_gr->get_gr_data($where);
		if ($get_gr->num_rows() > 0):
			$count_gr = $get_gr->row()->gr_printCount + 1;
		else:
			$count_gr = 1;
		endif;
		
		$update['gr_printCount']=$count_gr;
		$update['gr_printCountDate']=$print_date;
		
		if($this->tbl_gr->update_gr($where,$update)):
			$this->print_bpb_view($gr_id);
		endif;	
	}
	
	/*
	function set_print($gr_id) {

		$update['gr_printStatus']='0';
	
		$where['gr_id']=$gr_id;
		
		if($this->tbl_gr->update_gr($where,$update)):
			redirect(self::$link_controller.'/index');
		endif;
		
	}
	*/
}
?>