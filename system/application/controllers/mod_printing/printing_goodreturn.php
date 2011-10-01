<?php
class Printing_goodreturn extends MY_Controller {
	public static $link_view, $link_controller, $user_id, $print_status;
	function Printing_goodreturn() {
		parent::MY_Controller();
		
		$this->load->model(array('tbl_good_return','tbl_pr','tbl_gr','tbl_user','tbl_unit','tbl_rptnote','tbl_inventory','flexi_model'));
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
		
		self::$link_controller = 'mod_printing/printing_goodreturn';
		self::$link_view = 'purchase/mod_printing/goodreturn_printing';
		$data['link_controller'] = self::$link_controller;
		$data['link_view'] = self::$link_view;
		
		// THIS SESSION USER LOGIN
		self::$user_id = $this->session->userdata("usr_id");
		$user_name	= $this->tbl_user->get_user(self::$user_id)->row()->usr_name;
		$data['usr_name'] = $user_name;
		
		// TITLE
		if ($this->uri->segment(4) == 1)
			$data['page_title'] = $this->lang->line('print_dup_goodreturn_title');
		else
			$data['page_title'] = $this->lang->line('print_goodreturn_title');
		
		$this->load->vars($data);
	}
	
	// QUERY UNTUK NON FLEXIGRID ATO FLEXIGRID
	function flexigrid_sql($flexi=TRUE,$print_status,$count='ret_id',$where=FALSE) {
		$sql = "select g.ret_id, date_format(g.ret_date, '%d-%m-%Y') as ret_date, g.ret_no, p.po_no, s.sup_name {COUNT_STR}
            from prc_good_return as g 
			inner join prc_po as p on p.po_id = g.po_id
			inner join prc_master_supplier as s on p.sup_id = s.sup_id 
			where (g.ret_status=1 or g.ret_status=2) and g.ret_printStatus='".$print_status."' {SEARCH_STR}";
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
				$row->ret_id,
				$row->ret_date,
				$row->ret_no,
				$row->po_no,
				$row->sup_name,
				'<a href=\'javascript:void(0)\' onclick=\'open_retur('.$row->ret_id.','.$print_status.')\'><img border=\'0\' src=\'./asset/img_source/icon_info.gif\'></a>'
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
		$colModel['ret_date'] = array($this->lang->line('ret_date'),100,TRUE,'center',2);
		$colModel['ret_no'] = array($this->lang->line('ret_no'),100,TRUE,'left',1);
		$colModel['po_no'] = array($this->lang->line('po_no'),100, TRUE,'left',0);
		$colModel['sup_name'] = array($this->lang->line('supp_name'),150,TRUE,'center',0);		
		$colModel['action'] = array($this->lang->line('action'),50, FALSE, 'center',0);		
		
		//if ($print_status != 0):
			//$uri_array = $this->uri->assoc_to_uri($where);
			$ajax_model = site_url(self::$link_controller."/flexigrid_ajax/".$print_status);
		//else:
			//$ajax_model = site_url(self::$link_controller."/flexigrid_ajax");
		//endif;
		
		return build_grid_js('print_retur_list',$ajax_model,$colModel,'ret_id','desc',$this->flexi_engine->flexi_params($width,$height,$rp,$title));
		
	}
	
	function index($print_status=0) {
		$cek_data = $this->flexigrid_sql(FALSE,$print_status);
		if ($cek_data->num_rows() > 0):
		$data['js_grid'] = $this->flexigrid_builder($this->lang->line('flex_print_retur'),700,210,8,$print_status);
		else:
		$data['js_grid']= $this->lang->line('list_empty');
		endif;
		$data['content'] = self::$link_view.'/print_retur_main';
		$this->load->view('index',$data);
	}
	
	// MENAMPILKAN FORM CETAK BPB
	function print_retur_view($ret_id,$print_status) {
		//$idnote = 3; // untuk print BPB
		//$ret_print = $this->tbl_goodreturn->get_print_view($re_id);
		$ret_sql = "select g.ret_printCount, g.ret_id, date_format(g.ret_date, '%d-%m-%Y') as ret_date, g.ret_no, p.po_no, s.sup_name , g.ret_printStatus 
            from prc_good_return as g 
			inner join prc_po as p on p.po_id = g.po_id
			inner join prc_master_supplier as s on p.sup_id = s.sup_id 
			where (g.ret_status=1 or g.ret_status=2) and g.ret_id = $ret_id";
			
		$ret_det_sql = "select pro.pro_name, pro.pro_code, gd.qty, gd.keterangan, sat.satuan_name,sat.satuan_id, sat.satuan_format
            from prc_good_return_detail as gd 
			inner join prc_good_return as g on g.ret_id = gd.ret_id
			inner join prc_master_product as pro on gd.pro_id = pro.pro_id
			inner join prc_pr_detail as pr on pr.po_id = g.po_id and pr.pro_id = gd.pro_id
			inner join prc_master_satuan as sat on sat.satuan_id = pr.um_id
			where g.ret_id = $ret_id";
			
		$data['ret_list'] = $this->db->query($ret_sql);
		$data['ret_det_list'] = $this->db->query($ret_det_sql);
		
		$data['print_status'] = $print_status;
		
		$this->load->view(self::$link_view.'/print_retur_view',$data);
	}
	
