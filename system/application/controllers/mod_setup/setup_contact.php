<?php
class setup_contact extends MY_Controller{
	public static $link_view, $link_controller;
	function setup_contact()
	{
		parent::MY_Controller();
		$this->load->model(array('tbl_contact','tbl_departemen','tbl_jabatan','tbl_kota','tbl_supplier','tbl_legal'));
		$this->load->helper('flexigrid');
		
		$this->lang->load('mod_master/kontak','bahasa');
		$this->lang->load('general','bahasa');
		$this->load->library(array('flexigrid'));
		
		self::$link_controller = 'mod_setup/setup_contact';
		self::$link_view = 'purchase/mod_setup/contact';
 		$data['link_controller'] = self::$link_controller;
		$data['link_view'] = self::$link_view;
		
		$data['link_controller_term'] = 'mod_setup/setup_term';
		$data['link_controller_jabatan'] = 'mod_master/master_jabatan';
		$data['link_controller_departement'] = 'mod_master/master_departemen';
		
		$this->load->vars($data);		
		
	}	
	
	function flexigrid_ajax()
	{
		//List of all fields that can be sortable. This is Optional.
		//This prevents that a user sorts by a column that we dont want him to access, or that doesnt exist, preventing errors.
		$valid_fields = array('per_id','per_Fname','sup_id','per_address', 'per_city', 'per_phone', 'per_fax');
		
		$this->flexigrid->validate_post('per_id','asc',$valid_fields);

		$records = $this->tbl_contact->get_contact_flex();
		
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
			$sup = $this->Tbl_supplier->get_supplier($row->sup_id)->row();
			$leg = $this->Tbl_legal->get_legal($sup->legal_id)->row();
			if ($row->per_city){
				$rcity = $this->Tbl_kota->get_kota($row->per_city);
				$city = $rcity->row();
			}else{
				$city->kota_name = '';
			}
			$record_items[] = array($row->per_id,
			//$i,
			$row->per_Fname,
			$leg->legal_name.'. '.$sup->sup_name,
			$row->per_address,
			$city->kota_name,
			$row->per_phone,
			$row->per_fax,
			'<a href=\'javascript:void(0)\' onclick=\'editcontact('.$row->per_id.')\'><img border=\'0\' src=\'./asset/img_source/button_edit.png\'></a>',
			'<a href=\'javascript:void(0)\' onclick=\'deletecontact('.$row->per_id.')\'><img border=\'0\' src=\'./asset/img_source/button_empty.png\'></a>'
			);
		}
		}else{
			$record_items[] = array($this->lang->line('flex_empty'));
		}
		//Print please
		$this->output->set_output($this->flexigrid->json_build($records['record_count'],$record_items));
	}
	
	
	function deletec()
	{
		$contact_post_array = split(",",$this->input->post('items'));
		
		foreach($contact_post_array as $index => $per_id)
			if (is_numeric($per_id) && $per_id > 1) 
				$this->Tbl_contact->delete_contact($per_id);
						
			
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
		
		$data['content'] = self::$link_view.'/contact';
		$this->load->view('index',$data);
	
	}
	
	function contact_flexigrid(){
		//$colModel['no'] = array($this->lang->line('contact_flex_col_0'),20,TRUE,'center',0);
		$colModel['per_Fname'] = array($this->lang->line('contact_flex_col_1'),120,TRUE,'left',2);
		$colModel['sup_id'] = array($this->lang->line('contact_flex_col_2'),120,TRUE,'left',2);
		$colModel['per_address'] = array($this->lang->line('contact_flex_col_3'),200,TRUE,'left',0);
		$colModel['per_city'] = array($this->lang->line('contact_flex_col_4'),80, TRUE,'left',2);
		$colModel['per_phone'] = array($this->lang->line('contact_flex_col_5'),90, TRUE, 'left',0);
		$colModel['sup_fax'] = array($this->lang->line('contact_flex_col_6'),90, TRUE, 'left',0);
		$colModel['rubah'] = array($this->lang->line('contact_flex_col_7'),40, TRUE, 'center',0);
		$colModel['hapus'] = array($this->lang->line('contact_flex_col_8'),40, TRUE, 'center',0);
		
		/*
		 * Aditional Parameters
		 */
		$gridParams = array(
		'width' => 'auto',
		'height' => 271,
		'blockOpacity' => 0.5,
		'title' => $this->lang->line('contact_flex_ttl'),
		'showTableToggleBtn' => false,
		'singleSelect' => true
		);
		
		//Build js
		//View helpers/flexigrid_helper.php for more information about the params on this function
		$grid_js = build_grid_js('flex1',site_url(self::$link_controller."/flexigrid_ajax"),$colModel,'id','asc',$gridParams);
		$data['message'] = $this->lang->line('contact_confirm_del');
		$data['js_grid'] = $grid_js;
		$this->load->view(self::$link_view.'/contact_list',$data);
	}

	function contact_frm() {
		$data['list_sup'] = $this->tbl_supplier->list_supp();
		$data['list_dep'] = $this->tbl_departemen->list_dep();
		$data['list_jab'] = $this->tbl_jabatan->list_jabatan();
		$data['list_kota'] = $this->tbl_kota->list_kota();
		
		$this->load->view(self::$link_view.'/contact_add',$data);
	}

	function contact_edit_frm() {
		$id = $this->input->post('id');
		$data['list_contact'] = $this->tbl_contact->get_contact($id);
		$data['list_sup'] = $this->tbl_supplier->list_supp();
		$data['list_dep'] = $this->tbl_departemen->list_dep();
		$data['list_jab'] = $this->tbl_jabatan->list_jabatan();
		$data['list_kota'] = $this->tbl_kota->list_kota();
		
		$this->load->view(self::$link_view.'/contact_edit',$data);
	}
	
	function add_contact(){
		$usrid = $this->session->userdata('usr_id');
		$nama_depan = $this->input->post('nama_depan');
		$nama_belakang = $this->input->post('nama_belakang');
		$nama_panggilan = $this->input->post('nama_panggilan');
		$perusahaan = $this->input->post('perusahaan');
		$departemen = $this->input->post('departemen');
		$jabatan = $this->input->post('jabatan');
		$alamat = $this->input->post('alamat');
		$kota = $this->input->post('kota');
		$tlp = $this->input->post('tlp');
		$handphone = $this->input->post('handphone');
		$fax = $this->input->post('fax');
		$error = '';
		if ($nama_depan == ''){
			$error[] = $this->lang->line('contact_validasi_nama_depan');
		}
		if ($nama_belakang == ''){
			$error[] = $this->lang->line('contact_validasi_nama_blkng');
		}
		if ($nama_panggilan == ''){
			$error[] = $this->lang->line('contact_validasi_nama_pnglan');
		}
		if ($perusahaan == ''){
			$error[] = $this->lang->line('contact_validasi_perusahaan');
		}
		if ($error){
			echo $this->lang->line('contact_validasi_error')." : \n".implode("\n",$error);					
		}else{
			$this->tbl_contact->insert_contact($nama_depan, $nama_belakang, $nama_panggilan,
											$perusahaan, $departemen, $jabatan, $alamat, 
											$kota, $tlp, $handphone, $fax, $usrid );
			//$this->load->view('contact/contact');
			echo $this->lang->line('pesan_berhasil');
		}
	}
	
	function upd_contact(){
		$usrid = $this->session->userdata('usr_id');
		$id = $this->input->post('idper');
		$nama_depan = $this->input->post('nama_depan');
		$nama_belakang = $this->input->post('nama_belakang');
		$nama_panggilan = $this->input->post('nama_panggilan');
		$perusahaan = $this->input->post('perusahaan');
		$departemen = $this->input->post('departemen');
		$jabatan = $this->input->post('jabatan');
		$alamat = $this->input->post('alamat');
		$kota = $this->input->post('kota');
		$tlp = $this->input->post('tlp');
		$handphone = $this->input->post('handphone');
		$fax = $this->input->post('fax');
		$error = '';
		if ($nama_depan == ''){
			$error[] = $this->lang->line('contact_validasi_nama_depan');
		}
		if ($nama_belakang == ''){
			$error[] = $this->lang->line('contact_validasi_nama_blkng');
		}
		if ($nama_panggilan == ''){
			$error[] = $this->lang->line('contact_validasi_nama_pnglan');
		}
		if ($perusahaan == ''){
			$error[] = $this->lang->line('contact_validasi_perusahaan');
		}
		if ($error){
			echo $this->lang->line('contact_validasi_error')." : \n".implode("\n",$error);					
		}else{
			$this->tbl_contact->update_contact_person($id, $nama_depan, $nama_belakang, $nama_panggilan,
											$perusahaan, $departemen, $jabatan, $alamat, 
											$kota, $tlp, $handphone, $fax, $usrid);
			//$this->load->view('contact/contact');
			echo $this->lang->line('pesan_berhasil');
		}
	}
	
	function delete_contact(){
		$id = $this->input->post('id');
		$this->tbl_contact->delete_contact($id);
		//$this->load->view('contact/contact_list');
		$this->contact_flexigrid();
	}
}
?>