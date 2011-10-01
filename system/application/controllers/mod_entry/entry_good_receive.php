<?php
class Entry_good_receive extends MY_Controller {
	public static $link_view, $link_controller, $ppn_status;
	function Entry_good_receive() {
		parent::MY_Controller();
		$this->load->model(array('flexi_model','tbl_good_return','tbl_po','tbl_pr','tbl_gr','tbl_inventory','tbl_sys_counter','tbl_produk','tbl_unit','tbl_term'));
		$this->load->helper(array('general','flexigrid'));
		$this->load->config('tables');
		$this->config->load('flexigrid');
		$this->load->library(array('general','flexigrid','flexi_engine','pictures'));
		
		$this->lang->load('tables','bahasa');
		$this->lang->load('general','bahasa');
		$this->lang->load('label','bahasa');
		
		$arrayCSS = array (
		'asset/css/themes/start/ui.all.css',
		'asset/javascript/jQuery/flexigrid/css/flexigrid.css',
		'asset/javascript/jQuery/autocomplete/jquery.autocomplete.css',
		'asset/javascript/jQuery/tooltip/jquery.tooltip.css',
		'asset/css/table/DataView.css',
		);
		
		$arrayJS = array (
		'asset/javascript/jQuery/jquery.cookie.js',
		'asset/javascript/jQuery/tooltip/jquery.bgiframe.js',
		'asset/javascript/jQuery/tooltip/jquery.dimensions.js',
		'asset/javascript/jQuery/tooltip/jquery.tooltip.js',
		'asset/javascript/jQuery/form/jquery.form.js',
		'asset/javascript/jQuery/form/jquery.maskedinput.js',
		'asset/javascript/jQuery/form/jquery.validate.js',
		'asset/javascript/jQuery/form/jquery.validate-addon.js',
		'asset/javascript/jQuery/form/jquery.autoNumeric.js',
		'asset/javascript/helper/autoNumeric.js',
		'asset/javascript/jQuery/flexigrid/js/flexigrid.js',
		'asset/javascript/jQuery/autocomplete/lib/jquery.bgiframe.min.js',
		'asset/javascript/jQuery/autocomplete/lib/jquery.ajaxQueue.js',
		'asset/javascript/jQuery/autocomplete/lin/thickbox-compressed.js',
		'asset/javascript/jQuery/autocomplete/jquery.autocomplete.js',
		);
		
		$data['extraHeadContent'] = '';
		
		foreach ($arrayCSS as $css):
			$data['extraHeadContent'] .= '<link type="text/css" rel="stylesheet" href="'.base_url().$css.'"/>';
		endforeach;
		foreach ($arrayJS as $js):
			$data['extraHeadContent'] .= '<script type="text/javascript" src="'.base_url().$js.'"/></script>';
		endforeach;
		
		self::$link_controller = 'mod_entry/entry_good_receive';
		self::$link_view = 'purchase/mod_entry/mod_product';
		$data['link_controller'] = self::$link_controller;
		$data['link_view'] = self::$link_view;
		
		// LANG
		$data['btn_adjust_po'] = 'Perubahan';
		$data['btn_retur'] = $this->lang->line('retur');
		$data['btn_retur_save'] = $this->lang->line('retur_save');
		$data['btn_back'] = $this->lang->line('back');
		$data['btn_save'] = $this->lang->line('save');
		$data['btn_cancel'] = $this->lang->line('cancel');
		$data['btn_process'] = $this->lang->line('process');
		$data['btn_clear'] = $this->lang->line('clear');
		$data['btn_create_gr'] = 'Buat BPB';
		
		$this->load->vars($data);
		
		self::$ppn_status = '';
		if ($this->session->userdata('module_type') == 'PPN')
			self::$ppn_status = 'ppn_';
	}
	
