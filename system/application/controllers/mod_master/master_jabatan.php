<?php
class master_jabatan extends MY_Controller{
	public static $link_view, $link_controller;
	function master_jabatan()
	{
		parent::MY_Controller();
		$this->load->model('tbl_jabatan');
		$this->load->helper('flexigrid');
		$this->load->library(array('flexigrid','form_validation'));
		$this->lang->load('mod_master/jabatan','bahasa');
		
		self::$link_controller = 'mod_master/master_jabatan';
		self::$link_view = 'purchase/mod_master/jabatan';
 		$data['link_controller'] = self::$link_controller;
		$data['link_view'] = self::$link_view;
		
		$this->load->vars($data);
	}	
	
	function flexigrid_ajax()
	{
		//List of all fields that can be sortable. This is Optional.
		//This prevents that a user sorts by a column that we dont want him to access, or that doesnt exist, preventing errors.
		$valid_fields = array('jab_id','jab_name');
		
		$this->flexigrid->validate_post('jab_id','asc',$valid_fields);

		$records = $this->tbl_jabatan->get_jab_flex();
		
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
			$record_items[] = array($row->jab_id,
			//$i,
			"<span id='".$row->jab_id."' class='editjab'>".$row->jab_name."</span>",
			'<a href=\'javascript:void(0)\' onclick=\'deletejab('.$row->jab_id.')\'><img border=\'0\' src=\'./asset/img_source/button_empty.png\'></a>'
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
		$data['extraHeadContent'] .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"".base_url()."asset/javascript/jQuery/flexigrid/css/flexigrid.css\" />\n";
		$data['extraHeadContent'] .= "<script type=\"text/javascript\" src=\"". base_url()."asset/javascript/jQuery/flexigrid/js/flexigrid.js\" />\n </script>";
		
		$data['content'] = self::$link_view.'/jabatan';
		$this->load->view('index',$data);
	
	}

	function jab_flexigrid()
	{
		//$colModel['no'] = array($this->lang->line('jabatan_flex_col_0'),40,TRUE,'center',0);
		$colModel['jab_name'] = array($this->lang->line('jabatan_flex_col_1'),440,TRUE,'left',2,FALSE,'editjab');
		$colModel['action'] = array($this->lang->line('jabatan_flex_col_2'),30, TRUE, 'center',0);
		
		/*
		 * Aditional Parameters
		 */
		$gridParams = array(
		'width' => '560',
		'height' => '271',
		'blockOpacity' => 0.5,
		'title' => $this->lang->line('jabatan_flex_ttl'),
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
		$grid_js = build_grid_js('flex1',site_url(self::$link_controller."/flexigrid_ajax"),$colModel,'id','asc',$gridParams);
		$data['message'] = $this->lang->line('jabatan_confirm_del');
		$data['js_grid'] = $grid_js;
		$this->load->view(self::$link_view.'/jabatan_list',$data);
	}
	
	function jabatan_frm() {
		$this->load->view(self::$link_view.'/jabatan_add');
	}
	
	function jabatan_add() {
		$this->form_validation->set_rules('jabatan', '', 'required');
			if ($this->form_validation->run() == FALSE){
				echo 'error ci : zero length detected';
			}else{
				$usrid = $this->session->userdata('usr_id');
				$jab_name = $this->input->post('jabatan');
				$jab_name = strtoupper($jab_name);
				$cek = $this->tbl_jabatan->cek_jabatan($jab_name);
				if ($cek > 0){
					echo "ada";
				}else{
					//echo $jab_name;
					$jab_id = $this->tbl_jabatan->insert_jabatan($jab_name, $usrid);
					echo $jab_id;
					//$this->load->view('jabatan/jabatan');
				}
			}
	}
	
	function jabatan_update() {
		$usrid = $this->session->userdata('usr_id');
		$jab_id =  $this->input->post('id');
		$jab_name =  $this->input->post('value');
		$jab_name = strtoupper($jab_name);
		$this->tbl_jabatan->update_jabatan($jab_id, $jab_name,$usrid);
		echo $jab_name;
	}
	
	function jabatan_delete() {
		$dep_id =  $this->input->post('id');
		$this->tbl_jabatan->delete_jabatan($dep_id);
		$this->load->view(self::$link_view.'/jabatan');
	}
	
	function jabatan_cek($id) {
		$jab = $this->tbl_jabatan->cek_delete($id);
		$cekjab = $jab['cek1'] + $jab['cek2'];
		echo $cekjab;
	}
}
?>