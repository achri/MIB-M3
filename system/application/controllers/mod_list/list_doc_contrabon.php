<?php
class list_doc_contrabon extends MY_Controller {
	private static $link_view, $link_controller;
	function list_doc_contrabon() {
		parent::MY_Controller();
		$this->load->model(array('tbl_contrabon','tbl_po','flexi_model'));
		$this->load->library(array('flexigrid','flexi_engine'));
		$this->load->helper(array('flexigrid','html'));
		$this->config->load('tables');
		
		$this->config->load('flexigrid');
		
		// LANGUAGE
		$this->lang->load('mod_entry/contrabon','bahasa');
		$this->lang->load('mod_master/supplier','bahasa');
		$this->lang->load('tables','bahasa');
		$this->lang->load('general','bahasa');
		$this->lang->load('label','bahasa');
		
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

		self::$link_controller = 'mod_list/list_doc_contrabon';
		self::$link_view = 'purchase/mod_list/document_printing/contrabon_list';
		$data['link_controller'] = self::$link_controller;
		$data['link_view'] = self::$link_view;
		
		$this->load->vars($data);
	}
	
	// QUERY UNTUK NON FLEXIGRID ATO FLEXIGRID
	function flexigrid_sql($flexi=TRUE,$count='con_id',$where=FALSE) {
		
		$sql = "SELECT c.con_id, c.con_no, date_format(c.con_date,'%d-%m-%Y') as con_date, s.sup_name {COUNT_STR}
            FROM prc_contrabon as c
			inner join prc_po as p on c.po_id = p.po_id
			inner join prc_master_supplier as s on s.sup_id = p.sup_id
			where con_printStat='1' and con_payVal='0' {SEARCH_STR}";
		return $this->flexi_model->generate_sql($sql,$count,$where,$flexi);
	}
	
	// MEMPERSIAPKAN DATA YANG AKAN DITAMPILKAN
	function flexigrid_ajax()
	{		
		$this->flexigrid->validate_post('con_id','asc',array('con_id','con_no','con_date','sup_name'));
		
		$records = $this->flexigrid_sql();
				
		$this->output->set_header($this->config->item('json_header'));
		
		if ($records['count'] > 0):		
			foreach ($records['result']->result() as $row)
			{
				$record_items[] = array(
				$row->con_id,
				$row->con_no,
				$row->con_date,
				$row->sup_name,
				'<a href=\'javascript:void(0)\' onclick=\'open_con('.$row->con_id.')\'><img border=\'0\' src=\'./asset/img_source/icon_info.gif\'></a>'
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
		$colModel['con_no'] = array($this->lang->line('con_no'),100,TRUE,'center',2);
		$colModel['con_date'] = array($this->lang->line('con_date'),100,TRUE,'center',1);
		$colModel['sup_name'] = array($this->lang->line('supp_name'),200,TRUE,'left',1);		
		$colModel['action'] = array($this->lang->line('action'),50, FALSE, 'center',0);		
		
		if (is_array($where)):
			$uri_array = $this->uri->assoc_to_uri($where);
			$ajax_model = site_url(self::$link_controller."/flexigrid_ajax/".$uri_array);
		else:
			$ajax_model = site_url(self::$link_controller."/flexigrid_ajax");
		endif;
		
		return build_grid_js('con_list',$ajax_model,$colModel,'con_no','asc',$this->flexi_engine->flexi_params($width,$height,$rp,$title));
		
	}
	
	function index() {
		$records = $this->flexigrid_sql(false);
		if ($records->num_rows() > 0):		
			$data['js_grid'] = $this->flexigrid_builder('Daftar Kontra Bon',550,219,8);
		else:
			$data['js_grid'] = $this->lang->line('list_empty');
		endif;
		$data['content'] = self::$link_view.'/list_cb_main';
		$this->load->view('index',$data);
	}
	
	function print_bon_view($con_id) {
		$get_con = $this->tbl_contrabon->get_bon_print($con_id);
		
		$data['print_con'] = 
		$data['print_gr'] = 
		$data['con_get'] = $this->tbl_contrabon->get_bon(array('con_id'=>$con_id));
		$data['con_id'] = $con_id;
		//$data['content'] = self::$link_view.'/list_cb_view';
		//$this->load->view('index',$data);
		$this->load->view(self::$link_view.'/list_cb_view',$data);
	}
	
	function set_print($con_id) {
		$print_date		= date('Y-m-d');
		
		$where['con_id']=$con_id;
		$get_con = $this->tbl_contrabon->get_bon($where);
		if ($get_con->num_rows() > 0):
			$count_con = $get_con->row()->con_printCount + 1;
		else:
			$count_con = 1;
		endif;
		
		$update['con_printCount']=$count_con;
		$update['con_printCountDate']=$print_date;
		
		if($this->tbl_contrabon->update_bon($where,$update)):
			$this->print_bon_view($con_id);
		endif;	
	}
	/*
	function set_print($con_id) {

		$update['con_printStat']='0';
	
		$where['con_id']=$con_id;
		
		if($this->tbl_contrabon->update_bon($where,$update)):
			anchor(self::$link_controller.'/index');
		endif;
	}
	*/
}
?>
