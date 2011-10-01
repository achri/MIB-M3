<?php
class List_pettycash extends MY_Controller{
	public static $link_view, $link_controller;
	function List_pettycash(){
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
		
		self::$link_controller = 'mod_data_list/list_pettycash';
		self::$link_view = 'purchase/mod_data_list/mod_pettycash';
		$data['link_controller'] = self::$link_controller;
		$data['link_view'] = self::$link_view;
		
		$this->load->vars($data);
	}
	
	// QUERY UNTUK NON FLEXIGRID ATO FLEXIGRID
	function flexigrid_sql($flexi=TRUE,$count='po_id',$where=FALSE) {
		$sql = "SELECT pc.pcv_no, pc.pcv_id, pc.pcv_printCounter, pr.pr_no, u.usr_name {COUNT_STR} 
			from prc_pcv as pc
			inner join prc_pr as pr on pc.pr_id = pr.pr_id
			inner join prc_sys_user as u on pr.pr_requestor = u.usr_id
			where pc.pcv_status='2' and pc.pcv_printStat='1' {SEARCH_STR}";
		return $this->flexi_model->generate_sql($sql,$count,$where,$flexi);
	}
	
	function flexigrid_ajax()
	{		
		$valid_fields = array('pcv_id','pcv_no','pr_no','usr_name');
		
		$this->flexigrid->validate_post('pcv_id','asc',$valid_fields);

		$records = $this->flexigrid_sql();
		
		$this->output->set_header($this->config->item('json_header'));
		
		if ($records['count'] > 0):		
			foreach ($records['result']->result() as $row)
			{
				$record_items[] = array(
				$row->pcv_id, // TABLE ID
				$row->pcv_no,
				$row->pr_no,
				$row->usr_name,
				'<a href=\'javascript:void(0)\' onclick=\'open_pcv_print('.$row->pcv_id.')\'><img border=\'0\' src=\'./asset/img_source/icon_info.gif\'></a>'
				);
			}
		else: 
			$record_items[] = array('0','null','null','empty','empty','null','null');
		endif;
		
		$this->output->set_output($this->flexigrid->json_build($records['count'],$record_items));
	}
	
	function flexigrid_builder($title,$width,$height,$rp) {
		$colModel['pcv_no'] = array('NO PCV',80,TRUE,'center',1);
		$colModel['pr_no'] = array('NO PR',100,TRUE,'center',2);
		$colModel['usr_name'] = array('Pemohon',150,TRUE,'left',1);
		$colModel['opsi'] = array('Opsi',50,TRUE,'center',1);
		
		$ajax_model = site_url(self::$link_controller."/flexigrid_ajax");
		
		return build_grid_js('pr_list',$ajax_model,$colModel,'po_date','desc',$this->flexi_engine->flexi_params($width,$height,$rp,$title));
		
	}
	
	function index() {
		$records = $this->flexigrid_sql(false);
		if ($records->num_rows() > 0):		
			$data['js_grid'] = $this->flexigrid_builder('Daftar PO',730,219,8);
		else:
			$data['js_grid'] = $this->lang->line('list_empty');
		endif;
		$data['content'] = self::$link_view.'/index_pettycash_list_view';
		$this->load->view('index',$data);
	}
	
	function open_po($po){
		$data['data'] =  $this->tbl_po->get_openpo($po);
		$this->load->view(self::$link_controller.'/pcv_detailview',$data);
	}
	
	function close_po($po, $reas){
		$this->tbl_po->close_po($po, $reas);
	}
	
}
?>