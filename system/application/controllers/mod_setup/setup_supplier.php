<?php
class setup_supplier extends MY_Controller{
	public static $link_view, $link_controller;
	function setup_supplier()
	{
		parent::MY_Controller();
		$this->load->model(array('tbl_term','tbl_bank','tbl_category','tbl_legal','tbl_negara','tbl_supplier','tbl_sup_category','tbl_sup_bank','tbl_kota','tbl_provinsi'));
		$this->load->helper('flexigrid');
		$this->lang->load('mod_master/pemasok','bahasa');
		$this->load->library(array('flexigrid'));
		
		self::$link_controller = 'mod_setup/setup_supplier';
		self::$link_view = 'purchase/mod_setup/supplier';
 		$data['link_controller'] = self::$link_controller;
		$data['link_view'] = self::$link_view;
		
		$data['link_controller_term'] = 'mod_setup/setup_term';
		
		$this->load->vars($data);
		
	}	
	
	function flexigrid_ajax()
	{
		//List of all fields that can be sortable. This is Optional.
		//This prevents that a user sorts by a column that we dont want him to access, or that doesnt exist, preventing errors.
		$valid_fields = array('sup_id','sup_name','legal_id','sup_npwp','sup_address','sup_phone1','sup_fax','sup_email','sup_status');
		
		$this->flexigrid->validate_post('sup_id','asc',$valid_fields);

		$records = $this->tbl_supplier->get_supp_flex();
		
		$this->output->set_header($this->config->item('json_header'));
		
		/*
		 * Json build WITH json_encode. If you do not have this function please read
		 * http://flexigrid.eyeviewdesign.com/index.php/flexigrid/example#s3 to know how to use the alternative
		 */
		if ($records['record_count'] > 0){
		$i = 0;
		foreach ($records['records']->result() as $row)
		{
			$legal = $this->tbl_legal->get_legal($row->legal_id)->row();
			
			if ($row->sup_status == 0): $status = 'Non Aktif';
			elseif ($row->sup_status == 1): $status = 'Aktif';
			else: $status = 'Tunggu'; endif;
			
			$i = $i + 1;
			$record_items[] = array($row->sup_id,
			//$i,
			$row->sup_name.', '.$legal->legal_name,
			$row->sup_npwp,
			'<span style=\'color:#ff4400\'>'.addslashes($row->sup_address).'</span>',
			$row->sup_phone1,
			$row->sup_fax,
			$row->sup_email,
			$status,
			'<a href=\'javascript:void(0)\' onclick=\'editsup('.$row->sup_id.')\'><img border=\'0\' src=\'./asset/img_source/button_edit.png\'></a>'
	//bwt ngehapus dimatiin dulu,komanya jgn lp// '<a href=\'javascript:void(0)\' onclick=\'deletesup('.$row->sup_id.')\'><img border=\'0\' src=\'./asset/img_source/button_empty.png\'></a>'
			);
		}
		}else{
			$record_items[] = array($this->lang->line('flex_empty'));
		}
		//Print please
		$this->output->set_output($this->flexigrid->json_build($records['record_count'],$record_items));
	}
	

	function index() {
		$data['extraHeadContent'] = "<link type=\"text/css\" rel=\"stylesheet\" href=\"". base_url()."asset/css/themes/start/ui.all.css\" />\n";
		$data['extraHeadContent'] .= "<script type=\"text/javascript\" src=\"". base_url()."asset/javascript/jQuery/tables/jquery.jeditable.js\" />\n </script>";
		$data['extraHeadContent'] .= "<script type=\"text/javascript\" src=\"". base_url()."asset/javascript/jquery.meio.mask.js\" />\n </script>";
		$data['extraHeadContent'] .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"".base_url()."asset/css/table/DataView.css\" />\n";
		$data['extraHeadContent'] .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"".base_url()."asset/css/form/form_view.css\" />\n";
		$data['extraHeadContent'] .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"".base_url()."asset/css/supplier/supp_sort.css\" />\n";
		$data['extraHeadContent'] .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"".base_url()."asset/javascript/jQuery/flexigrid/css/flexigrid.css\" />\n";
		$data['extraHeadContent'] .= "<script type=\"text/javascript\" src=\"". base_url()."asset/javascript/jQuery/flexigrid/js/flexigrid.js\" />\n </script>";
		$data['extraHeadContent'] .= "<script type=\"text/javascript\" src=\"". base_url()."asset/javascript/jQuery/content/jquery.blockui.js\" />\n </script>";

		$data['content'] = self::$link_view.'/supplier';
		$this->load->view('index',$data);
	}
	
