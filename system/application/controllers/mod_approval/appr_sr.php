<?php
class appr_sr extends MY_Controller{
	public static $link_view, $link_controller,$link_controller2, $user_id;
	function appr_sr(){
		parent::MY_Controller();
		$this->load->model(array('tbl_sr','tbl_purchase_type','tbl_satuan','tbl_counter','tbl_pcv','tbl_satuan_pro'));
		$this->load->helper('flexigrid');
		$this->load->library('flexigrid');
		$this->config->load('flexigrid');
		
		$this->lang->load('general','bahasa');
		//$this->obj =& get_instance();
		self::$link_controller = 'mod_approval/appr_sr';
		self::$link_controller2 = 'mod_approval/appr_srflexigrid';
		self::$link_view = 'purchase/mod_approval/sr_appr';
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
		
		//$colModel['no'] = array($this->lang->line('pr_flex_col_0'),20,TRUE,'center',0);
		$colModel['sr_no'] = array($this->lang->line('sr_flex_col_1'),90,TRUE,'center',2);
		$colModel['sr_date'] = array($this->lang->line('sr_flex_col_2'),80,TRUE,'center',2);
		//$colModel['sr_due'] = array($this->lang->line('sr_flex_col_3'),60, TRUE,'center',0);
		$colModel['dep_name'] = array($this->lang->line('sr_flex_col_4'),80, TRUE, 'center',2);
		//$colModel['sr_emergency'] = array($this->lang->line('sr_flex_col_5'),80, TRUE, 'center',2);
		//$colModel['sr_waiting'] = array($this->lang->line('sr_flex_col_6'),60, TRUE, 'center',0);
		$colModel['sr_pending'] = array($this->lang->line('sr_flex_col_7'),60, TRUE, 'center',0);
		$colModel['sr_ok'] = array($this->lang->line('sr_flex_col_8'),60, TRUE, 'center',0);
		$colModel['sr_reject'] = array($this->lang->line('sr_flex_col_9'),60, TRUE, 'center',0);
		$colModel['sr_lastModified'] = array($this->lang->line('sr_flex_col_10'),80, TRUE, 'center',0);
		$colModel['opsi'] = array($this->lang->line('sr_flex_col_11'),40, TRUE, 'center',0);
		/*
		 * Aditional Parameters
		 */
		
		$this->flexigrid->validate_post('sr_no','asc'); // HARUS
		$records = $this->tbl_sr->sr_list();
			
		$gridParams = array(
		'width' => 'auto',
		'height' => 271,
		'blockOpacity' => 0.5,
		'title' => $this->lang->line('sr_flex_ttl'),
		'showTableToggleBtn' => false,
		'singleSelect' => true
		);
		
		if ($records['record_count'] == 0){
			$data['kosong'] = "Belum ada daftar SR untuk di proses";
		}else{
		//Build js
		//View helpers/flexigrid_helper.php for more information about the params on this function
		$grid_js = build_grid_js('flex1',site_url(self::$link_controller2),$colModel,'id','asc',$gridParams);
		$data['message'] = $this->lang->line('contact_confirm_del');
		$data['js_grid'] = $grid_js;
		$data['kosong'] = "";
		}
		$data['content'] = self::$link_view.'/sr';
		$this->load->view('index',$data);
	
	}
	
	function open_sr($id){
		$data['get_sr'] =  $this->tbl_sr->sr_content($id);
		$data['prc_type'] =  $this->tbl_purchase_type->list_prc_type();
		$data['list_sat'] =  $this->tbl_satuan->list_satuan();
		$this->load->view(self::$link_view.'/sr_app_cek',$data);
	}
	
	function open_history($proid){
		$data['get_history'] =  $this->tbl_sr->sr_history($proid);
		$this->load->view(self::$link_view.'/sr_history',$data);
	}
	
	function sr_save($status,$data) {
	
	}
	
