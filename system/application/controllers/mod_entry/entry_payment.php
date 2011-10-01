<?php
class Entry_payment extends MY_Controller {
	public static $link_view, $link_controller, $ppn_status;
	function Entry_payment() {
		parent::MY_Controller();
		$this->load->model(array('tbl_good_return','tbl_payment','tbl_contrabon','tbl_po','tbl_gr','tbl_sys_counter','tbl_supplier','tbl_currency','tbl_bkbk'));
		$this->load->helper(array('general'));
		
		$this->load->config('tables');
		$this->lang->load('general','bahasa');
		$this->lang->load('tables','bahasa');
		$this->lang->load('label','bahasa');
		
		$arrayCSS = array (
		'asset/css/themes/start/ui.all.css'
		);
		
		$arrayJS = array (
		'asset/javascript/jQuery/jquery.cookie.js',
		'asset/javascript/jQuery/form/jquery.form.js',
		//'asset/javascript/jQuery/form/jquery.maskedinput.js',
		'asset/javascript/jQuery/form/jquery.validate.js',
		'asset/javascript/jQuery/form/jquery.maio.mask.js',
		//'asset/javascript/plugins/jq_calendar.js',
		);
		
		$data['extraHeadContent'] = '';
		
		foreach ($arrayCSS as $css):
			$data['extraHeadContent'] .= '<link media="screen" type="text/css" rel="stylesheet" href="'.base_url().$css.'"/>';
		endforeach;
		foreach ($arrayJS as $js):
			$data['extraHeadContent'] .= '<script type="text/javascript" src="'.base_url().$js.'"/></script>';
		endforeach;

		self::$link_controller = 'mod_entry/entry_payment';
		self::$link_view = 'purchase/mod_entry/mod_product/mod_payment';
		$data['link_controller'] = self::$link_controller;
		$data['link_view'] = self::$link_view;
				
		$this->load->vars($data);
		
		self::$ppn_status = '';
		if ($this->session->userdata('module_type') == 'PPN')
			self::$ppn_status = 'ppn_';
	}
	
	function index() {		
		$data['list_sup_pay'] = $this->tbl_payment->get_sup_payment();
		$data['page_title'] = $this->lang->line('payment_title');
		$data['content'] = self::$link_view.'/entry_payment_main';
		$this->load->view('index',$data);
	}
	
	function list_gr($sup_id='') {
		if ($sup_id == ''):
			$sup_id = $this->input->post('sup_id');
		endif;
		$data['list_gr'] = $this->tbl_gr->get_gr_pay($sup_id);
		//$data['list_retur'] = $this->tbl_good_return->get_retur_pay($sup_id);
		$sql = "select * from prc_master_supplier as sup inner join prc_master_legality as leg on leg.legal_id = sup.legal_id where sup.sup_id=$sup_id";
		$get_sup = $this->db->query($sql)->row();
		$sup_name = $get_sup->legal_name.'. '.$get_sup->sup_name;
		$data['cur_symbol'] = $data['list_gr']->row()->cur_symbol;
		$data['page_title'] = $this->lang->line('payment_title').' : PEMASOK ( <strong>'.$sup_name.'</strong> )';
		$data['sup_id'] = $sup_id;
		$data['sup_name'] = $sup_name;
		$data['content'] = self::$link_view.'/entry_payment_'.self::$ppn_status.'listgr';
		$this->load->view('index',$data);
	}
	
