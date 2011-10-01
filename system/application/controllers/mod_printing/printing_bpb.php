<?php
class Printing_bpb extends MY_Controller {
	public static $link_view, $link_controller, $user_id, $ppn_status, $print_status;
	function Printing_bpb() {
		parent::MY_Controller();
		
		$this->load->model(array('tbl_gr','tbl_user','tbl_unit','tbl_rptnote','tbl_inventory','flexi_model'));
		$this->load->library(array('flexigrid','flexi_engine'));
		$this->load->helper(array('flexigrid','html'));
		$this->load->library('session');
		$this->config->load('tables');
		
		$this->config->load('flexigrid');
		
		// LANGUAGE
		$this->lang->load('mod_entry/po','bahasa');
		$this->lang->load('mod_master/supplier','bahasa');
		$this->lang->load('mod_entry/goodreceive','bahasa');
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
		
		self::$link_controller = 'mod_printing/printing_bpb';
		self::$link_view = 'purchase/mod_printing/bpb_printing';
		$data['link_controller'] = self::$link_controller;
		$data['link_view'] = self::$link_view;
		
		// THIS SESSION USER LOGIN
		self::$user_id = $this->session->userdata("usr_id");
		$user_name	= $this->tbl_user->get_user(self::$user_id)->row()->usr_name;
		$data['usr_name'] = $user_name;
		
		// TITLE
		if ($this->uri->segment(4) == 1)
			$data['page_title'] = $this->lang->line('print_dup_bpb_title');
		else
			$data['page_title'] = $this->lang->line('print_bpb_title');
		
		$this->load->vars($data);
		
		self::$ppn_status = '';
		if ($this->session->userdata('module_type') == 'PPN')
			self::$ppn_status = 'ppn_';
	}
	
	// QUERY UNTUK NON FLEXIGRID ATO FLEXIGRID
	function flexigrid_sql($flexi=TRUE,$print_status,$count='gr_id',$where=FALSE) {
		$sql = "select g.gr_id, date_format(g.gr_date, '%d-%m-%Y') as gr_date, g.gr_no, p.po_no, s.sup_name ,g.gr_suratJalan,leg.legal_name {COUNT_STR}
            from prc_gr as g inner join prc_po as p on p.po_id = g.po_id
			inner join prc_master_supplier as s on p.sup_id = s.sup_id 
			inner join prc_master_legality as leg on leg.legal_id = s.legal_id 
			where g.gr_status=0 and g.gr_printStatus='".$print_status."' {SEARCH_STR}";
		return $this->flexi_model->generate_sql($sql,$count,$where,$flexi);
	}
	
