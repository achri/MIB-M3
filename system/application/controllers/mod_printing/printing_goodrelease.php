<?php
class Printing_goodrelease extends MY_Controller{
	public static $link_view, $link_controller, $user_id, $ppn_status, $print_status;
	function Printing_goodrelease(){
		parent::MY_Controller();
		$this->load->model(array('Tbl_goodrelease','Tbl_purchase_type','Tbl_satuan','Tbl_user','tbl_rptnote'));
		$this->load->helper('flexigrid');
		$this->load->library('flexigrid');
		
		$this->lang->load('label','bahasa');
		
		self::$link_controller = 'mod_printing/printing_goodrelease';
		self::$link_view = 'purchase/mod_printing/goodrelease_printing';
		$data['link_controller'] = self::$link_controller;
		$data['link_view'] = self::$link_view;
		
		// TITLE
		if ($this->uri->segment(4) == 1)
			$data['page_title'] = $this->lang->line('print_dup_grl_title');
		else
			$data['page_title'] = $this->lang->line('print_grl_title');
		
		$this->load->vars($data);
	}
	
	function flexigrid_ajax($print_status = 0)
	{
		$ucat = $this->session->userdata('ucat_id');
		$usrid = $this->session->userdata('usr_id');
		//List of all fields that can be sortable. This is Optional.
		//This prevents that a user sorts by a column that we dont want him to access, or that doesnt exist, preventing errors.
		$valid_fields = array('grl_id','grl_no','no_mr','usr_name');
		
		$this->flexigrid->validate_post('grl_id','asc',$valid_fields);

		$records = $this->Tbl_goodrelease->goodrelease_list($ucat, $usrid, $print_status);
		
		$this->output->set_header($this->config->item('json_header'));
		
		/*
		 * Json build WITH json_encode. If you do not have this function please read
		 * http://flexigrid.eyeviewdesign.com/index.php/flexigrid/example#s3 to know how to use the alternative
		 */
		if ($records['record_count'] > 0){
			$i=0;
			foreach ($records['records']->result() as $row){
				$i = $i +1;
				$record_items[] = array($row->grl_id,
				$i,
				$row->grl_no,
				$row->mr_no,
				$row->usr_name,
				'<a href=\'javascript:void(0)\' onclick=\'open_grl('.$row->grl_id.','.$print_status.')\'><img border=\'0\' src=\'./asset/img_source/icon_info.gif\'></a>'
				);
			}
		}else{
			$records['record_count'] += 1;
			$record_items[] = array('empty','empty','empty','empty','empty');
		}
		//Print please
		$this->output->set_output($this->flexigrid->json_build($records['record_count'],$record_items));
	}
	
	function index($print_status = 0) {
		$data['extraHeadContent'] = "<link type=\"text/css\" rel=\"stylesheet\" href=\"". base_url()."asset/css/themes/start/ui.all.css\" />\n";
		$data['extraHeadContent'] .= "<script type=\"text/javascript\" src=\"". base_url()."asset/javascript/jQuery/tables/jquery.jeditable.js\" />\n </script>";
		$data['extraHeadContent'] .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"".base_url()."asset/javascript/jQuery/flexigrid/css/flexigrid.css\" />\n";
		$data['extraHeadContent'] .= "<script type=\"text/javascript\" src=\"". base_url()."asset/javascript/jQuery/flexigrid/js/flexigrid.js\" />\n </script>";
		$data['extraHeadContent'] .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"".base_url()."asset/css/table/DataView.css\" />\n";
		
		$colModel['no'] = array($this->lang->line('ex-brg_flex_col_0'),20,TRUE,'center',0);
		$colModel['grl_no'] = array($this->lang->line('ex-brg_flex_col_1'),100,TRUE,'center',2);
		$colModel['no_mr'] = array($this->lang->line('ex-brg_flex_col_2'),100,TRUE,'center',2);
		$colModel['usr_name'] = array($this->lang->line('ex-brg_flex_col_3'),100,TRUE,'center',2);
		$colModel['opsi'] = array($this->lang->line('ex-brg_flex_col_4'),45, TRUE, 'center',0);
		/*
		 * Aditional Parameters
		 */
		$gridParams = array(
		'width' => '600',
		'height' => 200,
		'rp' => 15,
		'rpOptions' => '[10,15,20,25,40]',
		//'pagestat' => 'Displaying: {from} to {to} of {total} items.',
		'blockOpacity' => 0.5,
		'title' => $this->lang->line('mr_flex_ttl'),
		'showTableToggleBtn' => true
		);
		
		$ucat = $this->session->userdata('ucat_id');
		$usrid = $this->session->userdata('usr_id');
		$records = $this->Tbl_goodrelease->goodrelease_list($ucat, $usrid,$print_status);
		if($records['record_count'] == 0){
			$data['kosong'] = "Belum ada data untuk Diprint";
		}else{
		//Build js
		//View helpers/flexigrid_helper.php for more information about the params on this function
		$grid_js = build_grid_js('flex1',site_url(self::$link_controller."/flexigrid_ajax/".$print_status),$colModel,'grl_id','desc',$gridParams);
		$data['message'] = $this->lang->line('contact_confirm_del');
		$data['js_grid'] = $grid_js;
		$data['kosong'] = "";
		}
		$data['content'] = self::$link_view.'/index_goodrelease_view';
		$this->load->view('index',$data);
	}
	
	function open_grl ($id,$print_status){
		$idnote = 5; // Goodrealeas (krluar barang)
		$note = $this->tbl_rptnote->get_note($idnote);
		$data['notes']='';
		if ($note->num_rows() > 0)
			$data['notes']=$note->row()->note;
		
		$usr = $this->session->userdata('usr_id');
		$data['usr'] = $this->Tbl_user->get_user($usr);
		$data['content']=$this->Tbl_goodrelease->get_grl_content($id);
		
		$data['print_status'] = $print_status;
		
		$this->load->view(self::$link_view.'/goodrelease_print_view',$data);
	}
	
	function print_update($id, $count, $tgl,$print_status){
		
		$usr = $this->session->userdata('usr_id');
		if ($this->Tbl_goodrelease->update_grl($id, $count, $tgl, $usr)):
			if ($print_status == 0) {
				echo 'ok';
			}else {
				$this->open_grl($id,$print_status);
			}
		endif;
	}
}
?>