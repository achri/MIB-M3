<?php
class Appr_mr extends MY_Controller{
	public static $link_view, $link_controller, $user_id;
	function Appr_mr(){
		parent::MY_Controller();
		$this->load->model(array('Tbl_mr','Tbl_purchase_type','Tbl_satuan', 'Tbl_counter', 'Tbl_goodrelease', 'Tbl_inventory', 'tbl_satuan_pro'));
		$this->load->helper('flexigrid');
		$this->load->library('flexigrid');
		$this->config->load('flexigrid');
		
		$this->lang->load('general','bahasa');
		
		self::$link_controller = 'mod_approval/appr_mr';
		self::$link_view = 'purchase/mod_approval/mr_appr';
 		$data['link_controller'] = self::$link_controller;
		$data['link_view'] = self::$link_view;
		
		$this->load->vars($data);
	}
	
	function flexigrid_ajax()
	{
		//List of all fields that can be sortable. This is Optional.
		//This prevents that a user sorts by a column that we dont want him to access, or that doesnt exist, preventing errors.
		$valid_fields = array('dep_name','mr_no','mr_id','mr_date','mr_lastModified','tgl_selisih','mr_waiting','mr_pending','mr_ok','mr_reject');
		
		$this->flexigrid->validate_post('mr_id','asc',$valid_fields);

		$records = $this->Tbl_mr->mr_list();
		
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
			$i = $i + 1;
			if ($row->tgl_selisih >= 3 ){
				$d = $d + 1;
				$record_items[] = array($row->mr_id,
				'<span style=\'color:#ff4400\'>'.addslashes($i).'</span>',
				'<span style=\'color:#ff4400\'>'.addslashes($row->mr_no).'</span>',
				'<span style=\'color:#ff4400\'>'.addslashes($row->mr_date).'</span>',
				'<span style=\'color:#ff4400\'>'.addslashes($row->tgl_selisih).'</span>',
				'<span style=\'color:#ff4400\'>'.addslashes($row->dep_name).'</span>',
				'<span style=\'color:#ff4400\'>'.addslashes($row->mr_waiting).'</span>',
				'<span style=\'color:#ff4400\'>'.addslashes($row->mr_pending).'</span>',
				'<span style=\'color:#ff4400\'>'.addslashes($row->mr_reject).'</span>',
				'<span style=\'color:#ff4400\'>'.addslashes($row->mr_ok).'</span>',
				'<span style=\'color:#ff4400\'>'.addslashes($row->mr_ubah).'</span>',
				'<span style=\'color:#ff4400\'>'.addslashes($row->mr_catatan).'</span>',
				'<span style=\'color:#ff4400\'>'.addslashes($row->mr_lastModified).'</span>',
				'<a href=\'javascript:void(0)\' onclick=\'open_mr('.$row->mr_id.')\'><img border=\'0\' src=\'./asset/img_source/s_warn.gif\'></a>'
				);
			}else{	
				
				if ($d == 0){
					$action = 'open_mr('.$row->mr_id.')';
				}else{
					$action = 'alert_pr()';
				}
				
				$record_items[] = array($row->mr_id,
				$i,
				$row->mr_no,
				$row->mr_date,
				$row->tgl_selisih,
				$row->dep_name,
				$row->mr_waiting,
				$row->mr_pending,
				$row->mr_reject,
				$row->mr_ok,
				$row->mr_ubah,
				$row->mr_catatan,
				$row->mr_lastModified,
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
		
		$colModel['no'] = array($this->lang->line('pr_flex_col_0'),20,TRUE,'center',0);
		$colModel['mr_no'] = array($this->lang->line('mr_flex_col_1'),90,TRUE,'center',2);
		$colModel['mr_date'] = array($this->lang->line('pr_flex_col_2'),80,TRUE,'center',2);
		$colModel['mr_due'] = array($this->lang->line('pr_flex_col_3'),60, TRUE,'center',0);
		$colModel['dep_name'] = array($this->lang->line('pr_flex_col_4'),80, TRUE, 'center',2);
		$colModel['mr_waiting'] = array($this->lang->line('pr_flex_col_6'),50, TRUE, 'center',0);
		$colModel['mr_pending'] = array($this->lang->line('pr_flex_col_7'),50, TRUE, 'center',0);
		$colModel['mr_ok'] = array($this->lang->line('pr_flex_col_8'),50, TRUE, 'center',0);
		$colModel['mr_reject'] = array($this->lang->line('pr_flex_col_9'),50, TRUE, 'center',0);
		$colModel['mr_ubah'] = array('Diubah<br>&Disetujui',50, TRUE, 'center',0);
		$colModel['mr_catatan'] = array('Disetujui<br>dgn catatan',55, TRUE, 'center',0);
		$colModel['mr_lastModified'] = array($this->lang->line('pr_flex_col_10'),80, TRUE, 'center',0);
		$colModel['opsi'] = array($this->lang->line('pr_flex_col_11'),40, TRUE, 'center',0);
		/*
		 * Aditional Parameters
		 */
		$gridParams = array(
		'width' => 'auto',
		'height' => 271,
		'blockOpacity' => 0.5,
		'title' => 'Daftar Material Request',
		'showTableToggleBtn' => false,
		'singleSelect' => true
		);
		
		$this->flexigrid->validate_post('mr_id','asc'); // HARUS
		
		$records = $this->Tbl_mr->mr_list();
		if ($records['record_count'] == 0){
			$data['kosong'] = "Belum ada daftar MR untuk di proses";
		}else{
			//Build js
			//View helpers/flexigrid_helper.php for more information about the params on this function
			$grid_js = build_grid_js('flex1',site_url(self::$link_controller."/flexigrid_ajax"),$colModel,'mr_id','desc',$gridParams);
			$data['message'] = $this->lang->line('contact_confirm_del');
			$data['js_grid'] = $grid_js;
			$data['kosong'] = "";
		}
		$data['content'] = self::$link_view.'/mr';
		$this->load->view('index',$data);
	
	}
	
	function open_mr($id){
		$data['get_pr'] =  $this->Tbl_mr->mr_content($id);
		$data['list_sat'] =  $this->Tbl_satuan->list_satuan();
		$this->load->view(self::$link_view.'/mr_app_cek',$data);
	}
	
	function cek_stok($id){
		$stock = $data['get_pr'] =  $this->Tbl_inventory->cek_stok($id)->row();
		echo $stock->stock;
	}
	
	function mr_add(){
		$usrid = $this->session->userdata('usr_id');
		$pro = $this->input->post('pro_id');
		$mr = $this->input->post('mr_id');
		$triger = 0;
		$now = date('Y-m-d');
		$time = explode("-",$now);
		
		//chek zero value......
		for($i=0;$i<sizeof($pro);$i++) {
			$status[$i] = $this->input->post('statusmr_'.$pro[$i]);
			if ($status[$i] == '0' ){
				$no = $i + 1;
				echo "- status MR <font color='red'>".$no."</font> belum diisi <br/>";
				$triger = $triger + $no;
			}
		}
		if ($triger != 0){
			echo "<br/><div style='text-align : right;'><input type='button' value='ok' onclick='closedialog()'></div>";
		}
		if ($triger == 0){
			$cekcounter = $this->Tbl_counter->cek_counter($time[0], $time[1])->num_rows();
			// GOOD RELEASE NUMBER
			$grl_doc_no = $this->lang->line('grl_doc_no');
			if ($cekcounter == 0){
				$start = 2;
				$this->Tbl_counter->insert_counter($time[0], $time[1], $start);
				
				$timecode = date('y-m-d');
				$timecode = explode ("-", $timecode);
				$counter = 1;
				$dtl_code = str_pad($counter, 4, "0", STR_PAD_LEFT);
				
				$head_code = $timecode[0].'/'.$timecode[1];
				$code = $head_code.'/'.$grl_doc_no.$dtl_code;
											
				//insert grl no untuk mendapatkan grl id
				$grl_id = $this->Tbl_goodrelease->insert_code($code, $mr);
					
			}else{
				//===== generate code goodrelease=========
				$timecode = date('y-m-d');
				$timecode = explode ("-", $timecode);
				$counter = $this->Tbl_counter->cek_counter($time[0], $time[1])->row();
				$dtl_code = str_pad($counter->grl_no, 4, "0", STR_PAD_LEFT);
				$head_code = $timecode[0].'/'.$timecode[1];
				$code = $head_code.'/'.$grl_doc_no.$dtl_code;
											
				//insert grl no untuk mendapatkan grl id
				$grl_id = $this->Tbl_goodrelease->insert_code($code, $mr);
					
				//update sys_counter
				$grl_id++;
				$field = 'grl_no';
				$this->Tbl_counter->update_counter($grl_id, $time[0], $time[1], $field);
				$grl_id = $grl_id -1;
			}
						
			for($i=0;$i<sizeof($pro);$i++) {
				$note[$i] = $this->input->post('pr_note_'.$pro[$i]);
				$qty[$i] = $this->input->post('qty_'.$pro[$i]);
				$sat[$i] = $this->input->post('satuan_'.$pro[$i]);
				$deldate[$i] = $this->input->post('deldate_'.$pro[$i]);
				$defqty[$i] = $this->input->post('defvalqty_'.$pro[$i]);
				$defsat[$i] = $this->input->post('defvalsat_'.$pro[$i]);
				$defdeldate[$i] = $this->input->post('defvaldeldate_'.$pro[$i]);
				$pro_name[$i] = $this->input->post('pro_name_'.$pro[$i]);
				$desc[$i] = $this->input->post('desc_'.$pro[$i]);
				$procode[$i] = $this->input->post('procode_'.$pro[$i]);
				
				if ($status[$i] == 1){
					//Disetujui	
					$result[] = $this->Tbl_mr->mr_insert_1($mr, $pro[$i], $status[$i], $defqty[$i], $defsat[$i], $defdeldate[$i], $usrid, $pro_name[$i], $desc[$i], $grl_id, $procode[$i]);
				} else if($status[$i] == 2){
					//Diubah dan disetujui
					$result[] = $this->Tbl_mr->mr_insert_2($mr, $pro[$i], $status[$i], $note[$i], $qty[$i], $sat[$i], $deldate[$i], $defqty[$i], $defsat[$i], $defdeldate[$i], $usrid, $pro_name[$i], $desc[$i], $grl_id, $procode[$i]);
				} else if ($status[$i] == 3){
					//Disetujui dengan catatan
					$result[] = $this->Tbl_mr->mr_insert_3_4($mr, $pro[$i], $status[$i], $note[$i], $defqty[$i], $defsat[$i], $defdeldate[$i], $usrid, $pro_name[$i], $desc[$i], $grl_id, $procode[$i]);
				} else if ($status[$i] == 4){
					//Ditunda
					$result[] = $this->Tbl_mr->mr_insert_3_4($mr, $pro[$i], $status[$i], $note[$i], $defqty[$i], $defsat[$i], $defdeldate[$i], $usrid, $pro_name[$i], $desc[$i], $grl_id, $procode[$i]);
				} else {
					//Ditolak
					$result[] = $this->Tbl_mr->mr_insert_5($mr, $pro[$i], $status[$i], $note[$i], $defqty[$i], $defsat[$i], $defdeldate[$i], $usrid, $pro_name[$i], $desc[$i], $grl_id, $procode[$i]);
				}
			}
			if ($result){
				$arr_status = array('','Disetujui','Diubah & Disetujui','Disetujui dgn catatan','Ditunda','Ditolak');
				echo '<table border=0 cellspacing=0 cellpadding=5>';
				for($i=0;$i<sizeof($result);$i++) {
					echo "<tr><td>- MR untuk produk <b><font color='red'>".$pro_name[$i]."</font></b>&nbsp</td><td>&nbsp;&nbsp;".$arr_status[$status[$i]]."</td></tr>";
				}
				echo '</table>';
			
				//echo "<br/><div style='text-align : right;'><input type='button' value='OK' onclick='batal()'></div>";
			}
		}
	}
	
}
?>