	// SETELAH PRINT STATUS BPB PRINT DI UPDATE
	function after_print($ret_id,$print_status,$print_count = 1) {
		$print_date		= date('Y-m-d');
		$user_id		= $this->session->userdata("usr_id");
		
		if ($print_status == 0):	
			$update['ret_printStatus']='1';
			$update['ret_printDate']=$print_date; 
			$update['ret_printUsr'] =$user_id;
		else:
			$update['ret_printCount'] = $print_count;
			$update['ret_printCountDate'] = $print_date;
		endif;
	
		$where['ret_id']=$ret_id;
		
		if($this->tbl_good_return->update_return($where,$update)):
			if ($print_status == 0):
				$get_retur = $this->tbl_good_return->get_retur($ret_id);
				if ($get_retur->num_rows() > 0):
					foreach ($get_retur->result() as $rows):
						$this->update_inventory($rows->is_stockJoin,$rows->pro_id,$rows->sup_id,$rows->qty,$ret_id,$rows->ret_no,$rows->price,$rows->cur_id,$rows->pro_sat,$rows->pr_sat);
						$this->update_pr_detail($rows->pr_id,$rows->pro_id,$rows->po_id,$rows->qty,$rows->qty_retur,$rows->pro_sat,$rows->pr_sat);
					endforeach;
				endif;
			endif;
			$this->print_retur_view($ret_id,$print_status);
		endif;
	}
	
	// UPDATE PR DETAIL
	function update_pr_detail($pr_id,$pro_id,$po_id,$qty_ret,$qty_pr_ret,$pro_sat,$pr_sat) {
		//echo "$pr_id,$pro_id,$po_id,$qty_ret,$qty_pr_ret,$pro_sat,$pr_sat<br>";
		$where_pr['pr_id'] = $pr_id;
		$where_pr['pro_id'] = $pro_id;
		$where_pr['po_id'] = $po_id;
		
		if ($pro_sat != $pr_sat):
			$get_satuan_val = $this->db->query("select * from prc_satuan_produk where pro_id = '".$pro_id."' and satuan_id = '".$get_qty2->pro_um_id."' and satuan_unit_id = '".$get_qty2->sub_um_id."'");
			if ($get_satuan_val->num_rows() > 0):
				$um_sub_val = $get_satuan_val->row()->value;
				$qty_ret = $um_sub_val * $qty_ret; // QUANTITY UNIT SATUAN
				//$pro_price = $pro_price / $um_sub_val; //PRICE UNIT SATUAN
				echo 'SUKSES';
			endif;
		endif;
		
		$update_pr['qty_retur'] = $qty_pr_ret + $qty_ret;
		
		$this->tbl_pr->update_pr_detail($where_pr,$update_pr);
		
	}
	
