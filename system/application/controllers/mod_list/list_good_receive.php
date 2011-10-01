<?php
class list_good_receive extends MY_Controller {
	function list_good_receive() {
		parent::MY_Controller();
		$this->load->model(array('tbl_gr','flexi_model'));
		$this->load->library(array('flexigrid','flexi_engine'));
		$this->load->helper(array('flexigrid','html'));
		$this->config->load('flexigrid');
		$this->config->load('tables');
		
		//$this->lang->load('product','bahasa');
		$this->lang->load('general','bahasa');
		$this->lang->load('tables','bahasa');
		$this->lang->load('mod_entry/goodreceive','bahasa');
		$this->lang->load('mod_entry/po','bahasa');
		$this->lang->load('mod_master/supplier','bahasa');
		
		$arrayCSS = array (
		'asset/css/themes/start/ui.all.css',
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
		
		$data['link_view'] = 'purchase/mod_list/good_receive';
		$data['link_controller'] = 'mod_list/list_good_receive';
		
		$this->load->vars($data);
	}
	
	// QUERY UNTUK NON FLEXIGRID ATO FLEXIGRID
	function flexigrid_sql($flexi=TRUE,$count='gr_id',$where=FALSE) {
		$sql = "select g.*, date_format(g.gr_date,'%d-%m-%Y') as gr_date,
		   s.sup_name, p.po_no {COUNT_STR}
           from prc_gr as g
		   inner join prc_po as p on p.po_id = g.po_id
		   inner join prc_master_supplier as s on p.sup_id = s.sup_id
		   where g.gr_type = 'rec' {SEARCH_STR}";
		return $this->flexi_model->generate_sql($sql,$count,$where,$flexi);
	}
	
	function flexigrid_ajax() {
		
		//$this->flexigrid->validate_post('gr_id','asc');
		//$records = $this->tbl_gr->get_gr_flexi();
		$this->flexigrid->validate_post('gr_id','asc',array('gr_id','gr_date','gr_no','po_no','sup_name','gr_suratJalan'));
		$records = $this->flexigrid_sql();
		
		if ($records['count'] > 0):		
			$no = 1;
			foreach ($records['result']->result() as $row)
			{
				$record_items[] = array(
				$row->gr_id, // TABLE ID
				//$no,
				$row->gr_no,
				$row->po_no,
				$row->gr_date,
				$row->sup_name,
				$row->gr_suratJalan
				);
				$no++;
			}
		else: 
			//$records['count'] += 1;
			$record_items[] = array('0','null','null','empty','empty','empty','n/a');
		endif;
		
		$this->output->set_header($this->config->item('json_header'));
		$this->output->set_output($this->flexigrid->json_build($records['count'],$record_items));
	}
	
	function flexigrid_builder($title,$width,$height,$rp,$where='') {

		//$colModel['no'] = array($this->lang->line('no'),60,FALSE,'center',0);
		$colModel['gr_no'] = array($this->lang->line('gr_no'),100,TRUE,'center',1);
		$colModel['po_no'] = array($this->lang->line('po_no'),100, TRUE,'center',1);
		$colModel['gr_date'] = array($this->lang->line('gr_date'),100,TRUE,'center',2);
		$colModel['sup_name'] = array($this->lang->line('supp_name'),200,TRUE,'left',1);		
		$colModel['gr_suratJalan'] = array($this->lang->line('gr_suratJalan'),200, TRUE, 'left',1);		
		
		$ajax_model = site_url("/mod_list/list_good_receive/flexigrid_ajax");
		
		return build_grid_js('gr_list',$ajax_model,$colModel,'gr_no','asc',$this->flexi_engine->flexi_params($width,$height,$rp,$title));
		
	}
	
	function index() {
		
		//$records = $this->tbl_gr->get_gr_flexi(false);
		$records = $this->flexigrid_sql(false);
		if ($records->num_rows() > 0):
			$data['js_grid'] = $this->flexigrid_builder('Daftar Terima Barang',800,210,8);
		else:
			$data['js_grid'] = $this->lang->line('list_empty');
		endif;
		
		$data['content'] = 'purchase/mod_list/good_receive_list/list_gr_main';
		$this->load->view('index',$data);		
	}
}
?>