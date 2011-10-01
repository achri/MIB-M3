<?php
class Tbl_kota extends Model {
	function Tbl_kota(){
	// call the Model constructor
		parent::Model();
	}
	
	function list_kota() {
		$this->db->order_by('kota_name','ASC');
		return $this->db->get('prc_master_kota');
	}
	
	function get_kota($id) {
		$this->db->where('provinsi_id',$id);
		return $query = $this->db->get('prc_master_kota');	
	}
	
	function gets_kota($id) {
		$this->db->where('kota_id',$id);
		return $query = $this->db->get('prc_master_kota');	
	}
	
	function list_code($id) {
		$this->db->select('code_area');
		$this->db->where('kota_id',$id);
		$query = $this->db->get('prc_master_kota');
		if ($query->num_rows() > 0):
		$code = $query->row();
		return $code->code_area;
		else:
		return '-';
		endif;
	}
	
	function cek_kota($name) {
		$query = $this->db->query("SELECT kota_name FROM prc_master_kota WHERE kota_name = '$name'");
		return $query->num_rows();
	}
	
	function insert_kota($prov_id, $kota, $kode, $usrid) {
		$data = array(
			   'provinsi_id' => $prov_id,
			   'kota_name' => $kota,
			   'code_area' => $kode,
			   'rec_creator' => $usrid,
			   'rec_created' => date('Y-m-d')
            );
		$this->db->insert('prc_master_kota', $data); 
		return $this->db->insert_id();
	}
	
	function update_kota($id,$name,$usrid) {
		$data = array(
			   'kota_name' => $name,
			   'rec_edit' => 1,
			   'rec_editor' => $usrid,
			   'rec_edited' => date('Y-m-d')
				
			);
		$this->db->where('kota_id', $id);
		$this->db->update('prc_master_kota', $data);
	}
}
?>