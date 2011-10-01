<?php
class appr_rfq extends MY_Controller{
	public static $link_controller,$link_view,$ppn_status,$print_status;
	function appr_rfq(){
		parent::MY_Controller();
		$this->load->model(array('tbl_rfq','tbl_po','tbl_counter','tbl_purchase_type','tbl_satuan','tbl_currency','tbl_term','tbl_supplier','tbl_legal'));
		$this->load->helper('flexigrid');
		$this->load->library('flexigrid');
		$this->config->load('flexigrid');
		
		// LANG PO DOKUMEN NO
		$this->lang->load('general','bahasa');
		
		// LINK CONTROLLER DAN VIEW
		self::$link_controller = 'mod_approval/appr_rfq';
		self::$link_view = 'purchase/mod_approval/rfq_appr';
		$data['link_controller'] = self::$link_controller;
		$data['link_view'] = self::$link_view;
		
		$this->load->vars($data);	
	}
	
	function flexigrid_ajax()
	{
		//List of all fields that can be sortable. This is Optional.
		//This prevents that a user sorts by a column that we dont want him to access, or that doesnt exist, preventing errors.
		$valid_fields = array('rfq_id',	'rfq_no', 'rfq_date', 'rfq_printDate', 'item_waiting');
		
		$this->flexigrid->validate_post('rfq_id','asc',$valid_fields);

		$records = $this->tbl_rfq->rfq_list_appr();
		
		$this->output->set_header($this->config->item('json_header'));
		
		/*
		 * Json build WITH json_encode. If you do not have this function please read
		 * http://flexigrid.eyeviewdesign.com/index.php/flexigrid/example#s3 to know how to use the alternative
		 */
		if ($records['record_count'] > 0){
		$i = 0;
			foreach ($records['records']->result() as $row)
			{
				$i = $i + 1;			
				$record_items[] = array($row->rfq_id,
				//$i,
				$row->rfq_no,
				$row->rfq_date,
				$row->rfq_printDate,
				$row->item_number,
				$row->item_waiting,
				$row->item_tunggu,
				$row->item_tolak,
				$row->item_ok,
				$row->rfq_lastModified,
				'<a href=\'javascript:void(0)\' onclick=\'open_rfq_manaj('.$row->rfq_id.')\'><img border=\'0\' src=\'./asset/img_source/icon_info.gif\'></a>'
				);
			}
		}else{
			$record_items[] = array('empty');
		}
		//Print please
		$this->output->set_output($this->flexigrid->json_build($records['record_count'],$record_items));
	}
	
	function index() {
		$data['extraHeadContent'] = "<link type=\"text/css\" rel=\"stylesheet\" href=\"". base_url()."asset/css/themes/start/ui.all.css\" />\n";
		$data['extraHeadContent'] .= "<script type=\"text/javascript\" src=\"". base_url()."asset/javascript/jQuery/tables/jquery.jeditable.js\" />\n </script>";
		$data['extraHeadContent'] .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"".base_url()."asset/css/table/DataView.css\" />\n";
		$data['extraHeadContent'] .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"".base_url()."asset/css/form/form_view.css\" />\n";
		$data['extraHeadContent'] .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"".base_url()."asset/javascript/jQuery/flexigrid/css/flexigrid.css\" />\n";
		$data['extraHeadContent'] .= "<script type=\"text/javascript\" src=\"". base_url()."asset/javascript/jQuery/flexigrid/js/flexigrid.js\" />\n </script>";
		
		//$colModel['no'] = array($this->lang->line('rfq_flex_col_0'),20,TRUE,'center',0);
		$colModel['rfq_no'] = array($this->lang->line('rfq_flex_col_1'),90,TRUE,'center',2);
		$colModel['rfq_date'] = array($this->lang->line('rfq_flex_col_2'),80,TRUE,'center',2);
		$colModel['rfq_printDate'] = array($this->lang->line('rfq_flex_col_3'),80,TRUE,'center',2);
		$colModel['item_number'] = array('Jumlah Item',60, TRUE,'center',0);
		$colModel['item_waiting'] = array($this->lang->line('rfq_flex_col_5'),60, TRUE,'center',0);
		$colModel['item_tunggu'] = array('Tunggu',60, TRUE,'center',0);
		$colModel['item_tolak'] = array('Ditolak',60, TRUE,'center',0);
		$colModel['item_ok'] = array('Disetujui',60, TRUE,'center',0);
		$colModel['update'] = array('Update terakhir',80, TRUE,'center',0);
		$colModel['opsi'] = array('Opsi',30, TRUE,'center',0);
		/*
		 * Aditional Parameters
		 */
		$gridParams = array(
		'width' => 'auto',
		'height' => 271,
		'blockOpacity' => 0.5,
		'title' => $this->lang->line('rfq_flex_ttl'),
		'showTableToggleBtn' => false,
		'singleSelect' => true
		);
		
		$this->flexigrid->validate_post('rfq_id','asc');
		
		$records = $this->tbl_rfq->rfq_list_appr();
		if ($records['record_count'] == 0){
			$data['kosong'] = "Belum ada data RFQ untuk diproses";
		}else{
		
		//Build js
		//View helpers/flexigrid_helper.php for more information about the params on this function
		$grid_js = build_grid_js('flex1',site_url(self::$link_controller."/flexigrid_ajax"),$colModel,'rfq_id','desc',$gridParams);
		$data['message'] = $this->lang->line('contact_confirm_del');
		$data['js_grid'] = $grid_js;
		$data['kosong'] = "";
		}
		$data['content'] = self::$link_view.'/rfq';
		$this->load->view('index',$data);
	
	}
	
