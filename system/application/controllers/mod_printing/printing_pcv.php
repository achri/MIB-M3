<?php
class Printing_pcv extends MY_Controller{
	public static $link_controller,$link_view,$ppn_status,$print_status;
	function Printing_pcv(){
		parent::MY_Controller();
		$this->load->model(array('Tbl_pcv','Tbl_pr','tbl_rptnote'));
		$this->load->helper('flexigrid');
		$this->load->library('flexigrid');
		
		$this->lang->load('label','bahasa');
		
		// LINK CONTROLLER DAN VIEW
		self::$link_controller = 'mod_printing/printing_pcv';
		self::$link_view = 'purchase/mod_printing/pcv_printing';
		$data['link_controller'] = self::$link_controller;
		$data['link_view'] = self::$link_view;
		
		// TITLE
		if ($this->uri->segment(4) == 1)
			$data['page_title'] = $this->lang->line('print_dup_pcv_title');
		else
			$data['page_title'] = $this->lang->line('print_pcv_title');
		
		$this->load->vars($data);
	}
	
	function flexigrid_ajax($print_status = 0)
	{
		//List of all fields that can be sortable. This is Optional.
		//This prevents that a user sorts by a column that we dont want him to access, or that doesnt exist, preventing errors.
		$valid_fields = array('pcv_id','pcv_no','pr_no','usr_name');
		
		$this->flexigrid->validate_post('pcv_id','asc',$valid_fields);

		$records = $this->Tbl_pcv->list_pcv_print($print_status);
		
		$this->output->set_header($this->config->item('json_header'));
		
		/*
		 * Json build WITH json_encode. If you do not have this function please read
		 * http://flexigrid.eyeviewdesign.com/index.php/flexigrid/example#s3 to know how to use the alternative
		 */
		if ($records['record_count'] > 0){
			$i=0;
			foreach ($records['records']->result() as $row){
				$i = $i +1;
				$record_items[] = array($row->pcv_id,
				//$i,
				$row->pcv_no,
				$row->pr_no,
				$row->usr_name,
				'<a href=\'javascript:void(0)\' onclick=\'open_pcv_print('.$row->pcv_id.','.$print_status.')\'><img border=\'0\' src=\'./asset/img_source/icon_info.gif\'></a>'
				);
			}
		}else{
			$record_items[] = array('empty');
		}
		//Print please
		$this->output->set_output($this->flexigrid->json_build($records['record_count'],$record_items));
	}
	
	function index($print_status = 0) {
		$data['extraHeadContent'] = "<link type=\"text/css\" rel=\"stylesheet\" href=\"". base_url()."asset/css/themes/start/ui.all.css\" />\n";
		$data['extraHeadContent'] .= "<script type=\"text/javascript\" src=\"". base_url()."asset/javascript/jQuery/tables/jquery.jeditable.js\" />\n </script>";
		$data['extraHeadContent'] .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"".base_url()."asset/css/flexigrid/flexigrid.css\" />\n";
		$data['extraHeadContent'] .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"".base_url()."asset/css/table/DataView.css\" />\n";
		$data['extraHeadContent'] .= "<script type=\"text/javascript\" src=\"". base_url()."asset/javascript/flexigrid.js\" />\n </script>";
		$data['extraHeadContent'] .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"".base_url()."asset/javascript/jQuery/flexigrid/css/flexigrid.css\" />\n";
		$data['extraHeadContent'] .= "<script type=\"text/javascript\" src=\"". base_url()."asset/javascript/jQuery/flexigrid/js/flexigrid.js\" />\n </script>";
		
		//$colModel['no'] = array($this->lang->line('pcvcetak_flex_col_0'),20,TRUE,'center',0);
		$colModel['pcv_no'] = array($this->lang->line('pcvcetak_flex_col_1'),100,TRUE,'center',2);
		$colModel['pr_no'] = array($this->lang->line('pcvcetak_flex_col_2'),100,TRUE,'center',2);
		$colModel['usr_name'] = array($this->lang->line('pcvcetak_flex_col_3'),100,TRUE,'center',2);
		$colModel['opsi'] = array($this->lang->line('pcvcetak_flex_col_4'),45, TRUE, 'center',0);
		/*
		 * Aditional Parameters
		 */
		$gridParams = array(
		'width' => '580',
		'height' => 271,
		'blockOpacity' => 0.5,
		'title' => $this->lang->line('pcvcetak_flex_ttl'),
		'showTableToggleBtn' => false,
		'singleSelect' => true
		);
		
		$records = $this->Tbl_pcv->get_num_flex($print_status);
		if ($records['record_count'] == 0){
			$data['kosong'] = "Belum ada data untuk diprint";
		}else{
		//Build js
		//View helpers/flexigrid_helper.php for more information about the params on this function
		$grid_js = build_grid_js('flex1',site_url(self::$link_controller."/flexigrid_ajax/".$print_status),$colModel,'pcv_id','desc',$gridParams);
		$data['js_grid'] = $grid_js;
		$data['kosong'] = "";
		}
		$data['content'] = self::$link_view.'/index_pcv_view';
		$this->load->view('index',$data);
	}
	
	function open_pcv_print($id,$print_status){
		$idnote = 6; // untuk print pettycash
		$note = $this->tbl_rptnote->get_note($idnote);
		$data['notes']='';
		if($note->num_rows() > 0)
			$data['notes']=$note->row()->note;
		
		$usr = $this->session->userdata('usr_id');
		$data['content']=$this->Tbl_pcv->get_pcv_dtlprint($usr,$id);
		
		$data['print_status'] = $print_status;
		
		$this->load->view(self::$link_view.'/pcv_print_view',$data);
		//echo $usr."-".$id;
	}
	
	function print_update($id, $count, $tgl, $print_status = 0){
		$usr = $this->session->userdata('usr_id');
		if ($this->Tbl_pcv->print_pcv($id, $count, $tgl, $usr)):
			if ($print_status == 0):
				echo 'ok';
			else:
				$this->open_pcv_print($id,$print_status);
			endif;
		endif;
	}
}
?>