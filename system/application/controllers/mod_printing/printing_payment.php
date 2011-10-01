<?php
class printing_payment extends MY_Controller {
	public static $link_view, $link_controller, $user_id, $print_status, $ppn_status;
	function printing_payment() {
		parent::MY_Controller();
		
		$this->load->model(array('tbl_payment','tbl_pr','tbl_gr','tbl_user','tbl_unit','tbl_rptnote','tbl_inventory','flexi_model'));
		$this->load->library(array('flexigrid','flexi_engine','general'));
		$this->load->helper(array('flexigrid','html'));
		$this->load->library('session');
		$this->config->load('tables');
		
		$this->config->load('flexigrid');
		
		// LANGUAGE
		$this->lang->load('mod_entry/po','bahasa');
		$this->lang->load('mod_master/supplier','bahasa');
		$this->lang->load('mod_entry/goodreturn','bahasa');
		$this->lang->load('mod_print/payment','bahasa');
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
		
		self::$link_controller = 'mod_printing/printing_payment';
		self::$link_view = 'purchase/mod_printing/payment_printing';
		$data['link_controller'] = self::$link_controller;
		$data['link_view'] = self::$link_view;
		
		// THIS SESSION USER LOGIN
		self::$user_id = $this->session->userdata("usr_id");
		$user_name	= $this->tbl_user->get_user(self::$user_id)->row()->usr_name;
		$data['usr_name'] = $user_name;
		
		// TITLE
		if ($this->uri->segment(4) == 1)
			$data['page_title'] = $this->lang->line('print_dup_payment_title');
		else
			$data['page_title'] = $this->lang->line('print_payment_title');
		
		// PPN
		self::$ppn_status = '';
		if ($this->session->userdata('module_type') == 'PPN')
			self::$ppn_status = 'ppn_';
		
		$this->load->vars($data);
	}
	
	// QUERY UNTUK NON FLEXIGRID ATO FLEXIGRID
	function flexigrid_sql($flexi=TRUE,$print_status,$count='bkbk_id',$where=FALSE) {
		$sql = "select pay.bkbk_id, date_format(pay.bkbk_date, '%d-%m-%Y') as bkbk_date, pay.bkbk_no, pay.bkbk_methode, s.sup_name, leg.legal_name {COUNT_STR}
            from prc_bkbk as pay 
			inner join prc_master_supplier as s on pay.sup_id = s.sup_id 
			inner join prc_master_legality as leg on leg.legal_id = s.legal_id
			where pay.bkbk_printStatus='".$print_status."' 
			and ((select sum(con_dibayar) from prc_bkbk_detail where bkbk_id = pay.bkbk_id) != 0  
			or (select sum(ppn_dibayar) from prc_bkbk_detail where bkbk_id = pay.bkbk_id) != 0) 
			{SEARCH_STR}";
		return $this->flexi_model->generate_sql($sql,$count,$where,$flexi);
	}
	
