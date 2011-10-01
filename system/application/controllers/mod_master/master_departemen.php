<?php
class master_departemen extends MY_Controller{
	public static $link_view, $link_controller;
	function master_departemen()
	{
		parent::MY_Controller();
		$this->load->model('tbl_departemen');
		$this->load->helper('flexigrid');
		$this->load->library(array('form_validation','flexigrid'));
		$this->lang->load('mod_master/departement','bahasa');
		
		self::$link_controller = 'mod_master/master_departemen';
		self::$link_view = 'purchase/mod_master/departemen';
 		$data['link_controller'] = self::$link_controller;
		$data['link_view'] = self::$link_view;
		
		$this->load->vars($data);
	}	
	
	function flexigrid_ajax()
	{
		//List of all fields that can be sortable. This is Optional.
		//This prevents that a user sorts by a column that we dont want him to access, or that doesnt exist, preventing errors.
		$valid_fields = array('dep_id','dep_name');
		
		$this->flexigrid->validate_post('dep_id','asc',$valid_fields);

		$records = $this->tbl_departemen->get_dep_flex();
		
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
			$record_items[] = array($row->dep_id,
			//$i,
			"<span id='".$row->dep_id."' class='editdep'>".$row->dep_name."</span>",
			'<a href=\'javascript:void(0)\' onclick=\'deletedep('.$row->dep_id.')\'><img border=\'0\' src=\'./asset/img_source/button_empty.png\'></a>'
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
		
		$data['content'] = self::$link_view.'/departemen';
		$this->load->view('index',$data);
	}
	
	function dep_flexigrid() {
		//$colModel['no'] = array($this->lang->line('dep_flex_col_0'),40,TRUE,'center',0);
		$colModel['dep_name'] = array($this->lang->line('dep_flex_col_1'),400,TRUE,'left',2,FALSE,'editdep');
		$colModel['actions'] = array($this->lang->line('dep_flex_col_2'),40, FALSE, 'center',0);
		
		$gridParams = array(
		'width' => 560,
		'height' => 271,
		'blockOpacity' => 0.5,
		'title' => $this->lang->line('dep_flex_ttl'),
		'showTableToggleBtn' => false,
		'singleSelect' => true
		);
		
		$records = $this->tbl_departemen->cek_dep();
		if ($records['record_count'] == 0){
			$data['kosong'] = $this->lang->line('dep_data_kosong');
		}else{
	
		$grid_js = build_grid_js('flex1',site_url(self::$link_controller."/flexigrid_ajax"),$colModel,'id','asc',$gridParams);
		$data['message'] = $this->lang->line('dep_ajax_confirm_del');
		$data['message_error'] = $this->lang->line('dep_ajax_del_error');
		$data['js_grid'] = $grid_js;
		$data['kosong'] = "";
		}
		
		$data['keterangan'] = $this->lang->line('dep_flexi_keterangan');
		$this->load->view(self::$link_view.'/departemen_list', $data);
	}

	function dep_frm() {
		$this->load->view(self::$link_view.'/departemen_add');
	}

	function dep_add() {
		$this->form_validation->set_rules('departemen', '', 'required');
			if ($this->form_validation->run() == FALSE){
				echo 'error ci : zero length detected';
			}else{
				$usrid = $this->session->userdata('usr_id');
				$dep_name = $this->input->post('departemen');
				$dep_name = strtoupper($dep_name);
				$cek = $this->tbl_departemen->cek_departemen($dep_name);
				if ($cek > 0){
					echo "ada";
				}else{
					//echo $dep_name;		
					$dep_id = $this->tbl_departemen->insert_dep($dep_name,$usrid);
					echo $dep_id;
					//$this->load->view('departemen/departemen');
				}
			}
	}
	
	function dep_update() {
		$usrid = $this->session->userdata('usr_id');
		$dep_id =  $this->input->post('id');
		$dep_name =  $this->input->post('value');
		$dep_name = strtoupper($dep_name);
		$this->tbl_departemen->update_dep($dep_id, $dep_name,$usrid);
		echo $dep_name;
	}
	
	function dep_delete() {
		$dep_id =  $this->input->post('id');
		$this->tbl_departemen->delete_dep($dep_id);
		$this->load->view(self::$link_view.'/departemen');
	}
	
	function cek_delete($id) {
		$dep = $this->tbl_departemen->cek_delete($id);
		$cekdep = $dep['cek1'] + $dep['cek2'];
		echo $cekdep;
	}
}
?>