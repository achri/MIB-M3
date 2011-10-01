<?php
class List_rfqtoapp extends MY_Controller{
	public static $link_view, $link_controller, $link_controller_rfq;
	function List_rfqtoapp(){
		parent::MY_Controller();
		$this->load->model(array('Tbl_rfq','Tbl_purchase_type','Tbl_satuan','Tbl_currency','Tbl_term','Tbl_supplier','Tbl_legal','Tbl_sup_produk','Tbl_category'));
		$this->load->helper('flexigrid');
		$this->load->library('flexigrid');
		$this->load->config('flexigrid');
		
		self::$link_controller_rfq = 'mod_entry/entry_rfq';
		self::$link_controller = 'mod_list/list_rfqtoapp';
		self::$link_view = 'purchase/mod_list/mod_rfqtoapp';
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
		$colModel['rfq_no'] = array($this->lang->line('rfq_flex_col_1'),90,TRUE,'center',2);
		$colModel['rfq_date'] = array($this->lang->line('rfq_flex_col_2'),80,TRUE,'center',2);
		$colModel['rfq_printDate'] = array($this->lang->line('rfq_flex_col_3'),80,TRUE,'center',2);
		$colModel['item_number'] = array($this->lang->line('rfq_flex_col_4'),60, TRUE,'center',0);
		$colModel['item_waiting'] = array($this->lang->line('rfq_flex_col_5'),60, TRUE,'center',0);
		$colModel['item_pending'] = array($this->lang->line('rfq_flex_col_6'),60, TRUE,'center',0);
		$colModel['item_reject'] = array($this->lang->line('rfq_flex_col_7'),60, TRUE,'center',0);
		$colModel['item_ok'] = array($this->lang->line('rfq_flex_col_8'),60, TRUE,'center',0);
		$colModel['opsi'] = array($this->lang->line('pr_flex_col_11'),60, TRUE,'center',0);
		/*
		 * Aditional Parameters
		 */
		$gridParams = array(
		'width' => 'auto',
		'height' => 200,
		'blockOpacity' => 0.5,
		'title' => $this->lang->line('rfq_flex_ttl'),
		'showTableToggleBtn' => true
		);
		
		$this->flexigrid->validate_post('rfq_id','asc');
		
		$records = $this->Tbl_rfq->rfq_list();
		if ($records['record_count'] == 0){
			$data['kosong'] = "Belum ada data RFQ untuk Diproses";
		}else{
		//Build js
		//View helpers/flexigrid_helper.php for more information about the params on this function
		$grid_js = build_grid_js('flex1',site_url(self::$link_controller_rfq."/flexigrid_ajax"),$colModel,'id','asc',$gridParams);
		$data['message'] = $this->lang->line('contact_confirm_del');
		$data['js_grid'] = $grid_js;
		$data['kosong'] = "";
		}
		$data['content'] = self::$link_view.'/rfq';
		$this->load->view('index',$data);
	}
	
	function open_rfq($id){
		$data['get_rfq'] =  $this->Tbl_rfq->rfq_content($id);
		$this->load->view(self::$link_view.'/rfq_app_cek',$data);
	}
	
	function get_history($pro_id){
		$data['hist'] = $this->Tbl_rfq->get_history($pro_id);
		$this->load->view(self::$link_view.'/rfq_history',$data);
	}
	
	function get_detail_history($detail, $pro_id){
		$data['detail'] = $this->Tbl_rfq->get_detail_history($detail, $pro_id);
		$this->load->view(self::$link_view.'/rfq_detail_history',$data);
	}
}
?>