	function list_payment() {
		$sup_id = $this->input->post('sup_id');
		$con_id = $this->input->post('con_id');
		$payment_method = $this->input->post('payment_method');

		$sql = "select * from prc_master_supplier as sup inner join prc_master_legality as leg on leg.legal_id = sup.legal_id where sup.sup_id=$sup_id";
		$get_sup = $this->db->query($sql)->row();
		$sup_name = $get_sup->legal_name.'. '.$get_sup->sup_name;
		
		$data['page_title'] = $this->lang->line('payment_title').' : PEMASOK ( <strong>'.$sup_name.'</strong> )';
		$data['content'] = self::$link_view.'/entry_payment_'.self::$ppn_status.'listpay';
		
		for ($i=0;$i<sizeOf($con_id);$i++):
			$list_bon = $this->tbl_contrabon->get_bon_payment($con_id[$i]);
			if ($list_bon->num_rows() > 0):
				$row_bon = $list_bon->row();
				$data_bon[$i]['con_id'] = $row_bon->con_id;
				$data_bon[$i]['con_no'] = $row_bon->con_no;
				$data_bon[$i]['con_remain'] = $row_bon->con_remain;
				$data_bon[$i]['con_ppn_remain'] = $row_bon->con_ppn_remain;
				$data_bon[$i]['con_id'] = $row_bon->con_id;
				$data_bon[$i]['cur_id'] = $row_bon->cur_id;
				$data_bon[$i]['cur_symbol'] = $row_bon->cur_symbol;
				$data_bon[$i]['cur_digit'] = $row_bon->cur_digit;
			endif;
		endfor;
		
		/*
		for ($i=0;$i<sizeOf($data_bon);$i++):
			echo $data_bon[$i]['con_id'];
		endfor;
		*/
		$data['sup_id'] = $sup_id;
		$data['payment_method'] = strtoupper($payment_method);
		$data['list_bon'] = $data_bon;
		$data['list_cur'] = $this->tbl_currency->get_currency();
		$this->load->view('index',$data);
		
	}
	
