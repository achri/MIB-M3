<?php
class appr_rfq_service extends MY_Controller{
	public static $link_view, $link_controller;
	function appr_rfq_service(){
		parent::MY_Controller();
		$this->load->model(array('tbl_rfq_service','tbl_so','tbl_counter','tbl_purchase_type','tbl_satuan','tbl_currency','tbl_term','tbl_supplier','tbl_legal'));
		$this->load->helper('flexigrid');
		$this->load->library('flexigrid');
		$this->config->load('flexigrid');
		$this->obj =& get_instance();
		
		// LANG PO DOKUMEN NO
		$this->lang->load('general','bahasa');
		
		self::$link_controller = 'mod_approval/appr_rfq_service';
		self::$link_view = 'purchase/mod_approval/rfq_service_appr';
 		$data['link_controller'] = self::$link_controller;
		$data['link_view'] = self::$link_view;
		
		$this->load->vars($data);
		
	}
	
	function index() {
		$data['extraHeadContent'] = "<link type=\"text/css\" rel=\"stylesheet\" href=\"". base_url()."asset/css/themes/start/ui.all.css\" />\n";
		$data['extraHeadContent'] .= "<script type=\"text/javascript\" src=\"". base_url()."asset/javascript/jQuery/tables/jquery.jeditable.js\" />\n </script>";
		$data['extraHeadContent'] .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"".base_url()."asset/css/table/DataView.css\" />\n";
		$data['extraHeadContent'] .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"".base_url()."asset/css/form/form_view.css\" />\n";
		$data['extraHeadContent'] .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"".base_url()."asset/javascript/jQuery/flexigrid/css/flexigrid.css\" />\n";
		$data['extraHeadContent'] .= "<script type=\"text/javascript\" src=\"". base_url()."asset/javascript/jQuery/flexigrid/js/flexigrid.js\" />\n </script>";
		
		//$colModel['no'] = array($this->lang->line('rfq_flex_col_0'),20,TRUE,'center',0);
		$colModel['srfq_no'] = array($this->lang->line('rfq_flex_col_1'),90,TRUE,'center',2);
		$colModel['srfq_date'] = array($this->lang->line('rfq_flex_col_2'),80,TRUE,'center',2);
		$colModel['srfq_printDate'] = array($this->lang->line('rfq_flex_col_3'),80,TRUE,'center',2);
		$colModel['item_waiting'] = array($this->lang->line('rfq_flex_col_5'),60, TRUE,'center',0);
		$colModel['opsi'] = array($this->lang->line('pr_flex_col_11'),60, TRUE,'center',0);
		/*
		 * Aditional Parameters
		 */
		$gridParams = array(
		'width' => 590,
		'height' => 271,
		'blockOpacity' => 0.5,
		'title' => 'Daftar RFQ SERVIS',
		'showTableToggleBtn' => false,
		'singleSelect' => true
		);
		
		$this->flexigrid->validate_post('srfq_id','asc');
		
		$records = $this->tbl_rfq_service->srfq_list_appr();
		if ($records['record_count'] == 0){
			$data['kosong'] = "Belum ada data RFQ SERVIS untuk diproses";
		}else{
		
		//Build js
		//View helpers/flexigrid_helper.php for more information about the params on this function
		$grid_js = build_grid_js('flex1',site_url("/mod_approval/appr_rfq_serviceflexigrid"),$colModel,'id','asc',$gridParams);
		$data['message'] = $this->lang->line('contact_confirm_del');
		$data['js_grid'] = $grid_js;
		$data['kosong'] = "";
		}
		$data['content'] = self::$link_view.'/rfq';
		$this->load->view('index',$data);
	
	}
	
	function open_srfq_manaj($id){
		$data['get_rfq'] =  $this->tbl_rfq_service->srfq_manaj($id);
		$this->load->view(self::$link_view.'/rfq_app_cek',$data);
	}
	