	// QUERY UNTUK NON FLEXIGRID ATO FLEXIGRID
	function flexigrid_sql($flexi=TRUE,$status) {
		$count='g.gr_id';
		$where=FALSE;
		
		$kur_status = 0;
		if ($status != 'kurs')
			$kur_status = 1;
			
		$sql = "select g.gr_no, g.gr_id, date_format(g.gr_date, '%d-%m-%Y') as gr_date, g.gr_suratJalan, p.po_no, s.sup_name, leg.legal_name {COUNT_STR}
            from prc_gr as g 
			inner join prc_po as p on p.po_id = g.po_id
			inner join prc_master_supplier as s on p.sup_id = s.sup_id 
			inner join prc_master_legality as leg on leg.legal_id = s.legal_id 
			where g.gr_status=0 and g.gr_printStatus='1' and g.gr_type='rec' and g.kur_status='$kur_status' 
			";
			
		if ($status == 'kurs')
			$sql .= " and p.cur_id = '2'";
			
		$sql .= " {SEARCH_STR}";
		return $this->flexi_model->generate_sql($sql,$count,$where,$flexi);
	}
	
	function flexigrid_ajax($status)
	{		
		$valid_fields = array('gr_id','gr_date','gr_no','po_no','sup_name','gr_suratJalan');
		
		$this->flexigrid->validate_post('gr_id','desc',$valid_fields);

		$records = $this->flexigrid_sql(TRUE,$status);
		
		$this->output->set_header($this->config->item('json_header'));
		
		if ($records['count'] > 0):		
			foreach ($records['result']->result() as $row)
			{
				$record_items[] = array(
				$row->gr_id, // TABLE ID
				$row->gr_date,
				$row->gr_no,
				$row->po_no,
				$row->sup_name.', '.$row->legal_name,
				$row->gr_suratJalan,
				"<a href='index.php/".self::$link_controller."/view_cbpb/".$row->gr_id."/".$status."'><img src='asset/img_source/magnifier.png' border='0' /></a>"
				);
			}
		else: 
			$record_items[] = array('0','null','null','empty','empty','null','null');
		endif;
		
		$this->output->set_output($this->flexigrid->json_build($records['count'],$record_items));
	}
	
	function flexigrid_builder($title,$width,$height,$rp,$status) {
		$colModel['gr_date'] = array('Tanggal BPB',80,TRUE,'center',1);
		$colModel['gr_no'] = array('No BPB',100,TRUE,'center',2);
		$colModel['po_no'] = array('No PO',100,TRUE,'center',1);
		$colModel['sup_name'] = array('Pemasok',200,TRUE,'left',1);
		$colModel['gr_suratJalan'] = array('Surat jalan',150,TRUE,'left',1);
		$colModel['opsi'] = array('Opsi',30,FALSE,'center',1);
		
		$ajax_model = site_url(self::$link_controller."/flexigrid_ajax/".$status);
		
		return build_grid_js('gr_list',$ajax_model,$colModel,'gr_id','desc',$this->flexi_engine->flexi_params($width,$height,$rp,$title));
		
	}
	
	function index($stats='gr_input'){
		switch ($stats):
		case 'gr_input':
			$data['page_title']= $this->lang->line('input_bpb_title');
			break;
		case 'gr_auth':
			$data['page_title']= $this->lang->line('oto_bpb_title');
			break;
		case 'gr_auth_list':
			$data['page_title']= $this->lang->line('doc_oto_bpb_title');
			break;
		case 'good_return':
			$data['page_title']= $this->lang->line('goodreturn_title');
			break;
		endswitch;
		
		$data['page_stats'] = $stats;
		if ($stats == 'good_return'):
			$data['sup_list'] = $this->tbl_po->get_bpb_sup_ret();
		else:
			$data['sup_list'] = $this->tbl_po->get_bpb_sup();
		endif;
		$data['content'] = self::$link_view.'/mod_gr/entry_gr_input_main';
		$this->load->view('index',$data);
	}
	
	function list_po_no() {
		$po_no = $this->input->post('po_no');
		$like_po['po_no']=$po_no;
		$where_po['po_status']='0';
		$qres = $this->tbl_po->get_po($where_po,$like_po);
		if ($qres->num_rows() > 0):
			$id = 1;
			foreach ($qres->result() as $rows):
				$arr[] = array($id => $rows->po_no);
				$id++;
			endforeach;
			echo json_encode($arr);
		endif;
	}
	