	function buat_pembayaran_ppn() {
		/*
		foreach ($_POST as $key=>$val):
			echo $key.'='.$val.'<br>';
		endforeach;
		*/
		$jum_row		= $this->input->post("jum_row");
		$payment_methode	= $this->input->post("payment_methode");
		$sup_id = $this->input->post('sup_id');

		$bkbk_no			= $this->input->post("bkbk_no");
		$bkbk_date			= $this->input->post("bkbk_date");
		
		//--transfer--
		$transfer_biaya		= $this->input->post("transfer_biaya"); // num
		$transfer_nomor		= $this->input->post("transfer_nomor");
		$transfer_rekening  = $this->input->post("transfer_rekening");
		$transfer_supplier	= $this->input->post("transfer_supplier");
		
		//--cek/giro--
		$cek_tempo			= $this->input->post("cek_tempo"); // num
		$cek_no				= $this->input->post("cek_no");
		$cek_rekening		= $this->input->post("cek_rekening");
		
		$memo				= $this->input->post("memo");
		
		/*
		for ($i=1;$i<=$jum_row;$i++):
			$con_id	= $this->input->post("con_id_".$i);
			$con_dibayar = $this->input->post("bayar_".$i);
			$con_remain = $this->input->post("con_remain_".$i);
			echo $con_id.'|'.$con_dibayar.'|'.$con_remain.'<br>';
		endfor;
		
		*/
		
		for ($i=1;$i<=$jum_row;$i++):
			$error[$i] = false;
			$error_ppn[$i] = false;
			$error_nol[$i] = false;
			$con_dibayar = $this->input->post("bayar_".$i);
			$con_ppn_dibayar = $this->input->post("bayar_ppn_".$i);
			$con_sisa = $this->input->post("sisa_".$i);
			$con_ppn_sisa = $this->input->post("sisa_ppn_".$i);
			if ($con_dibayar > $con_sisa):
				$error[$i] = true;
			elseif ($con_ppn_dibayar > $con_ppn_sisa):
				$error_ppn[$i] = true;
			elseif ($con_dibayar == 0 && $con_ppn_dibayar == 0):
				$error_nol[$i] = true;
			endif;
		endfor;
		
		if (in_array(true,$error)):
			echo 'Nilai Kontrabon di bayar melebihi !!!';
		elseif (in_array(true,$error_ppn)):
			echo 'Nilai PPN di bayar melebihi !!!';
		elseif (in_array(true,$error_nol)):
			echo 'Nilai Kontrabon dan PPN harus di isi !!!';
		else:
		
			// INSERT BKBK
			$data_bkbk['bkbk_no'] = $bkbk_no;
			$data_bkbk['sup_id'] = $sup_id;
			$data_bkbk['bkbk_date'] = date_format(date_create($bkbk_date),'Y-m-d');
			$data_bkbk['bkbk_methode'] = $payment_methode;
			$data_bkbk['memo'] = $memo;
			if ($this->tbl_bkbk->insert_bkbk($data_bkbk)):
				$bkbk_id = $this->db->insert_ID();
				//echo 'BKBK insert ';
				//echo $bkbk_id.' '.$payment_methode.' '.$jum_row;
				// TRANSFER
				if ($payment_methode=='TRANSFER'):
					$where_bkbk['bkbk_id'] = $bkbk_id;
					$data_tbk['transfer_biaya']=$transfer_biaya;
					$data_tbk['transfer_nomor']=$transfer_nomor;
					$data_tbk['transfer_rekening']=$transfer_rekening;
					$data_tbk['transfer_supplier']=$transfer_supplier;
					$data_tbk['post_stat'] = 0;
					if ($this->tbl_bkbk->update_bkbk($where_bkbk,$data_tbk)):
						//echo 'BKBK update Trans ';
					endif;
				// CEK/GIRO
				elseif($payment_methode=='CEK/GIRO'):
					$where_bkbk['bkbk_id'] = $bkbk_id;
					$data_cbk['cek_tempo'] = date_format(date_create($cek_tempo),'Y-m-d');
					$data_cbk['cek_no']= $cek_no;
					$data_cbk['cek_rekening']=$cek_rekening;
					$data_tbk['post_stat'] = 0;					
					if ($this->tbl_bkbk->update_bkbk($where_bkbk,$data_cbk)):
						//echo 'BKBK update Cek 
					endif;
				endif;

				// INSERT BKBK DETAIL
				for ($i=1;$i<=$jum_row;$i++):
					$con_id	= $this->input->post("con_id_".$i);
					$con_dibayar = $this->input->post("bayar_".$i);
					$con_remain = $this->input->post("con_remain_".$i);
					$con_ppn_dibayar = $this->input->post("bayar_ppn_".$i);
					$con_ppn_remain = $this->input->post("con_ppn_remain_".$i);
					// INSERT BKBK DETAIL
					$data_bkd['bkbk_id'] = $bkbk_id;
					$data_bkd['con_id']	= $con_id;
					$data_bkd['con_dibayar'] = $con_dibayar;
					$data_bkd['ppn_dibayar'] = $con_ppn_dibayar;
					$data_bkd['cur_id']	= $this->input->post("cur_id_".$i);
					$data_bkd['kurs']	= $this->input->post("kurs_".$i);
					//echo '<br>'.$con_id.' '.$con_dibayar.' '.$data_bkd['cur_id'].' '.$data_bkd['kurs'];
					if ($this->tbl_bkbk->insert_bkbk_det($data_bkd)):
						//echo 'BKBK DET ';
					endif;
					
					// UPDATE CONTRABON
					$where_bon['con_id'] = $con_id;
					$get_con_calc = $this->tbl_contrabon->get_bon($where_bon);
					$update_bon['con_payVal'] = $get_con_calc->row()->con_payVal + $con_dibayar;
					$update_bon['con_ppn_payVal'] = $get_con_calc->row()->con_ppn_payVal + $con_ppn_dibayar;
					if ($this->tbl_contrabon->update_bon($where_bon,$update_bon)):
						//echo 'BON ';
					endif;
		
					// CLOSE CONTRABON
					$con_stat = false;
					$ppn_stat = false;
					$data_con = $this->tbl_contrabon->get_bon($where_bon);
					if ($data_con->num_rows() > 0):
						$list_con = $data_con->row();
						//if(($con_dibayar+$con_remain) >= $list_con->con_value ):
						if($list_con->con_payVal >= $list_con->con_value ):
							$con_stat = true;
						endif;
						if($list_con->con_ppn_payVal >= $list_con->con_ppn_value ):
							$ppn_stat = true;
						endif;
					endif;
					
					// UPDATE GR
					$this->db->query("update prc_gr set post_stat = 0 where con_id = $con_id");
					
					if ($con_stat && $ppn_stat):
						$update_con['con_status'] = '1';
						if ($this->tbl_contrabon->update_bon($where_bon,$update_con)):
							//echo 'CONTRABON CLOSE';
						endif;
					endif;
					//echo $con_dibayar .'|'. $con_id.'|'.$con_remain;
				endfor;
				//echo $bkbk_no;
			endif;
		
		endif;
		
	}
	
