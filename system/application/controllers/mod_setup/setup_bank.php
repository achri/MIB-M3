<?php
class setup_bank extends MY_Controller{
	public static $link_view, $link_controller;
	function setup_bank()
	{
		parent::MY_Controller();
		$this->load->model('tbl_bank');
		$this->load->helper('flexigrid');
		$this->load->library(array('form_validation','flexigrid'));
		$this->lang->load('mod_master/bank','bahasa');
		
		self::$link_controller = 'mod_setup/setup_bank';
		self::$link_view = 'purchase/mod_setup/bank';
 		$data['link_controller'] = self::$link_controller;
		$data['link_view'] = self::$link_view;
		
		$this->load->vars($data);
	}	
	
	function flexigrid_ajax()
	{
		//List of all fields that can be sortable. This is Optional.
		//This prevents that a user sorts by a column that we dont want him to access, or that doesnt exist, preventing errors.
		$valid_fields = array('bank_id','bank_name_singkat','bank_name_lengkap');
		
		$this->flexigrid->validate_post('bank_id','asc',$valid_fields);

		$records = $this->tbl_bank->get_bank_flex();
		
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
			$record_items[] = array($row->bank_id,
			//$i,
			"<span id='".$row->bank_id."' class='editbank'>".$row->bank_name_singkat."</span>",
			"<span id='".$row->bank_id.".".$row->bank_id."' class='editname'>".$row->bank_name_lengkap."</span>",
			'<a href=\'javascript:void(0)\' onclick=\'deletebank('.$row->bank_id.')\'><img border=\'0\' src=\'./asset/img_source/button_empty.png\'></a>'
			);
		}
		}else{
			$record_items[] = array('empty');
		}
		//Print please
		$this->output->set_output($this->flexigrid->json_build($records['record_count'],$record_items));
	}
	
	
	function deletec()
	{
		$contact_post_array = split(",",$this->input->post('items'));
		
		foreach($contact_post_array as $index => $bank_id)
			if (is_numeric($bank_id) && $bank_id > 1) 
				$this->Mpurchase->delete_bank($bank_id);
						
			
		$error = "Selected countries (id's: ".$this->input->post('items').") deleted with success";

		$this->output->set_header($this->config->item('ajax_header'));
		$this->output->set_output($error);
	}

	function index() {
		$data['extraHeadContent'] = "<link type=\"text/css\" rel=\"stylesheet\" href=\"". base_url()."asset/css/themes/start/ui.all.css\" />\n";
		$data['extraHeadContent'] .= "<script type=\"text/javascript\" src=\"". base_url()."asset/javascript/jQuery/tables/jquery.jeditable.js\" />\n </script>";
		$data['extraHeadContent'] .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"".base_url()."asset/css/table/DataView.css\" />\n";
		$data['extraHeadContent'] .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"".base_url()."asset/css/form/form_view.css\" />\n";
		$data['extraHeadContent'] .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"".base_url()."asset/javascript/jQuery/flexigrid/css/flexigrid.css\" />\n";
		$data['extraHeadContent'] .= "<script type=\"text/javascript\" src=\"". base_url()."asset/javascript/jQuery/flexigrid/js/flexigrid.js\" />\n </script>";
		
		$data['content'] = self::$link_view.'/bank';
		$this->load->view('index',$data);
	}
	
	function bank_flexigrid(){		
		//$colModel['no'] = array($this->lang->line('bank_flex_col_0'),20,TRUE,'center',0);
		$colModel['bank_name_singkat'] = array($this->lang->line('bank_flex_col_1'),150,TRUE,'left',2,FALSE,'edit1');
		$colModel['bank_name_lengkap'] = array($this->lang->line('bank_flex_col_2'),250,TRUE,'left',2,FALSE,'edit2');
		$colModel['action'] = array($this->lang->line('bank_flex_col_3'),30, TRUE, 'center',0);
		
		/*
		 * Aditional Parameters
		 */
		$gridParams = array(
		'width' => 560,
		'height' => 271,
		'blockOpacity' => 0.5,
		'title' => $this->lang->line('bank_flex_ttl'),
		'showTableToggleBtn' => false,
		'singleSelect' => true
		);
		
		//Build js
		//View helpers/flexigrid_helper.php for more information about the params on this function
		$grid_js = build_grid_js('flex1',site_url(self::$link_controller."/flexigrid_ajax"),$colModel,'id','asc',$gridParams);
		$data['message'] = $this->lang->line('bank_confirm_del');
		$data['js_grid'] = $grid_js;
		//$data['content'] = 'bank/bank_list';
		$this->load->view(self::$link_view.'/bank_list',$data);
	}

	function bank_frm() {
		$this->load->view(self::$link_view.'/bank_add');
	}

	function add_bank() {
		/*foreach ($_POST as $key=>$val): 
			echo $key.'='.$val.'<br>'; 
		endforeach;*/
		$this->form_validation->set_rules('name_1', '', 'required');
			if ($this->form_validation->run() == FALSE){
				echo 'error ci : zero length detected';
			}else{
				$usrid = $this->session->userdata('usr_id');
				$bank_name1 = $this->input->post('name_1');
				$bank_name2 = $this->input->post('name_2');
				$cek = $this->tbl_bank->cek_bank($bank_name1);
				if ($cek > 0){
					echo "ada";
				}else{
					echo $bank_name1.' = '.$bank_name2;		
					$this->tbl_bank->insert_bank($bank_name1, $bank_name2, $usrid);
					//$this->load->view('bank/bank');
				}
			}
	}
	
	function bank_update1() {
		$usrid = $this->session->userdata('usr_id');
		$bank_id =  $this->input->post('id');
		$bank_name =  $this->input->post('value');
		$this->tbl_bank->update_bank1($bank_id, $bank_name, $usrid);
		echo $bank_name;
	}
	
	function bank_update2() {
		$usrid = $this->session->userdata('usr_id');
		$bank_id =  $this->input->post('id');
		$bank_name =  $this->input->post('value');
		$id = explode('.',$bank_id);
		$id[0];
		$this->tbl_bank->update_bank2($id[0], $bank_name, $usrid);
		echo $bank_name;
	}
	
	function delete_bank() {
		$bank_id =  $this->input->post('id');
		$this->tbl_bank->delete_bank($bank_id);
		$this->load->view(self::$link_view.'/bank');
	}
}
?>