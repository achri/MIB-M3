<?php
class printing_so extends MY_Controller{
	public static $link_view, $link_controller;
	function printing_so(){
		parent::MY_Controller();
		$this->load->model(array('Tbl_so','Tbl_purchase_type','Tbl_satuan','Tbl_user','tbl_rptnote'));
		$this->load->helper('flexigrid');
		$this->lang->load('label','bahasa');
		
		self::$link_controller = 'mod_printing/printing_so';
		self::$link_view = 'purchase/mod_printing/so_printing';
		$data['link_controller'] = self::$link_controller;
		$data['link_view'] = self::$link_view;
		
		if ($this->uri->segment(4) == 1)
			$data['page_title'] = $this->lang->line('print_dup_so_title');
		else
			$data['page_title'] = $this->lang->line('print_so_title');
				
		$this->load->vars($data);
		
	}
	
	function flexigrid_ajax()
	{
		//List of all fields that can be sortable. This is Optional.
		//This prevents that a user sorts by a column that we dont want him to access, or that doesnt exist, preventing errors.
		$valid_fields = array('so_id','so_no','so_date','sup_name','legal_name');
		
		$this->flexigrid->validate_post('so_id','asc',$valid_fields);

		$records = $this->Tbl_so->so_list();
		
		$this->output->set_header($this->config->item('json_header'));
		
		/*
		 * Json build WITH json_encode. If you do not have this function please read
		 * http://flexigrid.eyeviewdesign.com/index.php/flexigrid/example#s3 to know how to use the alternative
		 */
		if ($records['record_count'] > 0){
			$i=0;
			foreach ($records['records']->result() as $row){
				$i = $i +1;
				$record_items[] = array($row->so_id,
				$row->so_no,
				$row->so_date,
				$row->legal_name.'. '.$row->sup_name,
				'<a href=\'javascript:void(0)\' onclick=\'open_so('.$row->so_id.')\'><img border=\'0\' src=\'./asset/img_source/icon_info.gif\'></a>'
				);
			}
		}else{
			//$records['record_count'] += 1;
			$record_items[] = array('empty','empty','empty','empty','empty');
		}
		//Print please
		$this->output->set_output($this->flexigrid->json_build($records['record_count'],$record_items));
	}
	
	function index() {
		$data['extraHeadContent'] = "<link type=\"text/css\" rel=\"stylesheet\" href=\"". base_url()."asset/css/themes/start/ui.all.css\" />\n";
		$data['extraHeadContent'] .= "<script type=\"text/javascript\" src=\"". base_url()."asset/javascript/jQuery/tables/jquery.jeditable.js\" />\n </script>";
		$data['extraHeadContent'] .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"".base_url()."asset/javascript/jQuery/flexigrid/css/flexigrid.css\" />\n";
		$data['extraHeadContent'] .= "<script type=\"text/javascript\" src=\"". base_url()."asset/javascript/jQuery/flexigrid/js/flexigrid.js\" />\n </script>";
		$data['extraHeadContent'] .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"".base_url()."asset/css/table/DataView.css\" />\n";
		
		//$colModel['no'] = array($this->lang->line('po_flex_col_0'),20,TRUE,'center',0);
		$colModel['so_no'] = array('No SO',100,TRUE,'center',2);
		$colModel['so_date'] = array('Tgl SO',100,TRUE,'center',2);
		$colModel['sup_name'] = array('Pemasok',200, TRUE,'left',0);
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
		'title' => 'Daftar SO',
		'showTableToggleBtn' => true
		);
		
		$records = $this->Tbl_so->so_list();
		if ($records['record_count'] == 0){
			$data['kosong'] = "Belum ada Data Untuk Diprint";
		}else{
		//Build js
		//View helpers/flexigrid_helper.php for more information about the params on this function
		$grid_js = build_grid_js('flex1',site_url(self::$link_controller."/flexigrid_ajax"),$colModel,'id','asc',$gridParams);
		$data['message'] = $this->lang->line('contact_confirm_del');
		$data['js_grid'] = $grid_js;
		$data['kosong'] = "";
		}
		$data['content'] = self::$link_view.'/index_so_view';
		$this->load->view('index',$data);
	}
	
	function open_so ($id){
		$idnote = 2; // untuk print PO
		$usr = $this->session->userdata('usr_id');
		$data['usr'] = $this->Tbl_user->get_user($usr);
		$data['content']=$this->Tbl_so->get_so_content($id);
		$data['note']=$this->tbl_rptnote->get_note($idnote);
		$this->load->view(self::$link_view.'/so_print_view',$data);
	}
	
	function print_update($id, $count, $tgl){
		$usr = $this->session->userdata('usr_id');
		$this->Tbl_so->update_so($id, $count, $tgl, $usr);
	}
	
	function get_footer(){
		
	}
}
?>