	function supplier_flexigrid() {
		//$colModel['no'] = array($this->lang->line('sup_flex_col_0'),40,TRUE,'center',0);
		$colModel['sup_name'] = array($this->lang->line('sup_flex_col_1'),110,TRUE,'left',2);
		$colModel['sup_NPWP'] = array($this->lang->line('sup_flex_col_2'),105,TRUE,'left',0);
		$colModel['sup_address'] = array($this->lang->line('sup_flex_col_3'),180,TRUE,'left',2);
		$colModel['sup_phone1'] = array($this->lang->line('sup_flex_col_4'),80, TRUE,'left',0);
		$colModel['sup_fax'] = array($this->lang->line('sup_flex_col_5'),80, TRUE, 'left',0);
		$colModel['sup_email'] = array($this->lang->line('sup_flex_col_6'),100, TRUE, 'left',0);
		$colModel['sup_status'] = array('Status',80, TRUE, 'center',0);
		$colModel['actions'] = array($this->lang->line('sup_flex_col_7'),35, FALSE, 'center',0);
	//bwt hapus dimatiin dulu	//$colModel['hapus'] = array($this->lang->line('sup_flex_col_8'),35, FALSE, 'center',0);
		
		
		/*
		 * Aditional Parameters
		 */
		$gridParams = array(
		'width' => 'auto',
		'height' => 271,
		'blockOpacity' => 0.5,
		'title' => $this->lang->line('sup_flex_ttl'),
		'showTableToggleBtn' => false,
		'singleSelect' => true
		);
		
		/*
		 * 0 - display name
		 * 1 - bclass
		 * 2 - onpress
		 */
		//$buttons[] = array('Delete','delete','test');
		//$buttons[] = array('separator');
		//$buttons[] = array('Select All','add','test');
		//$buttons[] = array('DeSelect All','delete','test');
		//$buttons[] = array('separator');

		
		//Build js
		//View helpers/flexigrid_helper.php for more information about the params on this function
		$grid_js = build_grid_js('flex1',site_url(self::$link_controller."/flexigrid_ajax"),$colModel,'sup_name','asc',$gridParams);
		
		$data['js_grid'] = $grid_js;
		$this->load->view(self::$link_view.'/supplier_list', $data);
	}

	function supplier_frm() {
		$catParent = '0';
		$data['list_term'] = $this->tbl_term->list_term();
		$data['list_bank'] = $this->tbl_bank->list_bank();
		$data['list_cat'] = $this->tbl_category->category_get($catParent);
		$data['list_leg'] = $this->tbl_legal->list_legal();
		$data['list_neg'] = $this->tbl_negara->list_negara();
		$this->load->view(self::$link_view.'/supplier_add',$data);
	}
	
	function supplier_edit_frm() {
		$id = $this->input->post('id');
		$catParent = '0';
		$data['get_bank']= $this->tbl_bank->get_bank($id);
		$data['cat_sup'] = $this->tbl_sup_category->get_sup_cat($id);
		$data['list_supp'] = $this->tbl_supplier->get_supplier($id);
		$data['list_term'] = $this->tbl_term->list_term();
		$data['list_bank'] = $this->tbl_bank->list_bank();
		$data['list_cat'] = $this->tbl_category->category_get($catParent);
		$data['list_leg'] = $this->tbl_legal->list_legal();
		$data['list_neg'] = $this->tbl_negara->list_negara();
		$data['sup_id'] = $id;
		$this->load->view(self::$link_view.'/supplier_edit',$data);
	}
	
	
	function get_provinsi() {
		$id = $this->input->post('id');
		$data['set'] = 'prov';
		$data['list_prov'] = $this->tbl_provinsi->get_provinsi($id);
		$this->load->view(self::$link_view.'/list_provinsi',$data);
	}
	
	function get_kota() {
		$id = $this->input->post('id');
		$data['set'] = 'kota';	
		$data['list_kota'] = $this->tbl_kota->get_kota($id);
		$this->load->view(self::$link_view.'/list_provinsi',$data);
	}
	
	function get_code() {
		$id = $this->input->post('id');
		$data['set'] = 'code';	
		$code = $data['list_kota'] = $this->tbl_kota->list_code($id);
		echo "[".$code."] - ";
	}

	function list_supplier() {
			$data['get_sup'] = $this->tbl_supplier->list_supp();
			$this->load->view(self::$link_view.'/supp_list',$data);
	}
	
	function supplier_delete() {
		$sup_id =  $this->input->post('id');
		$this->tbl_supplier->delete_sup($sup_id);
		$this->load->view(self::$link_view.'/supplier');
	}
	
	
	