	// UPDATE INVENTORI STOK SETELAH PRINT BPB DI PROSES
	function update_inventory($join_stat,$pro_id,$sup_id,$qty,$ret_id,$ret_no,$pro_price,$currency,$pro_sat,$pr_sat) {
				
				/*$get_qty = $this->db->query("
					select pd.um_id as sub_um_id,pro.um_id as pro_um_id,d.qty, pro.pro_code, pro.pro_name,um.satuan_name 
					from prc_good_return as g
					inner join prc_good_return_detail as d on g.ret_id = d.ret_id
					inner join prc_master_product as pro on d.pro_id = pro.pro_id
					inner join prc_pr_detail as pd on pd.po_id = g.po_id and pd.pro_id = d.pro_id
					inner join prc_master_satuan as um on pd.um_id = um.satuan_id 
					where g.ret_id='".$ret_id."'");*/
				
				// CEK UNIT SATUAN
				//if ($get_qty->num_rows() > 0):
					//$get_qty2 = $get_qty->row();
					//if ($get_qty2->pro_um_id != $get_qty2->sub_um_id):
					if ($pro_sat != $pr_sat):
						$get_satuan_val = $this->db->query("select * from prc_satuan_produk where pro_id = '".$pro_id."' and satuan_id = '".$pro_sat."' and satuan_unit_id = '".$pr_sat."'");
						if ($get_satuan_val->num_rows() > 0):
							$um_sub_val = $get_satuan_val->row()->value;
							$qty = $um_sub_val * $qty; // QUANTITY UNIT SATUAN
							$pro_price = $pro_price / $um_sub_val; //PRICE UNIT SATUAN
						endif;
					endif;
				//endif;
				
				if($join_stat==0):
					$where_inv_join['pro_id'] = $pro_id;
					$where_inv_join['sup_id'] = $sup_id;
					$get_inventory = $this->tbl_inventory->get_inventory($where_inv_join);
					if($get_inventory->num_rows() > 0):
						$end_stock_card   = $get_inventory->row()->inv_end;
						$inv_id  = $get_inventory->row()->inv_id;
						$end_balance = $end_stock_card - $qty;
						
						$inv_price = $get_inventory->row()->inv_price;
						$inv_bal = $get_inventory->row()->bal_price;
						
					else:
						$data_inv_ins['pro_id']=$pro_id;
						$data_inv_ins['sup_id']=$sup_id;
						$data_inv_ins['inv_begin']=$qty;
						$data_inv_ins['inv_end']=$qty;
						$data_inv_ins['inv_document']=$ret_no;
						$data_inv_ins['inv_transDate']=date('Y-m-d H:i:s');
									
						if ($this->tbl_inventory->save_inventory($data_inv_ins)):
							$inv_id = $this->db->Insert_ID();
							$end_stock_card = 0;
							$end_balance = $qty;
						endif;
						
					endif;
					//echo 'notjoin cek stok|';
				
				else:
					$where_inv_notjoin['pro_id'] = $pro_id;
					$get_inventory = $this->tbl_inventory->get_inventory($where_inv_notjoin);
					if($get_inventory->num_rows() > 0):
						$end_stock_card   = $get_inventory->row()->inv_end;
						$inv_id  = $get_inventory->row()->inv_id;
						$end_balance = $end_stock_card - $qty;
						
						$inv_price = $get_inventory->row()->inv_price;
						$inv_bal = $get_inventory->row()->bal_price;
					else:
						$end_balance = 0;					
					endif;		
					//echo 'join cek stok|';
				endif;
				
				//echo 'STOK END '.$end_stock_card.'|END BALANCE '.$end_balance.'| PRICE '.$inv_price.'| BAL '.$inv_bal;
								
				//--step 3: insert to stock card
				$data_invhis_ins['inv_id'] = $inv_id;
				$data_invhis_ins['pro_id'] = $pro_id;
				
				if($join_stat==0):
					$data_invhis_ins['sup_id'] = $sup_id;
				endif;
					
				$data_invhis_ins['inv_begin'] = $end_stock_card;
				$data_invhis_ins['inv_out'] = $qty;
				$data_invhis_ins['inv_end'] = $end_balance;
				
				// Ballance Price
				$cal_inv_price = ($inv_price+$pro_price)/2;
				$cal_bal_price = $cal_inv_price * $end_balance;
				
				$data_invhis_ins['inv_price'] = $cal_inv_price;//$pro_price;
				$data_invhis_ins['bal_price'] = $cal_bal_price;
				
				//$data_invhis_ins['bal_price'] = $pro_price * ;
				$data_invhis_ins['cur_id'] = $currency;
				$data_invhis_ins['inv_document'] = $ret_no;
				$data_invhis_ins['inv_transDate']=date('Y-m-d H:i:s');
				
				if ($this->tbl_inventory->save_inv_history($data_invhis_ins))
					//echo 'insert inv history|';
					
				//--update on parent table	
				if ($join_stat==1):
					$where_inv_upd['pro_id'] = $pro_id;
					$data_inv_upd['cur_id'] = $currency;
					$data_inv_upd['inv_begin'] = $end_stock_card;
					$data_inv_upd['inv_in'] = '0';
					$data_inv_upd['inv_out'] = $qty;
					//$data_inv_upd['inv_price'] = $pro_price;
					
					// Ballance Price
					$data_inv_upd['inv_price'] = $cal_inv_price;
					$data_inv_upd['bal_price'] = $cal_bal_price;
					
					$data_inv_upd['inv_end'] = $end_balance;
					$data_inv_upd['inv_document'] = $ret_no;
					//$data_inv_upd['inv_transDate']=date('Y-m-d H:i:s');
						
					if($this->tbl_inventory->update_inventory($where_inv_upd,$data_inv_upd)):
						//echo 'update join inv sukses|';
					endif;
				else:	
					$where_inv_cek['pro_id'] = $pro_id;
					$where_inv_cek['sup_id'] = $sup_id;

					if ($this->tbl_inventory->get_inventory($where_inv_cek)->num_rows() > 0):
						$where_inv_upd['pro_id'] = $pro_id;
						$where_inv_upd['sup_id'] = $sup_id;
						$data_inv_upd['cur_id'] = $currency;
						$data_inv_upd['inv_begin'] = $end_stock_card;
						$data_inv_upd['inv_in'] = '0';
						$data_inv_upd['inv_out'] = $qty;
						//$data_inv_upd['inv_price'] = $pro_price;
						
						// Ballance Price
						$data_inv_upd['inv_price'] = $cal_inv_price;
						$data_inv_upd['bal_price'] = $cal_bal_price;
						
						$data_inv_upd['inv_end'] = $end_balance;
						$data_inv_upd['inv_document'] = $ret_no;
						//$data_inv_upd['inv_transDate']=date('Y-m-d H:i:s');
						
						if($this->tbl_inventory->update_inventory($where_inv_upd,$data_inv_upd)):
							//echo 'update notjoin inv sukses|';
						endif;
					endif;
				endif;
				//echo '<br>';
	}
}
?>