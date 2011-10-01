<?php
class Entry_pr_rfq extends MY_Controller {
	public static $link_view, $link_controller;
	function Entry_pr_rfq() {
		parent::MY_Controller();
		$this->load->model(array('tbl_pr','tbl_sys_counter','tbl_rfq'));
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
		
		self::$link_controller = 'mod_entry/entry_pr_rfq';
		self::$link_view = 'purchase/mod_entry/mod_product/mod_pr_rfq';
		$data['link_controller'] = self::$link_controller;
		$data['link_view'] = self::$link_view;
		
		$data['page_title'] = $this->lang->line('pr_rfq_title');
				
		$this->load->vars($data);
	}
	
	function index() {
		$cek_data = $this->tbl_rfq->get_pr_rfq();
		if ($cek_data->num_rows() > 0):
		$data['pr_list'] = $cek_data;
		else:
		$data['empty'] = $this->lang->line('list_empty');
		endif;
		$data['content'] = self::$link_view.'/entry_pr_rfq_main';
		$this->load->view('index',$data);
	}
	
	function buat_rfq() {
			
		$chk_pr = $this->input->post('chk_pr');
		$thn     = date("Y");
		$str_thn = date("y");
		$bln     = date("n");
		$str_bln = date("m");
	
		$where_counter['bln'] = $bln;
		$where_counter['thn'] = $thn;
		$get_counter = $this->tbl_sys_counter->get_counter($where_counter);
		
		if ($get_counter->num_rows() > 0):
			$rfq_no = $get_counter->row()->rfq_no;
		else:
			$rfq_no  = 1;
			$insert_counter['thn']=$thn;
			$insert_counter['bln']=$bln;
			$this->tbl_sys_counter->insert_counter($insert_counter);
		endif;
		
		$next_rfq_no = $rfq_no + 1;
		$update_counter['rfq_no'] = $next_rfq_no;
		if ($this->tbl_sys_counter->update_counter($where_counter,$update_counter)):
			//echo 'Update Counter > sukses ';
			$rfq_date =  date("Y-m-d");
			$rfq_no     =  str_pad($rfq_no, 4, "0", STR_PAD_LEFT);
	
			// RFQ NUMBER
			$rfq_doc_no = $this->lang->line('rfq_doc_no');
			$str_rfq_no =  $str_thn."/".$str_bln."/".$rfq_doc_no.$rfq_no;
		
			$insert_rfq['rfq_no'] = $str_rfq_no;
			$insert_rfq['rfq_date'] = $rfq_date;
			if ($this->tbl_rfq->insert_rfq($insert_rfq)):
				$rfq_id = $this->db->Insert_ID();
				//echo 'Insert rfq > sukses ';
				for($i=0;$i<sizeof($chk_pr);$i++) {
					$arr_chk = explode("_",$chk_pr[$i]);
					$where_pr['pr_id'] = $arr_chk[0];
					$where_pr['pro_id'] = $arr_chk[1];
					$update_pr['rfq_id'] = $rfq_id;
					$this->tbl_pr->update_pr_detail($where_pr,$update_pr);
					//echo $where_pr['pr_id'].$where_pr['pro_id'].'>sukses'.'<br>';
				}
				echo $str_rfq_no;
				//redirect(self::$link_controller.'/index');
			endif;
		endif;
	}
	
	function ubah_rfq() {
		$chk_pr = $this->input->post('chk_pr');
		$proses = array();
		$pro_stat = array();
		for($i=0;$i<sizeof($chk_pr);$i++) {
			$proses[$i] = false;
			$arr_chk = explode("_",$chk_pr[$i]);
			$data['requestStat']='5';
			$where['pr_id']	 =$arr_chk[0];
			$where['pro_id'] =$arr_chk[1];
			if ($this->tbl_pr->update_pr_detail($where,$data)):
				$proses[$i] = true;
				$pro_stat[$i] = '- ('.$arr_chk[2].') '.$arr_chk[1].' '.$arr_chk[3].'<br>';
			endif;
			//echo $arr_chk[0].'|'.$arr_chk[1];
		}
		
		if (in_array(false,$proses)):
			echo 'GAGAL';
		else:
			echo '<br>';
			foreach ($pro_stat as $key):
				echo $key;
			endforeach;
		endif;
		
	}
}
?>