	function list_po($stats) {
		$sup_id = $this->input->post('sup_id');
		$po_no = $this->input->post('po_no');
		if ($po_no!=''):			
			$where_po['po_no'] = $po_no;
			$where_po['po_status'] = 0;
			$qres = $this->tbl_po->get_po($where_po);
			if ($qres->num_rows() > 0):
				$this->list_po_det($qres->row()->po_id,$stats);
			else:
				$this->index();
			endif;
		else:
			if ($stats == 'good_return'):
				$data['po_list'] = $this->tbl_po->get_bpb_po_sup($sup_id);
			else:
				$data['po_list'] = $this->tbl_po->get_bpb_po($sup_id);
			endif;
			if ($data['po_list']->num_rows() > 0):
				$data['sup_name'] = '( '.$data['po_list']->row()->legal_name.'. '.$data['po_list']->row()->sup_name.' )';
				$data['sup_id'] = $data['po_list']->row()->sup_id;
			else:
				$data['sup_name'] = 'Kosong';
			endif;
			$data['page_stats'] = $stats;
			$data['content'] = self::$link_view.'/mod_gr/entry_gr_input_list';
			$this->load->view('index',$data);
		endif;
		
	}
	
	function list_po_det($po_id,$stats) {
		//$po_no = $a.'/'.$b.'/'.$c;
		if ($po_id == 'ret'):
			$po_id = $this->input->post('po_id');
		endif;
		if ($get_po_det = $this->tbl_po->get_bpb_po_det($po_id,$stats)):
			$data['page_stats'] = $stats;
			$data['po_list'] = $get_po_det['bpb_po_id'];
			$data['po_det'] = $get_po_det['bpb_po_det'];
			$data['gr_list'] = $get_po_det['bpb_gr'];
			$data['ret_list'] = $get_po_det['ret_bpb'];
			$data['po_id'] = $po_id;
			
			switch ($stats):
			case 'gr_input':
				$data['content'] = self::$link_view.'/mod_gr/entry_gr_input_po_det';
				break;
			case 'gr_auth':
				$data['content'] = self::$link_view.'/mod_gr_auth/entry_gr_input_auth';
				break;
			case 'gr_auth_list':
				$data['content'] = self::$link_view.'/mod_gr/entry_gr_input_po_det';
				break;
			case 'good_return':
				$data['content'] = self::$link_view.'/mod_gr/entry_gr_input_po_det';
				break;
			endswitch;
			$this->load->view('index',$data);
		endif;
	}
	
	function form_gr($stats) {
		//$this->db->trans_complete();
		//$this->db->trans_start();
		$po_no = $this->input->post('po_no');
		if ($stats == 'good_return'):
					
		else:
			if ($get_gr = $this->tbl_po->get_bpb_gr_form($po_no)):
				$data['page_stats'] = $stats;
				$data['po_no'] = $po_no;
				$data['po_id'] = $get_gr['bpb_po']->row()->po_id;
				$data['po_list'] = $get_gr['bpb_po'];
				$data['po_det'] = $get_gr['bpb_po_det'];
				$data['jum_produk'] = $get_gr['bpb_po_det']->num_rows();	
				$data['content'] = self::$link_view.'/mod_gr/entry_gr_input_form_gr';
				$this->load->view('index',$data);
				//$this->load->view(self::$link_view.'/mod_gr/entry_gr_input_form_gr',$data);
			endif;
		endif;
	}
	
