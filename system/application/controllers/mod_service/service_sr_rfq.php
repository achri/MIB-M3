<?php
class service_sr_rfq extends MY_Controller {
	public static $link_view, $link_controller;
	function service_sr_rfq() {
		parent::MY_Controller();
		$this->load->model(array('tbl_sr','tbl_sys_counter','tbl_rfq_service'));
		$this->load->library(array('session','pro_code'));
		$this->load->helper(array('html'));
		$this->config->load('tables');
		
		// LANGUAGE
		$this->lang->load('mod_entry/inventory','bahasa');
		$this->lang->load('mod_entry/pr_rfq','bahasa');
		$this->lang->load('tables','bahasa');
		$this->lang->load('general','bahasa');
		$this->lang->load('label','bahasa');
		
		$arrayCSS = array (
		'asset/css/themes/start/ui.all.css',
		'asset/css/table/DataView.css'
		);
		
		$arrayJS = array (
		'asset/javascript/jQuery/jquery.cookie.js',
		'asset/javascript/jQuery/form/jquery.form.js'
		);
		
		$data['extraHeadContent'] = '';
		
		foreach ($arrayCSS as $css):
			$data['extraHeadContent'] .= '<link media="screen" type="text/css" rel="stylesheet" href="'.base_url().$css.'"/>';
		endforeach;
		foreach ($arrayJS as $js):
			$data['extraHeadContent'] .= '<script type="text/javascript" src="'.base_url().$js.'"/></script>';
		endforeach;
		
		self::$link_controller = 'mod_service/service_sr_rfq';
		self::$link_view = 'purchase/mod_entry/mod_service/mod_sr_rfq';
		$data['link_controller'] = self::$link_controller;
		$data['link_view'] = self::$link_view;
		
		$data['page_title'] = $this->lang->line('inventory_sr_rfq_title');
				
		$this->load->vars($data);
	}
	
	function index() {
		$cek_data = $this->tbl_rfq_service->get_sr_rfq_service();
		if ($cek_data->num_rows() > 0):
		$data['sr_list'] = $cek_data;
		else:
		$data['empty'] = $this->lang->line('list_empty');
		endif;
		$data['content'] = self::$link_view.'/entry_sr_rfq_main';
		$this->load->view('index',$data);
	}
	
	function buat_srfq() {
			
		$chk_sr = $this->input->post('chk_sr');
		$thn     = date("Y");
		$str_thn = date("y");
		$bln     = date("n");
		$str_bln = date("m");
	
		$where_counter['bln'] = $bln;
		$where_counter['thn'] = $thn;
		$get_counter = $this->tbl_sys_counter->get_counter($where_counter);
		
		if ($get_counter->num_rows() > 0):
			$srfq_no = $get_counter->row()->srfq_no;
		else:
			$srfq_no  = 1;
			$insert_counter['thn']=$thn;
			$insert_counter['bln']=$bln;
			$this->tbl_sys_counter->insert_counter($insert_counter);
		endif;
		
		$next_srfq_no = $srfq_no + 1;
		$update_counter['srfq_no'] = $next_srfq_no;
		if ($this->tbl_sys_counter->update_counter($where_counter,$update_counter)):
			//echo 'Update Counter > sukses ';
			$srfq_date =  date("Y-m-d");
			$srfq_no     =  str_pad($srfq_no, 4, "0", STR_PAD_LEFT);
	
			// RFQ NUMBER
			$srfq_doc_no = $this->lang->line('srfq_doc_no');
			$str_srfq_no =  $str_thn."/".$str_bln."/".$srfq_doc_no.$srfq_no;
		
			$insert_srfq['srfq_no'] = $str_srfq_no;
			$insert_srfq['srfq_date'] = $srfq_date;
			if ($this->tbl_rfq_service->insert_srfq($insert_srfq)):
				$srfq_id = $this->db->Insert_ID();
				//echo 'Insert rfq > sukses ';
				for($i=0;$i<sizeof($chk_sr);$i++) {
					$arr_chk = explode("_",$chk_sr[$i]);
					$where_sr['sr_id'] = $arr_chk[0];
					$where_sr['pro_id'] = $arr_chk[1];
					$update_sr['srfq_id'] = $srfq_id;
					$this->tbl_sr->update_sr_detail($where_sr,$update_sr);
					//echo $where_pr['pr_id'].$where_pr['pro_id'].'>sukses'.'<br>';
				}
				echo $str_srfq_no;
				//redirect(self::$link_controller.'/index');
			endif;
		endif;
	}
	
	function ubah_rfq() {
		$chk_pr = $this->input->post('chk_pr');
		for($i=0;$i<sizeof($chk_pr);$i++) {
			$arr_chk = explode("_",$chk_pr[$i]);
			$data['requestStat']='5';
			$where['pro_id']=$arr_chk[0];
			$where['pr_id'] =$arr_chk[1];
			$this->tbl_pr->update_pr_detail($where,$data);
		}
	}
}
?>