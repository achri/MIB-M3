<?php
class list_doc_rfq extends MY_Controller {
	private static $link_controller, $link_view;
	function list_doc_rfq() {
		parent::MY_Controller();
		
		$this->load->model(array('tbl_rfq','flexi_model'));
		$this->load->library(array('flexigrid','flexi_engine'));
		$this->load->helper(array('flexigrid','html'));
		$this->config->load('flexigrid');
		$this->config->load('tables');
		
		//$this->lang->load('product','bahasa');
		$this->lang->load('tables','bahasa');
		$this->lang->load('general','bahasa');
		$this->lang->load('label','bahasa');
		$this->lang->load('mod_entry/rfq','bahasa');
		
		$arrayCSS = array (
		'asset/css/themes/start/ui.all.css',
		'asset/javascript/jQuery/flexigrid/css/flexigrid.css',
		'asset/css/table/DataView.css'
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
		
		self::$link_view = 'purchase/mod_list/document_printing/rfq_list';
		self::$link_controller = 'mod_list/list_doc_rfq';
		$data['link_view'] = self::$link_view;
		$data['link_controller'] = self::$link_controller;
		
		$this->load->vars($data);
	}
	
	function flexigrid_sql($flexi=TRUE,$count='rfq_id',$where=FALSE) {
		
		$sql = "SELECT rfq_id, rfq_no, date_format(rfq_date,'%d-%m-%Y') as rfq_date,
            (dayofyear(now()) - dayofyear(rfq_date)) as tgl_selisih,
			 (
			  SELECT count( pro_id ) 
			  FROM prc_pr_detail AS d
			  WHERE d.rfq_id = r.rfq_id
			 ) AS jum_item,
			 (
			  SELECT count( pro_id ) 
		      FROM prc_pr_detail AS d
			  WHERE d.rfq_id = r.rfq_id and d.emergencyStat=1
			 ) AS emergency {COUNT_STR}
			FROM `prc_rfq` as r
			where rfq_printStat='1' {SEARCH_STR}";
		return $this->flexi_model->generate_sql($sql,$count,$where,$flexi);
	}
	
	function flexigrid_ajax() {
		//$this->flexigrid->validate_post('po_id','asc');
		//$records = $this->tbl_rfq->get_rfq_flexi();
		$this->flexigrid->validate_post('rfq_id','asc',array('rfq_id','rfq_no','rfq_date','tgl_selisih','jum_item','emergency'));
		
		$records = $this->flexigrid_sql();
		$this->output->set_header($this->config->item('json_header'));
		
		if ($records['count'] > 0):		
		$no = 1;
			foreach ($records['result']->result() as $row)
			{
				if ($row->emergency > 0)
					$emergency = "<font color='red'>Emergency (".$row->emergency.")</font>";
				else 
					$emergency = "Normal";
				$record_items[] = array(
				$row->rfq_id, // TABLE ID
				//$no,
				$row->rfq_no,
				$row->rfq_date,
				$row->tgl_selisih,
				$row->jum_item,
				$emergency,
				'<a href=\'javascript:void(0)\' onclick=\'open_rfq('.$row->rfq_id.')\'><img border=\'0\' src=\'./asset/img_source/icon_info.gif\'></a>'
				);
				$no++;
			}
		else: 
			//$records['count'] += 1;
			$record_items[] = array('0','null','null','empty','empty','empty','empty','null','n/a');
		endif;
		
		$this->output->set_output($this->flexigrid->json_build($records['count'],$record_items));
	}
	
	function flexigrid_builder($title,$width,$height,$rp) {
		/* FIELD
		 * 0 - display name
		 * 1 - width
		 * 2 - sortable
		 * 3 - align
		 * 4 - searchable (2 -> yes and default, 1 -> yes, 0 -> no.)
		 */
		//$colModel['no'] = array($this->lang->line('no'),60,FALSE,'center',0);
		$colModel['rfq_no'] = array($this->lang->line('rfq_no'),100,TRUE,'center',2);
		$colModel['rfq_date'] = array($this->lang->line('rfq_date'),100,TRUE,'center',1);
		$colModel['tgl_selisih'] = array($this->lang->line('tgl_selisih'),80, TRUE,'center',1);
		$colModel['jum_item'] = array($this->lang->line('jum_item'),80, TRUE,'center',1);
		$colModel['emergency'] = array($this->lang->line('emergency'),90, TRUE,'center',1);
		$colModel['opsi'] = array($this->lang->line('action'),50,TRUE,'center',0);
		
		/* BUILD FLEXIGRID
		 * build_grid_js(<div id>,<ajax function>,<field model>,<first field selection>,<order by>,<configuration>,<button>);
		 */
		
		$ajax_model = site_url("/".self::$link_controller."/flexigrid_ajax");
		
		return build_grid_js('rfq_list',$ajax_model,$colModel,'rfq_id','asc',$this->flexi_engine->flexi_params($width,$height,$rp,$title));
	}
	
	function index() {
		
		$records = $this->flexigrid_sql(false);//$this->tbl_rfq->get_rfq_flexi(false);
		if ($records->num_rows() > 0):
			$data['js_grid'] = $this->flexigrid_builder('Daftar RFQ',600,210,8);
		else:
			$data['js_grid'] = $this->lang->line('list_empty');
		endif;
		
		$data['content'] = self::$link_view.'/list_rfq_main';
		$this->load->view('index',$data);		
	}
		
	function view_rfq_det($rfq_id) {
		$data['rfq_list'] = $this->tbl_rfq->get_rfq_list_det($rfq_id);
		$data['rfq_get'] = $this->tbl_rfq->get_rfq(array('rfq_id'=>$rfq_id));
		
		$data['rfq_id'] = $rfq_id;
		//$data['content'] = self::$link_view.'/list_rfq_detail';
		//$this->load->view('index',$data);
		$this->load->view(self::$link_view.'/list_rfq_detail',$data);
	}
	
	function set_print($rfq_id) {
		$print_date		= date('Y-m-d');
		
		$where['rfq_id']=$rfq_id;
		$get_rfq = $this->tbl_rfq->get_rfq($where);
		if ($get_rfq->num_rows() > 0):
			$count_rfq = $get_rfq->row()->rfq_printCount + 1;
		else:
			$count_rfq = 1;
		endif;
		
		$update['rfq_printCount']=$count_rfq;
		$update['rfq_printCountDate']=$print_date;
		
		if($this->tbl_rfq->update_rfq($where,$update)):
			$this->view_rfq_det($rfq_id);
		endif;	
	}
	
}
?>