	function save_bpb_gr() {
		$jum_product	= $this->input->post("jum_product");
		$po_id			= $this->input->post("po_id");
		$sup_id			= $this->input->post("sup_id");
		
		//---data driver--
		$no_sj				= $this->input->post("no_sj");
		$no_kendaraan		= $this->input->post("no_kendaraan");
		$tgl_sj				= $this->input->post("tgl_sj");
		$jenis_kendaraan	= $this->input->post("jenis_kendaraan");
		$nama_supir			= $this->input->post("nama_supir");
		$milik_kendaraan	= $this->input->post("milik_kendaraan");
		$no_identitas		= $this->input->post("no_identitas");
		
		$cur_check			= $this->input->post("cur_check");
		
		//---
		$thn     = date("Y");
		$str_thn = date("y");
		$bln     = date("n");
		$str_bln = date("m");
		
		// CEK COUNTER
		$where_sys['thn'] = $thn;
		$where_sys['bln'] = $bln;
		$get_counter = $this->tbl_sys_counter->get_counter($where_sys);
		if ($get_counter->num_rows() > 0):
			$rec_no = $get_counter->row()->rec_no;
		else:
			$this->tbl_sys_counter->insert_counter($where_sys);
			$rec_no = 1;
		endif;
		
		$next_rec_no = $rec_no + 1;
		
		$rec_no =  str_pad($rec_no, 4, "0", STR_PAD_LEFT);
		// GR NUMBER
		$gr_doc_no = $this->lang->line('gr_doc_no');
		$str_rec_no =  $str_thn.'/'.$str_bln."/".$gr_doc_no.$rec_no;
		$str_gr_no=$str_rec_no;
		
		$update_sys['rec_no'] = $next_rec_no;
		if($this->tbl_sys_counter->update_counter($where_sys,$update_sys))
			//echo 'update counter|';
		
		//--insert into gr table--
		$insert_gr['gr_no'] = $str_rec_no;
		$insert_gr['po_id'] = $po_id;
		$insert_gr['gr_suratJalan'] = $no_sj;
		$insert_gr['gr_suratJalanTgl'] = date_format(date_create($tgl_sj),'Y-m-d');
		$insert_gr['gr_namaSupir'] = $nama_supir;
		$insert_gr['gr_noIdentitas'] = $no_identitas;
		$insert_gr['gr_noKendaraan'] = $no_kendaraan;
		$insert_gr['gr_jenisKendaraan'] = $jenis_kendaraan;
		$insert_gr['gr_kendaraanMilik'] = $milik_kendaraan;
		$insert_gr['gr_type'] = 'rec';
		
		if ($cur_check == 1):
			$insert_gr['kur_status'] = 1;
		endif;
		
		$this->tbl_gr->insert_gr($insert_gr);
		$gr_id = $this->db->Insert_ID();
		
		for ($i=1;$i<=$jum_product;$i++):
			$var_pro_id	= "pro_id_".$i;
			$var_receive   = "receive_".$i;
			$var_price	    = "price_".$i;
			$var_discount  = "discount_".$i;
			$var_cur		= "cur_id_".$i;
			$var_alasan		= "alasan_".$i;
					 
			$pro_id         = $this->input->post($var_pro_id);	
			$qty	        = $this->input->post($var_receive);		
			$pro_price		= $this->input->post($var_price);
			$currency		= $this->input->post($var_cur);
			$item_disc		= $this->input->post($var_discount);
			$alasan			= $this->input->post($var_alasan);
			
			$var_pr_satuan	    = "pr_um_id_".$i;
			$var_pro_satuan	    = "pro_um_id_".$i;
			
			$pro_um_id	        = $this->input->post($var_pro_satuan);
			$pr_um_id	        = $this->input->post($var_pr_satuan);
			
			if($qty == '')
				$qty = 0;
					
			if($qty != 0):
				// CEK PR FULLFILL
				$where_pr_det['po_id'] = $po_id; 
				$where_pr_det['pro_id']= $pro_id;
				$get_pr_det = $this->tbl_pr->get_pr_detail($where_pr_det);
				$qty_order    = $get_pr_det->row()->qty;
				$sudah_terima = $get_pr_det->row()->qty_terima;
		
				$sudah_terima = $sudah_terima + $qty;
		
				if(number_format($sudah_terima,5,'.','') - $qty_order >= 0)
					$is_fullfill = 1;
				else
					$is_fullfill = 0;
						 
				$where_pr_udet['po_id'] = $po_id;
				$where_pr_udet['pro_id'] = $pro_id;
				$data_pr_udet['qty_terima'] = $sudah_terima;
				$data_pr_udet['is_po_fullfill'] = $is_fullfill;
						 
				if ($this->tbl_pr->update_pr_detail($where_pr_udet,$data_pr_udet))
					//echo 'update pr detail|';
				
				//--insert gr detail--
				$data_gr_det['gr_id'] = $gr_id;
				$data_gr_det['pro_id'] = $pro_id;
				$data_gr_det['qty'] = $qty;
				$data_gr_det['price'] = $pro_price;
				$data_gr_det['discount'] = $item_disc;
				$data_gr_det['cur_id'] = $currency;
				$data_gr_det['keterangan'] = $alasan;
						 
				if ($this->tbl_gr->insert_gr_det($data_gr_det)):
					//echo 'insert gr detail|';
					//--insert gr detail History--
					if ($this->session):
						$data_gr_det_his['usr_id'] = $this->session->userdata('usr_id');
					endif;
					$data_gr_det_his['gr_id'] = $gr_id;
					$data_gr_det_his['pro_id'] = $pro_id;
					$data_gr_det_his['qty'] = $qty;
					$data_gr_det_his['price'] = $pro_price;
					$data_gr_det_his['discount'] = $item_disc;
					$data_gr_det_his['cur_id'] = $currency;
					if ($this->tbl_gr->insert_gr_det_his($data_gr_det_his)):
						//echo 'insert gr detail His|';
					endif;
				endif;
				
				//--step 1: check master produk, check value in is_stockJoin field
				$where_pro_id['pro_id'] = $pro_id;
				$get_product  = $this->tbl_produk->get_product($where_pro_id);
				if($get_product->num_rows() > 0):
					$join_stat = $get_product->row()->is_stockJoin;
				endif;
				
				//--step 2: check stock end
				//$this->update_inventory($join_stat,$pro_id,$sup_id,$qty,$str_gr_no,$pro_price,$currency,$str_rec_no);
				
			endif;
			
			//echo '<br>pro_id = '.$pro_id.'|sup_id = '.$sup_id.'|price = '.$pro_price.'|end stok = '.$end_stock_card.'|end balance = '.$end_balance.'|qty = '.$qty.'|order = '.$qty_order.'|id = '.$inv_id;
			
			
		endfor;
		
		echo $str_rec_no;
		
		// CEK PR_DET FULL Jika pr detail terpenuhi
		$where_pr_close['po_id'] = $po_id;
		$where_pr_close['is_po_fullfill'] = '0';
		$jum_row = $this->tbl_pr->get_pr_detail($where_pr_close)->num_rows();
		$this_day = date('Y-m-d');
		if($jum_row==0):
			//$term_credit = $this->tbl_term->get_po_term($po_id)->row()->term_credit;
			//$due_date  = mktime(0, 0, 0, date("m")  , date("d")+$term_credit, date("Y"));
			//$str_due_date = date("Y-m-d", $due_date);
			
			//$sql = "select term_credit from tbl_prc_po where po_id='$po_id'";
			//$rs = $db->Execute($sql);
			//$term_credit = $rs->fields['term_credit'];
			//$due_date  = mktime(0, 0, 0, date("m")  , date("d")+$term_credit, date("Y"));
			//$str_due_date = date("Y-m-d", $due_date);
			$where_upo['po_id'] = $po_id;
			$data_upo['po_status'] = '1';
			$data_upo['po_closeDate'] = $this_day;
			if($this->tbl_po->update_bpb_po($where_upo,$data_upo)):
				//echo 'update bpb sukses';
				
			endif;
		endif;
		//$this->db->trans_complete();
	}
	