	// MEMPERSIAPKAN DATA YANG AKAN DITAMPILKAN
	function flexigrid_ajax($print_status)
	{		
		$this->flexigrid->validate_post('gr_id','asc');
		
		$records = $this->flexigrid_sql(TRUE,$print_status);
				
		$this->output->set_header($this->config->item('json_header'));
		
		if ($records['count'] > 0):		
			foreach ($records['result']->result() as $row)
			{
				$record_items[] = array(
				$row->gr_id,
				$row->gr_date,
				$row->gr_no,
				$row->po_no,
				$row->sup_name.', '.$row->legal_name,
				$row->gr_suratJalan,
				'<a href=\'javascript:void(0)\' onclick=\'open_bpb('.$row->gr_id.','.$print_status.')\'><img border=\'0\' src=\'./asset/img_source/icon_info.gif\'></a>'
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
		$colModel['gr_date'] = array($this->lang->line('gr_date'),100,TRUE,'center',2);
		$colModel['gr_no'] = array($this->lang->line('gr_no'),100,TRUE,'left',1);
		$colModel['po_no'] = array($this->lang->line('po_no'),100, TRUE,'left',0);
		$colModel['sup_name'] = array($this->lang->line('supp_name'),150,TRUE,'left',0);		
		$colModel['gr_suratJalan'] = array($this->lang->line('gr_suratJalan'),100, FALSE, 'left',0);
		$colModel['action'] = array($this->lang->line('action'),50, FALSE, 'center',0);		
		
		$ajax_model = site_url(self::$link_controller."/flexigrid_ajax/".$print_status);
		
		return build_grid_js('print_bpb_list',$ajax_model,$colModel,'gr_id','desc',$this->flexi_engine->flexi_params($width,$height,$rp,$title));
		
	}
	
	function index($print_status = 0) {
		$cek_data = $this->flexigrid_sql(FALSE,$print_status);
		if ($cek_data->num_rows() > 0):
		$data['js_grid'] = $this->flexigrid_builder($this->lang->line('flex_print_bpb'),700,210,8,$print_status);
		else:
		$data['js_grid']= $this->lang->line('list_empty');
		endif;
		$data['content'] = self::$link_view.'/print_bpb_main';
		$this->load->view('index',$data);
	}
	
	// MENAMPILKAN FORM CETAK BPB
	function print_bpb_view($gr_id,$print_status) {
		$idnote = 3; // untuk print BPB
		$note = $this->tbl_rptnote->get_note($idnote);
		$data['notes']='';
		if ($note->num_rows() > 0)
			$data['notes']=$note->row()->note;
		
		$gr_print = $this->tbl_gr->get_gr_print_view($gr_id);
		$data['gr_list'] = $gr_print['gr_list'];
		$data['po_det_list'] = $gr_print['po_det_list'];
		
		$data['print_status'] = $print_status;

		$this->load->view(self::$link_view.'/print_bpb_'.self::$ppn_status.'view',$data);

	}
	
	// SETELAH PRINT STATUS BPB PRINT DI UPDATE
	function after_print($gr_id,$print_status,$print_count = 1) {
		$print_date		= date('Y-m-d');
		$user_id		= $this->session->userdata("usr_id");

		if ($print_status == 0):	
			$update['gr_printStatus']='1';
			$update['gr_printDate']=$print_date; 
			$update['gr_printUsr'] =$user_id;
		else:
			$update['gr_printCount'] = $print_count;
			$update['gr_printCountDate'] = $print_date;
		endif;
	
		$where['gr_id']=$gr_id;
	
		if($this->tbl_gr->update_gr($where,$update)):
			if ($print_status == 0):	
				$get_gr_data = $this->tbl_gr->get_gr_inventory($gr_id);
				if ($get_gr_data->num_rows() > 0):
					foreach ($get_gr_data->result() as $rows):
						$this->update_inventory($rows->is_stockJoin,$rows->pro_id,$rows->sup_id,$rows->qty,$gr_id,$rows->gr_no,$rows->price,$rows->cur_id);
					endforeach;
					echo 'ok';
				endif;
			else:
				$this->print_bpb_view($gr_id,$print_status);
			endif;
		endif;
	}
	
	// UPDATE INVENTORI STOK SETELAH PRINT BPB DI PROSES
	function update_inventory($join_stat,$pro_id,$sup_id,$qty,$gr_id,$gr_no,$pro_price,$currency) {
		$str_gr_no = $gr_no;
		$str_rec_no = $gr_no;
				
		$inv_price = 0;
				
		// CEK UNIT SATUAN -->
		$get_qty = $this->db->query("
		select pd.um_id as sub_um_id,pro.um_id as pro_um_id,d.qty, pro.pro_code, pro.pro_name,um.satuan_name 
		from prc_gr as g
		inner join prc_gr_detail as d on g.gr_id = d.gr_id
		inner join prc_master_product as pro on d.pro_id = pro.pro_id
		inner join prc_pr_detail as pd on pd.po_id = g.po_id and pd.pro_id = d.pro_id
		inner join prc_master_satuan as um on pd.um_id = um.satuan_id 
		where g.gr_id='".$gr_id."'");
				
		if ($get_qty->num_rows() > 0):
			$get_qty2 = $get_qty->row();
			if ($get_qty2->pro_um_id != $get_qty2->sub_um_id):
				$get_satuan_val = $this->db->query("select * from prc_satuan_produk where pro_id = '".$pro_id."' and satuan_id = '".$get_qty2->pro_um_id."' and satuan_unit_id = '".$get_qty2->sub_um_id."'");
				if ($get_satuan_val->num_rows() > 0):
					$um_sub_val = $get_satuan_val->row()->value;
					$qty = $um_sub_val * $qty; // QUANTITY UNIT SATUAN
					//$pro_price = $pro_price / $um_sub_val; //PRICE UNIT SATUAN
				endif;
			endif;
		endif;
		// --> END SATUAN UNIT
				
		// KARTU STOK SPESIFIK -->		
		if($join_stat==0):
			$where_inv_join['pro_id'] = $pro_id;
			$where_inv_join['sup_id'] = $sup_id;
			$get_inventory = $this->tbl_inventory->get_inventory($where_inv_join);
			if($get_inventory->num_rows() > 0):
				$end_stock_card   = $get_inventory->row()->inv_end;
				$inv_id  = $get_inventory->row()->inv_id;
				$end_balance = $end_stock_card + $qty;
						
				$inv_price = $get_inventory->row()->inv_price;
				$inv_bal = $get_inventory->row()->bal_price;			
			else:
				$data_inv_ins['pro_id']=$pro_id;
				$data_inv_ins['sup_id']=$sup_id;
				$data_inv_ins['inv_begin']=$qty;
				$data_inv_ins['inv_end']=$qty;
				$data_inv_ins['inv_document']=$str_gr_no;
				$data_inv_ins['inv_transDate']=date('Y-m-d H:i:s');
								
				if ($this->tbl_inventory->save_inventory($data_inv_ins)):
					$inv_id = $this->db->Insert_ID();
					$end_stock_card = 0;
					$end_balance = $qty;
				endif;
			endif;
			
		// KARTU STOK GENERAL -->
		else:
			$where_inv_notjoin['pro_id'] = $pro_id;
			$get_inventory = $this->tbl_inventory->get_inventory($where_inv_notjoin);
			if($get_inventory->num_rows() > 0):
				$end_stock_card   = $get_inventory->row()->inv_end;
				$inv_id  = $get_inventory->row()->inv_id;
				$end_balance = $end_stock_card + $qty;
				
				$inv_price = $get_inventory->row()->inv_price;
				$inv_bal = $get_inventory->row()->bal_price;
			else:
				$end_balance = 0;					
			endif;		
			//echo 'join cek stok|';
		endif;
				
		// SET BALLANCE PRICE -->
		$cal_inv_price = ($inv_price+$pro_price)/2;
		$cal_bal_price = $cal_inv_price * $end_balance;
		// --> END
		
		// UPDATE INVENTORY HISTORY -->
		$data_invhis_ins['inv_id'] = $inv_id;
		$data_invhis_ins['pro_id'] = $pro_id;
		
		if($join_stat==0):
			$data_invhis_ins['sup_id'] = $sup_id;
		endif;
			
		$data_invhis_ins['inv_begin'] = $end_stock_card;
		$data_invhis_ins['inv_in'] = $qty;
		$data_invhis_ins['inv_end'] = $end_balance;
		
		$data_invhis_ins['inv_price'] = $pro_price;
		
		//$data_invhis_ins['inv_price'] = $cal_inv_price;
		$data_invhis_ins['bal_price'] = $cal_bal_price;
		
		$data_invhis_ins['cur_id'] = $currency;
		$data_invhis_ins['inv_document'] = $str_gr_no;
		$data_invhis_ins['inv_transDate']= date('Y-m-d H:i:s');
		
		$this->tbl_inventory->save_inv_history($data_invhis_ins);
		// --> END UPDATE INVENTORY HISTORY
			
		// UPDATE INVENTORY -->
		if ($join_stat==1):
			$where_inv_upd['pro_id'] = $pro_id;
			$data_inv_upd['cur_id'] = $currency;
			$data_inv_upd['inv_begin'] = $end_stock_card;
			$data_inv_upd['inv_in'] = $qty;
			$data_inv_upd['inv_out'] = '0';
			
			$data_inv_upd['inv_price'] = $pro_price;
			
			// Ballance Price
			//$data_inv_upd['inv_price'] = $cal_inv_price;
			$data_inv_upd['bal_price'] = $cal_bal_price;
			
			$data_inv_upd['inv_end'] = $end_balance;
			$data_inv_upd['inv_document'] = $str_rec_no;
			$data_inv_upd['inv_transDate']=date('Y-m-d H:i:s');
				
			$this->tbl_inventory->update_inventory($where_inv_upd,$data_inv_upd);
			
		else:	
			$where_inv_cek['pro_id'] = $pro_id;
			$where_inv_cek['sup_id'] = $sup_id;
			if ($this->tbl_inventory->get_inventory($where_inv_cek)->num_rows() > 0):
				$where_inv_upd['pro_id'] = $pro_id;
				$where_inv_upd['sup_id'] = $sup_id;
				$data_inv_upd['cur_id'] = $currency;
				$data_inv_upd['inv_begin'] = $end_stock_card;
				$data_inv_upd['inv_in'] = $qty;
				$data_inv_upd['inv_out'] = '0';
				
				$data_inv_upd['inv_price'] = $pro_price;
						
				// Ballance Price
				//$data_inv_upd['inv_price'] = $cal_inv_price;
				$data_inv_upd['bal_price'] = $cal_bal_price;
				
				$data_inv_upd['inv_end'] = $end_balance;
				$data_inv_upd['inv_document'] = $str_rec_no;
				$data_inv_upd['inv_transDate']= date('Y-m-d H:i:s');
						
				$this->tbl_inventory->update_inventory($where_inv_upd,$data_inv_upd);

			endif;
		endif;
		// --> END UPDATE INVENTORY

	}
}
?>