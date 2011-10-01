<?php
class Tbl_legal extends Model {
	function list_legal() {
		$this->db->order_by('legal_name','ASC');
		return $this->db->get('prc_master_legality'); 
	}
	
	function get_legal($id) {
		$this->db->where('legal_id',$id);
		return $query = $this->db->get('prc_master_legality');	
	}
}
?>