	// CEK OTORISASI
	function cek_auth() {
		$where['pro_id'] = $this->input->post('pro_id');
		$where['pr_id'] = $this->input->post('pr_id');
		$where['auth_no'] = $this->input->post('auth_no');
		$get_pr = $this->tbl_pr->get_pr_detail($where);
		if ($get_pr->num_rows() > 0):
			echo $get_pr->row()->auth_qty;
		endif;
	}	

	// OTORISASI
	function create_auth(){	
		$crow_id = $this->input->post('crow_id');
			
		$rand = mktime();
		$md = md5($rand);
		$auth_no = substr($md,rand(0,strlen($md)-6),6);
		
		$where['pro_id'] = $this->input->post('pro_id');
		$where['pr_id'] = $this->input->post('pr_id');
		$update['auth_no'] = $auth_no;
		$update['auth_qty'] = $this->input->post('auth_qty');
		$update['auth_note'] = $this->input->post('auth_note');
		
		if ($this->tbl_pr->update_pr_detail($where,$update))
			echo $auth_no;
		//echo $where['pro_id'].' '.$where['pr_id'].' '.$update['auth_no'].' '.$update['auth_qty'].' '.$update['auth_note'];
	}
	
	// CARI BPB
	function index_cbpb($status = 'kurs') {
		$records = $this->flexigrid_sql(false,$status);
		if ($records->num_rows() > 0):		
			$data['js_grid'] = $this->flexigrid_builder('Daftar BPB',740,330,11,$status);
		else:
			$data['js_grid'] = $this->lang->line('list_empty');
		endif;
		
		if ($status == 'kurs'):
			$data['page_title'] = 'MENU CEK BPB KURS OLEH PURCHASING';
		else:
			$data['page_title'] = 'MENU CEK BPB FINAL OLEH PURCHASING';
		endif;
		
		// STATUS PAGE
		$data['status'] = $status;
		
		//$data['list_gr'] = $this->tbl_gr->get_gr_prc();
		$data['content'] = self::$link_view.'/mod_gr_prc/entry_gr_sprc_main';
		$this->load->view('index',$data);
	}
	
