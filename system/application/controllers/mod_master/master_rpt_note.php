<?php
class Rpt_note extends MY_Controller{
	
	function Rpt_note()
	{
		parent::MY_Controller();
		$this->load->model('tbl_rptnote');
		$this->obj =& get_instance();
	}	
	
	function fckeditorform(){   
	   $data['extraHeadContent'] = "<script type=\"text/javascript\" src=\"". base_url()."asset/javascript/jquery.form.js\" /> </script>";
	   $data['extraHeadContent'] .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"". base_url()."asset/css/themes/start/ui.all.css\" />\n";
		
		$fckeditorConfig = array(
	          'instanceName' => 'content',
	          'BasePath' => base_url().'system/plugins/fckeditor/',
	          'ToolbarSet' => 'Default', //Basic
	          'Width' => '80%',
	          'Height' => '200',
	          'Value' => ''
	          );
	   $this->load->library('fckeditor', $fckeditorConfig);
	  
	   $data['varnote'] = $this->tbl_rptnote->list_rptnote();
	   $data['content'] = 'purchase/mod_printing/rpt_note_view';	
	   $this->load->view('index',$data);
	}

	function add_notes(){
		$var = $this->input->post('varnote'); 
	   	$content = $this->input->post('content');    
		$this->tbl_rptnote->update_note($var, $content);
	}
	
	function set_clearnotes($id){
		$content = "";    
		$this->tbl_rptnote->update_note($id, $content);
	}
}
?>