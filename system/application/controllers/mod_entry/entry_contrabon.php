<?php
class Entry_contrabon extends MY_Controller {
	public static $link_view, $link_controller, $ppn_status;
	function Entry_contrabon() {
		parent::MY_Controller();
		$this->load->model(array('tbl_contrabon','tbl_po','tbl_gr','tbl_good_return','tbl_sys_counter','tbl_supplier'));
		
		$this->load->config('tables');
		$this->lang->load('mod_entry/contrabon','bahasa');
		$this->lang->load('label','bahasa');
		$this->lang->load('general','bahasa');
		$this->lang->load('tables','bahasa');
		
		$arrayCSS = array (
		'asset/css/themes/start/ui.all.css',
		'asset/javascript/jQuery/autocomplete/jquery.autocomplete.css',
		);
		
		$arrayJS = array (
		'asset/javascript/jQuery/jquery.cookie.js',
		'asset/javascript/jQuery/form/jquery.form.js',
		'asset/javascript/jQuery/autocomplete/jquery.autocomplete.js',
		);
		
		$data['extraHeadContent'] = '';
		
		foreach ($arrayCSS as $css):
			$data['extraHeadContent'] .= '<link media="screen" type="text/css" rel="stylesheet" href="'.base_url().$css.'"/>';
		endforeach;
		foreach ($arrayJS as $js):
			$data['extraHeadContent'] .= '<script type="text/javascript" src="'.base_url().$js.'"/></script>';
		endforeach;

		self::$link_controller = 'mod_entry/entry_contrabon';
		self::$link_view = 'purchase/mod_entry/mod_product/mod_contrabon';
		$data['link_controller'] = self::$link_controller;
		$data['link_view'] = self::$link_view;
		
		$data['page_title'] = $this->lang->line('input_bon_title');
				
		$this->load->vars($data);
		
		self::$ppn_status = '';
		if ($this->session->userdata('module_type') == 'PPN')
			self::$ppn_status = 'ppn_';
	}
	
	function index() {
		/*
		$from = $this->config->item('tbl_gr');
		$join[$this->config->item('tbl_po').' as po'] = 'gr.po_id=po.po_id';
		$join[$this->config->item('tbl_supplier').' as sup'] = 'sup.sup_id=po.sup_id';
		$where['con_id'] = '0';
		$where['gr_status'] = '1';
		$where['gr_type'] = 'rec';
		
		$data['list_sup_bon'] = $this->tbl_contrabon->get_bon($where,false,$from,$join,false,true);
		*/
		
		$data['list_sup_bon'] = $this->tbl_contrabon->get_contrabon();
		$data['content'] = self::$link_view.'/entry_cb_main';
		$this->load->view('index',$data);
	}
	
	function list_po() {
		$sup_id = $this->input->post('sup_id');
		$po_id = $this->input->post('po_id');
		$spo_id = $this->input->post('spo_id');
		if ($po_id != ''):
			$this->list_gr($po_id,$spo_id);
		else:
			$data['list_po'] = $this->tbl_contrabon->get_po($sup_id);
			if ($data['list_po']->num_rows()>0)
				$data['page_title_next'] = 'PEMASOK ( <strong>'.$data['list_po']->row()->legal_name.'. '.$data['list_po']->row()->sup_name.'</strong> )';
			else
				$data['page_title_next'] = 'PEMASOK';
			$data['content'] = self::$link_view.'/entry_cb_listpo';
			$this->load->view('index',$data);
		endif;
	}
	
	function list_gr($po_id,$sup_id,$debug=0) {
		//$po_no = $a.'/'.$b.'/'.$c;
		$where_po['po_id'] = $po_id;
		$get_po = $this->tbl_po->get_po($where_po);
		$data['po_id'] = $po_id;
		$data['list_po'] = $this->tbl_po->get_po_bon($po_id);
		$data['list_retur'] = $this->tbl_good_return->get_retur_contrabon($po_id);
		$data['po_no'] = $get_po->row()->po_no;
		$sql_po = "select cur.cur_symbol from prc_master_currency as cur inner join prc_pr_detail as pr on pr.cur_id = cur.cur_id where pr.po_id = $po_id";
		$data['cur_symbol'] = $this->db->query($sql_po)->row()->cur_symbol;
		$data['sup_name'] = $this->tbl_supplier->get_supplier_full($sup_id);
		$data['debug'] = $debug;
		
		$data['page_title_next'] = '( <strong>'.$data['sup_name'].'</strong> ), No PO : '.$get_po->row()->po_no;
		$data['content'] =  self::$link_view.'/entry_cb_'.self::$ppn_status.'listgr';
		$this->load->view('index',$data);
	}
	