	function view_cbpb($gr_id,$status) {
		//$gr_no = $a.'/'.$b.'/'.$c;
		$get_gr = $this->tbl_gr->get_gr_prc_view($gr_id,$status);
		$data['list_gr'] = $get_gr['get_gr'];
		$data['list_gr_det'] = $get_gr['get_gr_det'];
		$data['list_gr_foot'] = $get_gr['get_gr_foot'];
		$data['gr_no'] = $get_gr['get_gr']->row()->gr_no;
		$data['jml_product'] = $get_gr['get_gr_det']->num_rows();
		$data['page_title'] = $this->lang->line('check_bpb_title');
		
		$data['content'] = self::$link_view.'/mod_gr_prc/entry_gr_sprc_'.self::$ppn_status.'view';
		
		$data['status'] = $status;
		
		$this->load->view('index',$data);
	}
	
	function create_faktur($status){
		$adj_stat = $this->input->post('adj_stat');
		$jum_product	= $this->input->post("jum_product");
		$gr_id		= $this->input->post("gr_id");
		$gr_no			= $this->input->post("gr_no");
		$sup_id			= $this->input->post("sup_id");
		$faktur = $this->input->post("faktur_sup");
		$kurs = $this->input->post('kurs');
		
		$process = false;
		
		if ($jum_product != 0):
			for ($i = 1; $i <= $jum_product; $i++):
				$pro_id = $this->input->post('pro_id_'.$i);
				$receive = $this->input->post('receive_'.$i);
				$price = $this->input->post('price_'.$i);
				$price_awal = $this->input->post('price_awal_'.$i);
				$cur_id = $this->input->post('cur_id_'.$i);
				$discount = $this->input->post('discount_'.$i);
				
				if ($status == 'kurs'):
				
					$where_gr_det['gr_id']	= $gr_id;
					$where_gr_det['pro_id'] = $pro_id;
					$data_gr_det['kurs'] = $kurs;
						
					if ($this->tbl_gr->update_gr_det($where_gr_det,$data_gr_det)):
						$where_gr['gr_id']	= $gr_id;
						$data_gr['kur_status'] = '1';
						if ($this->tbl_gr->update_gr($where_gr,$data_gr)):
							$process = true;
						endif;
					endif;
					
				else:
				
					$where_gr['gr_id']	= $gr_id;
					$data_gr['gr_fakturSup'] = $faktur;
				
					if ($adj_stat == 1)
						$data_gr['gr_status'] = '1';
					else
						$data_gr['gr_status'] = '2';
						
					if ($this->tbl_gr->update_gr($where_gr,$data_gr)):
						
						//$where_det['pro_id'] = $pro_id;
						/*
						$data_det['qty'] = $receive;
						$data_det['price'] = $price;
						$data_det['kurs'] = $kurs;
						*/
						//if ($this->tbl_gr->update_gr_det($where,$data_det)):
							$data_det['qty'] = $receive;
							$data_det['price'] = $price;
							$data_det['kurs'] = $kurs;
							$data_det['pro_id'] = $pro_id;
							$data_det['gr_id'] = $gr_id;
							$data_det['cur_id'] = $cur_id;
							$data_det['discount'] = $discount;
							if (($price!=$price_awal) && $adj_stat == 2):
								$data_det['document'] = 'ADJ';
							else:
								$data_det['document'] = 'GR';
							endif;
							if ($this->session):
								$data_det['usr_id'] = $this->session->userdata('usr_id');
							endif;
							if ($this->tbl_gr->insert_gr_det_his($data_det)):
								
							endif;
						$process = true;
						//endif;
					endif;
				
				endif;
				
			endfor;
			
		endif;
		
		if ($process)
			if ($status == 'kurs')
				echo $kurs;
			else
				echo $faktur;
		
	}
	
