<?php
class setup_term extends MY_Controller{
	public static $link_view, $link_controller;
	function setup_term()
	{
		parent::MY_Controller();
		$this->load->model('tbl_term');		
		$this->load->helper('flexigrid');
		$this->load->library(array('form_validation','flexigrid'));
		$this->lang->load('mod_master/lama_kredit','bahasa');
		
		self::$link_controller = 'mod_setup/setup_term';
		self::$link_view = 'purchase/mod_setup/credit_term';
 		$data['link_controller'] = self::$link_controller;
		$data['link_view'] = self::$link_view;
		
		$this->load->vars($data);
	}	
	
	function flexigrid_ajax()
	{
		//List of all fields that can be sortable. This is Optional.
		//This prevents that a user sorts by a column that we dont want him to access, or that doesnt exist, preventing errors.
		$valid_fields = array('term_id','term_id_name','term_name','term_days','term_discont');
		
		$this->flexigrid->validate_post('term_id','asc',$valid_fields);

		$records = $this->tbl_term->get_term_flex();
		
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
			$record_items[] = array($row->term_id,
			//$i,
			"<span id='".$row->term_id."' class='edit'>".$row->term_id_name."</span>",
			"<span id='".$row->term_id.".".$row->term_id."' class='edit'>".$row->term_name."</span>",
			"<span id='".$row->term_id.".".$row->term_id.".".$row->term_id."' class='edit'>".$row->term_days."</span>",
			"<span id='".$row->term_id.".".$row->term_id.".".$row->term_id.".".$row->term_id."' class='edit'>".$row->term_discount."</span>",
			"<a href='javascript:void(0)' onclick=deleteterm('".$row->term_id."')><img border='0' src='./asset/img_source/button_empty.png'></a>"
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
		$data['extraHeadContent'] .= "<script type=\"text/javascript\" src=\"". base_url()."asset/javascript/jQuery/form/jquery.autoNumeric.js\" />\n </script>";
		$data['extraHeadContent'] .= "<script type=\"text/javascript\" src=\"". base_url()."asset/javascript/helper/autoNumeric.js\" />\n </script>";
		$data['content'] = self::$link_view.'/credit';
		$this->load->view('index',$data);
	
	}
	
	function term_flexigrid(){		
		//$colModel['no'] = array($this->lang->line('term_flex_col_0'),30,TRUE,'center',0);
		$colModel['term_id_name'] = array($this->lang->line('term_flex_col_1'),100,TRUE,'left',2,FALSE,'edit');
		$colModel['term_name'] = array($this->lang->line('term_flex_col_2'),170,TRUE,'left',2,FALSE,'edit');
		$colModel['term_days'] = array($this->lang->line('term_flex_col_3'),100,TRUE,'left',2,FALSE,'edit');
		$colModel['term_discount'] = array($this->lang->line('term_flex_col_4'),100,TRUE,'left',2,FALSE,'edit');
		$colModel['action'] = array($this->lang->line('term_flex_col_5'),30, TRUE, 'center',0);
		
		$gridParams = array(
		'width' => 600,
		'height' => 271,
		'blockOpacity' => 0.5,
		'title' => $this->lang->line('term_flex_ttl'),
		'showTableToggleBtn' => false,
		'singleSelect' => true
		);
		
		//Build js
		//View helpers/flexigrid_helper.php for more information about the params on this function
		$grid_js = build_grid_js('flex1',site_url(self::$link_controller."/flexigrid_ajax"),$colModel,'id','asc',$gridParams);
		$data['message'] = $this->lang->line('term_confirm_del');
		$data['js_grid'] = $grid_js;
		//$data['content'] = 'bank/bank_list';
		$this->load->view(self::$link_view.'/credit_list',$data);
	}

	function term_frm() {
		$this->load->view(self::$link_view.'/credit_add');
	}
	
	function add_term(){
		$this->form_validation->set_rules('name', '', 'required');
			if ($this->form_validation->run() == FALSE){
				echo 'error ci : zero length detected';
			}else{
				$usrid = $this->session->userdata('usr_id');
				$name = $this->input->post('name');
				$desc = $this->input->post('desc');
				$days = $this->input->post('days');
				$disct = $this->input->post('disct');
				$cek = $this->tbl_term->cek_term($name);
				if ($cek > 0){
					echo "ada";
				}else{
					$id_nama =  $this->lang->line('ajax_dialog_tambah_id');
					$keterangan = $this->lang->line('ajax_dialog_tambah_keterangan');
					$lama_bayar = $this->lang->line('ajax_dialog_tambah_lama_bayar');
					$lama_diskon = $this->lang->line('ajax_dialog_tambah_diskon');
										
					//bwt nampilin di diolog msg klo dah bisa di tambahin
					//echo $id_nama.$name.'<br>'.$keterangan.$desc.'<br>'.$lama_bayar.$days.'<br>'.$lama_diskon.$disct;
					$term_id = $this->tbl_term->insert_term($name, $desc, $days, $disct,$usrid);
					echo $term_id;
					//$this->load->view('credit_term/credit');
				}
			}
	}
	
	function term_update() {
		$usrid = $this->session->userdata('usr_id');
		$id =  $this->input->post('id');
		$name =  $this->input->post('value');
		$set = substr_count($id, '.');
		if ($set == 0){
			$upd = 'term_id_name';
			$this->tbl_term->update_term($id, $name, $upd, $usrid);
			echo $name;
		}else if ($set == 1){
			$id = explode('.', $id);
			$id[0];
			$upd = 'term_name';
			$this->tbl_term->update_term($id[0], $name, $upd, $usrid);
			echo $name;
		}else if ($set == 2){
			$id = explode('.', $id);
			$id[0];
			$upd = 'term_days';
			$this->tbl_term->update_term($id[0], $name, $upd, $usrid);
			echo $name;
		}else{
			$id = explode('.', $id);
			$id[0];
			$upd = 'term_discount';
			$this->tbl_term->update_term($id[0], $name, $upd, $usrid);
			echo $name;
		}
	}
	
	function delete_term() {
		$id =  $this->input->post('id');
		if ($this->tbl_term->delete_term($id))
			$this->load->view(self::$link_view.'/credit');
	}
}
?>