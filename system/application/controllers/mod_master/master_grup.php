<?php
class master_grup extends MY_Controller{
	public static $link_view, $link_controller, $link_view_category, $link_controller_category, $link_view_class, $link_controller_class;
	function master_grup()
	{
		parent::MY_Controller();
		$this->load->model('Tbl_category');	
		$this->load->helper('html');
		$this->load->library('form_validation');
		
		$this->lang->load('mod_master/grup','bahasa');
		
		self::$link_controller_category = 'mod_master/master_category';
		self::$link_view_category = 'purchase/mod_master/category';
 		$data['link_controller_category'] = self::$link_controller_category;
		$data['link_view_category'] = self::$link_view_category;
		
		self::$link_controller_class = 'mod_master/master_kelas';
		self::$link_view_class = 'purchase/mod_master/class';
		$data['link_controller_class'] = self::$link_controller_class;
		$data['link_view_class'] = self::$link_view_class;
		
		self::$link_controller = 'mod_master/master_grup';
		self::$link_view = 'purchase/mod_master/grup';
		$data['link_controller'] = self::$link_controller;
		$data['link_view'] = self::$link_view;
				
		$this->load->vars($data);
	}	

	function index() {
		$data['extraHeadContent'] = "<link type=\"text/css\" rel=\"stylesheet\" href=\"". base_url()."asset/css/themes/start/ui.all.css\" />\n";
		$data['extraHeadContent'] .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"". base_url()."asset/css/kelascss/kelas_add.css\" />\n";
		$data['extraHeadContent'] .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"". base_url()."asset/css/table/jquery.treeTable.css\" />\n";
		$data['extraHeadContent'] .= "<script type=\"text/javascript\" src=\"". base_url()."asset/javascript/jQuery/tables/jquery.treeTable.js\" />\n </script>";
		$data['extraHeadContent'] .= "<script type=\"text/javascript\" src=\"". base_url()."asset/javascript/jQuery/tables/jquery.jeditable.js\" />\n </script>";
		$data['extraHeadContent'] .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"".base_url()."asset/css/table/DataView.css\" />\n";
		$data['content'] = self::$link_view.'/index_grup_view';
		$this->load->view('index',$data);
	
	}

	function grup_frm() {
		$catParent = '0';
		$record['get_cat'] = $this->Tbl_category->category_get($catParent);
		$this->load->view(self::$link_view.'/grup_add_view',$record);
	}

	function add_grup() {
				$usrid = $this->session->userdata('usr_id');
				$get_cat_id2 = $this->input->post('cat_id2');
				$get_cat_code = $this->input->post('cat_code');
				$name = $this->input->post('grup');
				$name = strtoupper($name);
				$detail = $this->input->post('detail');
				$cat_level = '3';
				if ($name == ''){
					echo "null";
				}else if ($detail == ''){
					echo "no";
				}else{
				$cat_name = $this->Tbl_category->category_cek_kelas($name, $cat_level);
					//if ($cat_name > 0){
						//echo "ada";
					//}else{
						$cat_code = $this->Tbl_category->category_num($get_cat_id2);
						$cat_code = substr($cat_code,6,2);
						if ($cat_code == ''){
							$cat_code = 0;
						}
						$cat_code++;
						$cat_code = str_pad($cat_code, 2, "0", STR_PAD_LEFT);
						$get_cat_code = $get_cat_code.'.'.$cat_code;
						$cat_level = '3';
						$this->Tbl_category->category_insert($get_cat_code, $get_cat_id2, $cat_level, $name, $detail,$usrid);
						$data['get_list'] = $this->Tbl_category->category_get($get_cat_id2);
						$this->load->view(self::$link_view_class.'/category_list_view',$data);
					//}
				}
	}
	
	function grup_cek_delete() {
		$id =  $this->input->post('id');
		$catpro = $this->Tbl_category->cek_produk($id)->num_rows();
		echo $catpro;
	}
	
	function grup_delete(){
		$cat_id =  $this->input->post('code');
		$this->Tbl_category->category_delete($cat_id);
		$this->load->view(self::$link_view.'/index_grup_view');
	}
	
	function grup_tree()
	{
		$data['get1'] = $this->Tbl_category->set_level1();
		$this->load->view(self::$link_view.'/grup_list_view',$data);
	}
	
	function dynatree_lazy($cat_id = 0) {
		echo $this->Tbl_category->dynatree_set($cat_id);
	}
	
	function grup_list($grup_id) {
		if ($grup_id == 'root')
			$grup_id = 0;
		$data['cat_list'] = $this->db->query("select * from prc_master_category where cat_id = $grup_id and cat_level=3");
		$this->load->view(self::$link_view.'/grup_list_form',$data);
	}
}
?>