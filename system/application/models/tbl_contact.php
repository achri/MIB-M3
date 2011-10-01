<?php
class Tbl_contact extends Model{

	function Tbl_contact(){
	// call the Model constructor
		parent::Model();
	// load database class and connect to MySQL
		$this->load->database();
		$this->CI =& get_instance();
	}
	
	function get_contact_flex(){
		$this->db->select('per_id, per_Fname, sup_id, per_address, per_city, per_phone, per_fax');
		$this->db->from('prc_master_contact_person');
		$this->CI->flexigrid->build_query(TRUE);
		$return['records'] = $this->db->get();

		//Build count query
		$this->db->select('count(per_id) as record_count');
		$this->db->from('prc_master_contact_person');
		$this->CI->flexigrid->build_query(FALSE);
		$return['record_count'] = $this->db->get()->row()->record_count;
		//$row = $record_count->row();
		
		return $return;
	}
	
	function get_contact($id) {
		$this->db->where('per_id',$id);
		return $query = $this->db->get('prc_master_contact_person');	
	}
	
	function insert_contact($nama_depan, $nama_belakang, $nama_panggilan,
		$perusahaan, $departemen, $jabatan, $alamat, $kota, $tlp, $handphone, $fax, $usrid) {
			$data = array(
					'per_Fname' => $nama_depan,
					'per_Lname' => $nama_belakang,
					'per_Nickname' => $nama_panggilan,
					'per_address' => $alamat,
					'per_city' => $kota,
					'ttl_id' => $jabatan,
					'dep_id' => $departemen,
					'per_phone' => $tlp,
					'per_handphone' => $handphone,
					'per_fax' => $fax,
					'sup_id' => $perusahaan,
					'rec_creator' => $usrid,
					'rec_created' => date('Y-m-d')
	            	);
			$this->db->insert('prc_master_contact_person', $data);
	}
	
	function update_contact_person($id, $nama_depan, $nama_belakang, $nama_panggilan,
						$perusahaan, $departemen, $jabatan, $alamat, 
						$kota, $tlp, $handphone, $fax, $usrid) {
			$data = array(
	               	'per_Fname' => $nama_depan,
					'per_Lname' => $nama_belakang,
					'per_Nickname' => $nama_panggilan,
					'per_address' => $alamat,
					'per_city' => $kota,
					'ttl_id' => $jabatan,
					'dep_id' => $departemen,
					'per_phone' => $tlp,
					'per_handphone' => $handphone,
					'per_fax' => $fax,
					'sup_id' => $perusahaan,
					'rec_edit' => 1,
					'rec_editor' => $usrid,
					'rec_edited' => date('Y-m-d')
			);
			$this->db->where('per_id',$id);
			$this->db->update('prc_master_contact_person', $data);
	}
	
	function delete_contact($id) {
		$this->db->where('per_id', $id);
		$this->db->delete('prc_master_contact_person'); 
	}
}
?>