	function srfq_add(){
		$usrid = $this->obj->session->userdata('usr_id');
		$session = $this->obj->session->userdata('session_id');
		$pro = $this->input->post('pro_id');
		$triger = 0;
		$now = date('Y-n-d');
		$time = explode("-",$now);
		
		//chek zero value......
		for($i=0;$i<sizeof($pro);$i++) {
			$status[$i] = $this->input->post('status_'.$pro[$i]);
			if ($status[$i] == '0' ){
				$no = $i + 1;
				echo "- status rfq ".$no." belum diisi <br/>";
				$triger = $triger + $no;
			}
		}

		if ($triger == 0){
			for($i=0;$i<sizeof($pro);$i++) {
				$status[$i] = $this->input->post('status_'.$pro[$i]);
				$sup[$i] = $this->input->post('id_sup_'.$pro[$i]);
				$pay[$i] = $this->input->post('pay_'.$pro[$i]);
				$cur[$i] = $this->input->post('cur_'.$pro[$i]);
				$pr[$i] = $this->input->post('pr_'.$pro[$i]);
				$procode[$i] = $this->input->post('procode_'.$pro[$i]);
				$produk[$i] = $this->input->post('pro_'.$pro[$i]);
				$err ='';
				
				if ($status[$i] == 5){
					$cek_po = $this->tbl_so->cek_so($session, $sup[$i], $cur[$i], $pay[$i])->num_rows();
					if ($cek_po == 0){
						$cekcounter = $this->tbl_counter->cek_counter($time[0], $time[1])->num_rows();
						if ($cekcounter == 0){
							$start = 2;
							$this->tbl_counter->insert_counter($time[0], $time[1], $start);
							
							$timecode = date('y-m-d');
							$timecode = explode ("-", $timecode);
							$counter = 1;
							$dtl_code = str_pad($counter, 4, "0", STR_PAD_LEFT);
							$head_code = $timecode[0].'/'.$timecode[1];
							// PO NUMBER
							$po_doc_no = $this->lang->line('so_doc_no');
							$code = $head_code.'/'.$po_doc_no.$dtl_code;
							$po_id = $this->tbl_po->insert_po($session, $code, $sup[$i], $cur[$i], $pay[$i]);
							$result[] = $this->tbl_rfq_service->srfq_appr_1($pro[$i], $pr[$i], $po_id, $status[$i], $produk[$i], $procode[$i]);
							$nopo[] = $code; //untuk menampilkan no PO
						}else{
							$timecode = date('y-m-d');
							$timecode = explode ("-", $timecode);
							$counter = $this->tbl_counter->cek_counter($time[0], $time[1])->row();
							$dtl_code = str_pad($counter->so_no, 4, "0", STR_PAD_LEFT);
							$head_code = $timecode[0].'/'.$timecode[1];
							// PO NUMBER
							$po_doc_no = $this->lang->line('so_doc_no');
							$code = $head_code.'/'.$po_doc_no.$dtl_code;		
							$nopo[] = $code; //untuk menampilkan no PO	
							$po_id = $this->tbl_so->insert_so($session, $code, $sup[$i], $cur[$i], $pay[$i]);
							$result[] = $this->tbl_rfq_service->srfq_appr_1($pro[$i], $pr[$i], $po_id, $status[$i], $produk[$i], $procode[$i]);
							//update sys_counter
							$field = 'so_no';
							$po = $counter->so_no +1;
							$this->tbl_counter->update_counter($po, $time[0], $time[1], $field);
						}
					}else{
						$get_po_id = $this->tbl_so->cek_so($session, $sup[$i], $cur[$i], $pay[$i])->row();
						$result[] = $this->tbl_rfq_service->srfq_appr_1($pro[$i], $pr[$i], $get_po_id->so_id, $status[$i], $produk[$i], $procode[$i]);
					}
				} else {
					//ditunda dan ditolak
					$result[] = $this->tbl_rfq_service->srfq_appr_2_3($pro[$i], $pr[$i], $status[$i], $produk[$i], $procode[$i]);
				}
			}
			if ($result){
				//remove sessionid
				$this->tbl_so->remove_session($session);
				for($i=0;$i<sizeof($result);$i++) {
					echo $this->lang->line('rfq_succes_alert').$result[$i]."<br/>";
				}
				echo "<br/> Nomor SO yang Dibuat :";
				for($i=0;$i<sizeof($nopo);$i++) {
					echo "<b> - ".$nopo[$i]."</b><br/>";
				}
				echo "<br/><div style='text-align : right;'><input type='button' value='ok' onclick='batal()'></div>";
			}
		}
	}
	
