<?php
class setup_satuan extends MY_Controller{
	public static $link_view, $link_controller;
	function setup_satuan()
	{
		parent::MY_Controller();
		$this->load->model('tbl_satuan');
		$this->load->helper('flexigrid');
		$this->lang->load('mod_master/satuan','bahasa');
		$this->load->library('flexigrid');
		
		self::$link_controller = 'mod_setup/setup_satuan';
		self::$link_view = 'purchase/mod_setup/satuan';
 		$data['link_controller'] = self::$link_controller;
		$data['link_view'] = self::$link_view;
		
		$data['link_controller_term'] = 'mod_setup/setup_term';
		
		$this->load->vars($data);
		
	}	
	
	function flexigrid_ajax()
	{
		//List of all fields that can be sortable. This is Optional.
		//This prevents that a user sorts by a column that we dont want him to access, or that doesnt exist, preventing errors.
		$valid_fields = array('satuan_id','satuan_name');
		
		$this->flexigrid->validate_post('satuan_id','asc',$valid_fields);

		$records = $this->tbl_satuan->get_satuan_flex();
		
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
			$record_items[] = array($row->satuan_id,
			//$i,
			"<span id='".$row->satuan_id."' class='editsatuan'>".$row->satuan_name."</span>",
			"<span id='digit_".$row->satuan_id."' class='editsatuan'>".$row->satuan_format."</span>",
			'<a href=\'javascript:void(0)\' onclick=\'deletesatuan('.$row->satuan_id.')\'><img border=\'0\' src=\'./asset/img_source/button_empty.png\'></a>'
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
		
		$data['content'] = self::$link_view.'/satuan';
		$this->load->view('index',$data);
	
	}
	
	function satuan_flexigrid(){		
		//$colModel['no'] = array($this->lang->line('satuan_flex_col_0'),30,TRUE,'center',0);
		$colModel['satuan_name'] = array($this->lang->line('satuan_flex_col_1'),230,TRUE,'left',2,FALSE,'edit');
		$colModel['satuan_format'] = array($this->lang->line('satuan_flex_col_3'),60,TRUE,'center',0,FALSE,'edit_digit');
		$colModel['action'] = array($this->lang->line('satuan_flex_col_2'),50, TRUE, 'center',0);
		
		/*
		 * Aditional Parameters
		 */
		$gridParams = array(
		'width' => 400,
		'height' => 271,
		'blockOpacity' => 0.5,
		'title' => $this->lang->line('satuan_flex_ttl'),
		'showTableToggleBtn' => false,
		'singleSelect' => true
		);
		
		/*
		 * 0 - display name
		 * 1 - bclass
		 * 2 - onpress
		 */
		/*$buttons[] = array('Delete','empty','test');
		$buttons[] = array('separator');
		$buttons[] = array('Select All','add','test');
		$buttons[] = array('DeSelect All','delete','test');
		$buttons[] = array('separator');*/

		
		//Build js
		//View helpers/flexigrid_helper.php for more information about the params on this function
		$grid_js = build_grid_js('flex1',site_url(self::$link_controller."/flexigrid_ajax"),$colModel,'satuan_name','asc',$gridParams);
		$data['message'] = $this->lang->line('satuan_confirm_del');
		$data['js_grid'] = $grid_js;
		//$data['content'] = 'bank/bank_list';
		
		
		
		$this->load->view(self::$link_view.'/satuan_list',$data);
	}

	function satuan_frm() {
		$this->load->view(self::$link_view.'/satuan_add');
	}
	
	function add_satuan() {
		$usrid = $this->session->userdata('usr_id');
		$name = $this->input->post('satuan');
		$format = $this->input->post('format');
		$cek = $this->tbl_satuan->cek_satuan($name);
		if ($cek > 0){
			echo "ada";
		}else{
			echo $name;
			$satuan = strtoupper($name);
			$format = $format;
			$this->tbl_satuan->insert_satuan($satuan,$format,$usrid);
			//$this->load->view('satuan/satuan');
			//echo strtoupper($name);
		}
	}
	
	function satuan_update() {
		$usrid = $this->session->userdata('usr_id');
		$satuan_id =  $this->input->post('id');
		$satuan_name =  $this->input->post('value');
		$satuan = strtoupper($satuan_name);
		$this->tbl_satuan->update_satuan($satuan_id, $satuan, $usrid);
		echo strtoupper($satuan_name);
	}
	
	function satuan_digit_update() {
		$usrid = $this->session->userdata('usr_id');
		$satuan_id = $this->input->post('id');
		$satuan_id = explode('_',$satuan_id);
		$satuan_format =  $this->input->post('value');
		//$satuan = strtoupper($satuan_format);
		$this->tbl_satuan->update_satuan_digit($satuan_id[1], $satuan_format, $usrid);
		echo strtoupper($satuan_format);
	}
	
	function delete_satuan() {
		$satuan_id =  $this->input->post('id');
		$this->tbl_satuan->delete_satuan($satuan_id);
		$this->load->view(self::$link_view.'/satuan');
	}
}
?>