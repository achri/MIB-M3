<?php
class appr_pr extends MY_Controller{
	public static $link_view, $link_controller, $user_id, $ppn_status, $print_status;
	function appr_pr(){
		parent::MY_Controller();
		$this->load->model(array('tbl_pr','tbl_purchase_type','tbl_satuan','tbl_counter','tbl_pcv','tbl_satuan_pro'));
		$this->load->helper('flexigrid');
		$this->load->library('flexigrid');
		$this->config->load('flexigrid');
		
		$this->lang->load('general','bahasa');
		//$this->obj =& get_instance();
		
		self::$link_controller = 'mod_approval/appr_pr';
		self::$link_view = 'purchase/mod_approval/pr_appr';
		$data['link_controller'] = self::$link_controller;
		$data['link_view'] = self::$link_view;
		
		$this->load->vars($data);
	}
	
	function flexigrid_ajax()
	{
		//List of all fields that can be sortable. This is Optional.
		//This prevents that a user sorts by a column that we dont want him to access, or that doesnt exist, preventing errors.
		$valid_fields = array('pr_id','pr_no','pr_date','pr_lastModified','pr_due','pr_waiting','dep_name','pr_pending','pr_ok','pr_reject','pr_emergency');
		
		$this->flexigrid->validate_post('pr_id','asc',$valid_fields);

		$records = $this->tbl_pr->pr_list();
		
		$this->output->set_header($this->config->item('json_header'));
		
		/*
		 * Json build WITH json_encode. If you do not have this function please read
		 * http://flexigrid.eyeviewdesign.com/index.php/flexigrid/example#s3 to know how to use the alternative
		 */
		if ($records['record_count'] > 0){
		$i = 0;
		$d = 0;
		foreach ($records['records']->result() as $row)
		{
			if ($row->pr_emergency == 0){
				$row->pr_emergency = 'Normal';
			}else{
				$row->pr_emergency = '<font color=red>Darurat</font>';
			}
			
			$i = $i + 1;
			if ($row->pr_due >= 3 ){
				$d = $d + 1;
				$record_items[] = array($row->pr_id,
				//'<span style=\'color:#ff4400\'>'.addslashes($i).'</span>',
				'<span style=\'color:#ff4400\'>'.addslashes($row->pr_no).'</span>',
				'<span style=\'color:#ff4400\'>'.addslashes($row->pr_date).'</span>',
				'<span style=\'color:#ff4400\'>'.addslashes($row->pr_due).'</span>',
				'<span style=\'color:#ff4400\'>'.addslashes($row->dep_name).'</span>',
				'<span style=\'color:#ff4400\'>'.addslashes($row->pr_emergency).'</span>',
				'<span style=\'color:#ff4400\'>'.addslashes($row->pr_waiting).'</span>',
				'<span style=\'color:#ff4400\'>'.addslashes($row->pr_pending).'</span>',
				'<span style=\'color:#ff4400\'>'.addslashes($row->pr_reject).'</span>',
				'<span style=\'color:#ff4400\'>'.addslashes($row->pr_ok).'</span>',
				'<span style=\'color:#ff4400\'>'.addslashes($row->pr_ubah).'</span>',
				'<span style=\'color:#ff4400\'>'.addslashes($row->pr_catatan).'</span>',
				'<span style=\'color:#ff4400\'>'.addslashes($row->pr_lastModified).'</span>',
				'<a href=\'javascript:void(0)\' onclick=\'open_pr('.$row->pr_id.')\'><img border=\'0\' src=\'./asset/img_source/s_warn.gif\'></a>'
				);
			}else{	
				
				if ($d == 0){
					$action = 'open_pr('.$row->pr_id.')';
				}else{
					$action = 'alert_pr()';
				}
				
				$record_items[] = array($row->pr_id,
				//$i,
				$row->pr_no,
				$row->pr_date,
				$row->pr_due,
				$row->dep_name,
				$row->pr_emergency,
				$row->pr_waiting,
				$row->pr_pending,
				$row->pr_reject,
				$row->pr_ok,
				$row->pr_ubah,
				$row->pr_catatan,
				$row->pr_lastModified,
				'<a href=\'javascript:void(0)\' onclick='.$action.'><img border=\'0\' src=\'./asset/img_source/icon_info.gif\'></a>'
				);
			}
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
		
		//$colModel['no'] = array($this->lang->line('pr_flex_col_0'),20,TRUE,'center',0);
		$colModel['pr_no'] = array('No PR',80,TRUE,'center',2);
		$colModel['pr_date'] = array($this->lang->line('pr_flex_col_2'),60,TRUE,'center',2);
		$colModel['pr_due'] = array($this->lang->line('pr_flex_col_3'),60, TRUE,'center',0);
		$colModel['dep_name'] = array($this->lang->line('pr_flex_col_4'),90, TRUE, 'center',2);
		$colModel['pr_emergency'] = array($this->lang->line('pr_flex_col_5'),60, TRUE, 'center',2);
		$colModel['pr_waiting'] = array($this->lang->line('pr_flex_col_6'),50, TRUE, 'center',0);
		$colModel['pr_pending'] = array($this->lang->line('pr_flex_col_7'),50, TRUE, 'center',0);
		$colModel['pr_ok'] = array($this->lang->line('pr_flex_col_8'),50, TRUE, 'center',0);
		$colModel['pr_reject'] = array($this->lang->line('pr_flex_col_9'),50, TRUE, 'center',0);
		$colModel['pr_ubah'] = array('Diubah',50, TRUE, 'center',0);
		$colModel['pr_catatan'] = array('Dgn catatan',70, TRUE, 'center',0);
		$colModel['pr_lastModified'] = array($this->lang->line('pr_flex_col_10'),60, TRUE, 'center',0);
		$colModel['opsi'] = array('Opsi',30, TRUE, 'center',0);
		/*
		 * Aditional Parameters
		 */
		
		$this->flexigrid->validate_post('pr_no','asc'); // HARUS
		$records = $this->tbl_pr->pr_list();
			
		$gridParams = array(
		'width' => 'auto',
		'height' => 271,
		'blockOpacity' => 0.5,
		'title' => $this->lang->line('pr_flex_ttl'),
		'showTableToggleBtn' => false,
		'singleSelect' => true
		);
		
		if ($records['record_count'] == 0){
			$data['kosong'] = "Belum ada daftar PR untuk di proses";
		}else{
		//Build js
		//View helpers/flexigrid_helper.php for more information about the params on this function
		$grid_js = build_grid_js('flex1',site_url(self::$link_controller."/flexigrid_ajax"),$colModel,'pr_id','desc',$gridParams);
		$data['message'] = $this->lang->line('contact_confirm_del');
		$data['js_grid'] = $grid_js;
		$data['kosong'] = "";
		}
		$data['content'] = self::$link_view.'/pr';
		$this->load->view('index',$data);
	
	}
	
	function open_pr($id){
		$data['get_pr'] =  $this->tbl_pr->pr_content($id);
		$data['prc_type'] =  $this->tbl_purchase_type->list_prc_type();
		$data['list_sat'] =  $this->tbl_satuan->list_satuan();
		$this->load->view(self::$link_view.'/pr_app_cek',$data);
	}
	
	function open_history($proid){
		$data['get_history'] =  $this->tbl_pr->pr_history($proid);
		$this->load->view(self::$link_view.'/pr_history',$data);
	}
	
	function pr_add(){
		$usrid = $this->session->userdata('usr_id');
		$pro = $this->input->post('pro_id');
		$triger = 0;
		
		//chek zero value......
		for($i=0;$i<sizeof($pro);$i++) {
			$status[$i] = $this->input->post('statuspr_'.$pro[$i]);
			$pro_name[$i] = $this->input->post('pro_name_'.$pro[$i]);
			if ($status[$i] == '0' ){
				$no = $i + 1;
				echo "- status PR <b>".$pro_name[$i]."</b> belum diisi <br/>";
				$triger = $triger + $no;
			}
		}
		if ($triger != 0){
			echo "<br/><div style='text-align : right;'><input type='button' value='ok' onclick='closedialog()'></div>";
		}
		if ($triger == 0){
			for($i=0;$i<sizeof($pro);$i++) {
				$status[$i] = $this->input->post('statuspr_'.$pro[$i]);
				$buy[$i] = $this->input->post('buy_'.$pro[$i]);
				$note[$i] = $this->input->post('pr_note_'.$pro[$i]);
				$type[$i] = $this->input->post('prctype_'.$pro[$i]);
				$qty[$i] = $this->input->post('qty_'.$pro[$i]);
				$sat[$i] = $this->input->post('satuan_'.$pro[$i]);
				$deldate[$i] = $this->input->post('deldate_'.$pro[$i]);
				$pr = $this->input->post('pr_id');
				$sup[$i] = $this->input->post('sup_'.$pro[$i]);
				$defpty[$i] = $this->input->post('defvalpty_'.$pro[$i]);
				$defqty[$i] = $this->input->post('defvalqty_'.$pro[$i]);
				$defsat[$i] = $this->input->post('defvalsat_'.$pro[$i]);
				$emergency[$i] = $this->input->post('emergency_'.$pro[$i]);
				$defdeldate[$i] = $this->input->post('defvaldeldate_'.$pro[$i]);
				$pro_name[$i] = $this->input->post('pro_name_'.$pro[$i]);
				$pro_code[$i] = $this->input->post('pro_code_'.$pro[$i]);
				$now = date('Y-n-d');
				$time = explode("-",$now);
				
				//generate PCV
				if ($buy[$i] == 'pcv'){
					$timecode = date('y-m-d');
					$timecode = explode ("-", $timecode);
					$cekpcv = $this->tbl_pcv->cek_pcv($pr)->num_rows();
					if ($cekpcv == 0){
						$count = $this->tbl_counter->cek_counter($time[0], $time[1])->num_rows();
						if ($count == 0){
						$this->Tbls_counter->insert_counter_def($time[0], $time[1]);
						}
						$counter = $this->tbl_counter->cek_counter($time[0], $time[1])->row();
						$dtl_code = str_pad($counter->pcv_no, 4, "0", STR_PAD_LEFT);
						$head_code = $timecode[0].'/'.$timecode[1];
						// Petty Cash Voucher NUMBER
						$pcv_doc_no = $this->lang->line('pcv_doc_no');
						$code = $head_code.'/'.$pcv_doc_no.$dtl_code;
						$pcv_id = $this->tbl_pcv->insert_pcv($code, $pr);
						$no = $counter->pcv_no + 1;
						$field = 'pcv_no';
						$this->tbl_counter->update_counter($no, $time[0], $time[1], $field);
					}else{
						$cekpcv = $this->tbl_pcv->cek_pcv($pr)->row();
						$pcv_id = $cekpcv->pcv_id;
					}
				}else{
					$code = '0';
					$pcv_id = '0';
				}
				
				if ($status[$i] == 1){
					//Disetujui
					$result[] = $this->tbl_pr->pr_insert_1($pr, $pro[$i], $status[$i], $buy[$i], $sup[$i], $defpty[$i], $defqty[$i], $defsat[$i], $defdeldate[$i], $usrid, $pro_name[$i], $pcv_id, $pro_code[$i]);
				} else if($status[$i] == 2){
					//Diubah dan disetujui
					$result[] = $this->tbl_pr->pr_insert_2($pr, $pro[$i], $status[$i], $buy[$i], $note[$i], $type[$i], $qty[$i], $sat[$i],
								$deldate[$i], $emergency[$i], $sup[$i], $defpty[$i], $defqty[$i], $defsat[$i], $defdeldate[$i], $usrid, $pro_name[$i], $pcv_id, $pro_code[$i]);
				} else if ($status[$i] == 3){
					//Disetujui dengan catatan
					$result[] = $this->tbl_pr->pr_insert_3_4($pr, $pro[$i], $status[$i], $buy[$i], $note[$i], $sup[$i], $defpty[$i], $defqty[$i], $defsat[$i], $defdeldate[$i], $usrid, $pro_name[$i], $pcv_id, $pro_code[$i]);
				} else if ($status[$i] == 4){
					//Ditunda
					$result[] = $this->tbl_pr->pr_insert_3_4($pr, $pro[$i], $status[$i], $buy[$i], $note[$i], $sup[$i], $defpty[$i], $defqty[$i], $defsat[$i], $defdeldate[$i], $usrid, $pro_name[$i], $pcv_id, $pro_code[$i]);
				} else {
					//Ditolak
					$result[] = $this->tbl_pr->pr_insert_5($pr, $pro[$i], $status[$i], $buy[$i], $sup[$i], $defpty[$i], $defqty[$i], $defsat[$i], $defdeldate[$i], $usrid, $pro_name[$i], $pro_code[$i], $note[$i]);
				}
			}
			if ($result){
				$arr_status = array('','Disetujui','Diubah & Disetujui','Disetujui dgn catatan','Ditunda','Ditolak');
				echo '<table border=0 cellspacing=0 cellpadding=5>';
				for($i=0;$i<sizeof($result);$i++) {
					echo "<tr><td>- PR untuk Produk <b><font color='red'>".$pro_name[$i]."</font></b>&nbsp</td><td>&nbsp;&nbsp;".$arr_status[$status[$i]]."</td></tr>";
				}
				echo '</table>';
				//echo "<br/><div style='text-align : right;'><input type='button' value='ok' onclick='batal()'></div>";
			}
		}
	}
	
}
?>