	function open_rfq_manaj($id){
		$data['get_rfq'] =  $this->tbl_rfq->rfq_manaj($id);
		$this->load->view(self::$link_view.'/rfq_app_cek',$data);
	}
	
	function rfq_add(){
		$usrid = $this->session->userdata('usr_id');
		$session = $this->session->userdata('session_id');
		$pro = $this->input->post('pro_id');
		$triger = 0;
		$now = date('Y-m-d');
		$time = explode("-",$now);
		$nopo = array();
		
		//chek zero value......
		for($i=0;$i<sizeof($pro);$i++) {
			$status[$i] = $this->input->post('status_'.$i);
			if ($status[$i] == '0' ){
				$no = $i + 1;
				echo "- status rfq ".$no." belum diisi <br/>";
				$triger = $triger + $no;
			}
		}

		if ($triger == 0){
			for($i=0;$i<sizeof($pro);$i++) {
				$status[$i] = $this->input->post('status_'.$i);
				$sup[$i] = $this->input->post('id_sup_'.$i);
				$pay[$i] = $this->input->post('pay_'.$i);
				$cur[$i] = $this->input->post('cur_'.$i);
				$pr[$i] = $this->input->post('pr_'.$i);
				$procode[$i] = $this->input->post('procode_'.$i);
				$produk[$i] = $this->input->post('pro_'.$i);
				$err ='';
	
				//echo $result[] = $i.'|'.$status[$i].'|'.$sup[$i].'|'.$pay[$i].'|'.$cur[$i].'|'.$pr[$i].'|'.$procode[$i].'|'.$produk[$i].'<br>';
				
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
							// PO NUMBER
							$po_doc_no = $this->lang->line('po_doc_no');
							$code = $head_code.'/'.$po_doc_no.$dtl_code;
							$po_id = $this->tbl_po->insert_po($session, $code, $sup[$i], $cur[$i], $pay[$i]);
							$result[] = $this->tbl_rfq->rfq_appr_1($pro[$i], $pr[$i], $po_id, $status[$i], $produk[$i], $procode[$i]);
							$nopo[] = $code; //untuk menampilkan no PO
						}else{
							$timecode = date('y-m-d');
							$timecode = explode ("-", $timecode);
							$counter = $this->tbl_counter->cek_counter($time[0], $time[1])->row();
							$dtl_code = str_pad($counter->po_no, 4, "0", STR_PAD_LEFT);
							$head_code = $timecode[0].'/'.$timecode[1];
							// PO NUMBER
							$po_doc_no = $this->lang->line('po_doc_no');
							$code = $head_code.'/'.$po_doc_no.$dtl_code;		
							$nopo[] = $code; //untuk menampilkan no PO	
							$po_id = $this->tbl_po->insert_po($session, $code, $sup[$i], $cur[$i], $pay[$i]);
							$result[] = $this->tbl_rfq->rfq_appr_1($pro[$i], $pr[$i], $po_id, $status[$i], $produk[$i], $procode[$i]);
							//update sys_counter
							$field = 'po_no';
							$po = $counter->po_no +1;
							$this->tbl_counter->update_counter($po, $time[0], $time[1], $field);
						}
					}else{
						$get_po_id = $this->tbl_po->cek_po($session, $sup[$i], $cur[$i], $pay[$i])->row();
						$result[] = $this->tbl_rfq->rfq_appr_1($pro[$i], $pr[$i], $get_po_id->po_id, $status[$i], $produk[$i], $procode[$i]);
					}
				} else if ($status[$i] == 3) {
					//ditunda dan ditolak
					//$nopo[] = '';
					$result[] = $this->tbl_rfq->rfq_appr_3($pro[$i], $pr[$i], $status[$i], $produk[$i], $procode[$i]);
				}
				else {
					//ditunda dan ditolak
					//$nopo[] = '';
					$result[] = $this->tbl_rfq->rfq_appr_2_3($pro[$i], $pr[$i], $status[$i], $produk[$i], $procode[$i]);
				}
				
				
			}
			
			if ($result){
				//remove sessionid
				$this->tbl_po->remove_session($session);
				/*
				for($i=0;$i<sizeof($result);$i++) {
					echo $this->lang->line('rfq_succes_alert').$result[$i]."<br/>";
				}
				*/
				$arr_status = array('','','Ditunda','Ditolak','','Disetujui');
				echo '<table border=0 cellspacing=0 cellpadding=5>';
				for($i=0;$i<sizeof($result);$i++) {
					echo "<tr><td>- RFQ untuk produk <b><font color='red'>".$produk[$i]."</font></b>&nbsp;</td><td>&nbsp;&nbsp;".$arr_status[$status[$i]]."</td></tr>";
				}
				echo '</table>';
				
				if ((sizeOf($nopo) > 0)):
					echo "<br/> Nomor PO yang dibuat :<br/>";
					for($i=0;$i<sizeof($nopo);$i++) {
						echo "<b> - ".$nopo[$i]."</b><br/>";
					}
				endif;

				//echo "<br/><div style='text-align : right;'><input type='button' value='ok' onclick='batal()'></div>";
			}
			
		}
	}
	
	/*function rfq_add(){
		$usrid = $this->session->userdata('usr_id');
		$session = $this->session->userdata('session_id');
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