	function buat_contra() {
		$jum_row		= $this->input->post("jum_row");
		$con_penerima	= $this->input->post("con_penerima");
		$gr_id = $this->input->post('gr_id');
		$gr_ppn = $this->input->post('gr_ppn');
		$kurs = $this->input->post('kurs');
		
		$ret_id = $this->input->post('ret_id');
		$po_id = $this->input->post('po_id');
		$thn     = date("Y");
		$str_thn = date("y");
		$bln     = date("n");
		$str_bln = date("m");
		/*
		foreach ($_POST as $key=>$val):
			echo $key.'='.$val.'<br>';
		endforeach;
		
		for($i=0;$i<sizeof($gr_id);$i++) {
			echo $gr_id[$i].' ';			
		}
		*/
		$where_counter['bln'] = $bln;
		$where_counter['thn'] = $thn;
		$get_counter = $this->tbl_sys_counter->get_counter($where_counter);
		
		if ($get_counter->num_rows() > 0):
			$con_no = $get_counter->row()->con_no;
		else:
			$con_no  = 1;
			$insert_counter['thn']=$thn;
			$insert_counter['bln']=$bln;
			$this->tbl_sys_counter->insert_counter($insert_counter);
		endif;
		
		$next_con_no = $con_no + 1;
		$con_no =  str_pad($con_no, 4, "0", STR_PAD_LEFT);
		// CONTRABON NUMBER
		$con_doc_no = $this->lang->line('con_doc_no');
		$str_con_no =  $str_thn.'/'.$str_bln."/".$con_doc_no.$con_no;
		
		$update_counter['con_no'] = $next_con_no;
		//echo $update_counter['con_no'].' '.$where_counter['bln'].' '.$where_counter['thn'].' '.$con_no;
		
		if ($this->tbl_sys_counter->update_counter($where_counter,$update_counter)):
			$con_date = date("Y-m-d");
			$insert_bon['con_no'] = $str_con_no;
			$insert_bon['con_date'] = $con_date;
			$insert_bon['con_value'] = '0';
			$insert_bon['con_penerima'] = $con_penerima;
			$insert_bon['cur_id'] = '1';
			$insert_bon['po_id'] = $po_id;
			if ($this->tbl_contrabon->insert_bon($insert_bon)):
				$con_id = $this->db->Insert_ID();
				$con_value     = 0;
				$con_ret_value = 0;
				$con_ppn = 0;
				
				for($i=0;$i<sizeof($gr_id);$i++) {
					$arr_gr = explode("_",$gr_id[$i]);
					$con_value = $con_value + $arr_gr[1];
					$con_cur   = $arr_gr[2];
					
					$where_gr['gr_id']=$arr_gr[0];
					$update_gr['con_id']=$con_id;
					//echo $where_gr['gr_id'].' '.$update_gr['con_id'].'<br>';
					
					if ($this->tbl_gr->update_gr($where_gr,$update_gr)):
						//--check value from good-return--
						$gr_value = $this->tbl_gr->cek_value_gr($arr_gr[0]);
						if ($gr_value->num_rows()>0):
							$con_ret_value = $con_ret_value + $gr_value->row()->gr_value_return;
						endif;
					endif; 
					// HITUNG PPN DAN KURS
					$con_ppn = $con_ppn + $gr_ppn[$i];
				}
				
				for($i=0;$i<sizeof($ret_id);$i++) {
					if ($ret_id[$i] != ''):
						// GET RETUR HARGA
						$get_retur_price = $this->tbl_good_return->get_retur_bon_price($ret_id[$i]);
						if ($get_retur_price->num_rows() > 0):
							$where_ret['ret_id'] = $ret_id[$i];
							$update_ret['con_id'] = $con_id;
							$this->tbl_good_return->update_return($where_ret,$update_ret);
							$con_value = $con_value - $get_retur_price->row()->price_retur;
						endif;
					endif;
				}				
				
				$con_value = $con_value - $con_ret_value;
				$where_ubon['con_id']  = $con_id;
				$update_bon['con_value']= $con_value;
				$update_bon['con_ppn_value']= $con_ppn;
				$update_bon['cur_id']   = $con_cur;
				//echo $where_ubon['con_id'].' '.$update_bon['con_value'].' '.$update_bon['cur_id'];
				
				if ($this->tbl_contrabon->update_bon($where_ubon,$update_bon)):
					echo $str_con_no;
				endif;
			endif;
		endif;
		
	}
	
	function list_autocomplate() {
		$q = strtoupper($this->input->get('q'));
		
		/*
		$like_po['po_no'] = $q;
		$where_po['po_status'] = 0;
		$where_po['po_printStat'] = 1;
		$qres = $this->tbl_po->get_po($where_po,$like_po);
		*/
		
		$qres = $this->db->query("select distinct p.po_id,p.po_no, s.sup_id, s.sup_name, leg.legal_name, s.sup_status from prc_gr as g
			inner join prc_po as p on g.po_id = p.po_id
			inner join prc_master_supplier as s on s.sup_id = p.sup_id 
			inner join prc_master_legality as leg on s.legal_id = leg.legal_id 
			where g.con_id='0' and (gr_status='1' or gr_status='3') and g.gr_type='rec' and p.po_no like '$q%' order by s.sup_name");
		
		$limit = strtoupper($this->input->get('limit'));
		
		if ($qres->num_rows() > 0):
			foreach ($qres->result() as $rows):
				if (strpos($rows->po_no, $q) !== false):
					echo "$rows->po_no|$rows->po_id|$rows->sup_id\n";
				endif;
			endforeach;
		endif;
		
		//echo $q.'|'.'|'.$limit;
	}

}
?>