	// MEMPERSIAPKAN DATA YANG AKAN DITAMPILKAN
	function flexigrid_ajax($print_status)
	{		
		$this->flexigrid->validate_post('ret_id','asc');
		
		$records = $this->flexigrid_sql(TRUE,$print_status);
				
		$this->output->set_header($this->config->item('json_header'));
		
		if ($records['count'] > 0):		
			foreach ($records['result']->result() as $row)
			{
				$record_items[] = array(
				$row->bkbk_id,
				$row->bkbk_date,
				$row->bkbk_no,
				$row->bkbk_methode,
				$row->sup_name.', '.$row->legal_name,
				'<a href=\'javascript:void(0)\' onclick=\'open_retur('.$row->bkbk_id.','.$print_status.')\'><img border=\'0\' src=\'./asset/img_source/icon_info.gif\'></a>'
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
		$colModel['bkbk_date'] = array($this->lang->line('bkbk_date'),80,TRUE,'center',2);
		$colModel['bkbk_no'] = array($this->lang->line('bkbk_no'),150,TRUE,'left',1);
		$colModel['bkbk_methode'] = array($this->lang->line('bkbk_methode'),120, TRUE,'left',0);
		$colModel['sup_name'] = array($this->lang->line('supp_name'),150,TRUE,'left',0);		
		$colModel['action'] = array($this->lang->line('action'),50, FALSE, 'center',0);		
		
		//if ($print_status != 0):
			//$uri_array = $this->uri->assoc_to_uri($where);
			$ajax_model = site_url(self::$link_controller."/flexigrid_ajax/".$print_status);
		//else:
			//$ajax_model = site_url(self::$link_controller."/flexigrid_ajax");
		//endif;
		
		return build_grid_js('print_payment_list',$ajax_model,$colModel,'bkbk_id','desc',$this->flexi_engine->flexi_params($width,$height,$rp,$title));
		
	}
	
	function index($print_status=0) {
		$cek_data = $this->flexigrid_sql(FALSE,$print_status);
		if ($cek_data->num_rows() > 0):
		$data['js_grid'] = $this->flexigrid_builder($this->lang->line('flex_print_payment'),700,210,8,$print_status);
		else:
		$data['js_grid']= $this->lang->line('list_empty');
		endif;
		$data['content'] = self::$link_view.'/print_payment_main';
		$this->load->view('index',$data);
	}
	
	function select_contrabon($bkbk_id,$print_stats) {
		$con_sql = "select con.con_id,con.con_no,date_format(con.con_date,'%d-%m-%Y') as con_date 
		from prc_bkbk as bk
		inner join prc_bkbk_detail as bkdet on bk.bkbk_id = bkdet.bkbk_id
		inner join prc_contrabon as con on bkdet.con_id = con.con_id
		where bk.bkbk_id = $bkbk_id
		order by bk.bkbk_date desc
		";
		
		$data['con_list'] = $this->db->query($con_sql);
		$data['bkbk_id'] = $bkbk_id;
		$data['print_stats'] = $print_stats;
		
		$this->load->view(self::$link_view.'/print_payment_conlist',$data);
	}
	
	// MENAMPILKAN FORM CETAK PEMBAYARAN
	function print_payment_view($bkbk_id,$print_status) {
		$pay_sql = "select cur.*,
		(select sum(con_dibayar) from prc_bkbk_detail where bkbk_id = $bkbk_id) as con_dibayar,
		(select sum(ppn_dibayar) from prc_bkbk_detail where bkbk_id = $bkbk_id) as ppn_dibayar,
		bk.bkbk_id,bk.bkbk_no,date_format(bk.bkbk_date,'%d-%m-%Y') as bkbk_date,
		bk.bkbk_methode,bk.transfer_biaya,bk.transfer_nomor,bk.transfer_rekening,bk.transfer_supplier,
		bk.bkbk_printStatus,bk.cek_tempo,bk.cek_no,bk.cek_rekening,bk.memo,sup.sup_name,date_format(bk.cek_tempo,'%d-%m-%Y') as cek_tempo,
		con.con_no
		from prc_bkbk as bk 
		inner join prc_bkbk_detail as bkdet on bk.bkbk_id = bkdet.bkbk_id
		inner join prc_master_supplier as sup on bk.sup_id = sup.sup_id 
		inner join prc_master_currency as cur on bkdet.cur_id = cur.cur_id
		inner join prc_contrabon as con on bkdet.con_id = con.con_id
		where bk.bkbk_id = $bkbk_id order by con.con_no";
			
		$data['bkbk_list'] = $this->db->query($pay_sql);
		
		$data['print_status'] = $print_status;
		
		$data['bkbk_id'] = $bkbk_id;
		
		$this->load->view(self::$link_view.'/print_payment_'.self::$ppn_status.'view',$data);
	}
	
	// SETELAH PRINT STATUS PEMBAYARAN PRINT DI UPDATE
	function after_print($bkbk_id,$print_status,$print_count = 1) {
		$print_date		= date('Y-m-d');
		$user_id		= $this->session->userdata("usr_id");
		
		if ($print_status == 0):	
			$update['bkbk_printStatus']='1';
			$update['bkbk_printDate']=$print_date; 
			$update['bkbk_printUsr'] =$user_id;
		else:
			$update['bkbk_printCount'] = $print_count;
			$update['bkbk_printCountDate'] = $print_date;
		endif;
	
		$where['bkbk_id']=$bkbk_id;
		
		if($this->tbl_payment->update_bkbk($where,$update)):
			echo 'Berhasil';
		endif;
	}

}
?>