	function buat_pembayaran() {
		/*
		foreach ($_POST as $key=>$val):
			echo $key.'='.$val.'<br>';
		endforeach;
		*/
		$jum_row		= $this->input->post("jum_row");
		$payment_methode	= $this->input->post("payment_methode");
		$sup_id = $this->input->post('sup_id');

		$bkbk_no			= $this->input->post("bkbk_no");
		$bkbk_date			= $this->input->post("bkbk_date");
		
		//--transfer--
		$transfer_biaya		= $this->input->post("transfer_biaya"); // num
		$transfer_nomor		= $this->input->post("transfer_nomor");
		$transfer_rekening  = $this->input->post("transfer_rekening");
		$transfer_supplier	= $this->input->post("transfer_supplier");
		
		//--cek/giro--
		$cek_tempo			= $this->input->post("cek_tempo"); // num
		$cek_no				= $this->input->post("cek_no");
		$cek_rekening		= $this->input->post("cek_rekening");
		
		$memo				= $this->input->post("memo");
		
		/*
		for ($i=1;$i<=$jum_row;$i++):
			$con_id	= $this->input->post("con_id_".$i);
			$con_dibayar = $this->input->post("bayar_".$i);
			$con_remain = $this->input->post("con_remain_".$i);
			echo $con_id.'|'.$con_dibayar.'|'.$con_remain.'<br>';
		endfor;
		
		*/
		
		for ($i=1;$i<=$jum_row;$i++):
			$error[$i] = false;
			$error_nol[$i] = false;
			$con_dibayar = $this->input->post("bayar_".$i);
			$con_sisa = $this->input->post("sisa_".$i);
			if ($con_dibayar > $con_sisa):
				$error[$i] = true;
			elseif ($con_dibayar == 0 ):
				$error_nol[$i] = true;
			endif;
		endfor;
		
		if (in_array(true,$error)):
			echo 'Nilai Kontrabon di bayar melebihi !!!';
		elseif (in_array(true,$error_nol)):
			echo 'Nilai Kontrabon harus di isi !!!';
		else:
		
			// INSERT BKBK
			$data_bkbk['bkbk_no'] = $bkbk_no;
			$data_bkbk['sup_id'] = $sup_id;
			$data_bkbk['bkbk_date'] = date_format(date_create($bkbk_date),'Y-m-d');
			$data_bkbk['bkbk_methode'] = $payment_methode;
			$data_bkbk['memo'] = $memo;
			if ($this->tbl_bkbk->insert_bkbk($data_bkbk)):
				$bkbk_id = $this->db->insert_ID();
				//echo 'BKBK insert ';
				//echo $bkbk_id.' '.$payment_methode.' '.$jum_row;
				// TRANSFER
				if ($payment_methode=='TRANSFER'):
					$where_bkbk['bkbk_id'] = $bkbk_id;
					$data_tbk['transfer_biaya']=$transfer_biaya;
					$data_tbk['transfer_nomor']=$transfer_nomor;
					$data_tbk['transfer_rekening']=$transfer_rekening;
					$data_tbk['transfer_supplier']=$transfer_supplier;
					$data_tbk['post_stat'] = 0;
					if ($this->tbl_bkbk->update_bkbk($where_bkbk,$data_tbk)):
						//echo 'BKBK update Trans ';
					endif;
				// CEK/GIRO
				elseif($payment_methode=='CEK/GIRO'):
					$where_bkbk['bkbk_id'] = $bkbk_id;
					$data_cbk['cek_tempo'] = date_format(date_create($cek_tempo),'Y-m-d');
					$data_cbk['cek_no']= $cek_no;
					$data_cbk['cek_rekening']=$cek_rekening;
					$data_tbk['post_stat'] = 0;					
					if ($this->tbl_bkbk->update_bkbk($where_bkbk,$data_cbk)):
						//echo 'BKBK update Cek 
					endif;
				endif;

				// INSERT BKBK DETAIL
				for ($i=1;$i<=$jum_row;$i++):
					$con_id	= $this->input->post("con_id_".$i);
					$con_dibayar = $this->input->post("bayar_".$i);
					$con_remain = $this->input->post("con_remain_".$i);
					// INSERT BKBK DETAIL
					$data_bkd['bkbk_id'] = $bkbk_id;
					$data_bkd['con_id']	= $con_id;
					$data_bkd['con_dibayar'] = $con_dibayar;
					$data_bkd['cur_id']	= $this->input->post("cur_id_".$i);
					$data_bkd['kurs']	= $this->input->post("kurs_".$i);
					//echo '<br>'.$con_id.' '.$con_dibayar.' '.$data_bkd['cur_id'].' '.$data_bkd['kurs'];
					if ($this->tbl_bkbk->insert_bkbk_det($data_bkd)):
						//echo 'BKBK DET ';
					endif;
					
					// UPDATE CONTRABON
					$where_bon['con_id'] = $con_id;
					$get_con_calc = $this->tbl_contrabon->get_bon($where_bon);
					$update_bon['con_payVal'] = $get_con_calc->row()->con_payVal + $con_dibayar;
					if ($this->tbl_contrabon->update_bon($where_bon,$update_bon)):
						//echo 'BON ';
					endif;
		
					// CLOSE CONTRABON
					$con_stat = false;
					$ppn_stat = false;
					$data_con = $this->tbl_contrabon->get_bon($where_bon);
					if ($data_con->num_rows() > 0):
						$list_con = $data_con->row();
						//if(($con_dibayar+$con_remain) >= $list_con->con_value ):
						if($list_con->con_payVal >= $list_con->con_value ):
							$con_stat = true;
						endif;
					endif;
					
					// UPDATE GR
					$this->db->query("update prc_gr set post_stat = 0 where con_id = $con_id");
					
					if ($con_stat && $ppn_stat):
						$update_con['con_status'] = '1';
						if ($this->tbl_contrabon->update_bon($where_bon,$update_con)):
							//echo 'CONTRABON CLOSE';
						endif;
					endif;
					//echo $con_dibayar .'|'. $con_id.'|'.$con_remain;
				endfor;
				//echo $bkbk_no;
			endif;
		
		endif;
		
	}
	
