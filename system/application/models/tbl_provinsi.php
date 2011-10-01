<?php
class Tbl_provinsi extends Model {
	function get_provinsi($id) {
		$this->db->where('negara_id',$id);
		$this->db->order_by('provinsi_name','ASC');
		return $this->db->get('prc_master_provinsi'); 
	}
	
	function get_prov($id) {
		$this->db->select('p.negara_id,p.provinsi_name,p.provinsi_id,k.kota_id,k.kota_name');
		$this->db->where('k.kota_id',$id);
		$this->db->from('prc_master_provinsi as p');
		$this->db->join('prc_master_kota as k','k.provinsi_id = p.provinsi_id');
		return $query = $this->db->get();	
	}
	
	function cek_prov($name) {
		$query = $this->db->query("SELECT provinsi_name FROM prc_master_provinsi WHERE provinsi_name = '$name'");
		return $query->num_rows();
	}
	
	function insert_provinsi($negara_id, $prov_name, $usrid) {
		$data = array(
               	'negara_id' => $negara_id,
				'provinsi_name' => $prov_name,
			   	'rec_creator' => $usrid,
			   	'rec_created' => date('Y-m-d')
            );
		$this->db->insert('prc_master_provinsi', $data); 
		return $this->db->insert_id();
	}
	
	function update_provinsi($id,$name,$usrid) {
		$data = array(
			   'provinsi_name' => $name,
			   'rec_edit' => 1,
			   'rec_editor' => $usrid,
			   'rec_edited' => date('Y-m-d')
				
			);
		$this->db->where('provinsi_id', $id);
		$this->db->update('prc_master_provinsi', $data);
	}
}
?>