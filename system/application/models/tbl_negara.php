<?php
class Tbl_negara extends Model {
	function list_negara() {
		$this->db->order_by('negara_name','ASC');
		return $this->db->get('prc_master_negara'); 
	}
	
	function get_negara($id) {
		$this->db->where('negara_id',$id);
		return $query = $this->db->get('prc_master_negara');	
	}
	
	function cek_negara($name) {
		$query = $this->db->query("SELECT negara_name FROM prc_master_negara WHERE negara_name = '$name'");
		return $query->num_rows();
	}
	
	function insert_negara($name,$usrid) {
		$data = array(
               'negara_name' => $name,
			   'rec_creator' => $usrid,
			   'rec_created' => date('Y-m-d')
            );
		$this->db->insert('prc_master_negara', $data); 
		return $this->db->insert_id();
	}
	
	function update_negara($id,$name,$usrid) {
		$data = array(
			   'negara_name' => $name,
			   'rec_edit' => 1,
			   'rec_editor' => $usrid,
			   'rec_edited' => date('Y-m-d')
				
			);
		$this->db->where('negara_id', $id);
		$this->db->update('prc_master_negara', $data);
	}
}
?>