	function sr_add(){
		$status_info = array('Null','Disetujui','Diubah & disetujui','Disetujui Dgn Catatan','Ditunda','Ditolak');
		$usrid = $this->session->userdata('usr_id');
		$sr_id = $this->input->post('sr_id');
		$pro_id = $this->input->post('pro_id');
		$pro_name = $this->input->post('pro_name');
		
		$status = $this->input->post('sr_status');
		$sr_desc = $this->input->post('sr_desc');
		
		$sr_note = $this->input->post('sr_note');
		
		// UPDATE
		$sr_cat = $this->input->post('sr_cat');
		$sr_type = $this->input->post('sr_type');
		$sr_qty = $this->input->post('sr_qty');
		$sr_um = $this->input->post('sr_um');
		$sr_sup = $this->input->post('sr_sup');
		
		// ORIGINAL
		$sr_cat_org = $this->input->post('sr_cat_org');
		$sr_type_org = $this->input->post('sr_type_org');
		$sr_qty_org = $this->input->post('sr_qty_org');
		$sr_um_org = $this->input->post('sr_um_org');
		$sr_sup_org = $this->input->post('sr_sup_org');
		
		for($i=1;$i<=sizeof($pro_id);$i++):
			$is_approve[$i] = true;
			$sr_approve[$i] = false;
			$status_approve[$i] = false;
			
			$sr_status = $status[$i];
					
			// DATAIL
			$where_det['sr_id'] = $sr_id;
			$where_det['pro_id'] = $pro_id[$i];
			$data_det['description'] = $sr_desc[$i];
			$data_det['requestStat'] = $status[$i];	
			// HISTORY
			$data_his['sr_id'] = $sr_id;
			$data_his['pro_id'] = $pro_id[$i];
			$data_his['description'] = $sr_desc[$i];
			$data_his['sr_usr'] = $usrid;
			$data_his['sr_usr_note'] = $sr_note[$i];
			$data_his['requestStat'] = $status[$i];			
			
			if ($sr_status == 2):
				// DATAIL
				$data_det['service_cat'] = $sr_cat[$i];
				$data_det['service_type'] = $sr_type[$i];
				$data_det['qty'] = $sr_qty[$i];
				$data_det['um_id'] = $sr_um[$i];
				$data_det['num_supplier'] = $sr_sup[$i];
				// HISTORY UBAH
				$data_his['service_cat'] = $sr_cat[$i];
				$data_his['service_type'] = $sr_type[$i];
				$data_his['qty'] = $sr_qty[$i];
				$data_his['um_id'] = $sr_um[$i];
				$data_his['num_supplier'] = $sr_sup[$i];
			else:
				// DATAIL
				$data_det['service_cat'] = $sr_cat_org[$i];
				$data_det['service_type'] = $sr_type_org[$i];
				$data_det['qty'] = $sr_qty_org[$i];
				$data_det['um_id'] = $sr_um_org[$i];
				$data_det['num_supplier'] = $sr_sup_org[$i];
				// HISTORY ORIGINAL
				$data_his['service_cat'] = $sr_cat_org[$i];
				$data_his['service_type'] = $sr_type_org[$i];
				$data_his['qty'] = $sr_qty_org[$i];
				$data_his['um_id'] = $sr_um_org[$i];
				$data_his['num_supplier'] = $sr_sup_org[$i];			
			endif;
			
			if ($this->tbl_sr->sr_update_detail($where_det,$data_det)):		
				if ($this->tbl_sr->sr_insert_history($data_his)):
					if ($sr_status == 4):
						$is_approve[$i] = false;
					endif;
					$sr_approve[$i] = $pro_name[$i];
					$status_approve[$i] = $status_info[$sr_status];
				endif;
			endif;
			
			/*
			if ($sr_status == 2):
				echo '[i:'.$i.'.stat:'.$sr_status.'|pro:'.$pro_id[$i].'|id:'.$sr_id[$i].'|sup:'.$sr_sup[$i].'|qty:'.$sr_qty[$i].'|um:'.$sr_um[$i].'|type:'.$sr_type[$i].'|cat:'.$sr_cat[$i].'|desc:'.$sr_desc[$i].'|note:'.$sr_note[$i].']';
			else:
				echo '[i:'.$i.'.stat:'.$sr_status.'|pro:'.$pro_id[$i].'|id:'.$sr_id[$i].'|sup:'.$sr_sup_org[$i].'|qty:'.$sr_qty_org[$i].'|um:'.$sr_um_org[$i].'|type:'.$sr_type_org[$i].'|cat:'.$sr_cat_org[$i].'|desc:'.$sr_desc[$i].'|note:'.$sr_note[$i].']';
			endif;
			*/
		endfor;
		
		$where_sr['sr_id'] = $sr_id;
		if (in_array(false,$is_approve)):
			$data_sr['is_approved'] = 0;
		else:
			$data_sr['is_approved'] = 1;
		endif;
		$data_sr['sr_lastModified'] = date('Y-m-d h:i:s');
		if ($this->tbl_sr->sr_update($where_sr,$data_sr)):
			for($i=1;$i<=sizeOf($sr_approve);$i++):
				echo '<b>- <font color="red">'.$sr_approve[$i].'</font> telah '.$status_approve[$i].'</b><br>';
			endfor;
		endif;

	}
	
}
?>