	function add_supplier(){
		$usrid = $this->session->userdata('usr_id');
		$supp = $this->input->post('idsupp');
		$legal = $this->input->post('legal');
		$npwp = $this->input->post('npwp');
		$alamat = $this->input->post('alamat');
		$negara = $this->input->post('negara');
		$prov = $this->input->post('provinsi');
		$kota = $this->input->post('kota');
		$phone1 = $this->input->post('phone1');
		$phone2 = $this->input->post('phone2');
		$phone3 = $this->input->post('phone3');
		$fax = $this->input->post('fax');
		$hp = $this->input->post('handphone');
		$email = $this->input->post('email');
		$barang = $this->input->post('barang');
		$jasa = $this->input->post('jasa');
		$cat_sup = $this->input->post('setcat');
		$term = $this->input->post('term');
		$bank = $this->input->post('bank');
		$norek = $this->input->post('no_rekening');
		$error = '';
		$status = '1';

		if ($supp == ''){
			$error[] = $this->lang->line('sup_error_1').', ';
		}
		if ($legal == ''){
			$error[] = $this->lang->line('sup_error_2').', ';
		}
		if ($negara == ''){
			$error[] = $this->lang->line('sup_error_3').', ';
		}
		if ($prov == ''){
			$error[] = $this->lang->line('sup_error_4').', ';
		}
		if ($kota == ''){
			$error[] = $this->lang->line('sup_error_5').', ';
		}
		if (strlen($phone1) <= 8){
			$error[] = $this->lang->line('sup_error_6').', ';
		}
		if ($cat_sup == ''){
			$error[] = $this->lang->line('sup_error_7').', ';
		}
		if ($term == ''){
			$error[] = $this->lang->line('sup_error_8');
		}
		if ($error){
			echo $this->lang->line('sup_error')." : \n".implode("\n",$error);
		}else{
		$id = $this->tbl_supplier->insert_supp($supp, $legal, $npwp, $alamat, $kota, 
							$phone1, $phone2, $phone3, $fax, $hp, $email,
							$term, $status, $usrid);
		$this->tbl_sup_category->supp_cat($id, $cat_sup);
		$this->tbl_sup_bank->supp_bank($id, $bank, $norek);
		echo 'sukses';
		}
	}
	
	function supplier_update() {
		$usrid = $this->session->userdata('usr_id');
		$id = $this->input->post('idsupp');
		$name = $this->input->post('namesupp');
		$legal = $this->input->post('legal');
		$npwp = $this->input->post('npwp');
		$alamat = $this->input->post('alamat');
		$negara = $this->input->post('negara');
		$prov = $this->input->post('provinsi');
		$kota = $this->input->post('kota');
		$phone1 = $this->input->post('phone1');
		$phone2 = $this->input->post('phone2');
		$phone3 = $this->input->post('phone3');
		$fax = $this->input->post('fax');
		$hp = $this->input->post('handphone');
		$email = $this->input->post('email');
		$barang = $this->input->post('barang');
		$jasa = $this->input->post('jasa');
		$cat_sup = $this->input->post('setcat');
		$term = $this->input->post('term');
		$bank = $this->input->post('bank');
		$norek = $this->input->post('no_rekening');	
		$error = '';
		
		if ($name == ''){
			$error[] = $this->lang->line('sup_error_1').', ';
		}
		if ($legal == ''){
			$error[] = $this->lang->line('sup_error_2').', ';
		}
		if ($negara == ''){
			$error[] = $this->lang->line('sup_error_3').', ';
		}
		if ($prov == ''){
			$error[] = $this->lang->line('sup_error_4').', ';
		}
		if ($kota == ''){
			$error[] = $this->lang->line('sup_error_5').', ';
		}
		if (strlen($phone1) <= 8){
			$error[] = $this->lang->line('sup_error_6').', ';
		}
		if ($term == ''){
			$error[] = $this->lang->line('sup_error_8');
		}
		if ($error){
			echo $this->lang->line('sup_error')." : \n".implode("\n",$error);
		}else{
		$this->tbl_supplier->update_supp($id, $name, $legal, $npwp, $alamat, $kota, 
							$phone1, $phone2, $phone3, $fax, $hp, $email,
							$term, $usrid);
		$this->tbl_sup_bank->delete_cat_bank($id);
		$this->tbl_sup_bank->supp_bank($id, $bank, $norek);
		
		if ($cat_sup != ''){
			$this->tbl_sup_category->delete_cat_sup($id);
			$this->tbl_sup_category->supp_cat($id, $cat_sup);	
		}
			echo 'sukses';
		}
	}
	
	function deactive_supplier() {
		$usrid = $this->session->userdata('usr_id');
		$sup_id = $this->input->post('sup_id');
		$sup_name = $this->input->post('sup_name');
		$alasan = $this->input->post('alasan_deactive');
		$date = date('Y-m-d');
		
		$sql = "update prc_master_supplier set sup_status = 2, deactive_note = '$alasan', deactive_req = $usrid, deactive_date = '$date' where sup_id = $sup_id";
		if ($this->db->query($sql)):
			echo $sup_name;
		endif;
		//echo $sup_id.'|'.$alasan;
	}

}
?>