	function list_autocomplate() {
		$q = strtoupper($this->input->get('q'));
		$like_po['po_no'] = $q;
		$where_po['po_status'] = 0;
		$where_po['po_printStat'] = 1;
		$qres = $this->tbl_po->get_po($where_po,$like_po);
		
		$limit = strtoupper($this->input->get('limit'));
		
		if ($qres->num_rows() > 0):
			foreach ($qres->result() as $rows):
				if (strpos($rows->po_no, $q) !== false):
					echo "$rows->po_no\n";
				endif;
			endforeach;
		endif;
		
		//echo $q.'|'.'|'.$limit;
	}
	
	function list_autocomplate_ret() {
		$q = strtoupper($this->input->get('q'));
		$sup_id = strtoupper($this->input->get('sup_id'));
		$qres = $this->tbl_po->get_bpb_po_grt($q,$sup_id);
		if ($qres->num_rows() > 0):
			foreach ($qres->result() as $rows):
				if (strpos($rows->po_no, $q) !== false):
					echo "$rows->po_no|$rows->po_id\n";
				endif;
			endforeach;
		endif;
	}
	
	function buat_retur() {
		$usr_id = $this->session->userdata('usr_id');
		//---
		$thn     = date("Y");
		$str_thn = date("y");
		$bln     = date("n");
		$str_bln = date("m");
		
		$po_id = $this->input->post('po_id');
		$pro_id = $this->input->post('pro_id');
		$qty = $this->input->post('qty_retur');
		$price = $this->input->post('price_retur');
		$discount = $this->input->post('discount_retur');
		$cur_id = $this->input->post('cur_retur');
		$kurs = $this->input->post('kurs_retur');
		$alasan = $this->input->post('alasan_retur');
		$proses = array();
		
		/*
		for ($i = 0; $i < sizeOf($pro_id); $i++):
			if ($qty[$i] != ''):
				echo $i.'|'.$po_id[$i].'|'.$pro_id[$i].'|'.$qty[$i].'|'.$price[$i].'|'.$discount[$i].'|'.$cur_id[$i].'|'.$kurs[$i].'|'.$alasan[$i].'<br>';
			endif;
		endfor;
		*/
		
		// CEK COUNTER
		$where_sys['thn'] = $thn;
		$where_sys['bln'] = $bln;
		$get_counter = $this->tbl_sys_counter->get_counter($where_sys);
		if ($get_counter->num_rows() > 0):
			$ret_no = $get_counter->row()->ret_no;
		else:
			$this->tbl_sys_counter->insert_counter($where_sys);
			$ret_no = 1;
		endif;
		
		$next_ret_no = $ret_no + 1;
		
		$ret_no =  str_pad($ret_no, 4, "0", STR_PAD_LEFT);
		
		// RET NUMBER
		$ret_doc_no = $this->lang->line('ret_doc_no');
		$str_ret_no =  $str_thn.'/'.$str_bln."/".$ret_doc_no.$ret_no;
		$update_sys['ret_no'] = $next_ret_no;
		
		if($this->tbl_sys_counter->update_counter($where_sys,$update_sys))
		
		// GOOD RETUR SAVE	
		if ($pro_id != ''):
			//if (!in_array('',$qty)): 
				$data['ret_no'] = $str_ret_no;
				$data['po_id'] = $po_id;
				$data['ret_date'] = date('Y-m-d H:i:s');
				$data['ret_requestor'] = $usr_id;
				if ($this->tbl_good_return->insert_return($data)):
					$data_det['ret_id'] = $this->db->Insert_id();
					for ($i = 0; $i < sizeOf($pro_id); $i++):
						if ($qty[$i] != ''):
							$data_det['pro_id'] = $pro_id[$i];
							$data_det['qty'] = $qty[$i];
							$data_det['price'] = $price[$i];
							$data_det['discount'] = $discount[$i];
							$data_det['cur_id'] = $cur_id[$i];							
							$data_det['kurs'] = $kurs[$i];
							$data_det['keterangan'] = $alasan[$i];
							if ($this->tbl_good_return->insert_return_detail($data_det)):
								$proses[] = true;
							else:
								$proses[] = false;
							endif;
						endif;
					endfor;
				endif;
				
				if (!in_array(true,$proses)):
				
				else:
					echo $str_ret_no;
				endif;
			//else:
				//echo 'DATA HARUS DI ISI';
			//endif;
		endif;	
		
		
	}
}
?>