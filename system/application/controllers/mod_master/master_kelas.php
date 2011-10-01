<?php
class master_kelas extends MY_Controller{
	public static $link_view, $link_controller, $link_view_category, $link_controller_category;
	function master_kelas()
	{
		parent::MY_Controller();
		$this->load->model('Tbl_category');	
		$this->load->library('form_validation');
		$this->load->helper('html');
		
		$this->lang->load('mod_master/kelas','bahasa');
		
		self::$link_controller_category = 'mod_master/master_category';
		self::$link_view_category = 'purchase/mod_master/category';
 		$data['link_controller_category'] = self::$link_controller_category;
		$data['link_view_category'] = self::$link_view_category;
		
		self::$link_controller = 'mod_master/master_kelas';
		self::$link_view = 'purchase/mod_master/class';
		$data['link_controller'] = self::$link_controller;
		$data['link_view'] = self::$link_view;
				
		$this->load->vars($data);
	}	

	function index() {
		$data['extraHeadContent'] = "<link type=\"text/css\" rel=\"stylesheet\" href=\"". base_url()."asset/css/themes/start/ui.all.css\" />\n";
		$data['extraHeadContent'] .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"". base_url()."asset/css/kelascss/kelas_add1.css\" />\n";
		$data['extraHeadContent'] .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"". base_url()."asset/css/table/jquery.treeTable.css\" />\n";		
		$data['extraHeadContent'] .= "<script type=\"text/javascript\" src=\"". base_url()."asset/javascript/jQuery/tables/jquery.treeTable.js\" />\n </script>";
		$data['extraHeadContent'] .= "<script type=\"text/javascript\" src=\"". base_url()."asset/javascript/jQuery/tables/jquery.jeditable.js\" />\n </script>";
		$data['extraHeadContent'] .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"".base_url()."asset/css/table/DataView.css\" />\n";
		$data['content'] = self::$link_view.'/index_kelas_view';
		$this->load->view('index',$data);
	
	}
	
	function kelas_list($parent) {
		$data['get_list'] = $this->Tbl_category->category_get($parent);
		$this->load->view(self::$link_view.'/category_list_view',$data);
	}

	function kelas_frm() {
		$catParent = '0';
		$record['get_cat'] = $this->Tbl_category->category_get($catParent);
		$this->load->view(self::$link_view.'/kelas_add_view',$record);
	}
	
	function kelas_add() {
		$this->form_validation->set_rules('kelas', '', 'required');
		if ($this->form_validation->run() == FALSE){
			echo 'error ci : zero length detected';
		}else{
			$cat_level = '2';
			$cat_name = $this->input->post('kelas');
			$cat_name = strtoupper($cat_name);
			$usrid = $this->session->userdata('usr_id');
			$cek = $this->Tbl_category->category_cek_kelas($cat_name, $cat_level);
			if ($cek > 0){
				echo "ada";
			}else{
				$get_cat_id = $this->input->post('cat_id');
				$get_cat_code = $this->input->post('cat_code');
				$cat_code = $this->Tbl_category->category_num($get_cat_id);
				$cat_code = substr($cat_code,3,2);
				if ($cat_code == ''){
					$cat_code = 0;
				}
				$cat_code++;
				$cat_code = str_pad($cat_code, 2, "0", STR_PAD_LEFT);
				$get_cat_code = $get_cat_code.'.'.$cat_code;
				$detail = '0';
				$this->Tbl_category->category_insert($get_cat_code, $get_cat_id, $cat_level, $cat_name, $detail,$usrid);
				$data['get_list'] = $this->Tbl_category->category_get($get_cat_id);
				$this->load->view(self::$link_view.'/category_list_view',$data);
			}
		}
	}

	function kelas_delete(){
		$cat_id =  $this->input->post('code');
		$this->Tbl_category->category_delete($cat_id);
		$this->load->view(self::$link_view.'/index_kelas_view');
	}

	function class_tree()
	{
		$data['get1'] = $this->Tbl_category->set_level1();
		$this->load->view(self::$link_view.'/kelas_list_view',$data);
	}
	
	function dynatree_lazy($cat_id = 0) {
		echo $this->Tbl_category->dynatree_set($cat_id,array('1','2'));
	}
	
	function class_list($class_id) {
		if ($class_id == 'root')
			$class_id = 0;
		$data['cat_list'] = $this->db->query("select * from prc_master_category where cat_id = $class_id and cat_level=2");
		$this->load->view(self::$link_view.'/kelas_list_form',$data);
	}
}
?>