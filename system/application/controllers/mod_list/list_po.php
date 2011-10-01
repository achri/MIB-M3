<?php
class List_po extends MY_Controller{
	public static $link_view, $link_controller;
	function List_po(){
		parent::MY_Controller();
		$this->load->model(array('tbl_po','flexi_model'));
		$this->load->helper('flexigrid');
		//$this->obj =& get_instance();
		$this->config->load('flexigrid');
		$this->load->library(array('general','flexigrid','flexi_engine'));
		$this->lang->load('general','bahasa');
		
		$arrayCSS = array (
		'asset/css/themes/start/ui.all.css',
		'asset/javascript/jQuery/flexigrid/css/flexigrid.css',
		'asset/css/table/DataView.css',
		'asset/css/form/form_view.css',
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
		
		self::$link_controller = 'mod_data_list/list_po';
		self::$link_view = 'purchase/mod_data_list/mod_po';
		$data['link_controller'] = self::$link_controller;
		$data['link_view'] = self::$link_view;
		
		$this->load->vars($data);
	}
	
	// QUERY UNTUK NON FLEXIGRID ATO FLEXIGRID
	function flexigrid_sql($flexi=TRUE,$count='po_id',$where=FALSE) {
		$sql = "SELECT p.po_id, p.po_no, date_format( p.po_date, '%d-%m-%Y' ) AS po_date, s.sup_name, s.legal_id, l.legal_name 
			{COUNT_STR} 
			FROM prc_po AS p
			INNER JOIN prc_master_supplier AS s ON s.sup_id = p.sup_id
			INNER JOIN prc_master_legality AS l ON s.legal_id = l.legal_id
			WHERE po_status = '0'
			AND po_printStat = '1' {SEARCH_STR}";
		return $this->flexi_model->generate_sql($sql,$count,$where,$flexi);
	}
	
	function flexigrid_ajax()
	{		
		$valid_fields = array('po_id','po_no','po_date','sup_name','po_date');
		
		$this->flexigrid->validate_post('po_id','asc',$valid_fields);

		$records = $this->flexigrid_sql();
		
		$this->output->set_header($this->config->item('json_header'));
		
		if ($records['count'] > 0):		
			foreach ($records['result']->result() as $row)
			{
				$record_items[] = array(
				$row->po_id, // TABLE ID
				$row->po_no,
				$row->po_date,
				$row->legal_name.' '.$row->sup_name,
				'<a href=\'javascript:void(0)\' onclick=\'open_po('.$row->po_id.')\'><img border=\'0\' src=\'./asset/img_source/icon_info.gif\'></a>'
				);
			}
		else: 
			$record_items[] = array('0','null','null','empty','empty','null','null');
		endif;
		
		$this->output->set_output($this->flexigrid->json_build($records['count'],$record_items));
	}
	
	function flexigrid_builder($title,$width,$height,$rp) {
		$colModel['po_no'] = array('No PO',100,TRUE,'center',1);
		$colModel['po_date'] = array('Tgl PO',100,TRUE,'center',1);
		$colModel['sup_name'] = array('Pemasok',200,TRUE,'left',2);
		$colModel['opsi'] = array('Opsi',50,TRUE,'center',0);
		
		$ajax_model = site_url(self::$link_controller."/flexigrid_ajax");
		
		return build_grid_js('po_list',$ajax_model,$colModel,'po_date','desc',$this->flexi_engine->flexi_params($width,$height,$rp,$title));
		
	}
	
	function index() {
		$records = $this->flexigrid_sql(false);
		if ($records->num_rows() > 0):		
			$data['js_grid'] = $this->flexigrid_builder('Daftar PO',550,219,8);
		else:
			$data['js_grid'] = $this->lang->line('list_empty');
		endif;
		$data['content'] = self::$link_view.'/index_po_list_view';
		$this->load->view('index',$data);
	}
	
	function open_po($po){
		$data['data'] =  $this->tbl_po->get_openpo($po);
		$this->load->view(self::$link_controller.'/po_detailview',$data);
	}
	
	function close_po($po, $reas){
		$this->tbl_po->close_po($po, $reas);
	}
	
}
?>