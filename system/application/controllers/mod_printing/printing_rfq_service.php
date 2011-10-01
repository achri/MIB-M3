<?php
class printing_rfq_service extends MY_Controller {
	public static $link_view, $link_controller, $user_id, $print_status;
	function printing_rfq_service() {
		parent::MY_Controller();
		
		$this->load->model(array('tbl_rfq_service','tbl_sr','tbl_gr','tbl_user','tbl_unit','tbl_rptnote','tbl_inventory','flexi_model'));
		$this->load->library(array('flexigrid','flexi_engine'));
		$this->load->helper(array('flexigrid','html'));
		$this->load->library('session');
		$this->config->load('tables');
		
		$this->config->load('flexigrid');
		
		// LANGUAGE
		$this->lang->load('mod_entry/po','bahasa');
		$this->lang->load('mod_master/supplier','bahasa');
		$this->lang->load('mod_entry/goodreturn','bahasa');
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
		
		self::$link_controller = 'mod_printing/printing_rfq_service';
		self::$link_view = 'purchase/mod_printing/rfq_service_printing';
		$data['link_controller'] = self::$link_controller;
		$data['link_view'] = self::$link_view;
		
		// THIS SESSION USER LOGIN
		self::$user_id = $this->session->userdata("usr_id");
		$user_name	= $this->tbl_user->get_user(self::$user_id)->row()->usr_name;
		$data['usr_name'] = $user_name;
		
		// TITLE
		if ($this->uri->segment(4) == 1)
			$data['page_title'] = $this->lang->line('print_dup_rfq_service_title');
		else
			$data['page_title'] = $this->lang->line('print_rfq_service_title');
		
		$this->load->vars($data);
	}
	
	// QUERY UNTUK NON FLEXIGRID ATO FLEXIGRID
	function flexigrid_sql($flexi=TRUE,$print_status,$count='srfq_id',$where=FALSE) {
		$sql = "
			SELECT srfq_id, srfq_no, date_format(srfq_date,'%d-%m-%Y') as srfq_date,
            (dayofmonth(now()) - dayofmonth(srfq_date)) as tgl_selisih,
			 (
			  SELECT count( pro_id ) 
			  FROM prc_sr_detail AS d
			  WHERE d.srfq_id = r.srfq_id
			 ) AS jum_item {COUNT_STR}
			FROM `prc_rfq_service` as r
			where srfq_printStat='".$print_status."' {SEARCH_STR}
		";
		return $this->flexi_model->generate_sql($sql,$count,$where,$flexi);
	}
	
	// MEMPERSIAPKAN DATA YANG AKAN DITAMPILKAN
	function flexigrid_ajax($print_status)
	{		
		$this->flexigrid->validate_post('srfq_id','asc');
		
		$records = $this->flexigrid_sql(TRUE,$print_status);
				
		$this->output->set_header($this->config->item('json_header'));
		
		if ($records['count'] > 0):		
			foreach ($records['result']->result() as $row)
			{
				$record_items[] = array(
				$row->srfq_id,
				$row->srfq_date,
				$row->srfq_no,
				$row->tgl_selisih,
				$row->jum_item,
				'Normal',
				'<a href=\'javascript:void(0)\' onclick=\'open_srfq('.$row->srfq_id.','.$print_status.')\'><img border=\'0\' src=\'./asset/img_source/icon_info.gif\'></a>'
				);
			}
		else: 
			$record_items[] = array('0','null','null','empty','empty','empty');
		endif;
		
		$this->output->set_output($this->flexigrid->json_build($records['count'],$record_items));
	}
	
	// MEMBANGUN DATA FLEXIGRID
	function flexigrid_builder($title,$width,$height,$rp,$print_status = 0) {

		//$colModel['no'] = array($this->lang->line('no'),20,FALSE,'center',0);
		$colModel['srfq_date'] = array('Tgl RFQ',100,TRUE,'center',2);
		$colModel['srfq_no'] = array('No RFQ',100,TRUE,'left',1);
		$colModel['tgl_selisih'] = array('Batas Waktu',100, TRUE,'left',0);
		$colModel['jum_item'] = array('Jumlah Item',150,TRUE,'center',0);		
		$colModel['status'] = array('Status',80,TRUE,'center',0);		
		$colModel['action'] = array($this->lang->line('action'),50, FALSE, 'center',0);		
		
		//if ($print_status != 0):
			//$uri_array = $this->uri->assoc_to_uri($where);
			$ajax_model = site_url(self::$link_controller."/flexigrid_ajax/".$print_status);
		//else:
			//$ajax_model = site_url(self::$link_controller."/flexigrid_ajax");
		//endif;
		
		return build_grid_js('print_srfq_list',$ajax_model,$colModel,'srfq_date','asc',$this->flexi_engine->flexi_params($width,$height,$rp,$title));
		
	}
	
	function index($print_status=0) {
		$cek_data = $this->flexigrid_sql(FALSE,$print_status);
		if ($cek_data->num_rows() > 0):
		$data['js_grid'] = $this->flexigrid_builder('Daftar RFQ Servis',700,210,8,$print_status);
		else:
		$data['js_grid']= $this->lang->line('list_empty');
		endif;
		$data['content'] = self::$link_view.'/print_srfq_main';
		$this->load->view('index',$data);
	}
	
	// MENAMPILKAN FORM CETAK BPB
	function print_srfq_view($srfq_id,$print_status) {
		//$idnote = 3; // untuk print BPB
		//$ret_print = $this->tbl_goodreturn->get_print_view($re_id);
		
		$srfq_sql = "select srfq_id,srfq_no,srfq_printStat, date_format(srfq_date,'%d-%m-%Y') as srfq_date from prc_rfq_service where srfq_id='$srfq_id'";
			
		$srfq_det_sql = "select p.sr_no, date_format(p.sr_date,'%d/%m') as sr_date, r.srfq_no, pd.num_supplier, pd.qty,
			 pd.service_cat, pd.service_type,
			 pro.pro_code, pro.pro_name, m.satuan_name, m.satuan_format
			 from prc_sr_detail as pd
             inner join prc_rfq_service as r on pd.srfq_id = r.srfq_id
             inner join prc_sr as p on pd.sr_id = p.sr_id
			 inner join prc_master_product as pro on pd.pro_id = pro.pro_id 
			 inner join prc_master_satuan as m on pd.um_id = m.satuan_id
			 where pd.srfq_id='$srfq_id'";
			
		$data['srfq_list'] = $this->db->query($srfq_sql);
		$data['srfq_det_list'] = $this->db->query($srfq_det_sql);
		
		$data['print_status'] = $print_status;
		
		$this->load->view(self::$link_view.'/print_srfq_view',$data);
	}
	
	// SETELAH PRINT STATUS BPB PRINT DI UPDATE
	function after_print($srfq_id,$print_status,$print_count = 1) {
		$print_date		= date('Y-m-d');
		$user_id		= $this->session->userdata("usr_id");
		
		if ($print_status == 0):	
			$update['srfq_printStat']='1';
			$update['srfq_printDate']=$print_date; 
			$update['srfq_printUsr'] =$user_id;
		else:
			$update['srfq_printCount'] = $print_count;
			$update['srfq_printCountDate'] = $print_date;
		endif;
	
		$where['srfq_id']=$srfq_id;
		
		if($this->tbl_rfq_service->update_srfq($where,$update)):
			$this->print_srfq_view($srfq_id,$print_status);
		endif;
	}
	
}
?>