<?php
class master_category extends MY_Controller{
	public static $link_view, $link_controller;
	function master_category()
	{
		parent::MY_Controller();
		$this->load->model('Tbl_category');
		$this->load->library(array('form_validation','treeview'));
		$this->load->helper('html');
			
		$this->lang->load('mod_master/kategori','bahasa');
		$this->lang->load('general','bahasa');
		
		self::$link_controller = 'mod_master/master_category';
		self::$link_view = 'purchase/mod_master/category';
 		$data['link_controller'] = self::$link_controller;
		$data['link_view'] = self::$link_view;
		
		$this->load->vars($data);
		
	}
	
	function index() {
		$data['extraHeadContent'] = "<link type=\"text/css\" rel=\"stylesheet\" href=\"". base_url()."asset/css/themes/start/ui.all.css\" />\n";
		$data['extraHeadContent'] .= "<script type=\"text/javascript\" src=\"". base_url()."asset/javascript/jQuery/tables/jquery.jeditable.js\" />\n </script>";
		$data['extraHeadContent'] .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"".base_url()."asset/css/table/DataView.css\" />\n";
		
		$data['extraHeadContent'] .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"". base_url()."asset/javascript/jQuery/dynatree/skin-vista/ui.dynatree.css\" />\n";
		$data['extraHeadContent'] .= "<script type=\"text/javascript\" src=\"". base_url()."asset/javascript/jQuery/dynatree/jquery.dynatree.js\" />\n </script>";
		
		$data['content'] = self::$link_view.'/index_category_view';
		$this->load->view('index',$data);
	}
	
	function treecat_root() {
		echo $this->treeview->generate_tree('',true,false);
	}
	
	function treecat_node($key) {
		echo $this->treeview->generate_tree($key);
	}
	
	//function list_cat() {
	function cat_list() {
		$catParent = '0';
		$data['get_list'] = $this->Tbl_category->category_get($catParent);
		$this->load->view(self::$link_view.'/category_list_view',$data);
	}
	
	function cat_frm() {
		$this->load->view(self::$link_view.'/category_add_view');
	}
	
	function cat_add() {
		$data['extraHeadContent'] = "<link type=\"text/css\" rel=\"stylesheet\" href=\"". base_url()."asset/css/themes/start/ui.all.css\" />\n";
		$this->form_validation->set_rules('kategori', '', 'required');
			if ($this->form_validation->run() == FALSE){
				echo 'error ci : zero length detected';
			}else{
				$usrid = $this->session->userdata('usr_id');
				$cat_name = $this->input->post('kategori');
				$cat_name = strtoupper($cat_name);
				$cat_parent = '0';
				$cat_level = '1';
				$cek = $this->Tbl_category->category_cek_kelas($cat_name, $cat_level);
				if ($cek > 0){
					echo "ada";
				}else{
				
					$numcode = $this->Tbl_category->category_num($cat_parent);
					$numcode++;
					$cat_code = str_pad($numcode, 2, "0", STR_PAD_LEFT);
					$detail = '0';
					$this->Tbl_category->category_insert($cat_code, $cat_parent, $cat_level, $cat_name, $detail, $usrid);
					
						echo $cat_name;
					//$this->load->view('purchase/mod_master/category/index_category_view',$data);
				}
			}
	}
	
	function cat_update($stats) {
		$cat_id =  $this->input->post('id');
		$cat_name =  $this->input->post('value');
		$cat_name = strtoupper($cat_name);
		$usrid = $this->session->userdata('usr_id');
		$this->Tbl_category->category_update($cat_id, $cat_name,$usrid);
		if ($stats == 'grup'):
			$this->load->view('purchase/mod_master/grup/index_grup_view');
		elseif ($stats == 'kelas'):
			$this->load->view('purchase/mod_master/class/index_kelas_view');
		elseif ($stats == 'kategori'):
			$this->load->view('purchase/mod_master/category/index_category_view');
		else:
			echo $cat_name;
		endif;
	}

	function cat_cek_delete($cat_id) {
		//$cat_id =  $this->input->post('id');
		$cat_row = $this->Tbl_category->category_num_kelas($cat_id);
		if ($cat_row > 0){
			echo "ada";
		}
	}
	
	function cat_delete(){
		$cat_id =  $this->input->post('code');
		$this->Tbl_category->category_delete($cat_id);
		$this->load->view(self::$link_view.'/index_category_view');
	}
	
	function dynatree_lazy($cat_id = 0) {
		echo $this->Tbl_category->dynatree_set($cat_id,array('1'));
	}
	
	function class_list($class_id) {
		if ($class_id == 'root')
			$class_id = 0;
		$data['cat_list'] = $this->db->query("select * from prc_master_category where cat_id = $class_id and cat_level=1");
		$this->load->view(self::$link_view.'/category_list_form',$data);
	}
}
?>