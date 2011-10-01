<?php
class setup_lokasi extends MY_Controller{
	public static $link_view, $link_controller;
	function setup_lokasi()
	{
		parent::MY_Controller();
		$this->load->model(array('tbl_negara','tbl_provinsi','tbl_kota'));
		$this->load->library('form_validation');
		
		self::$link_controller = 'mod_setup/setup_lokasi';
		self::$link_view = 'purchase/mod_setup/lokasi';
 		$data['link_controller'] = self::$link_controller;
		$data['link_view'] = self::$link_view;
		
		$this->load->vars($data);
	}	

	function index() {
		$data['extraHeadContent'] = "<link type=\"text/css\" rel=\"stylesheet\" href=\"". base_url()."asset/css/themes/start/ui.all.css\" />\n";
		$data['extraHeadContent'] .= "<script type=\"text/javascript\" src=\"". base_url()."asset/javascript/jQuery/tables/jquery.jeditable.js\" />\n </script>";
		$data['extraHeadContent'] .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"".base_url()."asset/css/table/DataView.css\" />\n";
		$data['extraHeadContent'] .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"".base_url()."asset/css/form/form_view.css\" />\n";
		$data['extraHeadContent'] .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"".base_url()."asset/javascript/jQuery/flexigrid/css/flexigrid.css\" />\n";
		$data['extraHeadContent'] .= "<script type=\"text/javascript\" src=\"". base_url()."asset/javascript/jQuery/flexigrid/js/flexigrid.js\" />\n </script>";
		
		$data['content'] = self::$link_view.'/lokasi_view';
		$this->load->view('index',$data);
	}
	
	function frm_daftar(){
		$data['list_neg'] = $this->tbl_negara->list_negara();
		$this->load->view(self::$link_view.'/daftar_lokasi',$data);
	}
	
	function frm_negara(){
		$this->load->view(self::$link_view.'/negara_add');
	}
	
	function frm_provinsi(){
		$this->load->view(self::$link_view.'/provinsi_add');
	}
	
	function get_provinsi() {
		$id = $this->input->post('id');
		$data['list_prov'] = $this->tbl_provinsi->get_provinsi($id);
		$this->load->view(self::$link_view.'/prov_list',$data);
	}
	
	function daftar_provinsi($id) {
		$data['list'] = $this->tbl_provinsi->get_provinsi($id);
		$this->load->view(self::$link_view.'/daftar_prov',$data);
	}
	
	function frm_kota(){
		$this->load->view(self::$link_view.'/kota_add');
	}
	
	function daftar_kota($id) {
		$data['list'] = $this->tbl_kota->get_kota($id);
		$this->load->view(self::$link_view.'/daftar_kota',$data);
	}
	
	function negara_update() {
		$neg_id = $this->input->post('id');
		$neg_name = $this->input->post('value');
		$neg_name = strtoupper($neg_name);
		$usrid = $this->session->userdata('usr_id');
		$this->tbl_negara->update_negara($neg_id, $neg_name,$usrid);
		echo $neg_name;
	}
	
	function prov_update() {
		$prov_id = $this->input->post('id');
		$prov_name = $this->input->post('value');
		$prov_name = strtoupper($prov_name);
		$usrid = $this->session->userdata('usr_id');
		$this->tbl_provinsi->update_provinsi($prov_id, $prov_name,$usrid);
		echo $prov_name;
	}
	
	function kota_update() {
		$kota_id = $this->input->post('id');
		$kota_name = $this->input->post('value');
		$kota_name = strtoupper($kota_name);
		$usrid = $this->session->userdata('usr_id');
		$this->tbl_kota->update_kota($kota_id, $kota_name,$usrid);
		echo $kota_name;
	}
	
	function negara_add() {
		$this->form_validation->set_rules('negara', '', 'required');
			if ($this->form_validation->run() == FALSE){
				echo 'error ci : zero length detected';
			}else{
				$usrid = $this->session->userdata('usr_id');
				$negara_name = $this->input->post('negara');
				$negara_name = strtoupper($negara_name);
				$cek = $this->tbl_negara->cek_negara($negara_name);
				if ($cek > 0){
					echo "ada";
				}else{	
					$negara_id = $this->tbl_negara->insert_negara($negara_name,$usrid);
					echo $negara_id;
				}
			}
	}
	
	function provinsi_add() {
		$this->form_validation->set_rules('i_provinsi', '', 'required');
		$this->form_validation->set_rules('negara1', '', 'required');
			if ($this->form_validation->run() == FALSE){
				echo 'error ci : zero length detected';
			}else{
				$usrid = $this->session->userdata('usr_id');
				$negara_id = $this->input->post('negara1');
				$provinsi_name = $this->input->post('i_provinsi');
				$provinsi_name = strtoupper($provinsi_name);
				$cek = $this->tbl_provinsi->cek_prov($provinsi_name);
				if ($cek > 0){
					echo "ada";
				}else{	
					$prov_id = $this->tbl_provinsi->insert_provinsi($negara_id, $provinsi_name, $usrid);
					echo $prov_id;
				}
				echo $provinsi_name."-".$negara_id;
			}
	}
	
	function kota_add() {
		$this->form_validation->set_rules('prov', '', 'required');
		$this->form_validation->set_rules('negara2', '', 'required');
		$this->form_validation->set_rules('kota1', '', 'required');
			if ($this->form_validation->run() == FALSE){
				echo 'error ci : zero length detected';
			}else{
				$usrid = $this->session->userdata('usr_id');
				$negara_id = $this->input->post('negara2');
				$prov_id = $this->input->post('prov');
				$kota_name = $this->input->post('kota1');
				$kode_area = $this->input->post('code');
				$kota_name = strtoupper($kota_name);
				$cek = $this->tbl_kota->cek_kota($kota_name);
				if ($cek > 0){
					echo "ada";
				}else{	
					$kota_id = $this->tbl_kota->insert_kota($prov_id, $kota_name, $kode_area, $usrid);
					echo $kota_id;
				}
			}
	}
	
	function del_lokasi($id,$table) {
		switch($table) {
			case "negara" : 
				$this->db->where('negara_id',$id);
				if ($this->db->get('prc_master_provinsi')->num_rows() > 0):
					echo 'ERROR';
				else:
					$this->db->where('negara_id',$id);
					if ($this->db->delete('prc_master_negara')):
						echo 'OK';
					endif;
				endif;
			break;
			case "provinsi" :
				$this->db->where('provinsi_id',$id);
				if ($this->db->get('prc_master_kota')->num_rows() > 0):
					echo 'ERROR';
				else:
					$this->db->where('provinsi_id',$id);
					if ($this->db->delete('prc_master_provinsi')):
						echo 'OK';
					endif;
				endif;
			break;
			case "kota" :
				$this->db->where('kota_id',$id);
				if ($this->db->delete('prc_master_kota')):
					echo 'OK';
				endif;
			break;
		}	
	}
}
?>