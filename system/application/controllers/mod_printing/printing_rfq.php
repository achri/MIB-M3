<?php
class Printing_rfq extends MY_Controller {
	public static $link_view, $link_controller, $user_id, $ppn_status, $print_status;
	function Printing_rfq() {
		parent::MY_Controller();
		$this->load->model(array('tbl_rfq','tbl_rptnote','flexi_model','tbl_user'));
		$this->load->library(array('flexigrid','flexi_engine'));
		$this->load->helper(array('flexigrid','html'));
		$this->load->library('session');
		$this->config->load('tables');
		
		$this->config->load('flexigrid');
		
		// LANGUAGE
		$this->lang->load('mod_entry/inventory','bahasa');
		$this->lang->load('mod_entry/rfq','bahasa');
		$this->lang->load('mod_entry/pr_rfq','bahasa');
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
		
		self::$link_controller = 'mod_printing/printing_rfq';
		self::$link_view = 'purchase/mod_printing/rfq_printing';
		$data['link_controller'] = self::$link_controller;
		$data['link_view'] = self::$link_view;
		
		// THIS SESSION USER LOGIN
		self::$user_id = $this->session->userdata("usr_id");
		$user_name	= $this->tbl_user->get_user(self::$user_id)->row()->usr_name;
		$data['usr_name'] = $user_name;
		
		// TITLE
		if ($this->uri->segment(4) == 1)
			$data['page_title'] = $this->lang->line('print_dup_rfq_title');
		else
			$data['page_title'] = $this->lang->line('print_rfq_title');
		
		$this->load->vars($data);
		
		//self::$ppn_status = '';
		//if ($this->session->userdata('module_type') == 'PPN')
			self::$ppn_status = 'ppn_';
	}
	
	// QUERY UNTUK NON FLEXIGRID ATO FLEXIGRID
	function flexigrid_sql($flexi=TRUE,$print_status,$count='rfq_id',$where=FALSE) {
		$sql = "SELECT rfq_id, rfq_no, date_format(rfq_date,'%d-%m-%Y') as rfq_date,
			DATEDIFF(now(), rfq_date) as tgl_selisih,
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
			where rfq_printStat='".$print_status."' 
			and (
			  SELECT count(po.po_id) as pos 
			  FROM prc_po as po
			  INNER JOIN prc_pr_detail AS d on d.po_id = po.po_id and po.po_status = 0
			  where d.rfq_id = r.rfq_id 
			 ) > 0 
			{SEARCH_STR}";
		return $this->flexi_model->generate_sql($sql,$count,$where,$flexi);
	}
	
	// MEMPERSIAPKAN DATA YANG AKAN DITAMPILKAN
	function flexigrid_ajax($print_status)
	{		
		$valid_fields = array('rfq_id','rfq_date','rfq_no','tgl_selisih','jum_item','emergency');
		
		$this->flexigrid->validate_post('rfq_id','asc',$valid_fields);
		
		$records = $this->flexigrid_sql(TRUE,$print_status);
				
		$this->output->set_header($this->config->item('json_header'));
		
		if ($records['count'] > 0):		
			foreach ($records['result']->result() as $row)
			{
				$record_items[] = array(
				$row->rfq_id,
				$row->rfq_date,
				$row->rfq_no,
				$row->tgl_selisih,
				$row->jum_item,
				($row->emergency > 0)?('<font color=red>Darurat</font>'):('Normal'),
				'<a href=\'javascript:void(0)\' onclick=\'open_rfq('.$row->rfq_id.','.$print_status.')\'><img border=\'0\' src=\'./asset/img_source/icon_info.gif\'></a>'
				);
			}
		else: 
			$record_items[] = array('0','null','null','empty','empty','empty');
		endif;
		
		$this->output->set_output($this->flexigrid->json_build($records['count'],$record_items));
	}
	
	// MEMBANGUN DATA FLEXIGRID
	function flexigrid_builder($title,$width,$height,$rp,$print_status) {

		//$colModel['no'] = array($this->lang->line('no'),20,FALSE,'center',0);
		$colModel['rfq_date'] = array($this->lang->line('rfq_date'),100,TRUE,'center',2);
		$colModel['rfq_no'] = array($this->lang->line('rfq_no'),100,TRUE,'center',1);
		$colModel['tgl_selisih'] = array($this->lang->line('tgl_selisih'),100, TRUE,'center',1);
		$colModel['jum_item'] = array($this->lang->line('jum_item'),100,TRUE,'center',1);		
		$colModel['emergency'] = array($this->lang->line('emergency'),100, FALSE, 'center',0);
		$colModel['action'] = array($this->lang->line('action'),50, FALSE, 'center',0);		
		
		$ajax_model = site_url(self::$link_controller."/flexigrid_ajax/".$print_status);
		$flexi_params = $this->flexi_engine->flexi_params($width,$height,$rp,$title);
		
		return build_grid_js('print_rfq_list',$ajax_model,$colModel,'rfq_id','desc',$flexi_params);
		
	}
	
	function index($print_status = 0) {
		$cek_data = $this->flexigrid_sql(FALSE,$print_status);//$this->tbl_rfq->get_rfq_print_list();
		if ($cek_data->num_rows() > 0):
		$data['js_grid'] = $this->flexigrid_builder($this->lang->line('flex_print_rfq'),640,210,8,$print_status);
		else:
		$data['js_grid'] = $this->lang->line('list_empty');
		endif;
		$data['content'] = self::$link_view.'/print_rfq_main';
		$this->load->view('index',$data);
	}
	
	function print_rfq_view($rfq_id,$print_status) {
		$idnote = 1; // untuk print PO
		$note = $this->tbl_rptnote->get_note($idnote);
		if ($note->num_rows() > 0):
		$data['notes']= $note->row()->note;
		else:
		$data['notes']= '';
		endif;
		
		$data['print_rfq'] = $this->tbl_rfq->get_rfq_print($rfq_id,$print_status);
		$data['rfq_id'] = $rfq_id;
		
		$data['print_status'] = $print_status;

		$this->load->view(self::$link_view.'/print_rfq_'.self::$ppn_status.'view',$data);

	}
	
	function after_print($rfq_id,$print_status,$print_count = 1) {
		$print_date		= date('Y-m-d');
		
		if ($print_status == 0):	
			$update['rfq_printStat']='1';
			$update['rfq_printDate']=$print_date; 
			$update['rfq_printUsr'] =self::$user_id;
		else:
			$update['rfq_printCount'] = $print_count;
			$update['rfq_printCountDate'] = $print_date;
		endif;
		
		$where['rfq_id']=$rfq_id;
		
		if($this->tbl_rfq->update_rfq($where,$update)):
			if ($print_status == 0):
				echo "ok";
			else:
				$this->print_rfq_view($rfq_id,$print_status);
			endif;
		endif;
	}
}
?>