	function list_bpb($con_id) {
		$sql = "select gr.gr_id, gr.gr_no, gr.gr_type, gr.gr_fakturSup, 
			 date_format(gr.gr_date, '%d-%m-%Y') as gr_date, p.po_id, p.po_no, trm.term_name,
			 sum(gd.qty*gd.price*(100 - gd.discount)/100) as gr_value, 
			 (sum((gd.qty*gd.price*(100 - gd.discount)/100) * 10 / 100)* gd.kurs) as gr_ppn_value, 
			 cur.cur_symbol, gd.cur_id, cur.cur_digit, gd.kurs 
			 from prc_gr as gr 
			 inner join prc_po as p on gr.po_id = p.po_id
			 inner join prc_gr_detail as gd on gr.gr_id = gd.gr_id
			 inner join prc_master_currency as cur on cur.cur_id = gd.cur_id
			 inner join prc_master_credit_term as trm on trm.term_id = p.term_id
			 where (gr.gr_status = 1 or gr.gr_status = 3) and gr.con_id='$con_id'
			 group by gd.gr_id
			 order by gr.gr_date";
		$data['list_bpb'] = $this->db->query($sql);
		
		$sql1 = "select  
			sum( gd.price * gd.qty * ( ( 100 - gd.discount ) /100 )) AS gr_total,
			sum( gd.price * gd.qty * ( ( 100 - gd.discount ) /100 ) * 110 / 100) AS gr_ppn_total,   
			 cur.cur_symbol, gd.cur_id, cur.cur_digit, gd.kurs 
			 from prc_gr as gr 
			 inner join prc_gr_detail as gd on gr.gr_id = gd.gr_id
			 inner join prc_master_currency as cur on cur.cur_id = gd.cur_id
			 where (gr.gr_status = 1 or gr.gr_status = 3) and gr.con_id='$con_id'
			 ";
		
		$data['row_bpb_tot'] = $this->db->query($sql1);
		
		$this->load->view(self::$link_view.'/entry_payment_listbpb',$data);
	}

}
?>
