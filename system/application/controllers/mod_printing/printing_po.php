<?php
class printing_po extends MY_Controller{
	public static $link_controller,$link_view,$ppn_status,$print_status;
	function printing_po(){
		parent::MY_Controller();
		$this->load->model(array('Tbl_po','Tbl_purchase_type','Tbl_satuan','Tbl_user','tbl_rptnote','flexi_model'));
		$this->load->helper('flexigrid');
		$this->load->library(array('flexigrid','flexi_engine'));
		
		$this->config->load('flexigrid');
		
		$this->lang->load('label','bahasa');
		
		// LINK CONTROLLER DAN VIEW
		self::$link_controller = 'mod_printing/printing_po';
		self::$link_view = 'purchase/mod_printing/po_printing';
		$data['link_controller'] = self::$link_controller;
		$data['link_view'] = self::$link_view;
		
		// STATUS PPN
		self::$ppn_status = '';
		if ($this->session->userdata('module_type') == 'PPN')
			self::$ppn_status = 'ppn_';
			
		// VARIABLE VIEW
		//$data['main_page'] = '';
		
		// TITLE
		if ($this->uri->segment(4) == 1)
			$data['page_title'] = $this->lang->line('print_dup_po_title');
		else
			$data['page_title'] = $this->lang->line('print_po_title');
		
		$this->load->vars($data);
		
	}
	
	// QUERY UNTUK NON FLEXIGRID ATO FLEXIGRID
	function flexigrid_sql($flexi=TRUE,$print_status,$count='po_id',$where=FALSE) {
		$sql = "SELECT p.po_id, p.po_no, date_format( p.po_date, '%d-%m-%Y' ) AS po_date, s.sup_name, l.legal_name {COUNT_STR}
			FROM prc_po AS p
			INNER JOIN prc_master_supplier AS s ON s.sup_id = p.sup_id
			INNER JOIN prc_master_legality AS l ON l.legal_id = s.legal_id
			WHERE po_printStat = '".$print_status."' and po_status='0' {SEARCH_STR}";
		return $this->flexi_model->generate_sql($sql,$count,$where,$flexi);
	}
	
	function flexigrid_ajax($print_status=0)
	{
		//List of all fields that can be sortable. This is Optional.
		//This prevents that a user sorts by a column that we dont want him to access, or that doesnt exist, preventing errors.
		$valid_fields = array('po_id','po_no','po_date','sup_name','legal_name');
		
		$this->flexigrid->validate_post('po_id','desc',$valid_fields);

		//$records = $this->Tbl_po->po_list($print_status);
		$records = $this->flexigrid_sql(TRUE,$print_status);
		
		$this->output->set_header($this->config->item('json_header'));
		
		/*
		 * Json build WITH json_encode. If you do not have this function please read
		 * http://flexigrid.eyeviewdesign.com/index.php/flexigrid/example#s3 to know how to use the alternative
		 */
		if ($records['count'] > 0){
			$i=0;
			foreach ($records['result']->result() as $row){
				//$i = $i +1;
				$record_items[] = array($row->po_id,
				//$i,
				$row->po_no,
				$row->po_date,
				$row->sup_name.', '.$row->legal_name,
				'<a href=\'javascript:void(0)\' onclick=\'open_po('.$row->po_id.','.$print_status.')\'><img border=\'0\' src=\'./asset/img_source/icon_info.gif\'></a>'
				);
			}
		}else{
			//$records['record_count'] += 1;
			$record_items[] = array('empty','empty','empty','empty','empty');
		}
		//Print please
		$this->output->set_output($this->flexigrid->json_build($records['count'],$record_items));
	}
	
	function index($print_status = 0) {
		$data['extraHeadContent'] = "<link type=\"text/css\" rel=\"stylesheet\" href=\"". base_url()."asset/css/themes/start/ui.all.css\" />\n";
		$data['extraHeadContent'] .= "<script type=\"text/javascript\" src=\"". base_url()."asset/javascript/jQuery/tables/jquery.jeditable.js\" />\n </script>";
		$data['extraHeadContent'] .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"".base_url()."asset/javascript/jQuery/flexigrid/css/flexigrid.css\" />\n";
		$data['extraHeadContent'] .= "<script type=\"text/javascript\" src=\"". base_url()."asset/javascript/jQuery/flexigrid/js/flexigrid.js\" />\n </script>";
		$data['extraHeadContent'] .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"".base_url()."asset/css/table/DataView.css\" />\n";
		
		//$colModel['no'] = array($this->lang->line('po_flex_col_0'),20,TRUE,'center',0);
		$colModel['po_no'] = array($this->lang->line('po_flex_col_1'),100,TRUE,'center',2);
		$colModel['po_date'] = array($this->lang->line('po_flex_col_2'),100,TRUE,'center',2);
		$colModel['sup_name'] = array('Pemasok',170, TRUE,'left',0);
		$colModel['opsi'] = array($this->lang->line('po_flex_col_4'),45, TRUE, 'center',0);
		/*
		 * Aditional Parameters
		 */
		$gridParams = array(
		'width' => '500',
		'height' => 200,
		'rp' => 15,
		'rpOptions' => '[10,15,20,25,40]',
		//'pagestat' => 'Displaying: {from} to {to} of {total} items.',
		'blockOpacity' => 0.5,
		'title' => $this->lang->line('po_flex_ttl'),
		'showTableToggleBtn' => true
		);
		
		$records = $this->Tbl_po->po_list($print_status);
		if ($records['record_count'] == 0){
			$data['kosong'] = "Belum ada Data Untuk Diprint";
		}else{
		//Build js
		//View helpers/flexigrid_helper.php for more information about the params on this function
		$grid_js = build_grid_js('flex1',site_url(self::$link_controller."/flexigrid_ajax/".$print_status),$colModel,'po_id','desc',$gridParams);
		$data['message'] = $this->lang->line('contact_confirm_del');
		$data['js_grid'] = $grid_js;
		$data['kosong'] = "";
		}
		$data['content'] = self::$link_view.'/index_po_view';
		$this->load->view('index',$data);
	}
	
	function open_po ($id,$print_status){
		$idnote = 2; // untuk print PO
		$note = $this->tbl_rptnote->get_note($idnote);
		$data['notes']='';
		if ($note->num_rows() > 0)
			$data['notes'] = $note->row()->note;

		$usr = $this->session->userdata('usr_id');
		$data['usr'] = $this->Tbl_user->get_user($usr);
		$data['content']=$this->Tbl_po->get_po_content($id);
		
		$data['print_status'] = $print_status;

		$this->load->view(self::$link_view.'/po_print_'.self::$ppn_status.'view',$data);

	}
	
	function print_update($id, $count, $tgl, $print_status){
		$usr = $this->session->userdata('usr_id');
		if ($this->Tbl_po->update_po($id, $count, $tgl, $usr)):
			if ($print_status == 0) {
				echo 'ok';
			}else {
				$this->open_po($id,$print_status);
			}
		endif;
	}
	
	function get_footer(){
		
	}
}
?>