	/*function rfq_add(){
		$usrid = $this->obj->session->userdata('usr_id');
		$session = $this->obj->session->userdata('session_id');
		$pro = $this->input->post('pro_id');
		$triger = 0;
		$now = date('Y-n-d');
		$time = explode("-",$now);
		
		//chek zero value......
		for($i=0;$i<sizeof($pro);$i++) {
			$status[$i] = $this->input->post('status_'.$pro[$i]);
			if ($status[$i] == '0' ){
				$no = $i + 1;
				echo "- status rfq ".$no." belum diisi <br/>";
				$triger = $triger + $no;
			}
		}

		if ($triger == 0){
			for($i=0;$i<sizeof($pro);$i++) {
				$status[$i] = $this->input->post('status_'.$pro[$i]);
				$sup[$i] = $this->input->post('id_sup_'.$pro[$i]);
				$pay[$i] = $this->input->post('pay_'.$pro[$i]);
				$cur[$i] = $this->input->post('cur_'.$pro[$i]);
				$pr[$i] = $this->input->post('pr_'.$pro[$i]);
				$produk[$i] = $this->input->post('pro_'.$pro[$i]);
				$err ='';
				
				if ($status[$i] == 5){
					$cek_po = $this->tbl_po->cek_po($session, $sup[$i], $cur[$i], $pay[$i])->num_rows();
					if ($cek_po == 0){
						$cekcounter = $this->tbl_counter->cek_counter($time[0], $time[1])->num_rows();
						if ($cekcounter == 0){
							$start = 2;
							$this->tbl_counter->insert_counter($time[0], $time[1], $start);
							
							$timecode = date('y-m-d');
							$timecode = explode ("-", $timecode);
							$counter = 1;
							$dtl_code = str_pad($counter, 4, "0", STR_PAD_LEFT);
							$head_code = $timecode[0].'/'.$timecode[1];
							$code = $head_code.'/'.$dtl_code;
							$po_id = $this->tbl_po->insert_po($session, $code, $sup[$i], $cur[$i], $pay[$i]);
							$result[] = $this->tbl_rfq->rfq_appr_1($pro[$i], $pr[$i], $po_id, $status[$i], $produk[$i]);
							//echo $session.' - '.$code;	
						}else{
							$timecode = date('y-m-d');
							$timecode = explode ("-", $timecode);
							$counter = $this->tbl_counter->cek_counter($time[0], $time[1])->row();
							$dtl_code = str_pad($counter->po_no, 4, "0", STR_PAD_LEFT);
							$head_code = $timecode[0].'/'.$timecode[1];
							$code = $head_code.'/'.$dtl_code;		
							//echo $code;	
							$po_id = $this->tbl_po->insert_po($session, $code, $sup[$i], $cur[$i], $pay[$i]);
							$result[] = $this->tbl_rfq->rfq_appr_1($pro[$i], $pr[$i], $po_id, $status[$i], $produk[$i]);
							//update sys_counter
							$field = 'po_no';
							$po = $po_id +1;
							$this->tbl_counter->update_counter($po, $time[0], $time[1], $field);
						}
					}else{
						$get_po_id = $this->tbl_po->cek_po($session, $sup[$i], $cur[$i], $pay[$i])->row();
						$result[] = $this->tbl_rfq->rfq_appr_1($pro[$i], $pr[$i], $get_po_id->po_id, $status[$i], $produk[$i]);
					}
				} else {
					//ditunda dan ditolak
					$result[] = $this->tbl_rfq->rfq_appr_2_3($pro[$i], $pr[$i], $status[$i], $produk[$i]);
				}
			}
			if ($result){
				//remove sessionid
				$this->tbl_po->remove_session($session);
				for($i=0;$i<sizeof($result);$i++) {
					echo $this->lang->line('rfq_succes_alert').$result[$i]."<br/>";
				}
				echo "<br/><div style='text-align : right;'><input type='button' value='ok' onclick='batal()'></div>";
			}
		}
	}*/
}
?>